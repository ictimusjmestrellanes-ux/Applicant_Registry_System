<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>System Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            position: relative;
            background: #ffffff;
            color: #000000;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(10px);
        }

        body::before {
            top: -120px;
            right: -80px;
            width: 320px;
            height: 320px;
            background: rgba(0, 0, 0, 0.04);
        }

        body::after {
            left: -70px;
            bottom: -90px;
            width: 280px;
            height: 280px;
            background: rgba(0, 0, 0, 0.03);
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .auth-card {
            border-radius: 28px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            box-shadow: 0 22px 50px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(16px);
        }

        .brand-section {
            position: relative;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .brand-section::before,
        .brand-section::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.03);
        }

        .brand-section::before {
            width: 220px;
            height: 220px;
            top: -70px;
            right: -80px;
        }

        .brand-section::after {
            width: 180px;
            height: 180px;
            left: -60px;
            bottom: -60px;
        }

        .brand-section>* {
            position: relative;
            z-index: 1;
        }

        .brand-title {
            color: #000000;
            font-size: clamp(2rem, 3vw, 2.7rem);
            letter-spacing: -0.03em;
            line-height: 1.1;
        }

        .brand-text {
            color: #4b5563;
            font-size: 1rem;
            line-height: 1.8;
        }

        .brand-section .text-muted {
            color: #6b7280 !important;
        }

        .microsoft-btn {
            min-height: 60px;
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #000000;
            font-weight: 700;
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .microsoft-btn:hover {
            background: #ffffff;
            border-color: #9ca3af;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.1);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #6b7280;
            font-size: 0.85rem;
        }

        .divider::before,
        .divider::after {   
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .divider:not(:empty)::before {
            margin-right: .75em;
        }

        .divider:not(:empty)::after {
            margin-left: .75em;
        }
    </style>
</head>

<body class="d-flex align-items-center">

    <div class="container auth-wrapper d-flex align-items-center justify-content-center">
        <div class="row w-100 shadow-sm bg-white auth-card overflow-hidden" style="max-width: 900px;">

            <!-- Left Branding Section -->
            <div class="col-md-6 p-5 brand-section d-flex flex-column justify-content-center">
                <h2 class="fw-bold mb-3 brand-title">Applicant Registry System</h2>
                <p class="brand-text">
                    Manage your application journey: from initial registration and
                    document submission to final evaluation and status updates.
                </p>
                <small class="text-muted mt-3">
                    Developed by the City Government IT Team.
                </small>
            </div>

            <!-- Right Login Section -->
            <div class="col-md-6 p-5">

                <h4 class="mb-4 fw-semibold text-center">Sign in to your account</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Microsoft Login -->
                <a href="{{ route('login.azure.redirect') }}"
                    class="btn microsoft-btn w-100 d-flex align-items-center justify-content-center gap-2">

                    <svg width="18" height="18" viewBox="0 0 23 23">
                        <rect width="10" height="10" fill="#F25022" />
                        <rect x="13" width="10" height="10" fill="#7FBA00" />
                        <rect y="13" width="10" height="10" fill="#00A4EF" />
                        <rect x="13" y="13" width="10" height="10" fill="#FFB900" />
                    </svg>

                    Sign in with Microsoft
                </a>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        © {{ now()->year }} City Government. All rights reserved.
                    </small>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
