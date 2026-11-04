<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Login · Mahishika Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1rem;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background:
                radial-gradient(circle at 15% 20%, rgba(129, 140, 248, .45), transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(45, 212, 191, .35), transparent 40%),
                linear-gradient(135deg, #1e1b4b, #111827);
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2rem;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, .5);
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 56px;
            height: 56px;
            margin: 0 auto 1rem;
            border-radius: 16px;
            background: linear-gradient(135deg, #4338ca, #818cf8);
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .input-group-text {
            background: #f4f6fb;
            color: #6b7280;
        }

        .form-control:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 .2rem rgba(67, 56, 202, .2);
        }

        .btn-login {
            padding: .75rem;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, #4338ca, #6366f1);
            font-weight: 600;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #3730a3, #4f46e5);
        }
    </style>
</head>
<body>
<main class="login-card">
    <div class="text-center mb-4">
        <div class="brand-mark">M</div>
        <h1 class="h4 fw-bold mb-1">Welcome back</h1>
        <p class="text-muted mb-0">Sign in to manage the Mahishika mega menu</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-medium" for="email">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                       value="{{ old('email') }}" autocomplete="username" required autofocus>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" for="password">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                       autocomplete="current-password" required>
                <button class="btn btn-outline-secondary" type="button" id="toggle-password" aria-label="Show password">
                    <i class="bi bi-eye"></i>
                </button>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <button class="btn btn-primary btn-login w-100">
            <i class="bi bi-box-arrow-in-right"></i> Sign in
        </button>
    </form>
</main>

<script>
    // Show / hide password
    document.getElementById('toggle-password').addEventListener('click', function () {
        const input = document.getElementById('password');
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        this.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
    });
</script>
@include('admin.partials.sweetalert')
</body>
</html>
