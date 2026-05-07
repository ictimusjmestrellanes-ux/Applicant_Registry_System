<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Applicant Portal</title>
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
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            background:
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.16), transparent 28%),
                radial-gradient(circle at left bottom, rgba(16, 185, 129, 0.14), transparent 32%),
                linear-gradient(135deg, #ffffff, #f3f8ff 58%, #edf7f5);
            color: var(--ink);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
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

        .portal-nav-item small {
            display: block;
            margin-top: 0.12rem;
            color: var(--muted);
            font-size: 0.74rem;
            font-weight: 600;
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

        .portal-shell {
            min-height: 100vh;
        }

        .container-portal {
            width: 100%;
            padding-inline: 22px;
        }

        .topbar,
        .overview-card,
        .filter-card,
        .records-card,
        .summary-card,
        .action-card {
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

        .hero-chip,
        .mini-pill,
        .search-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.55rem 0.9rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .hero-chip {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .mini-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
        }

        .search-chip {
            background: #f1f5f9;
            color: #334155;
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
            font-size: 2rem;
            font-weight: 900;
            color: var(--ink);
            line-height: 1.08;
            margin: 0.35rem 0 0.55rem;
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

        .section-copy {
            color: var(--muted);
            margin: 0;
        }

        .section-head-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
        }

        .filter-card,
        .records-card {
            border-radius: 30px;
            padding: 26px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 1rem;
        }

        .summary-card,
        .action-card {
            border-radius: 28px;
            padding: 22px;
            height: 100%;
        }

        .metric-list {
            display: grid;
            gap: 0.9rem;
            margin-top: 1rem;
        }

        .metric-item {
            padding: 0.95rem 1rem;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: linear-gradient(180deg, #ffffff, #f8fbff);
        }

        .metric-item span {
            display: block;
            margin-bottom: 0.25rem;
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .metric-item strong {
            font-size: 0.95rem;
            font-weight: 900;
        }

        .progress-track {
            height: 9px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
            margin-top: 0.55rem;
        }

        .progress-track > div {
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #1d4ed8, #2563eb);
        }

        .action-list {
            display: grid;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .action-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.05rem;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: #fff;
            text-decoration: none;
            color: #0f172a;
        }

        .action-link:hover {
            border-color: rgba(59, 130, 246, 0.2);
            background: #f8fbff;
        }

        .action-link strong {
            display: block;
            font-weight: 900;
        }

        .action-link small {
            display: block;
            color: var(--muted);
            margin-top: 0.15rem;
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

        .records-card {
            margin-top: 1rem;
        }

        .quick-status {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .status-box {
            padding: 1rem 1.05rem;
            border-radius: 20px;
            background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }

        .status-box .label {
            display: block;
            margin-bottom: 0.25rem;
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .status-box .value {
            color: var(--ink);
            font-weight: 900;
        }

        @media (max-width: 991.98px) {
            .portal-sidebar {
                position: static;
                width: 100%;
                border-right: 0;
                border-bottom: 1px solid rgba(148, 163, 184, 0.22);
            }

            .portal-main {
                margin-left: 0;
                padding-top: 0;
            }
        }

        @media (max-width: 767.98px) {
            .container-portal {
                padding-inline: 14px;
            }

            .topbar,
            .overview-card,
            .filter-card,
            .records-card,
            .summary-card,
            .action-card {
                border-radius: 24px;
            }

            .overview-grid,
            .details-grid,
            .quick-status {
                grid-template-columns: 1fr;
            }

            .section-head {
                flex-direction: column;
                align-items: stretch;
            }

            .hero-actions {
                justify-content: flex-start;
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
        $profileReady = $applicant->first_name !== 'PENDING' && $applicant->last_name !== 'APPLICANT';
        $permitReady = $applicant->isPermitComplete();
        $clearanceReady = $applicant->isClearanceComplete();
        $referralReady = $applicant->isReferralComplete();
        $completedSections = collect([$profileReady, $permitReady, $clearanceReady, $referralReady])->filter()->count();
        $completionPercent = round(($completedSections / 4) * 100);
        $permitFileLabel = $permit?->clearance_type === 'police' ? 'Police Clearance' : 'NBI Clearance';
        $permitFileValue = $permit?->clearance_type === 'police' ? $permit?->permit_police_clearance : $permit?->permit_nbi_clearance;
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
                <a href="{{ route('applicant.portal.index') }}" class="portal-nav-item active">
                    <span class="portal-nav-icon"><i class="bi bi-house"></i></span>
                    <div>
                        Dashboard
                    </div>
                </a>
                <a href="{{ route('applicant.portal.personal-info') }}" class="portal-nav-item">
                    <span class="portal-nav-icon"><i class="bi bi-person-vcard"></i></span>
                    <div>
                        Personal Info
                    </div>
                </a>
                <a href="{{ route('applicant.portal.requirements') }}" class="portal-nav-item">
                    <span class="portal-nav-icon"><i class="bi bi-folder2-open"></i></span>
                    <div>
                        Requirements
                    </div>
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
        <div class="portal-shell">
            <div class="container-portal py-3 py-lg-4">
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('applicant_code'))
                    <div class="alert alert-info border-0 shadow-sm mb-3">
                        <strong>Your Username:</strong> {{ session('applicant_code') }}
                        <div class="small mt-1">Keep this username safe for future sign-ins.</div>
                    </div>
                @endif

                <div class="topbar mb-3 mb-lg-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3">
                        <div>
                            <div class="hero-kicker mb-3">
                                <i class="bi bi-clipboard-data"></i>
                                Applicant Registry Overview
                            </div>
                            <h1 class="hero-title">Welcome back, {{ trim(($applicant->first_name !== 'PENDING' ? $applicant->first_name : 'Applicant').' '.($applicant->last_name !== 'APPLICANT' ? $applicant->last_name : '')) }}</h1>
                            <p class="hero-copy">
                                Track your profile progress, see which documents are already submitted, and jump straight to the sections that still need attention.
                            </p>
                        </div>
                        <div class="hero-actions">
                            <div class="hero-chip">
                                <i class="bi bi-person-badge"></i>
                                {{ $applicant->applicant_code }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overview-grid mb-4" id="overview-section">
                    <div class="overview-card">
                        <div class="overview-icon icon-blue"><i class="bi bi-person-badge"></i></div>
                        <span class="overview-label">Profile</span>
                        <div class="overview-value">{{ $profileReady ? 'Ready' : 'Setup needed' }}</div>
                        <p class="section-copy mb-0">Your applicant identity and details.</p>
                    </div>
                    <div class="overview-card">
                        <div class="overview-icon icon-emerald"><i class="bi bi-file-earmark-check"></i></div>
                        <span class="overview-label">Permit</span>
                        <div class="overview-value">{{ $permitReady ? 'Complete' : 'Pending' }}</div>
                        <p class="section-copy mb-0">{{ $permitFileLabel }} uploads and permit details.</p>
                    </div>
                    <div class="overview-card">
                        <div class="overview-icon icon-amber"><i class="bi bi-shield-check"></i></div>
                        <span class="overview-label">Clearance</span>
                        <div class="overview-value">{{ $clearanceReady ? 'Complete' : 'Pending' }}</div>
                        <p class="section-copy mb-0">Mayor’s clearance documents.</p>
                    </div>
                    <div class="overview-card">
                        <div class="overview-icon icon-slate"><i class="bi bi-folder2-open"></i></div>
                        <span class="overview-label">Referral</span>
                        <div class="overview-value">{{ $referralReady ? 'Complete' : 'Pending' }}</div>
                        <p class="section-copy mb-0">Resume and supporting clearance files.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
