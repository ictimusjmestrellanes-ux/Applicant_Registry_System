<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register | Applicant Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(180deg, #f7faff 0%, #edf3fb 100%);
        }

        .page-shell {
            min-height: 100vh;
            padding: 24px;
        }

        .register-card {
            max-width: 900px;
            margin: 0 auto;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.96);
        }

        .panel {
            padding: 36px;
        }
    </style>
</head>

<body>
    <div class="page-shell d-flex align-items-center">
        <div class="register-card w-100">
            <div class="panel">
                <div class="mb-4">
                    <h2 class="fw-bold">Applicant Registration Disabled</h2>
                    <p class="text-muted mb-0">
                        The applicant register flow is currently commented out. Please use Google sign-in from the login page instead.
                    </p>
                </div>

                <div class="alert alert-info border-0 shadow-sm">
                    Google sign-in remains active for applicant access.
                </div>

                <a href="{{ route('login') }}" class="btn btn-primary px-4">
                    Go to Login
                </a>
            </div>
        </div>
    </div>
</body>

</html>
