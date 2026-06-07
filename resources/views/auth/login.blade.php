<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPark — Login</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, Arial, sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: #1e293b;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 24px 48px rgba(0,0,0,0.4);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            justify-content: center;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: #2563eb;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .logo-text {
            font-size: 26px;
            font-weight: 700;
            color: white;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 700;
            color: white;
            margin-bottom: 6px;
            text-align: center;
        }

        .auth-subtitle {
            font-size: 14px;
            color: #64748b;
            text-align: center;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        input[type=email], input[type=password] {
            width: 100%;
            padding: 12px 16px;
            background: #0f172a;
            border: 1.5px solid #334155;
            border-radius: 10px;
            font-size: 14px;
            color: white;
            outline: none;
            transition: border-color 0.15s;
        }

        input[type=email]:focus, input[type=password]:focus {
            border-color: #2563eb;
        }

        input[type=email]::placeholder, input[type=password]::placeholder {
            color: #475569;
        }

        .remember-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #64748b;
            cursor: pointer;
        }

        .remember-label input {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
        }

        .forgot-link {
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
        }

        .forgot-link:hover { color: #60a5fa; }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
            margin-bottom: 20px;
        }

        .btn-login:hover { background: #1d4ed8; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: #334155;
        }

        .divider-text {
            font-size: 12px;
            color: #475569;
        }

        .register-link {
            display: block;
            width: 100%;
            padding: 13px;
            background: transparent;
            color: #94a3b8;
            border: 1.5px solid #334155;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.15s;
        }

        .register-link:hover {
            border-color: #2563eb;
            color: #60a5fa;
        }

        .error-msg {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            border-left: 4px solid #dc2626;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="logo">
            <div class="logo-icon">🅿</div>
            <div class="logo-text">SmartPark</div>
        </div>

        <div class="auth-title">Welcome back</div>
        <div class="auth-subtitle">Sign in to your SmartPark account</div>

        @if(session('status'))
            <div class="error-msg" style="background:#dcfce7; color:#166534; border-color:#16a34a;">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-msg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email"
                       placeholder="you@example.com"
                       value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <div class="divider">
            <div class="divider-line"></div>
            <div class="divider-text">New to SmartPark?</div>
            <div class="divider-line"></div>
        </div>

        <a href="{{ route('register') }}" class="register-link">Create an account</a>
    </div>
</body>
</html>