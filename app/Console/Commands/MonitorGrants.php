<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Grant;
use App\Models\User;

class MonitorGrants extends Command
{
    protected $signature = 'grants:monitor';
    protected $description = 'Автоматичний моніторинг нових грантів для користувачів';

    public function handle()
    {
        $this->info('Запуск моніторингу грантів...');

        $users = User::with('profile')->get();
        $this->info('Знайдено користувачів: ' . $users->count());

        foreach ($users as $user) {
            $this->info("Перевірка: {$user->email}");

            if (!$user->profile) {
                $this->info("Пропускаємо - немає профілю");
                continue;
            }

            if (!$user->profile->activity_field) {
                $this->info("Пропускаємо - немає сфери діяльності");
                continue;
            }

            $this->info("Відправляємо запит до Gemini для {$user->email}");

            $profile = $user->profile;
            $profileText = implode('; ', array_filter([
                $profile->org_type        ? "тип: {$profile->org_type}" : null,
                $profile->activity_field  ? "сфера: {$profile->activity_field}" : null,
                $profile->country         ? "країна: {$profile->country}" : null,
                $profile->funding_needs   ? "потреби: {$profile->funding_needs}" : null,
                $profile->budget_range    ? "бюджет: {$profile->budget_range}" : null,
                $profile->experience_level ? "досвід: {$profile->experience_level}" : null,
            ]));

            $apiKey = env('GEMINI_API_KEY');

            $prompt = "ПРОФІЛЬ: {$profileText}

Знайди 5 нових актуальних грантів які з'явились нещодавно. Оціни relevance_score (0-100) наскільки кожен підходить для цього профілю. Відбери TOP 3.

ТІЛЬКИ JSON з 3 грантів:
[{\"title\":\"\",\"description\":\"\",\"country\":\"\",\"category\":\"\",\"funding_amount\":\"\",\"deadline\":\"\",\"source_link\":\"\",\"relevance_score\":80,\"recommendation\":\"\"}]";

            $response = Http::timeout(60)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                ['contents' => [['parts' => [['text' => $prompt]]]]]
            );

            $this->info("Gemini статус: " . $response->status());

            if ($response->successful()) {
                $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $text = preg_replace('/```json|```/', '', $text);
                $parsed = json_decode(trim($text), true);

                $this->info("Розпарсено грантів: " . (is_array($parsed) ? count($parsed) : 0));

                if (is_array($parsed)) {
                    $newGrants = [];
                    foreach ($parsed as $item) {
                        $exists = Grant::where('title', $item['title'])->exists();
                        $this->info("Грант '{$item['title']}' - " . ($exists ? 'вже існує' : 'новий'));

                        if (!$exists) {
                            Grant::create([
                                'title'           => $item['title'],
                                'description'     => $item['description'] ?? '',
                                'country'         => $item['country'] ?? '',
                                'category'        => $item['category'] ?? '',
                                'funding_amount'  => $item['funding_amount'] ?? '',
                                'deadline'        => $item['deadline'] ?? null,
                                'source_link'     => $item['source_link'] ?? '#',
                                'relevance_score' => (int)($item['relevance_score'] ?? 0),
                            ]);
                            $newGrants[] = $item;
                        }
                    }

                    if (!empty($newGrants)) {
                        $this->sendNotification($user, $newGrants);
                        $this->info("Надіслано сповіщення для {$user->email}");
                    } else {
                        $this->info("Нових грантів не знайдено для {$user->email}");
                    }
                }
            } else {
                $this->error("Помилка Gemini: " . $response->body());
            }
        }

        $this->info('Моніторинг завершено!');
    }

    private function sendNotification($user, $grants)
    {
        $grantsHtml = '';
        foreach ($grants as $index => $grant) {
            $position = $index + 1;
            $score = $grant['relevance_score'] ?? 0;
            $grantsHtml .= "
            <div style='background:#f8f9fa; border-left:4px solid #6c5ce7; padding:20px; margin-bottom:20px; border-radius:8px;'>
                <div style='margin-bottom:12px;'>
                    <span style='background:#6c5ce7; color:white; width:28px; height:28px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-weight:bold; font-size:14px;'>{$position}</span>
                    <h3 style='color:#2d3748; margin:8px 0; font-size:16px;'>{$grant['title']}</h3>
                </div>
                <p style='color:#718096; margin:0 0 12px; font-size:14px; line-height:1.6;'>{$grant['description']}</p>
                <table style='width:100%; border-collapse:collapse;'>
                    <tr>
                        <td style='padding:4px 0; font-size:13px; color:#4a5568;'><strong>Країна:</strong> {$grant['country']}</td>
                        <td style='padding:4px 0; font-size:13px;'><strong>Збіг:</strong> <span style='color:#6c5ce7; font-weight:bold;'>{$score}%</span></td>
                    </tr>
                    <tr>
                        <td style='padding:4px 0; font-size:13px; color:#4a5568;'><strong>Фінансування:</strong> {$grant['funding_amount']}</td>
                        <td style='padding:4px 0; font-size:13px; color:#4a5568;'><strong>Дедлайн:</strong> {$grant['deadline']}</td>
                    </tr>
                </table>
                " . (!empty($grant['recommendation']) ? "<p style='margin:12px 0 0; font-size:13px; color:#553c9a; background:#e9d8fd; padding:10px 14px; border-radius:6px;'><strong>AI рекомендація:</strong> {$grant['recommendation']}</p>" : "") . "
                <a href='{$grant['source_link']}' style='display:inline-block; margin-top:14px; background:#6c5ce7; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:bold; font-size:13px;'>Перейти до гранту →</a>
            </div>";
        }

        $html = "
        <div style='font-family:Arial,sans-serif; max-width:620px; margin:0 auto; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;'>
            <div style='background:linear-gradient(135deg, #6c5ce7, #a29bfe); padding:32px; text-align:center;'>
                <h1 style='color:white; margin:0 0 8px; font-size:28px; letter-spacing:-1px;'>GrantAI</h1>
                <p style='color:rgba(255,255,255,0.85); margin:0; font-size:15px;'>Нові гранти для вас</p>
            </div>
            <div style='padding:28px; background:white;'>
                <p style='color:#2d3748; font-size:15px; margin:0 0 8px;'>Вітаємо, <strong>{$user->name}</strong>!</p>
                <p style='color:#718096; font-size:14px; margin:0 0 24px; line-height:1.6;'>AI-агент знайшов нові грантові програми які відповідають вашому профілю:</p>
                {$grantsHtml}
                <div style='text-align:center; margin-top:28px; padding-top:24px; border-top:1px solid #e2e8f0;'>
                    <a href='http://localhost:8000/grants' style='background:#6c5ce7; color:white; padding:14px 32px; border-radius:8px; text-decoration:none; font-weight:bold; font-size:15px;'>Переглянути на сайті</a>
                </div>
            </div>
            <div style='background:#f7fafc; padding:16px; text-align:center; border-top:1px solid #e2e8f0;'>
                <p style='color:#a0aec0; font-size:12px; margin:0;'>GrantAI · Автоматичний моніторинг грантів</p>
            </div>
        </div>";

        Mail::html($html, function($message) use ($user) {
            $message->to($user->email)
                ->subject('GrantAI: Нові гранти для вас!');
        });
    }
}
