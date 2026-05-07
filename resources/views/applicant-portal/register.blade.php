<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Applicant Register</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --line: rgba(148, 163, 184, 0.22);
            --panel: rgba(255, 255, 255, 0.92);
            --primary: #1d4ed8;
            --primary-soft: rgba(29, 78, 216, 0.12);
            --accent: #10b981;
            --bg: #eef4fb;
        }

        body {
            min-height: 100vh;
            color: var(--ink);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(29, 78, 216, 0.14), transparent 26%),
                radial-gradient(circle at 85% 15%, rgba(16, 185, 129, 0.1), transparent 22%),
                radial-gradient(circle at bottom right, rgba(59, 130, 246, 0.08), transparent 24%),
                linear-gradient(180deg, #f9fbff 0%, var(--bg) 100%);
        }

        .shell {
            min-height: 100vh;
            padding: 22px 0 36px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 14px 18px;
            border: 1px solid var(--line);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(14px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-mark {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-mark img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .brand-copy small {
            display: block;
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.11em;
            text-transform: uppercase;
        }

        .brand-copy h1 {
            margin: 0;
            color: var(--ink);
            font-size: 1.02rem;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .ghost-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 1rem;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.28);
            background: rgba(255, 255, 255, 0.82);
            color: var(--ink);
            text-decoration: none;
            font-weight: 800;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        }

        .ghost-link:hover {
            color: var(--primary);
            border-color: rgba(29, 78, 216, 0.28);
        }

        .register-shell {
            display: grid;
            place-items: center;
        }

        .register-card {
            width: min(760px, 100%);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 34px;
            background: var(--panel);
            backdrop-filter: blur(16px);
            box-shadow: 0 24px 54px rgba(15, 23, 42, 0.08);
        }

        .register-card::before {
            content: "";
            position: absolute;
            right: -80px;
            top: -80px;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.08);
        }

        .register-head {
            padding: 30px 30px 20px;
        }

        .register-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 0.76rem;
            font-weight: 900;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .register-title {
            margin: 16px 0 10px;
            font-size: clamp(2rem, 4vw, 3.2rem);
            line-height: 0.98;
            letter-spacing: -0.05em;
            font-weight: 950;
        }

        .register-copy {
            max-width: 58ch;
            color: var(--muted);
            margin-bottom: 0;
        }

        .feature-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
            margin-top: 1.2rem;
        }

        .feature-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 0.85rem;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.18);
            background: #fff;
            color: #334155;
            font-size: 0.84rem;
            font-weight: 800;
        }

        .register-body {
            padding: 0 30px 30px;
        }

        .mini-panel {
            padding: 1rem 1.05rem;
            border-radius: 24px;
            background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }

        .mini-label {
            display: block;
            margin-bottom: 0.25rem;
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .mini-value {
            color: var(--ink);
            font-weight: 800;
        }

        .form-card {
            padding: 24px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }

        .form-label {
            margin-bottom: 0.45rem;
            color: #334155;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .form-control {
            min-height: 50px;
            border-radius: 14px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: rgba(248, 251, 255, 0.95);
            padding: 0.75rem 0.95rem;
        }

        .form-control:focus {
            border-color: rgba(59, 130, 246, 0.55);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
            background: #fff;
        }

        .btn-submit {
            min-width: 180px;
            padding: 0.92rem 1.25rem;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #fff;
            font-weight: 900;
            box-shadow: 0 16px 30px rgba(29, 78, 216, 0.22);
        }

        .btn-submit:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 18px;
        }

        .form-note {
            color: var(--muted);
        }

        @media (max-width: 767.98px) {
            .topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .ghost-link {
                justify-content: center;
                width: 100%;
            }

            .register-head,
            .register-body {
                padding-left: 18px;
                padding-right: 18px;
            }

            .register-head {
                padding-top: 22px;
            }

            .register-body {
                padding-bottom: 18px;
            }

            .form-card,
            .register-card {
                border-radius: 24px;
            }

            .btn-submit {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="shell">
        <div class="container py-3 py-lg-4">
            <div class="topbar mb-3 mb-lg-4">
                <div class="brand">
                    <div class="brand-mark">
                        <img src="{{ asset('images/logo_peso.png') }}" alt="PESO Logo">
                    </div>
                    <div class="brand-copy">
                        <small>Applicant Registry</small>
                        <h1>Applicant Registration</h1>
                    </div>
                </div>

                <a href="{{ route('applicant.portal.login') }}" class="ghost-link">
                    <i class="bi bi-arrow-left"></i>
                    Back to Login
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-3">
                    <strong>Please check the form:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="register-shell">
                <div class="register-card">
                    <div class="register-head">
                        <div class="row g-4 align-items-center">
                            <div class="col-lg-8">
                                <span class="register-kicker"><i class="bi bi-person-plus"></i> Create account</span>
                                <h2 class="register-title">Create your username and password.</h2>
                                <p class="register-copy">
                                    Keep it simple. Choose your username and set your password, then complete your details after signing in.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="register-body">
                        <div class="form-card">
                            <form action="{{ route('applicant.portal.register.store') }}" method="POST">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" name="applicant_code" class="form-control"
                                            value="{{ old('applicant_code') }}" placeholder="your.username" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Create a password" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Repeat your password" required>
                                    </div>
                                </div>

                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-4 pt-3 border-top">
                                    <p class="form-note mb-0">
                                        You will be signed in right after registration.
                                    </p>
                                    <button type="submit" class="btn btn-submit">
                                        <i class="bi bi-person-plus-fill me-2"></i>Create Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
