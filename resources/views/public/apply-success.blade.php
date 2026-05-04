<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(180deg, #f8fbff 0%, #eef4fb 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .success-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .success-card {
            width: min(720px, 100%);
            border-radius: 28px;
            border: 1px solid rgba(148, 163, 184, 0.22);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            padding: 32px;
            text-align: center;
        }

        .success-icon {
            width: 78px;
            height: 78px;
            border-radius: 26px;
            margin: 0 auto 18px;
            display: grid;
            place-items: center;
            font-size: 2rem;
            color: #059669;
            background: rgba(16, 185, 129, 0.12);
        }

        .success-title {
            margin: 0 0 10px;
            color: #0f172a;
            font-size: clamp(1.75rem, 3vw, 2.5rem);
            font-weight: 900;
            letter-spacing: -0.04em;
        }

        .success-copy {
            color: #64748b;
            margin: 0 auto 22px;
            max-width: 560px;
        }

        .success-name {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1rem;
            margin-bottom: 22px;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-weight: 800;
        }

        .success-actions {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-primary-soft,
        .btn-secondary-soft {
            padding: 0.85rem 1.1rem;
            border-radius: 14px;
            font-weight: 800;
            text-decoration: none;
        }

        .btn-primary-soft {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #fff;
        }

        .btn-secondary-soft {
            border: 1px solid #dbe4ef;
            background: #fff;
            color: #0f172a;
        }

        .btn-primary-soft:hover,
        .btn-secondary-soft:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 576px) {
            .success-card {
                padding: 24px;
            }

            .btn-primary-soft,
            .btn-secondary-soft {
                width: 100%;
                justify-content: center;
                display: inline-flex;
            }
        }
    </style>
</head>
<body>
    <div class="success-shell">
        <div class="success-card">
            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <h1 class="success-title">Application submitted</h1>
            <p class="success-copy">
                Your applicant details have been received successfully. The PESO office can now review the record for the next steps.
            </p>

            @if (session('submitted_name'))
                <div class="success-name">
                    <i class="bi bi-person-check"></i>
                    {{ session('submitted_name') }}
                </div>
            @endif

            <div class="success-actions">
                <a href="{{ route('apply') }}" class="btn-primary-soft">
                    Submit another application
                </a>
                <a href="{{ route('login') }}" class="btn-secondary-soft">
                    Staff login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
