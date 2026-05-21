<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GrantAI — Пошук грантів</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0a0a0f; --bg-card: #111118; --bg-card-hover: #16161f;
            --border: rgba(255,255,255,0.07); --border-hover: rgba(255,255,255,0.14);
            --accent: #6c5ce7; --accent-2: #a29bfe;
            --text: #f0f0f5; --text-muted: #6b6b80; --text-mid: #9898aa;
            --success: #00b894; --danger: #e17055;
        }
        body { font-family: 'Manrope', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; overflow-x: hidden; }
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
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .nav-user { font-size: 13px; color: var(--text-muted); }
        .nav-link { font-size: 13px; color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
        .nav-link:hover { color: var(--text); }
        .btn-logout {
            font-family: 'Manrope', sans-serif; font-size: 13px; font-weight: 600;
            color: var(--text-muted); background: transparent;
            border: 1px solid var(--border); border-radius: 8px;
            padding: 8px 16px; cursor: pointer; transition: all 0.2s;
        }
        .btn-logout:hover { color: var(--text); border-color: var(--border-hover); background: rgba(255,255,255,0.04); }

        .hero { position: relative; z-index: 1; max-width: 800px; margin: 0 auto; padding: 72px 24px 48px; text-align: center; }
        .hero-label { display: inline-block; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-2); margin-bottom: 20px; padding: 6px 14px; border: 1px solid rgba(162,155,254,0.2); border-radius: 999px; background: rgba(108,92,231,0.08); }
        .hero h1 { font-size: 52px; font-weight: 800; line-height: 1.1; letter-spacing: -2px; margin-bottom: 16px; }
        .hero h1 span { color: var(--accent-2); }
        .hero-sub { font-size: 16px; color: var(--text-muted); line-height: 1.7; max-width: 520px; margin: 0 auto; }

        .profile-section { position: relative; z-index: 1; max-width: 860px; margin: 0 auto; padding: 0 24px; }
        .profile-banner {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px; border-radius: 12px; margin-bottom: 20px;
            background: rgba(108,92,231,0.06); border: 1px solid rgba(108,92,231,0.15);
        }
        .profile-banner-text { font-size: 13px; color: var(--text-mid); }
        .profile-banner-text strong { color: var(--accent-2); }
        .profile-banner-link {
            font-size: 13px; font-weight: 700; color: var(--accent-2);
            text-decoration: none; padding: 8px 16px;
            border: 1px solid rgba(108,92,231,0.3); border-radius: 8px;
            background: rgba(108,92,231,0.08); transition: all 0.2s; white-space: nowrap;
        }
        .profile-banner-link:hover { background: rgba(108,92,231,0.16); }

        .prompt-section { position: relative; z-index: 1; max-width: 860px; margin: 0 auto; padding: 0 24px; }
        .prompt-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px; padding: 32px; }
        .prompt-title { font-size: 13px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px; }
        .prompt-hint { font-size: 13px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.5; }
        .prompt-hint span { color: var(--accent-2); }
        .prompt-field textarea {
            width: 100%; padding: 16px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border); border-radius: 12px;
            color: var(--text); font-family: 'Manrope', sans-serif;
            font-size: 15px; outline: none; transition: border-color 0.2s;
            resize: vertical; min-height: 100px; line-height: 1.7;
        }
        .prompt-field textarea:focus { border-color: rgba(108,92,231,0.5); }
        .prompt-field textarea::placeholder { color: var(--text-muted); }
        .prompt-actions { display: flex; gap: 12px; margin-top: 16px; }
        .btn-search {
            flex: 1; padding: 14px; background: var(--accent); color: white;
            border: none; border-radius: 10px; font-family: 'Manrope', sans-serif;
            font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s;
        }
        .btn-search:hover { background: #5a4bd1; }
        .btn-reset {
            padding: 14px 20px; background: transparent; color: var(--text-muted);
            border: 1px solid var(--border); border-radius: 10px;
            font-family: 'Manrope', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all 0.2s; text-decoration: none;
            display: flex; align-items: center;
        }
        .btn-reset:hover { color: var(--text); border-color: var(--border-hover); }

        .current-query { margin-top: 16px; padding: 12px 16px; border-radius: 10px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); font-size: 13px; color: var(--text-muted); }
        .current-query strong { color: var(--text-mid); }

        .error-bar { max-width: 860px; margin: 16px auto 0; padding: 0 24px; }
        .error-inner { padding: 12px 18px; border-radius: 10px; background: rgba(225,112,85,0.08); border: 1px solid rgba(225,112,85,0.2); color: var(--danger); font-size: 14px; }

        .results-section { position: relative; z-index: 1; max-width: 1280px; margin: 56px auto 0; padding: 0 40px 100px; }
        .results-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .results-title { font-size: 12px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--text-muted); }
        .results-count { font-size: 12px; color: var(--text-muted); padding: 5px 12px; border: 1px solid var(--border); border-radius: 999px; }

        /* TOP-3 LEGEND */
        .top-legend {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 28px; padding: 12px 16px;
            background: rgba(108,92,231,0.04); border: 1px solid rgba(108,92,231,0.12);
            border-radius: 10px; font-size: 13px; color: var(--text-muted);
        }
        .top-legend-dot { width: 10px; height: 10px; border-radius: 50%; background: linear-gradient(135deg, #6c5ce7, #a29bfe); flex-shrink: 0; }

        .grants-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 20px; }

        /* ЗВИЧАЙНА КАРТКА */
        .grant-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 20px; padding: 28px; display: flex; flex-direction: column;
            transition: all 0.25s; position: relative;
        }
        .grant-card:hover { background: var(--bg-card-hover); border-color: var(--border-hover); transform: translateY(-3px); }

        /* TOP-3 КАРТКА */
        .grant-card.top-card {
            border-color: rgba(108,92,231,0.45);
            background: linear-gradient(135deg, rgba(108,92,231,0.08) 0%, rgba(162,155,254,0.04) 100%);
            box-shadow: 0 0 0 1px rgba(108,92,231,0.2), 0 4px 24px rgba(108,92,231,0.12);
        }
        .grant-card.top-card:hover {
            border-color: rgba(108,92,231,0.65);
            box-shadow: 0 0 0 1px rgba(108,92,231,0.35), 0 8px 40px rgba(108,92,231,0.2);
            transform: translateY(-5px);
        }

        /* TOP BADGE */
        .top-badge {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 11px; font-weight: 800; letter-spacing: 1px;
            text-transform: uppercase; color: white;
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            padding: 6px 14px; border-radius: 999px; margin-bottom: 14px;
            width: fit-content;
        }
        .top-badge-num {
            width: 18px; height: 18px; border-radius: 50%;
            background: rgba(255,255,255,0.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 900;
        }

        .card-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
        .card-tag { font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--accent-2); background: rgba(108,92,231,0.1); border: 1px solid rgba(108,92,231,0.2); padding: 4px 10px; border-radius: 6px; }
        .card-score { font-size: 13px; font-weight: 800; padding: 4px 12px; border-radius: 999px; border: 1px solid; }
        .score-high { color: #00b894; background: rgba(0,184,148,0.08); border-color: rgba(0,184,148,0.2); }
        .score-mid { color: #fdcb6e; background: rgba(253,203,110,0.08); border-color: rgba(253,203,110,0.2); }
        .score-low { color: var(--danger); background: rgba(225,112,85,0.08); border-color: rgba(225,112,85,0.2); }

        .card-title { font-size: 18px; font-weight: 700; line-height: 1.35; letter-spacing: -0.3px; color: var(--text); margin-bottom: 12px; }
        .card-desc { font-size: 13px; color: var(--text-muted); line-height: 1.65; margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .card-info { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px; }
        .info-item { padding: 12px 14px; background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 10px; }
        .info-item-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 5px; }
        .info-item-value { font-size: 13px; font-weight: 600; color: var(--text-mid); }

        .recommendation { padding: 12px 14px; background: rgba(0,184,148,0.06); border: 1px solid rgba(0,184,148,0.15); border-radius: 10px; margin-bottom: 16px; }
        .recommendation-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--success); margin-bottom: 6px; }
        .recommendation-text { font-size: 13px; color: var(--text-mid); line-height: 1.6; }

        .card-link { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: var(--accent-2); text-decoration: none; padding: 10px 16px; border: 1px solid rgba(108,92,231,0.25); border-radius: 9px; background: rgba(108,92,231,0.06); transition: all 0.2s; align-self: flex-start; margin-top: auto; }
        .card-link:hover { background: rgba(108,92,231,0.14); border-color: rgba(108,92,231,0.4); }

        .empty { grid-column: 1 / -1; text-align: center; padding: 80px 20px; border: 1px dashed var(--border); border-radius: 16px; }
        .empty-icon { width: 52px; height: 52px; border-radius: 14px; background: rgba(108,92,231,0.08); border: 1px solid rgba(108,92,231,0.15); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .empty-icon svg { width: 24px; height: 24px; stroke: var(--accent-2); fill: none; stroke-width: 1.5; stroke-linecap: round; }
        .empty h3 { font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
        .empty p { font-size: 14px; color: var(--text-muted); max-width: 480px; margin: 0 auto; line-height: 1.6; }

        .footer { position: relative; z-index: 1; text-align: center; padding: 28px; border-top: 1px solid var(--border); font-size: 12px; color: var(--text-muted); }

        @media (max-width: 768px) {
            .hero h1 { font-size: 36px; }
            .nav { padding: 0 20px; }
            .results-section { padding: 0 16px 80px; }
            .grants-grid { grid-template-columns: 1fr; }
            .profile-banner { flex-direction: column; gap: 12px; text-align: center; }
        }
    </style>
</head>
<body>

<nav class="nav">
    <a href="/grants" class="nav-logo"><div class="nav-logo-dot"></div>GrantAI</a>
    <div class="nav-right">
        <a href="/profile/edit" class="nav-link">Мій профіль</a>
        <span class="nav-user">{{ auth()->user()->name }}</span>
        <form method="POST" action="/logout" style="margin:0">
            @csrf
            <button type="submit" class="btn-logout">Вийти</button>
        </form>
    </div>
</nav>

<div class="hero">
    <div class="hero-label">AI Grant Agent</div>
    <h1>Знайди свій<br><span>грант</span></h1>
    <p class="hero-sub">Опишіть що шукаєте — AI-агент проаналізує ваш профіль за 10 метриками і підбере найрелевантніші гранти.</p>
</div>

@php $profile = auth()->user()->profile; @endphp

<div class="profile-section">
    @if($profile && $profile->org_type)
        <div class="profile-banner">
            <div class="profile-banner-text">
                Профіль: <strong>{{ $profile->organization_name ?: $profile->org_type }}</strong>
                @if($profile->activity_field) · <strong>{{ $profile->activity_field }}</strong> @endif
                @if($profile->country) · <strong>{{ $profile->country }}</strong> @endif
            </div>
            <a href="/profile/edit" class="profile-banner-link">Редагувати профіль</a>
        </div>
    @else
        <div class="profile-banner">
            <div class="profile-banner-text">
                <strong>Заповніть профіль</strong> — AI-агент зможе точніше підбирати гранти за 10 метриками
            </div>
            <a href="/profile/edit" class="profile-banner-link">Заповнити профіль</a>
        </div>
    @endif
</div>

<div class="prompt-section" style="margin-top: 20px;">
    <div class="prompt-card">
        <div class="prompt-title">Запит до AI-агента</div>
        <div class="prompt-hint">
            Опишіть що саме шукаєте. Наприклад: <span>"Шукаю грант на навчання в Європі в сфері IT, бюджет до $10,000"</span>
        </div>
        <form method="POST" action="/grants/search">
            @csrf
            <div class="prompt-field">
                <textarea name="prompt" placeholder="Опишіть що шукаєте — AI-агент врахує ваш профіль і знайде найкращі гранти...">{{ $query ?? '' }}</textarea>
            </div>
            <div class="prompt-actions">
                <button type="submit" class="btn-search">Знайти гранти</button>
                <a href="/grants" class="btn-reset">Скинути</a>
            </div>
        </form>

        @if(isset($query) && $query)
            <div class="current-query">
                <strong>Поточний запит:</strong> {{ $query }}
            </div>
        @endif
    </div>
</div>

@if(isset($error))
    <div class="error-bar"><div class="error-inner">{{ $error }}</div></div>
@endif

<div class="results-section">
    <div class="results-header">
        <div class="results-title">Результати пошуку</div>
        @if(count($grants) > 0)
            <div class="results-count">{{ count($grants) }} знайдено</div>
        @endif
    </div>

    @if(count($grants) > 0)
        <div class="top-legend">
            <div class="top-legend-dot"></div>
            Перші 3 результати — найкращий збіг з вашим профілем. Вони також надіслані на вашу пошту.
        </div>
    @endif

    <div class="grants-grid">
        @if(isset($grants) && count($grants) > 0)
            @foreach($grants as $index => $grant)
                @php
                    $isTop = $index < 3;
                    $score = $grant->relevance_score ?? 0;
                    $scoreClass = $score >= 80 ? 'score-high' : ($score >= 60 ? 'score-mid' : 'score-low');
                @endphp
                <div class="grant-card {{ $isTop ? 'top-card' : '' }}">

                    @if($isTop)
                        <div class="top-badge">
                            <div class="top-badge-num">{{ $index + 1 }}</div>
                            Найкращий збіг
                        </div>
                    @endif

                    <div class="card-meta">
                        <div class="card-tag">{{ $grant->category ?: 'Грант' }}</div>
                        <div class="card-score {{ $scoreClass }}">{{ $score }}% збіг</div>
                    </div>

                    <div class="card-title">{{ $grant->title }}</div>
                    <div class="card-desc">{{ $grant->description }}</div>

                    <div class="card-info">
                        <div class="info-item">
                            <div class="info-item-label">Країна</div>
                            <div class="info-item-value">{{ $grant->country ?: 'Міжнародний' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Фінансування</div>
                            <div class="info-item-value">{{ $grant->funding_amount ?: 'Уточнити' }}</div>
                        </div>
                        <div class="info-item" style="grid-column: 1 / -1">
                            <div class="info-item-label">Дедлайн</div>
                            <div class="info-item-value">{{ $grant->deadline ?: 'Уточнити на сайті' }}</div>
                        </div>
                    </div>

                    @if(isset($grant->recommendation) && $grant->recommendation)
                        <div class="recommendation">
                            <div class="recommendation-title">AI Рекомендація</div>
                            <div class="recommendation-text">{{ $grant->recommendation }}</div>
                        </div>
                    @endif

                    <a href="/grants/{{ $grant->id }}" class="card-link">Детальніше &nbsp;→</a>
                </div>
            @endforeach
        @else
            <div class="empty">
                <div class="empty-icon">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </div>
                <h3>Опишіть що шукаєте</h3>
                <p>Напишіть запит у вільній формі — AI-агент врахує ваш профіль і знайде найкращі гранти</p>
            </div>
        @endif
    </div>
</div>

<div class="footer">GrantAI &nbsp;·&nbsp; {{ date('Y') }}</div>

</body>
</html>
