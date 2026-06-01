<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | Applicant Registry System</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        :root {
            --ink: #17223b;
            --muted: #64748b;
            --panel-border: rgba(148, 163, 184, 0.18);
            --panel-shadow: 0 30px 80px rgba(15, 23, 42, 0.12);
            --accent: #0b74d1;
            --accent-soft: rgba(11, 116, 209, 0.09);
            --bg-start: #eef3fb;
            --bg-end: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 14% 82%, rgba(82, 146, 255, 0.18), transparent 22%),
                radial-gradient(circle at 84% 18%, rgba(66, 153, 225, 0.16), transparent 18%),
                linear-gradient(135deg, var(--bg-start) 0%, #f7faff 46%, var(--bg-end) 100%);
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        .auth-card {
            width: 100%;
            min-height: calc(100vh - 44px);
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(12px);
            border: 1px solid var(--panel-border);
            overflow: hidden;
            box-shadow: var(--panel-shadow);
        }

        .hero-panel {
            position: relative;
            min-height: 100%;
            padding: clamp(2rem, 4vw, 4rem);
            display: flex;
            align-items: center;
            background:
                radial-gradient(circle at 8% 90%, rgba(12, 89, 186, 0.16), transparent 24%),
                radial-gradient(circle at 92% 14%, rgba(102, 175, 255, 0.2), transparent 22%),
                linear-gradient(135deg, #f7f9ff 0%, #edf3fb 52%, #eef5ff 100%);
        }

        .hero-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(255, 255, 255, 0.35), transparent 40%),
                linear-gradient(180deg, transparent, rgba(255, 255, 255, 0.16));
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 560px;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        .brand-logo {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 8px 22px rgba(11, 116, 209, 0.18);
            background: #fff;
        }

        .brand-kicker {
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            color: var(--accent);
            text-transform: uppercase;
            line-height: 1;
        }

        .brand-title {
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 800;
            line-height: 1.05;
            margin: 0.15rem 0 1.5rem;
            color: var(--ink);
        }

        .hero-copy {
            font-size: clamp(1.05rem, 1.6vw, 1.35rem);
            line-height: 1.65;
            color: var(--muted);
            margin-bottom: 1.8rem;
            max-width: 34rem;
        }

        .feature-list {
            display: grid;
            gap: 1rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #475569;
            font-size: 0.98rem;
        }

        .feature-icon {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(11, 116, 209, 0.12);
            color: var(--accent);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            flex: 0 0 auto;
        }

        .auth-panel {
            background: rgba(255, 255, 255, 0.94);
            padding: clamp(2rem, 4vw, 4rem);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signin-card {
            width: 100%;
            max-width: 370px;
            text-align: center;
        }

        .signin-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 0.45rem;
        }

        .signin-subtitle {
            color: var(--muted);
            font-size: 0.98rem;
            margin-bottom: 2.5rem;
        }

        .microsoft-btn {
            width: 100%;
            border: 0;
            border-radius: 14px;
            padding: 0.95rem 1.1rem;
            background: linear-gradient(180deg, #0f81dc 0%, #0b74d1 100%);
            color: #fff;
            font-weight: 800;
            box-shadow: 0 14px 24px rgba(11, 116, 209, 0.24);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }

        .microsoft-btn:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 18px 30px rgba(11, 116, 209, 0.28);
        }

        .microsoft-icon {
            width: 18px;
            height: 18px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1px;
            flex: 0 0 auto;
        }

        .microsoft-icon span {
            display: block;
            width: 8px;
            height: 8px;
        }

        .microsoft-icon .one { background: #f25022; }
        .microsoft-icon .two { background: #7fba00; }
        .microsoft-icon .three { background: #00a4ef; }
        .microsoft-icon .four { background: #ffb900; }

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 2rem 0;
            color: #c5cfde;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e4ebf4;
        }

        .support-text {
            color: #8aa0be;
            font-size: 0.95rem;
            line-height: 1.65;
            margin-top: 1.6rem;
        }

        .support-text a {
            color: var(--accent);
            font-weight: 800;
            text-decoration: none;
        }

        .support-text a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 14px;
            text-align: left;
        }

        @media (max-width: 991.98px) {
            .auth-card {
                min-height: auto;
                border-radius: 26px;
            }

            .hero-panel {
                padding-bottom: 2rem;
                min-height: auto;
            }

            .signin-card {
                max-width: 100%;
            }
        }

        @media (max-width: 767.98px) {

            .auth-card {
                border-radius: 0;
                min-height: 100vh;
            }

            .hero-panel,
            .auth-panel {
                padding: 1.5rem;
            }

            .brand-title {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body>
    <main class="auth-shell">
        <section class="auth-card row g-0">
            <div class="col-lg-8 hero-panel">
                <div class="hero-content">
                    <div class="brand-badge">
                        <img src="{{ asset('images/logo_peso.png') }}" alt="System logo" class="brand-logo">
                        <div>
                            <div class="brand-kicker">PESO</div>
                            <div class="brand-kicker" style="letter-spacing: 0.14em; color: #1d4ed8; margin-top: 2px;">
                                Applicant Registry System
                            </div>
                        </div>
                    </div>

                    <h1 class="brand-title">Monitor applications in real time.</h1>
                    <p class="hero-copy">
                        Track applicant records, manage requirements, and keep document workflows moving without
                        leaving the portal.
                    </p>

                    <ul class="feature-list">
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            Submit and review applicant requirements instantly
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            Real-time status updates for staff and admin
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            Secure Microsoft account login
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 auth-panel">
                <div class="signin-card">
                    <h2 class="signin-title">Welcome back</h2>
                    <div class="signin-subtitle">Sign in to your account to continue</div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <a href="{{ route('login.azure.redirect') }}" class="microsoft-btn mt-3">
                        <span class="microsoft-icon" aria-hidden="true">
                            <span class="one"></span>
                            <span class="two"></span>
                            <span class="three"></span>
                            <span class="four"></span>
                        </span>
                        Sign in with Microsoft
                    </a>

                    <div class="auth-divider">Authorized access</div>

                    <div class="support-text">
                        Need assistance? Contact the
                        <a href="mailto:it.support@citrmu.gov.ph">IT Support Team</a>
                        for help accessing your account.
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
