<?php

namespace App\Http\Controllers;

use App\Models\Grant;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GrantController extends Controller
{
    public function index(Request $request)
    {
        $grants = Grant::orderBy('relevance_score', 'desc')->get();
        $query = '';
        return view('grants', compact('grants', 'query'));
    }

    public function search(Request $request)
    {
        set_time_limit(180);
        ini_set('max_execution_time', 180);

        $userPrompt = $request->input('prompt', '');
        $profile = auth()->user()->profile;

        $profileText = 'Профіль не заповнено';
        if ($profile) {
            $parts = [];
            if ($profile->organization_name)   $parts[] = "організація: {$profile->organization_name}";
            if ($profile->org_type)            $parts[] = "тип: {$profile->org_type}";
            if ($profile->activity_field)      $parts[] = "сфера: {$profile->activity_field}";
            if ($profile->country)             $parts[] = "країна: {$profile->country}";
            if ($profile->activity_region)     $parts[] = "регіон: {$profile->activity_region}";
            if ($profile->funding_needs)       $parts[] = "фінансування: {$profile->funding_needs}";
            if ($profile->budget_range)        $parts[] = "бюджет: {$profile->budget_range}";
            if ($profile->experience_level)    $parts[] = "досвід: {$profile->experience_level}";
            if ($profile->previous_grants)     $parts[] = "попередні гранти: {$profile->previous_grants}";
            if ($profile->team_size)           $parts[] = "команда: {$profile->team_size} осіб";
            if ($profile->project_description) $parts[] = "проєкт: {$profile->project_description}";
            if ($profile->project_goals)       $parts[] = "цілі: {$profile->project_goals}";
            if ($profile->project_duration)    $parts[] = "тривалість: {$profile->project_duration}";
            if ($profile->target_audience)     $parts[] = "аудиторія: {$profile->target_audience}";
            if ($profile->languages)           $parts[] = "мови: {$profile->languages}";
            if ($profile->partners)            $parts[] = "партнери: {$profile->partners}";
            if ($profile->keywords)            $parts[] = "ключові слова: {$profile->keywords}";
            if (!empty($parts)) $profileText = implode('; ', $parts);
        }

        $apiKey = env('GEMINI_API_KEY');

        $prompt = "ПРОФІЛЬ: {$profileText}

ЗАПИТ: {$userPrompt}

Знайди 10 реальних грантів. Оціни кожен за 10 метриками 0-10: org_match, field_match, geo_match, budget_match, feasibility, urgency, experience_match, audience_match, impact_potential, competition_level. relevance_score = середнє * 10.

ТІЛЬКИ JSON з 10 грантів без пояснень:
[{\"title\":\"\",\"description\":\"\",\"country\":\"\",\"category\":\"\",\"funding_amount\":\"\",\"deadline\":\"\",\"source_link\":\"\",\"relevance_score\":80,\"recommendation\":\"\"}]";

        $response = Http::timeout(150)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
            [
                'contents' => [[
                    'parts' => [['text' => $prompt]]
                ]]
            ]
        );

        Log::info('Gemini response: ' . substr($response->body(), 0, 500));

        $grants = collect();
        $error = null;

        if ($response->successful()) {
            $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $text = preg_replace('/```json|```/', '', $text);
            $parsed = json_decode(trim($text), true);

            if (is_array($parsed)) {
                $grantsArray = [];
                foreach ($parsed as $item) {
                    $grant = Grant::updateOrCreate(
                        ['title' => $item['title']],
                        [
                            'description'     => $item['description'] ?? '',
                            'country'         => $item['country'] ?? '',
                            'category'        => $item['category'] ?? '',
                            'funding_amount'  => $item['funding_amount'] ?? '',
                            'deadline'        => $item['deadline'] ?? null,
                            'source_link'     => $item['source_link'] ?? '#',
                            'relevance_score' => isset($item['relevance_score']) ? (int)$item['relevance_score'] : 0,
                        ]
                    );
                    $grant->recommendation = $item['recommendation'] ?? '';
                    $grantsArray[] = $grant;
                }

                usort($grantsArray, fn($a, $b) => $b->relevance_score - $a->relevance_score);
                $grants = collect($grantsArray);

                $top3 = array_slice($grantsArray, 0, 3);
                try {
                    $this->sendGrantsEmail(auth()->user(), $top3);
                } catch (\Exception $e) {
                    Log::error('Email error: ' . $e->getMessage());
                }
            }
        } else {
            $error = 'AI сервіс тимчасово недоступний. Спробуйте пізніше.';
            $grants = Grant::orderBy('relevance_score', 'desc')->get();
        }

        $query = $userPrompt;
        return view('grants', compact('grants', 'query', 'error'));
    }

    public function sendGrantsEmail($user, $grants)
    {
        $grantsHtml = '';
        foreach ($grants as $index => $grant) {
            $position = $index + 1;
            $score = $grant->relevance_score ?? 0;
            $grantsHtml .= "
            <div style='background:#f8f9fa; border-left:4px solid #6c5ce7; padding:20px; margin-bottom:20px; border-radius:8px;'>
                <div style='margin-bottom:12px;'>
                    <span style='background:#6c5ce7; color:white; width:28px; height:28px; border-radius:50%; display:inline-block; text-align:center; line-height:28px; font-weight:bold; font-size:14px;'>{$position}</span>
                    <h3 style='color:#2d3748; margin:8px 0; font-size:16px;'>{$grant->title}</h3>
                </div>
                <p style='color:#718096; margin:0 0 12px; font-size:14px; line-height:1.6;'>{$grant->description}</p>
                <p style='font-size:13px; color:#4a5568;'><strong>Країна:</strong> {$grant->country} &nbsp;|&nbsp; <strong>Збіг:</strong> <span style='color:#6c5ce7; font-weight:bold;'>{$score}%</span></p>
                <p style='font-size:13px; color:#4a5568;'><strong>Фінансування:</strong> {$grant->funding_amount}</p>
                <p style='font-size:13px; color:#4a5568;'><strong>Дедлайн:</strong> {$grant->deadline}</p>
                " . ($grant->recommendation ? "<p style='margin:12px 0 0; font-size:13px; color:#553c9a; background:#e9d8fd; padding:10px 14px; border-radius:6px;'><strong>AI рекомендація:</strong> {$grant->recommendation}</p>" : "") . "
                <a href='{$grant->source_link}' style='display:inline-block; margin-top:14px; background:#6c5ce7; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:bold; font-size:13px;'>Перейти до гранту →</a>
            </div>";
        }

        $html = "
        <div style='font-family:Arial,sans-serif; max-width:620px; margin:0 auto; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;'>
            <div style='background:linear-gradient(135deg, #6c5ce7, #a29bfe); padding:32px; text-align:center;'>
                <h1 style='color:white; margin:0 0 8px; font-size:28px;'>GrantAI</h1>
                <p style='color:rgba(255,255,255,0.85); margin:0; font-size:15px;'>Ваші персональні TOP-3 гранти</p>
            </div>
            <div style='padding:28px; background:white;'>
                <p style='color:#2d3748; font-size:15px; margin:0 0 8px;'>Вітаємо, <strong>{$user->name}</strong>!</p>
                <p style='color:#718096; font-size:14px; margin:0 0 24px; line-height:1.6;'>AI-агент проаналізував ваш профіль за 10 метриками та відібрав найкращі 3 гранти:</p>
                {$grantsHtml}
                <div style='text-align:center; margin-top:28px; padding-top:24px; border-top:1px solid #e2e8f0;'>
                    <a href='http://localhost:8000/grants' style='background:#6c5ce7; color:white; padding:14px 32px; border-radius:8px; text-decoration:none; font-weight:bold; font-size:15px;'>Переглянути всі результати</a>
                </div>
            </div>
            <div style='background:#f7fafc; padding:16px; text-align:center; border-top:1px solid #e2e8f0;'>
                <p style='color:#a0aec0; font-size:12px; margin:0;'>GrantAI · Інтелектуальна система пошуку грантів</p>
            </div>
        </div>";

        Mail::html($html, function($message) use ($user) {
            $message->to($user->email)
                ->subject('GrantAI: Ваші персональні TOP-3 гранти');
        });
    }

    public function show($id)
    {
        $grant = Grant::findOrFail($id);
        return view('grant-details', compact('grant'));
    }
}
