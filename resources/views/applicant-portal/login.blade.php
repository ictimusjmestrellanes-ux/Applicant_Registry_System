<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Applicant Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(29, 78, 216, 0.14), transparent 28%),
                linear-gradient(180deg, #f8fbff 0%, #eef4fb 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .portal-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .portal-card {
            width: min(560px, 100%);
            border-radius: 28px;
            border: 1px solid rgba(148, 163, 184, 0.22);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            padding: 32px;
        }

        .portal-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.12);
            color: #1d4ed8;
            font-size: 0.78rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .portal-title {
            margin: 14px 0 10px;
            color: #0f172a;
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 900;
            letter-spacing: -0.04em;
        }

        .portal-copy {
            color: #64748b;
            margin-bottom: 24px;
        }

        .form-label {
            color: #334155;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .form-control {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            padding: 0.75rem 0.95rem;
        }

        .form-control:focus {
            border-color: rgba(59, 130, 246, 0.55);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .btn-login {
            min-width: 180px;
            padding: 0.9rem 1.2rem;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #fff;
            font-weight: 900;
            box-shadow: 0 16px 30px rgba(29, 78, 216, 0.22);
        }

        .btn-secondary-soft {
            border: 1px solid #dbe4ef;
            background: #fff;
            color: #0f172a;
            border-radius: 14px;
            padding: 0.9rem 1.2rem;
            font-weight: 800;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="portal-shell">
        <div class="portal-card">
            <span class="portal-kicker">Applicant Portal</span>
            <h1 class="portal-title">Sign in with your username</h1>
            <p class="portal-copy">
                Use your username and password to access your portal account.
            </p>

            @if (session('success'))
                <div class="alert alert-success border-0">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('applicant.portal.authenticate') }}" method="POST" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="applicant_code" class="form-control" value="{{ old('applicant_code') }}"
                        placeholder="Enter your username" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" value="{{ old('password') }}"
                        placeholder="Enter your password" required>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 align-items-stretch">
                    <button type="submit" class="btn btn-login flex-fill">Login</button>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('applicant.portal.register') }}" class="small fw-semibold text-decoration-none">
                        Create a new applicant account
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
