<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GrantAI — Інтелектуальний пошук грантів</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0a0a0f; --bg-card: #111118; --border: rgba(255,255,255,0.07);
            --accent: #6c5ce7; --accent-2: #a29bfe;
            --text: #f0f0f5; --text-muted: #6b6b80; --text-mid: #9898aa;
            --success: #00b894;
        }
        body { font-family: 'Manrope', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; overflow-x: hidden; }
        body::before {
            content: ''; position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 15% 10%, rgba(108,92,231,0.15) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 85% 80%, rgba(162,155,254,0.1) 0%, transparent 55%),
                radial-gradient(ellipse 40% 30% at 50% 50%, rgba(0,184,148,0.04) 0%, transparent 50%);
            pointer-events: none; z-index: 0;
        }


        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 60px; height: 64px;
            border-bottom: 1px solid var(--border);
            background: rgba(10,10,15,0.8); backdrop-filter: blur(20px);
        }
        .nav-logo { font-size: 20px; font-weight: 800; color: var(--text); text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .nav-logo-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }
        .nav-links { display: flex; align-items: center; gap: 12px; }
        .btn-login {
            font-family: 'Manrope', sans-serif; font-size: 14px; font-weight: 600;
            color: var(--text-muted); background: transparent;
            border: 1px solid var(--border); border-radius: 10px;
            padding: 10px 20px; cursor: pointer; text-decoration: none;
            transition: all 0.2s;
        }
        .btn-login:hover { color: var(--text); border-color: rgba(255,255,255,0.14); background: rgba(255,255,255,0.04); }
        .btn-register {
            font-family: 'Manrope', sans-serif; font-size: 14px; font-weight: 700;
            color: white; background: var(--accent);
            border: none; border-radius: 10px;
            padding: 10px 20px; cursor: pointer; text-decoration: none;
            transition: background 0.2s;
        }
        .btn-register:hover { background: #5a4bd1; }


        .hero {
            position: relative; z-index: 1;
            min-height: 100vh; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center; padding: 100px 24px 60px;
        }
        .hero-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--success); animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
        .hero h1 {
            font-size: 80px; font-weight: 800; line-height: 1.05;
            letter-spacing: -3px; margin-bottom: 24px;
        }
        .hero h1 .line1 { display: block; color: var(--text); }
        .hero h1 .line2 { display: block; color: var(--accent-2); }
        .hero-sub {
            font-size: 18px; color: var(--text-muted); line-height: 1.8;
            max-width: 580px; margin: 0 auto 48px;
        }
        .hero-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
        .btn-cta {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'Manrope', sans-serif; font-size: 16px; font-weight: 700;
            color: white; background: var(--accent);
            border: none; border-radius: 12px;
            padding: 16px 32px; cursor: pointer; text-decoration: none;
            transition: all 0.2s;
        }
        .btn-cta:hover { background: #5a4bd1; transform: translateY(-2px); box-shadow: 0 8px 30px rgba(108,92,231,0.3); }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'Manrope', sans-serif; font-size: 16px; font-weight: 600;
            color: var(--text-muted); background: transparent;
            border: 1px solid var(--border); border-radius: 12px;
            padding: 16px 32px; cursor: pointer; text-decoration: none;
            transition: all 0.2s;
        }
        .btn-secondary:hover { color: var(--text); border-color: rgba(255,255,255,0.14); background: rgba(255,255,255,0.04); }


        .stats {
            position: relative; z-index: 1;
            display: flex; justify-content: center; gap: 0;
            max-width: 640px; margin: 0 auto;
            border: 1px solid var(--border); border-radius: 16px;
            background: var(--bg-card); overflow: hidden;
        }
        .stat { flex: 1; padding: 24px; text-align: center; border-right: 1px solid var(--border); }
        .stat:last-child { border-right: none; }
        .stat-num { font-size: 32px; font-weight: 800; color: var(--text); letter-spacing: -1px; }
        .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 4px; }


        .section { position: relative; z-index: 1; max-width: 1100px; margin: 100px auto; padding: 0 40px; }
        .section-label { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-2); margin-bottom: 16px; text-align: center; }
        .section-title { font-size: 40px; font-weight: 800; letter-spacing: -1.5px; text-align: center; margin-bottom: 16px; }
        .section-sub { font-size: 16px; color: var(--text-muted); text-align: center; max-width: 500px; margin: 0 auto 60px; line-height: 1.7; }

        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .step { background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px; padding: 32px; position: relative; transition: all 0.3s; }
        .step:hover { border-color: rgba(108,92,231,0.3); transform: translateY(-4px); }
        .step-num {
            width: 40px; height: 40px; border-radius: 12px;
            background: rgba(108,92,231,0.1); border: 1px solid rgba(108,92,231,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 800; color: var(--accent-2);
            margin-bottom: 20px;
        }
        .step h3 { font-size: 18px; font-weight: 700; margin-bottom: 12px; }
        .step p { font-size: 14px; color: var(--text-muted); line-height: 1.7; }


        .features { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .feature { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 28px; display: flex; gap: 20px; align-items: flex-start; transition: all 0.3s; }
        .feature:hover { border-color: rgba(108,92,231,0.25); }
        .feature-icon {
            width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
            background: rgba(108,92,231,0.1); border: 1px solid rgba(108,92,231,0.2);
            display: flex; align-items: center; justify-content: center; font-size: 20px;
        }
        .feature-text h3 { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
        .feature-text p { font-size: 13px; color: var(--text-muted); line-height: 1.6; }


        .cta-section {
            position: relative; z-index: 1;
            max-width: 800px; margin: 80px auto 100px;
            padding: 60px 40px; text-align: center;
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 24px;
        }
        .cta-section h2 { font-size: 40px; font-weight: 800; letter-spacing: -1.5px; margin-bottom: 16px; }
        .cta-section p { font-size: 16px; color: var(--text-muted); margin-bottom: 36px; line-height: 1.7; }


        .footer { position: relative; z-index: 1; text-align: center; padding: 32px; border-top: 1px solid var(--border); font-size: 13px; color: var(--text-muted); }

        @media (max-width: 768px) {
            .hero h1 { font-size: 48px; letter-spacing: -2px; }
            .nav { padding: 0 20px; }
            .steps { grid-template-columns: 1fr; }
            .features { grid-template-columns: 1fr; }
            .section { padding: 0 20px; }
            .stats { margin: 0 20px; }
        }
    </style>
</head>
<body>

<nav class="nav">
    <a href="/" class="nav-logo"><div class="nav-logo-dot"></div>GrantAI</a>
    <div class="nav-links">
        <a href="/login" class="btn-login">Увійти</a>
        <a href="/register" class="btn-register">Розпочати</a>
    </div>
</nav>


<div class="hero">
    <div class="hero-badge">
    </div>
    <h1>
        <span class="line1">Знайди свій грант</span>
        <span class="line2">за допомогою AI Агенту</span>
    </h1>
    <p class="hero-sub">AI Агент аналізує ваш профіль, оцінює гранти і підбирає найрелевантніші програми саме для вас!</p>
    <div class="hero-actions">
        <a href="/register" class="btn-cta">Почати безкоштовно →</a>
        <a href="/login" class="btn-secondary">Увійти до системи</a>
    </div>
</div>

<div class="section">
    <div class="section-label">Як це працює</div>
    <h2 class="section-title">Три кроки до гранту</h2>
    <p class="section-sub">AI-агент автоматизує весь процес пошуку та оцінки грантових програм</p>

    <div class="steps">
        <div class="step">
            <div class="step-num">1</div>
            <h3>Заповніть профіль</h3>
            <p>Вкажіть тип організації, сферу діяльності, країну та цілі. AI-агент використає цю інформацію для персоналізації результатів.</p>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <h3>Опишіть запит</h3>
            <p>Напишіть у вільній формі що саме шукаєте. Агент проаналізує запит разом з вашим профілем і знайде найкращі варіанти.</p>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <h3>Отримайте результат</h3>
            <p>Система повертає TOP-3 гранти з персональними рекомендаціями та відсотком відповідності саме вашим потребам.</p>
        </div>
    </div>
</div>

<div class="footer">
    GrantAI &nbsp;·&nbsp; {{ date('Y') }} &nbsp;·&nbsp; Інтелектуальна система пошуку грантів
</div>

</body>
</html>
