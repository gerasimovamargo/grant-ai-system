<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $grant->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0a0a0f; --bg-card: #111118; --border: rgba(255,255,255,0.07);
            --accent: #6c5ce7; --accent-2: #a29bfe; --text: #f0f0f5;
            --text-muted: #6b6b80; --text-mid: #9898aa; --success: #00b894;
        }
        body { font-family: 'Manrope', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
        body::before {
            content: ''; position: fixed; inset: 0;
            background: radial-gradient(ellipse 80% 60% at 10% 0%, rgba(108,92,231,0.12) 0%, transparent 60%);
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
        .btn-back {
            font-family: 'Manrope', sans-serif; font-size: 13px; font-weight: 600;
            color: var(--text-muted); background: transparent;
            border: 1px solid var(--border); border-radius: 8px;
            padding: 8px 16px; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .btn-back:hover { color: var(--text); border-color: rgba(255,255,255,0.14); background: rgba(255,255,255,0.04); }
        .container { position: relative; z-index: 1; max-width: 860px; margin: 0 auto; padding: 60px 24px 100px; }
        .breadcrumb { font-size: 13px; color: var(--text-muted); margin-bottom: 32px; }
        .breadcrumb a { color: var(--accent-2); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .tag {
            display: inline-block; font-size: 11px; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: var(--accent-2);
            background: rgba(108,92,231,0.1); border: 1px solid rgba(108,92,231,0.2);
            padding: 5px 12px; border-radius: 6px; margin-bottom: 20px;
        }
        h1 { font-size: 42px; font-weight: 800; line-height: 1.15; letter-spacing: -1px; margin-bottom: 20px; }
        .description {
            font-size: 16px; color: var(--text-mid); line-height: 1.8;
            margin-bottom: 40px; padding-bottom: 40px;
            border-bottom: 1px solid var(--border);
        }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 40px; }
        .info-card {
            padding: 20px; background: var(--bg-card);
            border: 1px solid var(--border); border-radius: 14px;
        }
        .info-card-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 8px; }
        .info-card-value { font-size: 15px; font-weight: 600; color: var(--text); line-height: 1.5; }
        .score-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 18px; border-radius: 10px;
            background: rgba(0,184,148,0.08); border: 1px solid rgba(0,184,148,0.2);
            color: var(--success); font-size: 14px; font-weight: 700; margin-bottom: 32px;
        }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 32px; border-radius: 12px;
            background: var(--accent); color: white;
            font-family: 'Manrope', sans-serif; font-size: 15px; font-weight: 700;
            text-decoration: none; transition: background 0.2s;
        }
        .btn-primary:hover { background: #5a4bd1; }
        .footer { text-align: center; padding: 28px; border-top: 1px solid var(--border); font-size: 12px; color: var(--text-muted); position: relative; z-index: 1; }
    </style>
</head>
<body>

<nav class="nav">
    <a href="/grants" class="nav-logo">
        <div class="nav-logo-dot"></div>
        GrantAI
    </a>
    <a href="/grants" class="btn-back">← Назад</a>
</nav>

<div class="container">
    <div class="breadcrumb">
        <a href="/grants">Гранти</a> &nbsp;/&nbsp; {{ $grant->title }}
    </div>

    <div class="tag">{{ $grant->category ?: 'Грант' }}</div>

    <h1>{{ $grant->title }}</h1>

    <div class="score-badge">
        92% збіг із запитом
    </div>

    <div class="description">{{ $grant->description }}</div>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-card-label">Країна</div>
            <div class="info-card-value">{{ $grant->country ?: 'Міжнародний' }}</div>
        </div>
        <div class="info-card">
            <div class="info-card-label">Фінансування</div>
            <div class="info-card-value">{{ $grant->funding_amount ?: 'Уточнити' }}</div>
        </div>
        <div class="info-card" style="grid-column: 1 / -1">
            <div class="info-card-label">Дедлайн</div>
            <div class="info-card-value">{{ $grant->deadline ?: 'Уточнити на офіційному сайті' }}</div>
        </div>
    </div>

    <a href="{{ $grant->source_link ?: '#' }}" target="_blank" class="btn-primary">
        Перейти на офіційний сайт →
    </a>
</div>

<div class="footer">GrantAI &nbsp;·&nbsp; {{ date('Y') }}</div>

</body>
</html>
