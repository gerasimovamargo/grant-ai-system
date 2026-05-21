<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мій профіль — GrantAI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0a0a0f; --bg-card: #111118; --border: rgba(255,255,255,0.07);
            --border-hover: rgba(255,255,255,0.14); --accent: #6c5ce7; --accent-2: #a29bfe;
            --text: #f0f0f5; --text-muted: #6b6b80; --text-mid: #9898aa; --success: #00b894;
        }
        body { font-family: 'Manrope', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
        body::before {
            content: ''; position: fixed; inset: 0;
            background: radial-gradient(ellipse 80% 60% at 10% 0%, rgba(108,92,231,0.12) 0%, transparent 60%),
            radial-gradient(ellipse 60% 50% at 90% 100%, rgba(162,155,254,0.08) 0%, transparent 55%);
            pointer-events: none; z-index: 0;
        }
        .nav {
            position: sticky; top: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px; height: 64px;
            border-bottom: 1px solid var(--border);
            background: rgba(10,10,15,0.85); backdrop-filter: blur(20px);
        }
        .nav-logo { font-size: 18px; font-weight: 800; color: var(--text); text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .nav-logo-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }
        .nav-right { display: flex; align-items: center; gap: 16px; }
        .nav-link { font-size: 13px; color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
        .nav-link:hover { color: var(--text); }
        .btn-logout {
            font-family: 'Manrope', sans-serif; font-size: 13px; font-weight: 600;
            color: var(--text-muted); background: transparent;
            border: 1px solid var(--border); border-radius: 8px;
            padding: 8px 16px; cursor: pointer; transition: all 0.2s;
        }
        .btn-logout:hover { color: var(--text); border-color: var(--border-hover); background: rgba(255,255,255,0.04); }

        .container { position: relative; z-index: 1; max-width: 860px; margin: 0 auto; padding: 60px 24px 100px; }

        .page-header { margin-bottom: 40px; }
        .page-label { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-2); margin-bottom: 12px; display: block; }
        .page-title { font-size: 36px; font-weight: 800; letter-spacing: -1px; margin-bottom: 10px; }
        .page-sub { font-size: 15px; color: var(--text-muted); line-height: 1.6; }

        .success-bar {
            padding: 14px 20px; border-radius: 12px;
            background: rgba(0,184,148,0.08); border: 1px solid rgba(0,184,148,0.2);
            color: var(--success); font-size: 14px; font-weight: 600; margin-bottom: 28px;
        }

        .profile-tip {
            padding: 16px 20px; border-radius: 12px;
            background: rgba(108,92,231,0.06); border: 1px solid rgba(108,92,231,0.15);
            color: var(--text-mid); font-size: 13px; line-height: 1.6; margin-bottom: 28px;
        }
        .profile-tip strong { color: var(--accent-2); }

        .section { background: var(--bg-card); border: 1px solid var(--border); border-radius: 18px; padding: 32px; margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }

        .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .field { margin-bottom: 0; }
        .field-full { grid-column: 1 / -1; }

        label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 8px; }

        select, input[type="text"], input[type="url"], input[type="number"], textarea {
            width: 100%; padding: 12px 16px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border); border-radius: 10px;
            color: var(--text); font-family: 'Manrope', sans-serif;
            font-size: 14px; outline: none; transition: border-color 0.2s;
            appearance: none;
        }
        select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b6b80' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }
        select option { background: #111118; }
        select:focus, input:focus, textarea:focus { border-color: rgba(108,92,231,0.5); }
        input::placeholder, textarea::placeholder { color: var(--text-muted); }
        textarea { resize: vertical; min-height: 100px; line-height: 1.6; }

        .actions { display: flex; gap: 12px; margin-top: 8px; }
        .btn-save {
            flex: 1; padding: 16px; background: var(--accent); color: white;
            border: none; border-radius: 12px; font-family: 'Manrope', sans-serif;
            font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s;
        }
        .btn-save:hover { background: #5a4bd1; }
        .btn-cancel {
            padding: 16px 24px; background: transparent; color: var(--text-muted);
            border: 1px solid var(--border); border-radius: 12px;
            font-family: 'Manrope', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; text-decoration: none; display: flex; align-items: center; transition: all 0.2s;
        }
        .btn-cancel:hover { color: var(--text); border-color: var(--border-hover); }

        .metrics-info {
            display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; margin-top: 20px;
        }
        .metric-chip {
            padding: 8px 10px; background: rgba(108,92,231,0.06);
            border: 1px solid rgba(108,92,231,0.15); border-radius: 8px;
            text-align: center; font-size: 11px; color: var(--accent-2); font-weight: 600;
        }

        @media (max-width: 768px) {
            .nav { padding: 0 20px; }
            .container { padding: 40px 16px 80px; }
            .field-grid { grid-template-columns: 1fr; }
            .metrics-info { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<nav class="nav">
    <a href="/grants" class="nav-logo"><div class="nav-logo-dot"></div>GrantAI</a>
    <div class="nav-right">
        <a href="/grants" class="nav-link">Пошук грантів</a>
        <form method="POST" action="/logout" style="margin:0">
            @csrf
            <button type="submit" class="btn-logout">Вийти</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="page-header">
        <span class="page-label">Налаштування AI-агента</span>
        <h1 class="page-title">Мій профіль</h1>
        <p class="page-sub">Заповніть детальну інформацію — AI-агент використає її для оцінки грантів за 10 метриками та підбору найрелевантніших програм.</p>
    </div>

    @if(session('success'))
        <div class="success-bar">{{ session('success') }}</div>
    @endif


    <form method="POST" action="/profile/update">
        @csrf

        <div class="section">
            <div class="section-title">Про організацію</div>
            <div class="field-grid">
                <div class="field">
                    <label>Назва організації</label>
                    <input type="text" name="organization_name" value="{{ old('organization_name', $profile->organization_name ?? '') }}" placeholder="Наприклад: ГО Молодь України">
                </div>
                <div class="field">
                    <label>Веб-сайт</label>
                    <input type="text" name="website" value="{{ old('website', $profile->website ?? '') }}" placeholder="https://yoursite.com">
                </div>
                <div class="field">
                    <label>Тип організації</label>
                    <select name="org_type">
                        <option value="">Оберіть тип</option>
                        <option value="студент" {{ old('org_type', $profile->org_type ?? '') == 'студент' ? 'selected' : '' }}>Студент</option>
                        <option value="НГО" {{ old('org_type', $profile->org_type ?? '') == 'НГО' ? 'selected' : '' }}>НГО / Громадська організація</option>
                        <option value="стартап" {{ old('org_type', $profile->org_type ?? '') == 'стартап' ? 'selected' : '' }}>Стартап / Бізнес</option>
                        <option value="науковець" {{ old('org_type', $profile->org_type ?? '') == 'науковець' ? 'selected' : '' }}>Науковець / Дослідник</option>
                        <option value="університет" {{ old('org_type', $profile->org_type ?? '') == 'університет' ? 'selected' : '' }}>Університет / Навчальний заклад</option>
                        <option value="фізична особа" {{ old('org_type', $profile->org_type ?? '') == 'фізична особа' ? 'selected' : '' }}>Фізична особа</option>
                        <option value="державна установа" {{ old('org_type', $profile->org_type ?? '') == 'державна установа' ? 'selected' : '' }}>Державна установа</option>
                        <option value="медіа" {{ old('org_type', $profile->org_type ?? '') == 'медіа' ? 'selected' : '' }}>Медіа / Журналістика</option>
                    </select>
                </div>
                <div class="field">
                    <label>Розмір команди (осіб)</label>
                    <input type="number" name="team_size" value="{{ old('team_size', $profile->team_size ?? '') }}" placeholder="Наприклад: 5">
                </div>
                <div class="field">
                    <label>Сфера діяльності</label>
                    <select name="activity_field">
                        <option value="">Оберіть сферу</option>
                        <option value="освіта" {{ old('activity_field', $profile->activity_field ?? '') == 'освіта' ? 'selected' : '' }}>Освіта</option>
                        <option value="наука та дослідження" {{ old('activity_field', $profile->activity_field ?? '') == 'наука та дослідження' ? 'selected' : '' }}>Наука та дослідження</option>
                        <option value="технології та IT" {{ old('activity_field', $profile->activity_field ?? '') == 'технології та IT' ? 'selected' : '' }}>Технології та IT</option>
                        <option value="культура та мистецтво" {{ old('activity_field', $profile->activity_field ?? '') == 'культура та мистецтво' ? 'selected' : '' }}>Культура та мистецтво</option>
                        <option value="охорона здоров'я" {{ old('activity_field', $profile->activity_field ?? '') == "охорона здоров'я" ? 'selected' : '' }}>Охорона здоров'я</option>
                        <option value="екологія" {{ old('activity_field', $profile->activity_field ?? '') == 'екологія' ? 'selected' : '' }}>Екологія</option>
                        <option value="соціальні проєкти" {{ old('activity_field', $profile->activity_field ?? '') == 'соціальні проєкти' ? 'selected' : '' }}>Соціальні проєкти</option>
                        <option value="бізнес та підприємництво" {{ old('activity_field', $profile->activity_field ?? '') == 'бізнес та підприємництво' ? 'selected' : '' }}>Бізнес та підприємництво</option>
                        <option value="права людини" {{ old('activity_field', $profile->activity_field ?? '') == 'права людини' ? 'selected' : '' }}>Права людини</option>
                        <option value="медіа та журналістика" {{ old('activity_field', $profile->activity_field ?? '') == 'медіа та журналістика' ? 'selected' : '' }}>Медіа та журналістика</option>
                        <option value="ветерани та переселенці" {{ old('activity_field', $profile->activity_field ?? '') == 'ветерани та переселенці' ? 'selected' : '' }}>Ветерани та переселенці</option>
                    </select>
                </div>
                <div class="field">
                    <label>Соціальні мережі</label>
                    <input type="text" name="social_links" value="{{ old('social_links', $profile->social_links ?? '') }}" placeholder="Instagram, LinkedIn, Facebook...">
                </div>
                <div class="field">
                    <label>Партнерські організації</label>
                    <input type="text" name="partners" value="{{ old('partners', $profile->partners ?? '') }}" placeholder="Наприклад: USAID, British Council...">
                </div>
                <div class="field">
                    <label>Мови комунікації</label>
                    <input type="text" name="languages" value="{{ old('languages', $profile->languages ?? '') }}" placeholder="Наприклад: українська, англійська, польська">
                </div>
            </div>
        </div>


        <div class="section">
            <div class="section-title">Географія та досвід</div>
            <div class="field-grid">
                <div class="field">
                    <label>Країна реєстрації</label>
                    <select name="country">
                        <option value="">Оберіть країну</option>
                        <option value="Україна" {{ old('country', $profile->country ?? '') == 'Україна' ? 'selected' : '' }}>Україна</option>
                        <option value="Польща" {{ old('country', $profile->country ?? '') == 'Польща' ? 'selected' : '' }}>Польща</option>
                        <option value="Німеччина" {{ old('country', $profile->country ?? '') == 'Німеччина' ? 'selected' : '' }}>Німеччина</option>
                        <option value="США" {{ old('country', $profile->country ?? '') == 'США' ? 'selected' : '' }}>США</option>
                        <option value="Велика Британія" {{ old('country', $profile->country ?? '') == 'Велика Британія' ? 'selected' : '' }}>Велика Британія</option>
                        <option value="інша" {{ old('country', $profile->country ?? '') == 'інша' ? 'selected' : '' }}>Інша</option>
                    </select>
                </div>
                <div class="field">
                    <label>Географія діяльності</label>
                    <input type="text" name="activity_region" value="{{ old('activity_region', $profile->activity_region ?? '') }}" placeholder="Наприклад: Харків, Східна Україна, вся Україна">
                </div>
                <div class="field">
                    <label>Досвід подачі заявок</label>
                    <select name="experience_level">
                        <option value="">Оберіть рівень</option>
                        <option value="немає досвіду" {{ old('experience_level', $profile->experience_level ?? '') == 'немає досвіду' ? 'selected' : '' }}>Немає досвіду</option>
                        <option value="початківець" {{ old('experience_level', $profile->experience_level ?? '') == 'початківець' ? 'selected' : '' }}>Початківець (1-2 заявки)</option>
                        <option value="середній" {{ old('experience_level', $profile->experience_level ?? '') == 'середній' ? 'selected' : '' }}>Середній (3-5 заявок)</option>
                        <option value="досвідчений" {{ old('experience_level', $profile->experience_level ?? '') == 'досвідчений' ? 'selected' : '' }}>Досвідчений (5+ заявок)</option>
                    </select>
                </div>
                <div class="field">
                    <label>Попередні отримані гранти</label>
                    <input type="text" name="previous_grants" value="{{ old('previous_grants', $profile->previous_grants ?? '') }}" placeholder="Наприклад: USAID 2023, EU Grant 2022">
                </div>
            </div>
        </div>


        <div class="section">
            <div class="section-title">Фінансові потреби</div>
            <div class="field-grid">
                <div class="field">
                    <label>Напрям фінансування</label>
                    <select name="funding_needs">
                        <option value="">Оберіть напрям</option>
                        <option value="навчання за кордоном" {{ old('funding_needs', $profile->funding_needs ?? '') == 'навчання за кордоном' ? 'selected' : '' }}>Навчання за кордоном</option>
                        <option value="наукові дослідження" {{ old('funding_needs', $profile->funding_needs ?? '') == 'наукові дослідження' ? 'selected' : '' }}>Наукові дослідження</option>
                        <option value="розвиток проєкту" {{ old('funding_needs', $profile->funding_needs ?? '') == 'розвиток проєкту' ? 'selected' : '' }}>Розвиток проєкту</option>
                        <option value="стажування" {{ old('funding_needs', $profile->funding_needs ?? '') == 'стажування' ? 'selected' : '' }}>Стажування</option>
                        <option value="обладнання" {{ old('funding_needs', $profile->funding_needs ?? '') == 'обладнання' ? 'selected' : '' }}>Обладнання та інфраструктура</option>
                        <option value="заробітна плата команди" {{ old('funding_needs', $profile->funding_needs ?? '') == 'заробітна плата команди' ? 'selected' : '' }}>Заробітна плата команди</option>
                        <option value="маркетинг та комунікації" {{ old('funding_needs', $profile->funding_needs ?? '') == 'маркетинг та комунікації' ? 'selected' : '' }}>Маркетинг та комунікації</option>
                        <option value="операційні витрати" {{ old('funding_needs', $profile->funding_needs ?? '') == 'операційні витрати' ? 'selected' : '' }}>Операційні витрати</option>
                    </select>
                </div>
                <div class="field">
                    <label>Бажаний розмір гранту</label>
                    <select name="budget_range">
                        <option value="">Оберіть діапазон</option>
                        <option value="до $5,000" {{ old('budget_range', $profile->budget_range ?? '') == 'до $5,000' ? 'selected' : '' }}>До $5,000</option>
                        <option value="$5,000 - $20,000" {{ old('budget_range', $profile->budget_range ?? '') == '$5,000 - $20,000' ? 'selected' : '' }}>$5,000 — $20,000</option>
                        <option value="$20,000 - $50,000" {{ old('budget_range', $profile->budget_range ?? '') == '$20,000 - $50,000' ? 'selected' : '' }}>$20,000 — $50,000</option>
                        <option value="$50,000 - $100,000" {{ old('budget_range', $profile->budget_range ?? '') == '$50,000 - $100,000' ? 'selected' : '' }}>$50,000 — $100,000</option>
                        <option value="$100,000 - $500,000" {{ old('budget_range', $profile->budget_range ?? '') == '$100,000 - $500,000' ? 'selected' : '' }}>$100,000 — $500,000</option>
                        <option value="понад $500,000" {{ old('budget_range', $profile->budget_range ?? '') == 'понад $500,000' ? 'selected' : '' }}>Понад $500,000</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="section">
            <div class="section-title">Деталі проєкту</div>
            <div class="field-grid">
                <div class="field field-full">
                    <label>Опис проєкту або діяльності</label>
                    <textarea name="project_description" placeholder="Опишіть ваш проєкт, його суть та чим він унікальний...">{{ old('project_description', $profile->project_description ?? '') }}</textarea>
                </div>
                <div class="field field-full">
                    <label>Цілі проєкту</label>
                    <textarea name="project_goals" placeholder="Що ви плануєте досягти? Які результати очікуєте?" style="min-height:80px;">{{ old('project_goals', $profile->project_goals ?? '') }}</textarea>
                </div>
                <div class="field">
                    <label>Тривалість проєкту</label>
                    <select name="project_duration">
                        <option value="">Оберіть тривалість</option>
                        <option value="до 3 місяців" {{ old('project_duration', $profile->project_duration ?? '') == 'до 3 місяців' ? 'selected' : '' }}>До 3 місяців</option>
                        <option value="3-6 місяців" {{ old('project_duration', $profile->project_duration ?? '') == '3-6 місяців' ? 'selected' : '' }}>3-6 місяців</option>
                        <option value="6-12 місяців" {{ old('project_duration', $profile->project_duration ?? '') == '6-12 місяців' ? 'selected' : '' }}>6-12 місяців</option>
                        <option value="1-2 роки" {{ old('project_duration', $profile->project_duration ?? '') == '1-2 роки' ? 'selected' : '' }}>1-2 роки</option>
                        <option value="більше 2 років" {{ old('project_duration', $profile->project_duration ?? '') == 'більше 2 років' ? 'selected' : '' }}>Більше 2 років</option>
                    </select>
                </div>
                <div class="field">
                    <label>Цільова аудиторія</label>
                    <input type="text" name="target_audience" value="{{ old('target_audience', $profile->target_audience ?? '') }}" placeholder="Наприклад: молодь 18-25 років, ветерани...">
                </div>
                <div class="field field-full">
                    <label>Ключові слова</label>
                    <input type="text" name="keywords" value="{{ old('keywords', $profile->keywords ?? '') }}" placeholder="Наприклад: цифровізація, інновації, відновлення, молодь...">
                </div>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn-save">Зберегти профіль</button>
            <a href="/grants" class="btn-cancel">Скасувати</a>
        </div>
    </form>
</div>

</body>
</html>
