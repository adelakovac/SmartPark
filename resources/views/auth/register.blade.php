<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPark — Register</title>
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

        input[type=text], input[type=email], input[type=password] {
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

        input:focus { border-color: #2563eb; }
        input::placeholder { color: #475569; }

        .btn-register {
            width: 100%;
            padding: 13px;
            background: #16a34a;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
            margin-bottom: 20px;
        }

        .btn-register:hover { background: #15803d; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .divider-line { flex: 1; height: 1px; background: #334155; }
        .divider-text { font-size: 12px; color: #475569; }

        .login-link {
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

        .login-link:hover { border-color: #2563eb; color: #60a5fa; }

        .error-msg {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            border-left: 4px solid #dc2626;
        }

        .error-msg ul { margin-left: 16px; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="logo">
            <div class="logo-icon">🅿</div>
            <div class="logo-text">SmartPark</div>
        </div>

        <div class="auth-title">Create account</div>
        <div class="auth-subtitle">Join SmartPark to reserve parking spots</div>

        @if($errors->any())
            <div class="error-msg">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name"
                       placeholder="Your full name"
                       value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email"
                       placeholder="you@example.com"
                       value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Min. 8 characters" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation"
                       name="password_confirmation"
                       placeholder="Repeat your password" required>
            </div>

            <button type="submit" class="btn-register">Create Account</button>
        </form>

        <div class="divider">
            <div class="divider-line"></div>
            <div class="divider-text">Already have an account?</div>
            <div class="divider-line"></div>
        </div>

        <a href="{{ route('login') }}" class="login-link">Sign in instead</a>
    </div>
</body>
</html>