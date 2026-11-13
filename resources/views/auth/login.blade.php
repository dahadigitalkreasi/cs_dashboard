
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#f5f7fb">

    <title>Login - CS Meta Ads</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            min-height: 100dvh;
            background:
                radial-gradient(
                    circle at top left,
                    #dbeafe 0%,
                    transparent 38%
                ),
                radial-gradient(
                    circle at bottom right,
                    #e0e7ff 0%,
                    transparent 35%
                ),
                #f5f7fb;

            font-family: system-ui, -apple-system,
                BlinkMacSystemFont, "Segoe UI", sans-serif;

            color: var(--text);
        }

        .login-wrapper {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
            border: 1px solid rgba(226,232,240,.8);
            border-radius: 24px;
            box-shadow:
                0 20px 60px rgba(15,23,42,.07);
            padding: 38px 34px;
        }

        .brand-icon {
            width: 68px;
            height: 68px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 18px;
            background: #eff6ff;
            border-radius: 20px;
            color: var(--primary);
            font-size: 30px;
        }

        .brand-title {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -.7px;
            margin-bottom: 6px;
        }

        .brand-description {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 0;
        }

        .form-section {
            margin-top: 32px;
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 9px;
            color: #334155;
        }

        .input-group-custom {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            font-size: 18px;
            color: #94a3b8;
            z-index: 2;
            pointer-events: none;
        }

        .custom-input {
            width: 100%;
            height: 52px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #ffffff;
            padding: 12px 45px 12px 45px;
            font-size: 15px;
            transition: all .2s ease;
        }

        .custom-input::placeholder {
            color: #94a3b8;
        }

        .custom-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37,99,235,.10);
        }

        .custom-input.is-invalid {
            border-color: #dc3545;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            color: #64748b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .toggle-password:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        .btn-login {
            background: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            min-height: 52px;
            font-size: 15px;
            font-weight: 700;
            transition: all .2s ease;
        }

        .btn-login:hover,
        .btn-login:focus {
            background: var(--primary-dark);
            color: #ffffff;
        }

        .btn-login:disabled {
            opacity: .75;
            cursor: not-allowed;
        }

        .login-footer {
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
            margin-top: 30px;
        }

        .login-alert {
            border-radius: 12px;
            font-size: 13px;
            padding: 12px 14px;
        }

        @media (max-width: 576px) {
            .login-wrapper {
                padding: 20px 14px;
            }

            .login-card {
                padding: 32px 23px;
                border-radius: 20px;
            }

            .brand-icon {
                width: 62px;
                height: 62px;
                font-size: 27px;
            }

            .brand-title {
                font-size: 24px;
            }

            .form-section {
                margin-top: 28px;
            }
        }

        @media (max-width: 360px) {
            .login-card {
                padding: 26px 18px;
            }
        }
    </style>
</head>

<body>

<main class="login-wrapper">

    <div class="login-card">

        <!-- BRAND -->
        <div class="text-center">

            <div class="brand-icon">
                <i class="bi bi-graph-up-arrow"></i>
            </div>

            <h1 class="brand-title">
                CS Meta Ads
            </h1>

            <p class="brand-description">
                Monitoring & Manajemen Pesanan
            </p>

        </div>

        <!-- FORM LOGIN -->
        <div class="form-section">

            @if($errors->any())
                <div class="alert alert-danger login-alert mb-4"
                     role="alert">

                    <i class="bi bi-exclamation-circle me-2"></i>

                    {{ $errors->first() }}

                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success login-alert mb-4"
                     role="status">

                    <i class="bi bi-check-circle me-2"></i>

                    {{ session('status') }}

                </div>
            @endif

            <form method="POST"
                  action="{{ route('login.submit') }}"
                  id="loginForm">

                @csrf

                <!-- USERNAME -->
                <div class="mb-4">

                    <label for="username"
                           class="form-label">
                        Username
                    </label>

                    <div class="input-group-custom">

                        <i class="bi bi-person input-icon"></i>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="custom-input @error('username') is-invalid @enderror"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            autocomplete="username"
                            maxlength="50"
                            required
                            autofocus
                        >

                    </div>

                </div>

                <!-- PASSWORD -->
                <div class="mb-4">

                    <label for="password"
                           class="form-label">
                        Password
                    </label>

                    <div class="input-group-custom">

                        <i class="bi bi-lock input-icon"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="custom-input"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="Tampilkan password"
                            aria-pressed="false"
                        >
                            <i class="bi bi-eye"
                               id="eyeIcon"></i>
                        </button>

                    </div>

                </div>

                <!-- BUTTON LOGIN -->
                <button
                    type="submit"
                    class="btn btn-login w-100 d-flex
                           align-items-center justify-content-center gap-2"
                    id="loginButton"
                >
                    <span id="buttonText">
                        Masuk ke Dashboard
                    </span>

                    <i class="bi bi-arrow-right"
                       id="buttonIcon"></i>
                </button>

            </form>

        </div>

        <!-- FOOTER -->
        <div class="login-footer">
            <i class="bi bi-shield-lock me-1"></i>
            Akses khusus pengguna terdaftar

            <div class="mt-2">
                &copy; {{ date('Y') }} CS Meta Ads
            </div>
        </div>

    </div>

</main>

<script>
    // ==========================================
    // SHOW / HIDE PASSWORD
    // ==========================================

    const passwordInput =
        document.getElementById('password');

    const togglePassword =
        document.getElementById('togglePassword');

    const eyeIcon =
        document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {

        const isHidden =
            passwordInput.type === 'password';

        passwordInput.type =
            isHidden ? 'text' : 'password';

        eyeIcon.className = isHidden
            ? 'bi bi-eye-slash'
            : 'bi bi-eye';

        togglePassword.setAttribute(
            'aria-label',
            isHidden ? 'Sembunyikan password'
                     : 'Tampilkan password'
        );

        togglePassword.setAttribute(
            'aria-pressed',
            String(isHidden)
        );
    });

    // ==========================================
    // PREVENT DOUBLE SUBMIT
    // ==========================================

    const loginForm =
        document.getElementById('loginForm');

    loginForm.addEventListener('submit', function () {

        const button =
            document.getElementById('loginButton');

        if (button.disabled) {
            return;
        }

        button.disabled = true;

        document.getElementById('buttonText')
            .textContent = 'Memproses...';

        document.getElementById('buttonIcon')
            .className =
                'spinner-border spinner-border-sm';
    });
</script>

</body>
</html>
