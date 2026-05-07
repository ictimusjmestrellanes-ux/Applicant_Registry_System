<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Applicant Requirements</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_peso.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --line: rgba(148, 163, 184, 0.24);
            --panel: rgba(255, 255, 255, 0.92);
            --bg: #eef4fb;
            --sidebar-width: 300px;
            --create-line: #d9e4ef;
            --create-panel: rgba(255, 255, 255, 0.88);
            --create-primary: #1d4ed8;
            --create-primary-soft: #dbeafe;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--ink);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.16), transparent 28%),
                radial-gradient(circle at left bottom, rgba(16, 185, 129, 0.14), transparent 32%),
                linear-gradient(135deg, #ffffff, #f3f8ff 58%, #edf7f5);
        }

        .portal-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            background: rgba(255, 255, 255, 0.92);
            border-right: 1px solid rgba(148, 163, 184, 0.22);
            box-shadow: 16px 0 40px rgba(15, 23, 42, 0.06);
            display: flex;
            flex-direction: column;
            z-index: 20;
            backdrop-filter: blur(18px);
        }

        .portal-sidebar-top {
            padding: 20px 18px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            background: rgba(248, 250, 252, 0.8);
        }

        .portal-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark {
            width: 52px;
            height: 52px;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08);
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

        .portal-brand-copy h1 {
            margin: 0;
            font-size: 1rem;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .portal-brand-copy p {
            margin: 0.2rem 0 0;
            color: var(--muted);
            font-size: 0.78rem;
        }

        .portal-nav-wrap {
            padding: 18px 16px 14px;
            flex: 1;
            overflow: auto;
        }

        .portal-nav-label {
            margin: 0 0 0.85rem;
            color: #64748b;
            font-size: 0.8rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .portal-nav {
            display: grid;
            gap: 0.55rem;
        }

        .portal-nav-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.95rem 1rem;
            border-radius: 18px;
            color: #111827;
            text-decoration: none;
            font-weight: 800;
            background: transparent;
            border: 1px solid transparent;
            transition: background .2s ease, border-color .2s ease, transform .2s ease;
        }

        .portal-nav-item:hover,
        .portal-nav-item.active {
            background: #f8fbff;
            border-color: rgba(59, 130, 246, 0.18);
            transform: translateX(2px);
        }

        .portal-nav-icon {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            background: rgba(29, 78, 216, 0.08);
            color: #1d4ed8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .portal-sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(148, 163, 184, 0.18);
            background: rgba(255, 255, 255, 0.9);
        }

        .portal-user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.8rem 0.9rem;
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.24);
            background: #fff;
        }

        .portal-user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: #a3a3a3;
            color: #111827;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            flex-shrink: 0;
        }

        .portal-user-card strong {
            display: block;
            font-size: 0.9rem;
            font-weight: 900;
        }

        .portal-user-card small {
            color: var(--muted);
        }

        .sidebar-logout {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            gap: 0.55rem;
            padding: 0.9rem 1rem;
            border-radius: 16px;
            border: 1px solid #dbe4ef;
            background: #fff;
            color: #0f172a;
            font-weight: 800;
            text-decoration: none;
        }

        .portal-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 24px 0 40px;
        }

        .container-portal {
            width: 100%;
            padding-inline: 22px;
            margin: 0 auto;
        }

        .topbar,
        .overview-card,
        .records-card,
        .summary-card,
        .action-card,
        .tab-card {
            position: relative;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(14px);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.06);
        }

        .topbar {
            border-radius: 30px;
            padding: 28px 28px 26px;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.1);
            color: #1d4ed8;
            font-size: 0.78rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-title {
            margin: 14px 0 10px;
            font-size: clamp(2rem, 4vw, 3.4rem);
            line-height: 0.98;
            letter-spacing: -0.05em;
            font-weight: 950;
        }

        .hero-copy {
            max-width: 760px;
            color: var(--muted);
            margin: 0;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
            justify-content: flex-end;
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.55rem 0.9rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 800;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .overview-card {
            border-radius: 28px;
            padding: 22px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.92);
        }

        .overview-card::after {
            content: "";
            position: absolute;
            right: -18px;
            bottom: -24px;
            width: 92px;
            height: 92px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.08);
        }

        .overview-icon {
            width: 54px;
            height: 54px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .icon-blue { background: rgba(37, 99, 235, 0.12); color: #2563eb; }
        .icon-emerald { background: rgba(5, 150, 105, 0.12); color: #059669; }
        .icon-amber { background: rgba(245, 158, 11, 0.14); color: #b45309; }
        .icon-slate { background: rgba(71, 85, 105, 0.12); color: #334155; }

        .overview-label {
            display: block;
            color: var(--muted);
            font-size: 0.75rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .overview-value {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--ink);
            line-height: 1.08;
            margin: 0.35rem 0 0.55rem;
        }

        .section-copy {
            color: var(--muted);
            margin: 0;
        }

        .records-card,
        .tab-card {
            border-radius: 30px;
            padding: 26px;
            margin-top: 1rem;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
            font-weight: 900;
            letter-spacing: 0.08em;
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

        .section-head-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-index-primary,
        .btn-index-secondary {
            border-radius: 14px;
            padding: 0.82rem 1.15rem;
            font-weight: 700;
        }

        .btn-index-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.2);
            color: #fff;
        }

        .btn-index-primary:hover {
            color: #fff;
        }

        .btn-index-secondary {
            background: #fff;
            border: 1px solid #dbeafe;
            color: #1e3a8a;
        }

        .requirements-tabs-shell {
            padding: 10px;
            border-radius: 28px;
            border: 1px solid #dbeafe;
            background: #fff;
            margin-bottom: 1rem;
        }

        .requirements-tabs {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0;
            margin: 0;
            border: none;
        }

        .requirements-tabs .nav-item {
            margin: 0;
        }

        .requirements-tabs .nav-link {
            width: 100%;
            min-height: 50px;
            border: 1px solid transparent;
            border-radius: 18px;
            color: #64748b;
            font-weight: 800;
            background: transparent;
            margin: 0;
            padding: 0.9rem 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
        }

        .requirements-tabs .nav-link i {
            font-size: 1rem;
        }

        .requirements-tabs .nav-link.active {
            border-color: #dbeafe;
            background: #fff;
            color: #0f172a;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        }

        .tab-pane-shell {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .upload-card,
        .status-card {
            border-radius: 24px;
            border: 1px solid #e4edf7;
            background: #fff;
            padding: 1.25rem;
        }

        .upload-card h3,
        .status-card h3 {
            margin: 0 0 0.4rem;
            font-size: 1rem;
            font-weight: 900;
        }

        .upload-card p,
        .status-card p {
            color: var(--muted);
            margin-bottom: 1rem;
        }

        .upload-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .permit-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .clearance-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 0;
        }

        .referral-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }


        .upload-field {
            padding: 1rem;
            border-radius: 18px;
            border: 1px solid #e6eef7;
            background: linear-gradient(180deg, #ffffff, #f8fbff);
        }

        .upload-field.full {
            grid-column: 1 / -1;
        }

        .upload-field label {
            display: block;
            margin-bottom: 0.45rem;
            color: #44526f;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .upload-field .form-control,
        .upload-field .form-select {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid var(--create-line);
            background: #f8fbff;
        }

        .upload-file {
            display: none;
        }

        .upload-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 42px;
            padding: 0.7rem 1rem;
            border-radius: 999px;
            border: 1px solid #dbeafe;
            background: #fff;
            color: #1e293b;
            font-weight: 900;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s ease, transform .2s ease, border-color .2s ease;
        }

        .upload-button:hover {
            background: #f8fbff;
            border-color: rgba(59, 130, 246, 0.25);
            transform: translateY(-1px);
        }

        .upload-button.disabled {
            opacity: 0.55;
            pointer-events: none;
        }

        .current-file {
            margin-top: 0.7rem;
            display: grid;
            gap: 0.45rem;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .current-file a {
            color: #1d4ed8;
            text-decoration: none;
        }

        .current-file span {
            color: var(--muted);
        }

        .upload-note {
            margin-top: 0.55rem;
            color: var(--muted);
            font-size: 0.85rem;
        }

        .status-list {
            display: grid;
            gap: 0.75rem;
        }

        .status-row {
            padding: 0.95rem 1rem;
            border-radius: 18px;
            border: 1px solid #e6eef7;
            background: #f8fbff;
        }

        .status-row .label {
            display: block;
            margin-bottom: 0.2rem;
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .status-row strong {
            font-size: 0.95rem;
            font-weight: 900;
        }

        .alert-shell {
            margin-bottom: 1rem;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #e4edf7;
        }

        .btn-cancel,
        .btn-save {
            border-radius: 14px;
            padding: 10px 20px;
            font-weight: 700;
        }

        .btn-cancel {
            background: #f4f6fb;
            color: #5b6b8b;
            border: 1px solid #dce3ef;
        }

        .btn-save {
            background: #1d4ed8;
            border: none;
            color: #ffffff;
            box-shadow: 0 10px 22px rgba(29, 78, 216, 0.24);
        }

        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(29, 78, 216, 0.28);
            color: #000000;
        }

        .section-list {
            display: grid;
            gap: 0.85rem;
        }

        .file-item {
            padding: 0.9rem 1rem;
            border-radius: 16px;
            background: #fff;
            border: 1px solid #e5edf5;
        }

        .file-item span {
            display: block;
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 0.2rem;
        }

        .file-item strong {
            display: block;
            font-weight: 900;
            margin-bottom: 0.25rem;
        }

        .file-item a {
            text-decoration: none;
            font-size: 0.88rem;
        }

        .nav-tabs {
            border: none;
            gap: 0;
            padding: 0;
            margin-bottom: 0;
        }

        .nav-tabs .nav-item {
            margin-bottom: 0;
        }

        .nav-tabs .nav-link {
            border-radius: 999px;
            border: 1px solid #dbeafe;
            color: #1e3a8a;
            font-weight: 800;
            background: #fff;
            margin-right: 0;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            border-color: transparent;
        }

        .tab-content {
            padding-top: 1rem;
        }

        .select2-container .select2-selection--single {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid var(--create-line);
            background: #f8fbff;
            display: flex;
            align-items: center;
            padding: 0 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #212529;
            line-height: 46px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            right: 10px;
        }

        @media (max-width: 991.98px) {
            .portal-sidebar {
                position: static;
                width: 100%;
            }

            .portal-main {
                margin-left: 0;
                padding-top: 16px;
            }

            .tab-pane-shell {
                grid-template-columns: 1fr;
            }

            .permit-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .referral-grid {
                grid-template-columns: 1fr;
            }

            .requirements-tabs {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .container-portal {
                padding-inline: 14px;
            }

            .topbar,
            .records-card,
            .tab-card {
                padding: 18px;
                border-radius: 24px;
            }

            .form-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-cancel,
            .btn-save {
                width: 100%;
            }

            .upload-grid {
                grid-template-columns: 1fr;
            }

            .permit-grid {
                grid-template-columns: 1fr;
            }

            .referral-grid {
                grid-template-columns: 1fr;
            }

            .requirements-tabs {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    @php
        $permit = $permit ?? optional($applicant->permit);
        $clearance = $clearance ?? optional($applicant->clearance);
        $referral = $referral ?? optional($applicant->referral);
        $isImusResident = $applicant->city && stripos($applicant->city, 'IMUS CITY') !== false;

        $permitClearanceType = old('permit_clearance_type', $permit?->clearance_type);
        if (empty($permitClearanceType)) {
            if (! empty($permit?->permit_police_clearance)) {
                $permitClearanceType = 'police';
            } elseif (! empty($permit?->permit_nbi_clearance)) {
                $permitClearanceType = 'nbi';
            } else {
                $permitClearanceType = 'nbi';
            }
        }

        $permitClearanceLabel = $permitClearanceType === 'police' ? 'Police Clearance File' : 'NBI Clearance File';
        $permitClearancePath = $permitClearanceType === 'police' ? ($permit?->permit_police_clearance ?? null) : ($permit?->permit_nbi_clearance ?? null);
        $permitClearanceUrl = $permitClearancePath ? asset('storage/'.$permitClearancePath) : null;
        $permitClearanceFilename = $permitClearancePath ? basename($permitClearancePath) : null;

        $permitHealthCardUrl = ! empty($permit?->health_card) ? asset('storage/'.$permit->health_card) : null;
        $permitHealthCardFilename = $permit?->health_card ? basename($permit->health_card) : null;
        $permitCedulaUrl = ! empty($permit?->cedula) ? asset('storage/'.$permit->cedula) : null;
        $permitCedulaFilename = $permit?->cedula ? basename($permit->cedula) : null;
        $permitReferralUrl = ! empty($permit?->referral_letter) ? asset('storage/'.$permit->referral_letter) : null;
        $permitReferralFilename = $permit?->referral_letter ? basename($permit->referral_letter) : null;

        $clearanceProsecutorUrl = ! empty($clearance?->prosecutor_clearance) ? asset('storage/'.$clearance->prosecutor_clearance) : null;
        $clearanceProsecutorFilename = $clearance?->prosecutor_clearance ? basename($clearance->prosecutor_clearance) : null;
        $clearanceMtcUrl = ! empty($clearance?->mtc_clearance) ? asset('storage/'.$clearance->mtc_clearance) : null;
        $clearanceMtcFilename = $clearance?->mtc_clearance ? basename($clearance->mtc_clearance) : null;
        $clearanceRtcUrl = ! empty($clearance?->rtc_clearance) ? asset('storage/'.$clearance->rtc_clearance) : null;
        $clearanceRtcFilename = $clearance?->rtc_clearance ? basename($clearance->rtc_clearance) : null;
        $clearanceNbiUrl = ! empty($clearance?->nbi_clearance) ? asset('storage/'.$clearance->nbi_clearance) : null;
        $clearanceNbiFilename = $clearance?->nbi_clearance ? basename($clearance->nbi_clearance) : null;
        $clearanceBarangayUrl = ! empty($clearance?->barangay_clearance) ? asset('storage/'.$clearance->barangay_clearance) : null;
        $clearanceBarangayFilename = $clearance?->barangay_clearance ? basename($clearance->barangay_clearance) : null;

        $refResumeUrl = ! empty($referral?->resume) ? asset('storage/'.$referral->resume) : null;
        $refResumeFilename = $referral?->resume ? basename($referral->resume) : null;
        $refBarangayUrl = ! empty($referral?->ref_barangay_clearance) ? asset('storage/'.$referral->ref_barangay_clearance) : null;
        $refBarangayFilename = $referral?->ref_barangay_clearance ? basename($referral->ref_barangay_clearance) : null;
        $refPoliceUrl = ! empty($referral?->ref_police_clearance) ? asset('storage/'.$referral->ref_police_clearance) : null;
        $refPoliceFilename = $referral?->ref_police_clearance ? basename($referral->ref_police_clearance) : null;
        $refNbiUrl = ! empty($referral?->ref_nbi_clearance) ? asset('storage/'.$referral->ref_nbi_clearance) : null;
        $refNbiFilename = $referral?->ref_nbi_clearance ? basename($referral->ref_nbi_clearance) : null;

    @endphp

    <aside class="portal-sidebar">
        <div class="portal-sidebar-top">
            <div class="portal-brand">
                <div class="brand-mark">
                    <img src="{{ asset('images/logo_peso.png') }}" alt="PESO Logo">
                </div>
                <div class="portal-brand-copy">
                    <h1>Applicant Registry</h1>
                    <p>Public Employment Service Office</p>
                </div>
            </div>
        </div>

        <div class="portal-nav-wrap">
            <div class="portal-nav-label">Navigation</div>
            <nav class="portal-nav">
                <a href="{{ route('applicant.portal.index') }}" class="portal-nav-item">
                    <span class="portal-nav-icon"><i class="bi bi-house"></i></span>
                    <div>Dashboard</div>
                </a>
                <a href="{{ route('applicant.portal.personal-info') }}" class="portal-nav-item">
                    <span class="portal-nav-icon"><i class="bi bi-person-vcard"></i></span>
                    <div>Personal Info</div>
                </a>
                <a href="{{ route('applicant.portal.requirements') }}" class="portal-nav-item active">
                    <span class="portal-nav-icon"><i class="bi bi-folder2-open"></i></span>
                    <div>Requirements</div>
                </a>
            </nav>
        </div>

        <div class="portal-sidebar-footer">
            <div class="portal-user-card mb-3">
                <div class="portal-user-avatar">{{ strtoupper(substr($applicant->applicant_code ?? 'A', 0, 1)) }}</div>
                <div>
                    <strong>{{ $applicant->applicant_code }}</strong>
                    <small>Applicant account</small>
                </div>
            </div>
            <form action="{{ route('applicant.portal.logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="portal-main">
        <div class="container-portal">
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm alert-shell">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-shell">
                    {{ session('success') }}
                </div>
            @endif

            <div class="tab-card">
                <div class="section-head">
                    <div>
                        <div class="section-title">Requirement tabs</div>
                        <p class="section-copy">Switch between tabs to upload the files for each requirement group.</p>
                    </div>
                </div>

                <div class="requirements-tabs-shell">
                <ul class="nav nav-tabs requirements-tabs" id="requirementsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="permit-tab" data-bs-toggle="tab" data-bs-target="#permit-pane" type="button" role="tab">
                            <i class="bi bi-person-fill-lock"></i>
                            Permit to Work
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="clearance-tab" data-bs-toggle="tab" data-bs-target="#clearance-pane" type="button" role="tab">
                            <i class="bi bi-shield-check"></i>
                            Mayor's Clearance
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="referral-tab" data-bs-toggle="tab" data-bs-target="#referral-pane" type="button" role="tab">
                            <i class="bi bi-send"></i>
                            Mayor's Referral
                        </button>
                    </li>
                </ul>
                </div>

                <div class="tab-content" id="requirementsTabContent">
                    <div class="tab-pane fade show active" id="permit-pane" role="tabpanel" aria-labelledby="permit-tab">
                        <div class="tab-pane-shell">
                            <div class="upload-card">
                                <h3>Permit to Work Uploads</h3>
                                <p>Upload the permit documents required for processing.</p>

                                <form action="{{ route('applicant.portal.requirements.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="permit-grid">
                                        <div class="upload-field">
                                            <label for="permit_clearance_type">Clearance Type (NBI or Police) <span class="text-danger">*</span></label>
                                            <select name="permit_clearance_type" id="permit_clearance_type" class="form-select" required>
                                                <option value="">Select Type</option>
                                                <option value="nbi" {{ $permitClearanceType === 'nbi' ? 'selected' : '' }}>NBI Clearance</option>
                                                <option value="police" {{ $permitClearanceType === 'police' ? 'selected' : '' }}>Police Clearance</option>
                                            </select>
                                            <input type="file" class="form-control upload-file" name="permit_clearance_file" id="permit_clearance_file">
                                            <label for="permit_clearance_file" class="upload-button mt-3" id="permit_clearance_button">Upload File</label>
                                            @if ($permitClearanceFilename)
                                                <div class="current-file">
                                                    <span>{{ $permitClearanceFilename }}</span>
                                                    <a href="{{ $permitClearanceUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="upload-field">
                                            <label for="health_card">Health Card <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control upload-file" name="health_card" id="health_card">
                                            <label for="health_card" class="upload-button mt-3">Upload File</label>
                                            @if ($permitHealthCardFilename)
                                                <div class="current-file">
                                                    <span>{{ $permitHealthCardFilename }}</span>
                                                    <a href="{{ $permitHealthCardUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="upload-field">
                                            <label for="cedula">Cedula <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control upload-file" name="cedula" id="cedula">
                                            <label for="cedula" class="upload-button mt-3">Upload File</label>
                                            @if ($permitCedulaFilename)
                                                <div class="current-file">
                                                    <span>{{ $permitCedulaFilename }}</span>
                                                    <a href="{{ $permitCedulaUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="upload-field">
                                            <label for="referral_letter">Referral Letter</label>
                                            @if ($isImusResident)
                                                <label class="upload-button disabled mt-3" aria-disabled="true">Upload File</label>
                                                <div class="upload-note text-success fw-semibold mt-3 mb-0">
                                                    Not required for Imus residents
                                                </div>
                                            @else
                                                <input type="file" class="form-control upload-file" name="referral_letter" id="referral_letter">
                                                <label for="referral_letter" class="upload-button mt-3">Upload File</label>
                                                @if ($permitReferralFilename)
                                                    <div class="current-file">
                                                        <span>{{ $permitReferralFilename }}</span>
                                                        <a href="{{ $permitReferralUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-save">
                                            <i class="bi bi-cloud-upload-fill me-2"></i>Save Permit Files
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="clearance-pane" role="tabpanel" aria-labelledby="clearance-tab">
                        <div class="tab-pane-shell">
                            <div class="upload-card">
                                <h3>Mayor's Clearance Uploads</h3>
                                <p>Upload the clearance documents used for review and verification.</p>

                                <form action="{{ route('applicant.portal.requirements.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="clearance-grid">
                                        <div class="upload-field">
                                            <label for="prosecutor_clearance">Prosecutor Clearance</label>
                                            <input type="file" class="form-control upload-file" name="prosecutor_clearance" id="prosecutor_clearance">
                                            <label for="prosecutor_clearance" class="upload-button mt-3">Upload File</label>
                                            @if ($clearanceProsecutorFilename)
                                                <div class="current-file">
                                                    <span>{{ $clearanceProsecutorFilename }}</span>
                                                    <a href="{{ $clearanceProsecutorUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="upload-field">
                                            <label for="mtc_clearance">MTC Clearance</label>
                                            <input type="file" class="form-control upload-file" name="mtc_clearance" id="mtc_clearance">
                                            <label for="mtc_clearance" class="upload-button mt-3">Upload File</label>
                                            @if ($clearanceMtcFilename)
                                                <div class="current-file">
                                                    <span>{{ $clearanceMtcFilename }}</span>
                                                    <a href="{{ $clearanceMtcUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="upload-field">
                                            <label for="rtc_clearance">RTC Clearance</label>
                                            <input type="file" class="form-control upload-file" name="rtc_clearance" id="rtc_clearance">
                                            <label for="rtc_clearance" class="upload-button mt-3">Upload File</label>
                                            @if ($clearanceRtcFilename)
                                                <div class="current-file">
                                                    <span>{{ $clearanceRtcFilename }}</span>
                                                    <a href="{{ $clearanceRtcUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="upload-field">
                                            <label for="nbi_clearance">NBI Clearance</label>
                                            <input type="file" class="form-control upload-file" name="nbi_clearance" id="nbi_clearance">
                                            <label for="nbi_clearance" class="upload-button mt-3">Upload File</label>
                                            @if ($clearanceNbiFilename)
                                                <div class="current-file">
                                                    <span>{{ $clearanceNbiFilename }}</span>
                                                    <a href="{{ $clearanceNbiUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="upload-field">
                                            <label for="barangay_clearance">Barangay Clearance</label>
                                            <input type="file" class="form-control upload-file" name="barangay_clearance" id="barangay_clearance">
                                            <label for="barangay_clearance" class="upload-button mt-3">Upload File</label>
                                            @if ($clearanceBarangayFilename)
                                                <div class="current-file">
                                                    <span>{{ $clearanceBarangayFilename }}</span>
                                                    <a href="{{ $clearanceBarangayUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-save">
                                            <i class="bi bi-cloud-upload-fill me-2"></i>Save Clearance Files
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="referral-pane" role="tabpanel" aria-labelledby="referral-tab">
                        <div class="tab-pane-shell">
                            <div class="upload-card">
                                <h3>Mayor's Referral Requirements</h3>
                                <p>Upload your resume or bio-data and one supporting clearance for referral processing.</p>

                                <form action="{{ route('applicant.portal.requirements.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="upload-field full">
                                        <label for="resume">Resume / Bio-data <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control upload-file" name="resume" id="resume">
                                        <label for="resume" class="upload-button mt-3">Upload File</label>
                                        @if ($refResumeFilename)
                                            <div class="current-file">
                                                <span>{{ $refResumeFilename }}</span>
                                                <a href="{{ $refResumeUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                            </div>
                                        @elseif ($refBioDataFilename)
                                            <div class="current-file">
                                                <span>{{ $refBioDataFilename }}</span>
                                                <a href="{{ $refBioDataUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="section-head mt-3 mb-2">
                                        <div>
                                            <div class="section-title mb-0" style="font-size: .8rem;">Choose at least one of the following:</div>
                                        </div>
                                    </div>

                                    <div class="referral-grid">
                                        <div class="upload-field">
                                            <label for="ref_barangay_clearance">Barangay Clearance <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control upload-file" name="ref_barangay_clearance" id="ref_barangay_clearance">
                                            <label for="ref_barangay_clearance" class="upload-button mt-3">Upload File</label>
                                            @if ($refBarangayFilename)
                                                <div class="current-file">
                                                    <span>{{ $refBarangayFilename }}</span>
                                                    <a href="{{ $refBarangayUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="upload-field">
                                            <label for="ref_police_clearance">Police Clearance <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control upload-file" name="ref_police_clearance" id="ref_police_clearance">
                                            <label for="ref_police_clearance" class="upload-button mt-3">Upload File</label>
                                            @if ($refPoliceFilename)
                                                <div class="current-file">
                                                    <span>{{ $refPoliceFilename }}</span>
                                                    <a href="{{ $refPoliceUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="upload-field">
                                            <label for="ref_nbi_clearance">NBI Clearance <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control upload-file" name="ref_nbi_clearance" id="ref_nbi_clearance">
                                            <label for="ref_nbi_clearance" class="upload-button mt-3">Upload File</label>
                                            @if ($refNbiFilename)
                                                <div class="current-file">
                                                    <span>{{ $refNbiFilename }}</span>
                                                    <a href="{{ $refNbiUrl }}" target="_blank" rel="noopener" class="upload-button">View Current</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-save">
                                            <i class="bi bi-cloud-upload-fill me-2"></i>Save Referral Files
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
