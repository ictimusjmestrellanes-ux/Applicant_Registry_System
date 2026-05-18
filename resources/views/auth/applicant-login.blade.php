<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | Applicant Portal Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">

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
        
        .google-btn{
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            color: #111827;
            font-weight: 500;
        }

        .google-btn:hover {
            background-color: #c5c5c5;
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
    </style>
</head>

<body class="d-flex align-items-center">
    <div class="container auth-wrapper d-flex align-items-center justify-content-center">
        <div class="row w-100 shadow-sm bg-white auth-card overflow-hidden" style="max-width: 900px;">
            <div class="col-md-6 p-5 brand-section d-flex flex-column justify-content-center">
                <h2 class="fw-bold mb-3 brand-title">Applicant Registry Portal</h2>
                <p class="brand-text">
                    Sign in with your Google account to access the applicant portal.
                </p>
                <small class="text-muted mt-3">
                    Developed by the City Government IT Team.
                </small>
            </div>

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

                <a href="{{ route('login.google.redirect') }}"
                    class="btn google-btn w-100 d-flex align-items-center justify-content-center gap-2 mt-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Google_Favicon_2025.svg?utm_source=commons.wikimedia.org&utm_campaign=index&utm_content=original"
                        alt=""
                        aria-hidden="true"
                        width="18"
                        height="18">
                    Sign in with Google
                </a>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        Ⓒ 2026 City Government. All rights reserved.
                    </small>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
