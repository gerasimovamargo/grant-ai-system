<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід — GrantAI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0a0a0f; --bg-card: #111118; --border: rgba(255,255,255,0.07);
            --border-hover: rgba(255,255,255,0.14); --accent: #6c5ce7; --accent-2: #a29bfe;
            --text: #f0f0f5; --text-muted: #6b6b80; --danger: #e17055;
        }
        body {
            font-family: 'Manrope', sans-serif; background: var(--bg);
            color: var(--text); min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        body::before {
            content: ''; position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 10% 0%, rgba(108,92,231,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 90% 100%, rgba(162,155,254,0.08) 0%, transparent 55%);
            pointer-events: none; z-index: 0;
        }
        .card {
            position: relative; z-index: 1;
            width: 100%; max-width: 420px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            margin: 24px;
        }
        .logo {
            display: flex; align-items: center; gap: 10px;
            font-size: 20px; font-weight: 800; color: var(--text);
            text-decoration: none; margin-bottom: 32px;
        }
        .logo-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }
        h1 { font-size: 24px; font-weight: 800; margin-bottom: 8px; letter-spacing: -0.5px; }
        .subtitle { font-size: 14px; color: var(--text-muted); margin-bottom: 32px; }
        .field { margin-bottom: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px; letter-spacing: 0.3px; }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%; padding: 12px 16px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 10px; color: var(--text);
            font-family: 'Manrope', sans-serif; font-size: 14px;
            outline: none; transition: border-color 0.2s;
        }
        input:focus { border-color: rgba(108,92,231,0.5); }
        input::placeholder { color: var(--text-muted); }
        .error { font-size: 12px; color: var(--danger); margin-top: 6px; }
        .checkbox-row {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 24px;
        }
        .checkbox-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; }
        .checkbox-row label { margin: 0; font-size: 13px; color: var(--text-muted); cursor: pointer; }
        .btn {
            width: 100%; padding: 14px;
            background: var(--accent); color: white;
            border: none; border-radius: 10px;
            font-family: 'Manrope', sans-serif; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: background 0.2s; margin-bottom: 20px;
        }
        .btn:hover { background: #5a4bd1; }
        .divider {
            text-align: center; font-size: 13px;
            color: var(--text-muted); margin-bottom: 20px;
        }
        .link-row { text-align: center; font-size: 13px; color: var(--text-muted); }
        .link-row a { color: var(--accent-2); text-decoration: none; font-weight: 600; }
        .link-row a:hover { text-decoration: underline; }
        .forgot { display: block; text-align: right; font-size: 12px; color: var(--text-muted); text-decoration: none; margin-top: 6px; }
        .forgot:hover { color: var(--accent-2); }
    </style>
</head>
<body>
<div class="card">
    <a href="/" class="logo">
        <div class="logo-dot"></div>
        GrantAI
    </a>

    <h1>Вхід до системи</h1>
    <p class="subtitle">Введіть свої дані для входу</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
            @error('email')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label>Пароль</label>
            <input type="password" name="password" placeholder="••••••••" required>
            <a href="{{ route('password.request') }}" class="forgot">Забули пароль?</a>
            @error('password')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="checkbox-row">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Запам'ятати мене</label>
        </div>

        <button type="submit" class="btn">Увійти</button>

        <div class="link-row">
            Немає акаунту? <a href="{{ route('register') }}">Зареєструватись</a>
        </div>
    </form>
</div>
</body>
</html>
