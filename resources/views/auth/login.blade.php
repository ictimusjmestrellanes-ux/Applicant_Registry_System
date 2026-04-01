<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>System Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f4f6f9;
            min-height: 100vh;
        }

        .auth-wrapper {
            min-height: 100vh;
        }

        .auth-card {
            border-radius: 16px;
            border: 1px solid #e5e7eb;
        }

        .brand-section {
            background-color: #ffffff;
            border-right: 1px solid #e5e7eb;
        }

        .brand-title {
            color: #1f2937;
        }

        .brand-text {
            color: #6b7280;
        }

        .microsoft-btn {
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            color: #111827;
            font-weight: 500;
        }

        .microsoft-btn:hover {
            background-color: #f9fafb;
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

                <!-- Traditional Login -->
                {{-- <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required
                            autofocus>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Login
                    </button>
                </form> --}}

                <!-- Divider -->
                {{-- <div class="divider my-4">OR</div> --}}

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
