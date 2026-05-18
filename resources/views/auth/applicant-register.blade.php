<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register | Applicant Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #dbe4f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.16), transparent 28%),
                radial-gradient(circle at top right, rgba(16, 185, 129, 0.10), transparent 24%),
                linear-gradient(180deg, #f7faff 0%, #edf3fb 100%);
            color: var(--ink);
        }

        .page-shell {
            min-height: 100vh;
            padding: 24px;
        }

        .register-card {
            max-width: 1280px;
            margin: 0 auto;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
        }

        .form-side {
            padding: 36px;
        }

        .form-kicker {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .75rem;
            border-radius: 999px;
            background: #eaf1ff;
            color: var(--primary-dark);
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .form-title {
            margin: 14px 0 8px;
            font-size: clamp(1.55rem, 2vw, 2.1rem);
            font-weight: 800;
            letter-spacing: -.04em;
        }

        .form-copy {
            margin: 0;
            color: var(--muted);
            line-height: 1.65;
        }

        .status-chip {
            padding: .6rem .85rem;
            border-radius: 16px;
            border: 1px solid #dbe6f3;
            background: #f8fbff;
            font-size: .84rem;
            font-weight: 700;
            color: #334155;
        }

        .field-card {
            padding: 18px;
            border-radius: 22px;
            border: 1px solid #e4ebf5;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin: 0 0 1rem;
            color: #0f172a;
            font-size: .88rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .section-title::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, #10b981, #2563eb);
            box-shadow: 0 0 0 6px rgba(37, 99, 235, 0.08);
        }

        .form-control,
        .form-select {
            min-height: 50px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #f9fbff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #7aa2ff;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 130, 235, 0.12);
        }

        .form-label {
            margin-bottom: .45rem;
            color: #344155;
            font-size: .84rem;
            font-weight: 700;
        }

        .required-mark {
            color: #ef4444;
        }

        .btn-primary-modern,
        .btn-secondary-modern {
            min-height: 48px;
            padding: .85rem 1.2rem;
            border-radius: 14px;
            font-weight: 800;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: #fff;
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.24);
        }

        .btn-primary-modern:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 18px 32px rgba(37, 99, 235, 0.28);
        }

        .btn-secondary-modern {
            border-color: #d1dbe8;
            background: #fff;
            color: #334155;
        }

        .btn-secondary-modern:hover {
            background: #f8fbff;
            color: #0f172a;
        }

        @media (max-width: 991.98px) {
            .page-shell {
                padding: 16px;
            }

            .form-side {
                padding: 22px;
            }
        }
    </style>
</head>

<body>
    <div class="page-shell d-flex align-items-center">
        <div class="register-card w-100">
            <div class="form-side">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <span class="form-kicker"><i class="bi bi-pencil-square"></i> New account</span>
                        <h2 class="form-title">Register your applicant account</h2>
                        <p class="form-copy">Fill in the details below. Your account will be reviewed by an admin before you can sign in.</p>
                    </div>
                    <span class="status-chip">Public portal</span>
                </div>

                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm">
                        <div class="fw-semibold">{{ session('success') }}</div>
                        @if (session('username'))
                            <div class="small mt-2"><strong>Username:</strong> {{ session('username') }}</div>
                        @endif
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('applicant.register.post') }}" method="POST">
                    @csrf

                    <div class="field-card">
                        <div class="section-title">Account details</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name <span class="required-mark">*</span></label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" placeholder="e.g. JOHN" oninput="this.value=this.value.toUpperCase()" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}" placeholder="Optional" oninput="this.value=this.value.toUpperCase()">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Last Name <span class="required-mark">*</span></label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" placeholder="e.g. DOE" oninput="this.value=this.value.toUpperCase()" required>
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Suffix</label>
                                <select name="suffix" class="form-select">
                                    <option value="">Optional</option>
                                    @foreach (['JR.', 'SR.', 'II', 'III', 'IV'] as $suffix)
                                        <option value="{{ $suffix }}" {{ old('suffix') === $suffix ? 'selected' : '' }}>
                                            {{ $suffix }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Username <span class="required-mark">*</span></label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="juandelacruz" required>
                                <div class="form-text">Use letters, numbers, dashes, or underscores.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Password <span class="required-mark">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Confirm Password <span class="required-mark">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                        <small class="text-muted">
                            After registration, your account will wait for admin approval before sign-in.
                        </small>

                        <div class="d-flex gap-2">
                            <a href="{{ route('applicant.login') }}" class="btn btn-secondary-modern px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary-modern px-4 text-white">
                                <i class="bi bi-check2-circle me-1"></i> Create Account
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
