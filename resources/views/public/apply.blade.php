<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Intake</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --line: rgba(148, 163, 184, 0.28);
            --panel: rgba(255, 255, 255, 0.88);
            --panel-solid: #ffffff;
            --primary: #1d4ed8;
            --primary-soft: rgba(29, 78, 216, 0.12);
            --success: #059669;
            --bg: #eef4fb;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(29, 78, 216, 0.12), transparent 28%),
                radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.08), transparent 24%),
                linear-gradient(180deg, #f8fbff 0%, var(--bg) 100%);
            color: var(--ink);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .landing-shell {
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }

        .landing-shell .container {
            max-width: 1800px;
        }

        .landing-shell::before,
        .landing-shell::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .landing-shell::before {
            top: -120px;
            right: -80px;
            width: 300px;
            height: 300px;
            background: rgba(29, 78, 216, 0.08);
        }

        .landing-shell::after {
            left: -100px;
            bottom: -120px;
            width: 340px;
            height: 340px;
            background: rgba(16, 185, 129, 0.08);
        }

        .topbar {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 0 8px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-mark {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            background: var(--panel-solid);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-mark img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .brand-copy small {
            display: block;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .brand-copy h1 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .login-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1rem;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.8);
            color: var(--ink);
            text-decoration: none;
            font-weight: 800;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        }

        .login-link:hover {
            color: var(--primary);
            border-color: rgba(59, 130, 246, 0.28);
            transform: translateY(-1px);
        }

        .hero-card,
        .form-card,
        .info-card {
            position: relative;
            z-index: 1;
            border: 1px solid var(--line);
            background: var(--panel);
            backdrop-filter: blur(14px);
            box-shadow: 0 22px 48px rgba(15, 23, 42, 0.08);
        }

        .hero-card {
            border-radius: 30px;
            padding: 28px;
            overflow: hidden;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 0.78rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-title {
            margin: 14px 0 12px;
            font-size: clamp(2rem, 4vw, 3.6rem);
            line-height: 0.98;
            letter-spacing: -0.05em;
            font-weight: 950;
            color: var(--ink);
        }

        .hero-copy {
            max-width: 740px;
            color: var(--muted);
            font-size: 1.02rem;
        }

        .hero-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 0.85rem;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.26);
            background: rgba(255, 255, 255, 0.88);
            color: #334155;
            font-size: 0.85rem;
            font-weight: 800;
        }

        .hero-aside {
            display: grid;
            gap: 0.9rem;
        }

        .mini-card {
            padding: 1rem 1.05rem;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid rgba(148, 163, 184, 0.22);
        }

        .mini-card-label {
            display: block;
            margin-bottom: 0.25rem;
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .mini-card-value {
            color: var(--ink);
            font-size: 0.98rem;
            font-weight: 800;
        }

        .form-card {
            border-radius: 30px;
            padding: 24px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            font-weight: 900;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .section-title::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, #10b981, #3b82f6);
            box-shadow: 0 0 0 6px rgba(59, 130, 246, 0.08);
        }

        .form-label {
            margin-bottom: 0.45rem;
            color: #334155;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .form-control,
        .form-select {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: rgba(248, 251, 255, 0.95);
            padding: 0.75rem 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(59, 130, 246, 0.55);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
            background: #fff;
        }

        .required {
            color: #ef4444;
        }

        .form-note {
            color: var(--muted);
        }

        .btn-submit {
            min-width: 180px;
            padding: 0.9rem 1.2rem;
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

        .floating-success {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 1080;
            width: min(420px, calc(100vw - 32px));
            padding: 1rem 1.1rem;
            border-radius: 20px;
            border: 1px solid rgba(16, 185, 129, 0.22);
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.16);
            backdrop-filter: blur(14px);
            animation: floatIn 0.35s ease-out both;
        }

        .floating-success.is-hiding {
            animation: floatOut 0.45s ease-in forwards;
        }

        .floating-success .floating-success-inner {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
        }

        .floating-success .floating-success-icon {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            color: #059669;
            background: rgba(16, 185, 129, 0.12);
            font-size: 1.25rem;
        }

        .floating-success .floating-success-title {
            margin: 0;
            color: #0f172a;
            font-size: 1rem;
            font-weight: 900;
        }

        .floating-success .floating-success-copy {
            margin: 0.25rem 0 0;
            color: #64748b;
            font-size: 0.92rem;
            line-height: 1.45;
        }

        .floating-success .floating-success-name {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 0.7rem;
            padding: 0.42rem 0.72rem;
            border-radius: 999px;
            background: #f0fdf4;
            color: #166534;
            font-size: 0.83rem;
            font-weight: 800;
        }

        @keyframes floatIn {
            from {
                opacity: 0;
                transform: translateY(-12px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes floatOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateY(-10px) scale(0.98);
            }
        }

        .location-grid {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }

        @media (max-width: 991.98px) {
            .topbar {
                gap: 1rem;
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 767.98px) {
            .hero-card,
            .form-card {
                padding: 18px;
                border-radius: 24px;
            }

            .btn-submit,
            .login-link {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="landing-shell">
        <div class="container py-3 py-lg-4">
            <div class="topbar">
                <div class="brand">
                    <div class="brand-mark">
                        <img src="{{ asset('images/logo_peso.png') }}" alt="PESO Logo">
                    </div>
                    <div class="brand-copy">
                        <small>Applicant Registry</small>
                        <h1>Public Intake Portal</h1>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="login-link">
                    <i class="bi bi-shield-lock"></i>
                    Staff Login
                </a>
            </div>

            @if (session('success'))
                <div class="floating-success" id="floating-success" role="status" aria-live="polite">
                    <div class="floating-success-inner">
                        <div class="floating-success-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <p class="floating-success-title">Application submitted</p>
                            <p class="floating-success-copy">{{ session('success') }}</p>
                            @if (session('submitted_name'))
                                <div class="floating-success-name">
                                    <i class="bi bi-person-check"></i>
                                    {{ session('submitted_name') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mt-3">
                    <strong>Please check the form:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="hero-card mt-3 mt-lg-4">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <span class="hero-kicker"><i class="bi bi-person-check"></i> Applicant submission</span>
                        <h2 class="hero-title">Submit your personal details online</h2>
                        <p class="hero-copy">
                            Fill out your profile once and the system will use it for applicant records and follow-up processing.
                            Please make sure your contact number and address details are accurate.
                        </p>

                        <div class="hero-pills">
                            <div class="hero-pill"><i class="bi bi-person-vcard"></i> Personal info</div>
                            <div class="hero-pill"><i class="bi bi-geo-alt"></i> Address details</div>
                            <div class="hero-pill"><i class="bi bi-journal-text"></i> Profile data</div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="hero-aside">
                            <div class="mini-card">
                                <span class="mini-card-label">Who can use this</span>
                                <div class="mini-card-value">Applicants submitting their own details</div>
                            </div>
                            <div class="mini-card">
                                <span class="mini-card-label">What to prepare</span>
                                <div class="mini-card-value">Name, address, contact, and profile info</div>
                            </div>
                            <div class="mini-card">
                                <span class="mini-card-label">Need help?</span>
                                <div class="mini-card-value">Ask the PESO office staff for assistance</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-card mt-4">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                    <div>
                        <h3 class="h5 fw-bold mb-1">Applicant Personal Details</h3>
                        <p class="form-note mb-0">Fields marked with an asterisk are required.</p>
                    </div>
                    <span class="badge rounded-pill text-bg-light border px-3 py-2">
                        <i class="bi bi-lock-open me-1"></i> Public form
                    </span>
                </div>

                <form action="{{ route('apply.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <div class="section-title">Profile</div>
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">First Time Jobseeker <span class="required">*</span></label>
                                <select name="first_time_job_seeker" class="form-select" required>
                                    <option value="NO" {{ old('first_time_job_seeker', 'NO') === 'NO' ? 'selected' : '' }}>NO</option>
                                    <option value="YES" {{ old('first_time_job_seeker') === 'YES' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">First Name <span class="required">*</span></label>
                                <input type="text" name="first_name" class="form-control"
                                    value="{{ old('first_name') }}" oninput="this.value = this.value.toUpperCase()"
                                    placeholder="e.g. JUAN" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control"
                                    value="{{ old('middle_name') }}" oninput="this.value = this.value.toUpperCase()"
                                    placeholder="Optional">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Last Name <span class="required">*</span></label>
                                <input type="text" name="last_name" class="form-control"
                                    value="{{ old('last_name') }}" oninput="this.value = this.value.toUpperCase()"
                                    placeholder="e.g. DELA CRUZ" required>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Suffix</label>
                                <select name="suffix" class="form-select">
                                    <option value="">Optional</option>
                                    @foreach (['JR.', 'SR.', 'II', 'III', 'IV'] as $suffix)
                                        <option value="{{ $suffix }}" {{ old('suffix') === $suffix ? 'selected' : '' }}>{{ $suffix }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Age <span class="required">*</span></label>
                                <input type="number" name="age" class="form-control" value="{{ old('age') }}"
                                    min="1" max="120" placeholder="e.g. 25" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sex <span class="required">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select Sex</option>
                                    <option value="MALE" {{ old('gender') === 'MALE' ? 'selected' : '' }}>MALE</option>
                                    <option value="FEMALE" {{ old('gender') === 'FEMALE' ? 'selected' : '' }}>FEMALE</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Civil Status <span class="required">*</span></label>
                                <select name="civil_status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="SINGLE" {{ old('civil_status') === 'SINGLE' ? 'selected' : '' }}>SINGLE</option>
                                    <option value="MARRIED" {{ old('civil_status') === 'MARRIED' ? 'selected' : '' }}>MARRIED</option>
                                    <option value="WIDOWED" {{ old('civil_status') === 'WIDOWED' ? 'selected' : '' }}>WIDOWED</option>
                                </select>
                            </div>
                            <div class="col-md-1 text-center">
                                <label class="form-label">PWD<span class="required">*</span></label>
                                <select name="pwd" class="form-select" required>
                                    <option value="NO" {{ old('pwd', 'NO') === 'NO' ? 'selected' : '' }}>NO</option>
                                    <option value="YES" {{ old('pwd') === 'YES' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                            <div class="col-md-1 text-center">
                                <label class="form-label">4Ps<span class="required">*</span></label>
                                <select name="four_ps" class="form-select" required>
                                    <option value="NO" {{ old('four_ps', 'NO') === 'NO' ? 'selected' : '' }}>NO</option>
                                    <option value="YES" {{ old('four_ps') === 'YES' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="section-title">Contact and Address</div>
                        <div class="row g-3 location-grid">
                            <div class="col-md-4">
                                <label class="form-label">Contact Number <span class="required">*</span></label>
                                <input type="tel" name="contact_no" class="form-control"
                                    value="{{ old('contact_no') }}" placeholder="09123456789"
                                    pattern="[0-9]{11}" maxlength="11" inputmode="numeric" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Complete Address <span class="required">*</span></label>
                                <input type="text" name="address_line" class="form-control"
                                    value="{{ old('address_line') }}" oninput="this.value = this.value.toUpperCase()"
                                    placeholder="House No. / Street / Barangay" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Province <span class="required">*</span></label>
                                <select name="province" id="province" class="form-select" required>
                                    <option value="">Select Province</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City / Municipality <span class="required">*</span></label>
                                <select name="city" id="city" class="form-select" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Barangay <span class="required">*</span></label>
                                <select name="barangay" id="barangay" class="form-select" required>
                                    <option value="">Select Barangay</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h2 class="section-title">Education & Hiring</h2>
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label class="form-label">Educational Attainment <span class="required">*</span></label>
                                <select name="educational_attainment" class="form-select" required>
                                    <option value="">Select educational attainment</option>
                                    @foreach ($educationalAttainments as $attainment)
                                        <option value="{{ $attainment }}" {{ old('educational_attainment') === $attainment ? 'selected' : '' }}>
                                            {{ $attainment }}
                                        </option>
                                    @endforeach
                                    @if (old('educational_attainment') && ! in_array(old('educational_attainment'), $educationalAttainments, true))
                                        <option value="{{ old('educational_attainment') }}" selected>{{ old('educational_attainment') }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hiring Company <span class="required">*</span></label>
                                <input type="text" name="hiring_company" class="form-control"
                                    value="{{ old('hiring_company') }}" oninput="this.value = this.value.toUpperCase()"
                                    placeholder="e.g. TECH CORP" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Position Hired <span class="required">*</span></label>
                                <input type="text" name="position_hired" class="form-control"
                                    value="{{ old('position_hired') }}" oninput="this.value = this.value.toUpperCase()"
                                    placeholder="e.g. SOFTWARE ENGINEER" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 pt-2 border-top">
                        <p class="form-note mb-0">
                            This is a public intake form. Please double-check your details before submitting.
                        </p>
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-send me-2"></i>Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const floatingSuccess = document.getElementById('floating-success');

            if (floatingSuccess) {
                window.setTimeout(() => {
                    floatingSuccess.classList.add('is-hiding');

                    floatingSuccess.addEventListener('animationend', () => {
                        floatingSuccess.remove();
                    }, { once: true });
                }, 5000);
            }

            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');
            const barangaySelect = document.getElementById('barangay');
            const savedProvince = @json(old('province'));
            const savedCity = @json(old('city'));
            const savedBarangay = @json(old('barangay'));

            if (!provinceSelect || !citySelect || !barangaySelect) return;

            function normalizeName(value) {
                return String(value || '').replace(/^\s*(city of|municipality of)\s+/i, '').trim().toUpperCase();
            }

            function loadProvinces() {
                provinceSelect.innerHTML = '<option value="">Loading provinces...</option>';

                fetch('https://psgc.gitlab.io/api/provinces/')
                    .then(res => res.json())
                    .then(data => {
                        const provinces = Array.isArray(data) ? data : [];
                        provinceSelect.innerHTML = '<option value="">Select Province</option>';
                        window._provinceCodeMap = window._provinceCodeMap || {};

                        provinces.sort((a, b) => (a.name || '').localeCompare(b.name || '', undefined, { sensitivity: 'base' }));

                        provinces.forEach(province => {
                            const rawName = province.name || province.province || province.description || '';
                            const name = String(rawName).toUpperCase();
                            const code = province.code || '';

                            const option = document.createElement('option');
                            option.value = name;
                            option.textContent = name;
                            option.dataset.code = code;

                            if (savedProvince && name === String(savedProvince).toUpperCase()) {
                                option.selected = true;
                            }

                            provinceSelect.appendChild(option);
                            if (name && code) {
                                window._provinceCodeMap[name] = code;
                            }
                        });

                        const selected = provinceSelect.options[provinceSelect.selectedIndex];
                        if (selected) {
                            const code = selected.dataset.code || window._provinceCodeMap[selected.value];
                            if (code) {
                                loadCities(code);
                            }
                        }
                    })
                    .catch(() => {
                        provinceSelect.innerHTML = '<option value="">Unable to load provinces</option>';
                    });
            }

            function loadCities(provinceIdentifier) {
                citySelect.innerHTML = '<option value="">Loading cities...</option>';
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                let provinceCode = provinceIdentifier;
                if (isNaN(Number(provinceCode))) {
                    provinceCode = window._provinceCodeMap && window._provinceCodeMap[provinceCode]
                        ? window._provinceCodeMap[provinceCode]
                        : provinceCode;
                }

                fetch(`https://psgc.gitlab.io/api/provinces/${encodeURIComponent(provinceCode)}/cities-municipalities/`)
                    .then(res => res.json())
                    .then(data => {
                        const cities = Array.isArray(data) ? data : [];
                        citySelect.innerHTML = '<option value="">Select City</option>';

                        cities.sort((a, b) => (a.name || '').localeCompare(b.name || '', undefined, { sensitivity: 'base' }));

                        cities.forEach(city => {
                            const rawName = city.name || city.description || '';
                            const cleaned = normalizeName(rawName);
                            let name = cleaned;
                            if (/city/i.test(rawName) && !/\bCITY$/.test(name)) {
                                name = `${name} CITY`;
                            }

                            const option = document.createElement('option');
                            option.value = name;
                            option.textContent = name;
                            option.dataset.code = city.code || '';

                            if (savedCity && name === String(savedCity).toUpperCase()) {
                                option.selected = true;
                            }

                            citySelect.appendChild(option);
                        });

                        const selectedCity = citySelect.options[citySelect.selectedIndex];
                        if (selectedCity && selectedCity.dataset.code) {
                            loadBarangays(selectedCity.dataset.code);
                        }
                    })
                    .catch(() => {
                        citySelect.innerHTML = '<option value="">Unable to load cities</option>';
                    });
            }

            function loadBarangays(cityCode) {
                barangaySelect.innerHTML = '<option value="">Loading barangays...</option>';

                if (!cityCode) {
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    return;
                }

                fetch(`https://psgc.gitlab.io/api/cities-municipalities/${encodeURIComponent(cityCode)}/barangays/`)
                    .then(res => res.json())
                    .then(data => {
                        const barangays = Array.isArray(data) ? data : [];
                        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                        barangays.sort((a, b) => (a.name || '').localeCompare(b.name || '', undefined, { sensitivity: 'base' }));

                        barangays.forEach(barangay => {
                            const rawName = barangay.name || '';
                            const cleaned = String(rawName).replace(/\(\s*POB\.?\s*\)/ig, '').trim();
                            const name = String(cleaned).toUpperCase();

                            const option = document.createElement('option');
                            option.value = name;
                            option.textContent = name;

                            if (savedBarangay && name === String(savedBarangay).toUpperCase()) {
                                option.selected = true;
                            }

                            barangaySelect.appendChild(option);
                        });
                    })
                    .catch(() => {
                        barangaySelect.innerHTML = '<option value="">Unable to load barangays</option>';
                    });
            }

            provinceSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const code = selected?.dataset.code;

                if (code) {
                    loadCities(code);
                } else if (selected?.value) {
                    loadCities(selected.value);
                } else {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                }
            });

            citySelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const code = selected?.dataset.code;

                if (code) {
                    loadBarangays(code);
                } else {
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                }
            });

            loadProvinces();
        });
    </script>
</body>
</html>
