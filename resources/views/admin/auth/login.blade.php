<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            background: #0f172a;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }

        .login-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .login-brand-icon {
            width: 44px;
            height: 44px;
            background: #4f46e5;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-brand-icon svg { width: 24px; height: 24px; color: #fff; }

        .login-brand-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f1f5f9;
            letter-spacing: -0.025em;
        }

        .login-card {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .login-card h1 {
            text-align: center;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.375rem;
        }

        .login-card p {
            text-align: center;
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1.75rem;
        }

        .form-group { margin-bottom: 1.25rem; }

        .form-group label {
            display: block;
            margin-bottom: 0.375rem;
            font-weight: 600;
            font-size: 0.8rem;
            color: #475569;
        }

        .form-group input {
            width: 100%;
            padding: 0.65rem 0.875rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: inherit;
            color: #1e293b;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-group input::placeholder { color: #94a3b8; }

        .btn-login {
            display: block;
            width: 100%;
            padding: 0.7rem;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-login:hover {
            background: #4338ca;
            box-shadow: 0 1px 3px rgba(79, 70, 229, 0.3);
        }

        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            background: #fef2f2;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #fecaca;
            margin-bottom: 1.25rem;
            font-size: 0.8rem;
        }

        .alert-error svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-brand">
            <div class="login-brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"/></svg>
            </div>
            <span class="login-brand-text">Admin Panel</span>
        </div>

        <div class="login-card">
            <h1>Welcome back</h1>
            <p>Sign in to your admin account</p>

            @if($errors->any())
                <div class="alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn-login">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>
