<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | Applicant Portal Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .auth-page {
            min-height: 100vh;
            background: linear-gradient(180deg, #f8fafc 0%, #f4f6f9 100%);
            display: flex;
            align-items: center;
        }

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

        .applicant-btn {
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            color: #111827;
            font-weight: 500;
        }

        .applicant-btn:hover {
            background-color: #c5c5c5;
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
    @if (session('approval_notice'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
            <div id="approvalToast" class="toast align-items-center text-bg-warning border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-semibold">
                        <div>{{ session('approval_notice') }}</div>
                        @if (session('username'))
                            <div class="small mt-1">Username: {{ session('username') }}</div>
                        @endif
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="container auth-wrapper d-flex align-items-center justify-content-center">
        <div class="row w-100 shadow-sm bg-white auth-card overflow-hidden mx-auto" style="max-width: 900px;">

            <!-- Left Branding Section -->
            <div class="col-md-6 p-5 brand-section d-flex flex-column justify-content-center">
                <h2 class="fw-bold mb-3 brand-title">Applicant Registry Portal</h2>
                <p class="brand-text">
                    Access your application status, upload documents, and view notifications. New accounts are reviewed by an admin before sign-in.
                </p>
                <small class="text-muted mt-3">
                    Developed by the City Government IT Team.
                </small>
            </div>

            <!-- Right Login Section -->
            <div class="col-md-6 p-5">

                <h4 class="mb-4 fw-semibold text-center">Sign in to your applicant account</h4>

                @if (session('success'))
                    <div class="alert alert-success">
                        <div class="fw-semibold">{{ session('success') }}</div>
                        @if (session('username'))
                            <div class="small mt-2">
                                <div><strong>Username:</strong> {{ session('username') }}</div>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('applicant.login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="johndoe"
                            value="{{ old('username', session('username')) }}" required>
                        <div class="form-text">Enter the username you created during registration.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>

                        <div class="position-relative">
                            <input type="password" name="password" id="password" class="form-control pe-5" required>

                            <i class="bi bi-eye-slash" id="togglePassword" 
                            style="
                                position:absolute;
                                top:50%;
                                right:15px;
                                transform:translateY(-50%);
                                cursor:pointer;
                                font-size:18px;
                                ">
                            </i>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn applicant-btn" type="submit">Sign in</button>
                    </div>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            No account yet?
                            <a href="{{ route('applicant.register') }}" class="fw-semibold text-decoration-none">
                                Create an account
                            </a>
                        </small>
                    </div>

                    <div class="text-center mt-4">
                        <small class="text-muted">
                            © 2026 City Government. All rights reserved.
                        </small>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', function () {

        // Toggle password visibility
        const type = password.getAttribute('type') === 'password'
            ? 'text'
            : 'password';

        password.setAttribute('type', type);

        // Toggle eye icon
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });

    const approvalToast = document.getElementById('approvalToast');

    if (approvalToast) {
        const toast = bootstrap.Toast.getOrCreateInstance(approvalToast, {
            delay: 6000,
        });

        toast.show();
    }
</script>

</html>
