@extends('layouts.app')

@section('title', 'Update Applicant')

@section('content')

    @if(session('created_success'))

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                Swal.fire({
                    title: 'Applicant Successfully Created',
                    html: `

                                    <div style="font-size:14px;">
                                        <p class="mb-2">The applicant profile has been saved successfully.</p>
                                        <p class="text-muted">Would you like to continue editing the applicant requirements?</p>
                                    </div>
                                    `,
                    icon: 'success',
                    background: '#ffffff',
                    color: '#333',
                    width: 420,
                    showCancelButton: true,

                    confirmButtonText: '<i class="fa-solid fa-pen-to-square me-2"></i> Continue Editing',
                    cancelButtonText: '<i class="fa-solid fa-arrow-left me-2"></i> Back to List',

                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',

                    buttonsStyling: true,
                    reverseButtons: true,

                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }

                }).then((result) => {

                    if (!result.isConfirmed) {
                        window.location.href = "{{ route('applicants.index') }}";
                    }

                });

            });
        </script>

    @endif

    @php
        $fullName = trim($applicant->first_name . ' ' . ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') . $applicant->last_name . ' ' . ($applicant->suffix ?? ''));

        $permit = optional($applicant->permit);
        $isImusResident = $applicant->city && stripos($applicant->city, 'City of Imus') !== false;
        $hasPermitClearance =
            ($permit->clearance_type === 'nbi' && !empty($permit->permit_nbi_clearance)) ||
            ($permit->clearance_type === 'police' && !empty($permit->permit_police_clearance));
        $permitRequirements = [
            !empty($permit->health_card),
            !empty($permit->cedula),
            $hasPermitClearance,
        ];

        if (!$isImusResident) {
            $permitRequirements[] = !empty($permit->referral_letter);
        }

        $permitTotal = count($permitRequirements);
        $permitUploaded = collect($permitRequirements)->filter()->count();
        $permitPercent = $permitTotal > 0 ? round(($permitUploaded / $permitTotal) * 100) : 0;

        $clearance = optional($applicant->clearance);
        $clearanceRequirements = [
            $clearance->prosecutor_clearance,
            $clearance->mtc_clearance,
            $clearance->rtc_clearance,
            $clearance->nbi_clearance,
            $clearance->barangay_clearance,
        ];
        $clearanceUploaded = collect($clearanceRequirements)->filter()->count();
        $clearanceTotal = count($clearanceRequirements);
        $clearancePercent = $clearanceTotal > 0 ? round(($clearanceUploaded / $clearanceTotal) * 100) : 0;

        $referral = optional($applicant->referral);
        $hasResume = !empty($referral->resume);
        $hasReferralClearance = collect([
            $referral->ref_barangay_clearance,
            $referral->ref_police_clearance,
            $referral->ref_nbi_clearance,
        ])->filter()->count() > 0;
        $referralUploaded = ($hasResume ? 1 : 0) + ($hasReferralClearance ? 1 : 0);
        $referralPercent = round(($referralUploaded / 2) * 100);
    @endphp

    <style>
        :root {
            --edit-ink: #10243d;
            --edit-slate: #5f7088;
            --edit-line: #d9e4ef;
            --edit-soft: #f5f8fc;
            --edit-panel: rgba(255, 255, 255, 0.84);
            --edit-primary: #1d4ed8;
            --edit-primary-soft: #dbeafe;
            --edit-success: #059669;
            --edit-success-soft: #d1fae5;
            --edit-warm: #f59e0b;
            --edit-warm-soft: #fef3c7;
            --edit-deep: #0f172a;
            --edit-glow: rgba(37, 99, 235, 0.18);
        }

        .applicant-wrapper {
            max-width: 1800px;
        }

        .page-header {
            padding: 32px 32px;
            border-radius: 30px;
            border: 1px solid #e5edf5;
            background: #ffffff;
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.08);
        }

        .page-header::after {
            content: "";
            position: absolute;
            right: -80px;
            top: -70px;
            width: 250px;
            height: 250px;
            border-radius: 999px;
            background: rgba(59, 130, 246, 0.04);
        }

        .page-header::before {
            content: "";
            position: absolute;
            left: 48%;
            top: -120px;
            width: 260px;
            height: 260px;
            border-radius: 999px;
            background: rgba(16, 185, 129, 0.04);
        }

        .page-title-wrap {
            position: relative;
            z-index: 1;
        }

        .page-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            padding: 8px 13px;
            border-radius: 999px;
            background: #ffffff;
            color: #4b5f7a;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border: 1px solid #dce6f0;
        }

        .page-header h2 {
            margin-bottom: 8px;
            color: #10243d;
            font-size: clamp(2rem, 3vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .page-subtitle {
            max-width: 720px;
            color: #5f7088;
            margin: 0;
        }

        .page-header-actions {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .page-header-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 0.95rem;
            border-radius: 999px;
            background: #ffffff;
            border: 1px solid #dce6f0;
            color: #4b5f7a;
            font-size: 0.82rem;
            font-weight: 700;
            backdrop-filter: blur(10px);
        }

        .btn-back-list {
            border-radius: 16px;
            padding: 0.8rem 1rem;
            font-weight: 700;
            border: 1px solid #dce6f0;
            background: #ffffff;
            color: #10243d;
            backdrop-filter: blur(10px);
        }

        .btn-back-list:hover {
            background: #f8fbff;
            color: #10243d;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin: 20px 0 22px;
        }

        .summary-card {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            padding: 20px 20px 18px;
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            background: #ffffff;
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 24px 42px rgba(15, 23, 42, 0.12);
            border-color: #c9ddf5;
        }

        .summary-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .summary-icon-blue {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
        }

        .summary-icon-emerald {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
        }

        .summary-icon-amber {
            background: rgba(245, 158, 11, 0.16);
            color: #b45309;
        }

        .summary-icon-slate {
            background: rgba(71, 85, 105, 0.12);
            color: #334155;
        }

        .summary-label {
            display: block;
            margin-bottom: 8px;
            color: var(--edit-slate);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .summary-value {
            color: var(--edit-ink);
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .summary-subtext {
            display: block;
            margin-top: 6px;
            color: #708198;
            font-size: 0.82rem;
        }

        .requirements-container {
            padding: 24px;
            border-radius: 30px;
            border: 1px solid #e5edf5;
            background: #ffffff;
            backdrop-filter: blur(14px);
            box-shadow: 0 28px 70px rgba(15, 34, 58, 0.1);
        }

        .content-intro {
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
            padding: 2px 4px 0;
        }

        .content-intro p {
            margin: 0;
            color: var(--edit-slate);
        }

        .workspace-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 0.9rem;
            border-radius: 999px;
            background: #ffffff;
            color: #4b5f7a;
            font-size: 0.8rem;
            font-weight: 800;
            border: 1px solid #dce6f0;
        }

        .workflow-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 0.85rem;
        }

        .workflow-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 0.78rem;
            border-radius: 999px;
            background: #ffffff;
            color: #475569;
            border: 1px solid #dce6f0;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .nav-tab-label {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }

        .record-meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 0.8rem;
        }

        .record-meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.42rem 0.75rem;
            border-radius: 999px;
            background: #ffffff;
            border: 1px solid #dce6f0;
            color: #3f556f;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .tab-shell {
            padding: 12px;
            border-radius: 26px;
            background: #ffffff;
            border: 1px solid #e5edf5;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.95);
        }

        .nav-tabs {
            border: none;
            gap: 10px;
            padding: 0;
            flex-wrap: wrap;
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 14px 20px;
            border-radius: 18px;
            background: #ffffff;
            color: #5b6d86;
            font-weight: 700;
            transition: all .25s ease;
            border: 1px solid transparent;
        }

        .nav-tabs .nav-link:hover {
            background: #ffffff;
            border-color: #d8e4f2;
            color: var(--edit-ink);
            transform: translateY(-1px);
        }

        .nav-tabs .nav-link.active {
            background: #ffffff;
            color: #10243d;
            border: 1px solid #cfdbe8;
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
        }

        .tab-pane {
            animation: fadeSlide .35s ease;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tab-content {
            margin-top: 16px;
            padding: 28px;
            border-radius: 28px;
            background: #ffffff;
            border: 1px solid #e4edf7;
            box-shadow: 0 22px 46px rgba(15, 34, 58, 0.08);
        }

        .form-card {
            padding: 4px 2px 0;
        }

        .profile-action-bar {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e4edf7;
        }

        .section-title {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            color: var(--edit-ink);
            font-size: 0.96rem;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .section-title-c {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            color: black;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .section-title-c::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: linear-gradient(135deg, #10b981, #3b82f6);
            box-shadow: 0 0 0 6px rgba(59, 130, 246, 0.08);
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
            margin-bottom: 7px;
            color: #44526f;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .form-control,
        .form-select {
            min-height: 48px;
            border-radius: 16px;
            border: 1px solid var(--edit-line);
            padding: 11px 14px;
            font-size: 14px;
            background: #f8fbff;
            transition: all .25s ease;
        }

        .form-control:hover,
        .form-select:hover {
            border-color: #bfd0e6;
            background: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #7aa2ff;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .select2-container .select2-selection--single {
            min-height: 48px;
            border-radius: 16px;
            border: 1px solid var(--edit-line);
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
            right: 12px;
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--open .select2-selection--single {
            border-color: #7aa2ff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
            background: #fff;
        }

        .select2-dropdown {
            border: 1px solid #d7e3f0;
            border-radius: 14px;
            overflow: hidden;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #d7e3f0;
            border-radius: 10px;
            padding: 8px 10px;
        }

        input[type=file] {
            background: #f5f9ff;
            border: 1px dashed #c8d7eb;
        }

        .document-upload-card {
            height: 100%;
            padding: 20px;
            border-radius: 22px;
            border: 1px solid #dce7f3;
            background: #ffffff;
            box-shadow: 0 14px 26px rgba(15, 34, 58, 0.05);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .document-upload-card-resume {
            padding: 20px;
            border-radius: 22px;
            border: 1px solid #dce7f3;
            background: #ffffff;
            box-shadow: 0 14px 26px rgba(15, 34, 58, 0.05);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .document-upload-card:hover {
            transform: translateY(-2px);
            border-color: #a5c1f5;
            box-shadow: 0 16px 32px rgba(15, 34, 58, 0.08);
        }

        .document-upload-card-resume:hover {
            transform: translateY(-2px);
            border-color: #a5c1f5;
            box-shadow: 0 16px 32px rgba(15, 34, 58, 0.08);
        }


        .clearance-upload-row {
            display: flex;
            gap: 0;
            overflow: hidden;
            padding-bottom: 0.5rem;
        }

        .clearance-upload-col {
            flex: 0 0 20%;
            max-width: 20%;
            padding: 0;
        }

        .upload-disabled {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            opacity: 0.82;
            cursor: not-allowed;
        }

        .file-name-text {
            display: block;
            margin-top: 8px;
            color: #64748b;
            font-size: 0.8rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .required-mark {
            color: #ef4444;
            margin-left: 3px;
            font-weight: 800;
        }

        .btn {
            border-radius: 14px;
            font-weight: 700;
            transition: all .25s ease;
        }

        .btn-primary,
        .btn-success,
        .btn-secondary,
        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-light.border,
        .btn-back-list {
            background: #ffffff !important;
            border: 1px solid #d8e3ee !important;
            color: #10243d !important;
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        }

        .btn-primary:hover,
        .btn-success:hover,
        .btn-secondary:hover,
        .btn-outline-primary:hover,
        .btn-outline-secondary:hover,
        .btn-light.border:hover,
        .btn-back-list:hover {
            background: #d1d5db !important;
            border-color: #9ca3af !important;
            color: #10243d !important;
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        }

        .btn:disabled,
        .btn.disabled {
            background: #f4f7fa !important;
            border-color: #d8e3ee !important;
            color: #7b8796 !important;
            box-shadow: none;
        }

        .activity-log-card {
            margin-top: 20px;
            padding: 20px 22px;
            border-radius: 24px;
            border: 1px solid #dfe9f3;
            background: linear-gradient(180deg, #fbfdff, #f3f8fd);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
        }

        .activity-log-item {
            border-radius: 16px;
            padding: 0.2rem 0;
        }

        .activity-log-item+.activity-log-item {
            border-top: 1px solid #e6edf5;
            margin-top: 1rem;
            padding-top: 1rem;
        }

        .activity-log-meta {
            font-size: 0.82rem;
            color: #64748b;
        }

        .activity-log-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.24rem 0.78rem;
            margin: 0.2rem 0.35rem 0 0;
            border-radius: 999px;
            background: #e0ecff;
            color: #2952a3;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .tab-pane {
            animation: fadeSlide .35s ease;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        html {
            scroll-behavior: smooth;
        }

        @media (max-width: 1200px) {
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .col-md-2 {
                flex: 0 0 33%;
                max-width: 33%;
            }

            .clearance-upload-row {
                overflow: auto;
            }

            .clearance-upload-col {
                flex: 0 0 33.3333%;
                max-width: 33.3333%;
            }
        }

        @media (max-width: 768px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .col-md-2,
            .col-md-3,
            .col-md-4,
            .col-md-5,
            .col-md-6,
            .col-md-1 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .clearance-upload-col {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .page-header {
                padding: 22px 20px;
            }

            .page-header h2 {
                font-size: 1.55rem;
            }

            .content-intro {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-header-actions {
                margin-top: 1rem;
            }

            .tab-content {
                padding: 18px;
            }

            .profile-action-bar>* {
                width: 100%;
            }
        }
    </style>

    <div class="container applicant-wrapper">
        <div class="requirements-container">
            <div class="content-intro">
                <div>
                    <h5 class="fw-bold mb-1">Document Compliance</h5>
                    <p class="small">Manage permit, clearance, and referral requirements with a cleaner workflow.</p>
                </div>
            </div>

            <div class="tab-shell">
                <ul class="nav nav-tabs mb-0" id="mayorTabs">

                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal">
                            <span class="nav-tab-label">
                                <i class="bi bi-person-lines-fill"></i>
                                Personal Information
                            </span>
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#permit">
                            <span class="nav-tab-label">
                                <i class="bi bi-patch-check-fill"></i>
                                Mayor's Permit to Work
                            </span>
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#clearance">
                            <span class="nav-tab-label">
                                <i class="bi bi-shield-fill-check"></i>
                                Mayor's Clearance
                            </span>
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#referral">
                            <span class="nav-tab-label">
                                <i class="bi bi-send-fill"></i>
                                Mayor's Referral
                            </span>
                        </button>
                    </li>

                </ul>
            </div>

            <div class="tab-content">

                <!-- ===================================================== -->
                <!-- PERSONAL INFORMATION -->
                <!-- ===================================================== -->

                <div class="tab-pane fade show active" id="personal">

                    <div class="form-card">

                        <form action="{{ route('applicants.update', $applicant->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <section class="form-section">
                                <h6 class="section-title">Personal Information</h6>
                                <div class="row g-4">
                                    <div class="col-md-2">
                                        <label class="form-label">First Time Jobseeker <span
                                                class="required-mark">*</span></label>
                                        <select name="first_time_job_seeker" class="form-select" required>
                                            <option value="No" {{ $applicant->first_time_job_seeker == 'No' ? 'selected' : '' }}>No</option>
                                            <option value="Yes" {{ $applicant->first_time_job_seeker == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">First Name <span class="required-mark">*</span></label>
                                        <input type="text" name="first_name" class="form-control"
                                            value="{{ $applicant->first_name }}" placeholder="e.g. John" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control"
                                            value="{{ $applicant->middle_name }}" placeholder="Optional">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Last Name <span class="required-mark">*</span></label>
                                        <input type="text" name="last_name" class="form-control"
                                            value="{{ $applicant->last_name }}" placeholder="e.g. Doe" required>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Suffix</label>
                                        <select name="suffix" class="form-select">
                                            <option value="">None</option>
                                            <option value="Jr." {{ $applicant->suffix == 'Jr.' ? 'selected' : '' }}>Jr.
                                            </option>
                                            <option value="Sr." {{ $applicant->suffix == 'Sr.' ? 'selected' : '' }}>Sr.
                                            </option>
                                            <option value="II" {{ $applicant->suffix == 'II' ? 'selected' : '' }}>II</option>
                                            <option value="III" {{ $applicant->suffix == 'III' ? 'selected' : '' }}>III
                                            </option>
                                            <option value="IV" {{ $applicant->suffix == 'IV' ? 'selected' : '' }}>IV</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Age <span class="required-mark">*</span></label>
                                        <input type="number" name="age" class="form-control" value="{{ $applicant->age }}"
                                            placeholder="e.g. 25" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Sex/Gender <span class="required-mark">*</span></label>
                                        <select name="gender" class="form-select" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ $applicant->gender == 'Male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="Female" {{ $applicant->gender == 'Female' ? 'selected' : '' }}>
                                                Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Civil Status <span class="required-mark">*</span></label>
                                        <select name="civil_status" class="form-select" required>
                                            <option value="">Select Status</option>
                                            <option value="Single" {{ $applicant->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ $applicant->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Widowed" {{ $applicant->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">PWD?<span class="required-mark">*</span></label>
                                        <select name="pwd" class="form-select" required>
                                            <option value="No" {{ $applicant->pwd == 'No' ? 'selected' : '' }}>No</option>
                                            <option value="Yes" {{ $applicant->pwd == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">4Ps?<span class="required-mark">*</span></label>
                                        <select name="four_ps" class="form-select" required>
                                            <option value="No" {{ $applicant->four_ps == 'No' ? 'selected' : '' }}>No</option>
                                            <option value="Yes" {{ $applicant->four_ps == 'Yes' ? 'selected' : '' }}>Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </section>

                            <section class="form-section mt-4">
                                <h6 class="section-title">Contact & Location</h6>
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Contact Number <span
                                                class="required-mark">*</span></label>
                                        <input type="tel" name="contact_no" class="form-control"
                                            value="{{ $applicant->contact_no }}" placeholder="09123456789"
                                            pattern="[0-9]{11}" maxlength="11" inputmode="numeric" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Complete Address <span
                                                class="required-mark">*</span></label>
                                        <input type="text" name="address_line" class="form-control"
                                            value="{{ $applicant->address_line }}"
                                            placeholder="House No. / Street / Phase / Block" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Province <span class="required-mark">*</span></label>
                                        <select name="province" id="province" class="form-select" required>
                                            <option value="">Select Province</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">City / Municipality <span
                                                class="required-mark">*</span></label>
                                        <select name="city" id="city" class="form-select" required>
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Barangay <span class="required-mark">*</span></label>
                                        <select name="barangay" id="barangay" class="form-select" required>
                                            <option value="">Select Barangay</option>
                                        </select>
                                    </div>
                                </div>
                            </section>

                            <section class="form-section mt-4">
                                <h6 class="section-title">Education & Hiring</h6>
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Educational Attainment <span
                                                class="required-mark">*</span></label>
                                        <select name="educational_attainment" id="educationalAttainmentSelect"
                                            class="form-select" required>
                                            <option value="">Select educational attainment</option>
                                            @foreach(config('educational_attainments', []) as $attainment)
                                                <option value="{{ $attainment }}" {{ $applicant->educational_attainment === $attainment ? 'selected' : '' }}>
                                                    {{ $attainment }}
                                                </option>
                                            @endforeach
                                            @if($applicant->educational_attainment && !in_array($applicant->educational_attainment, config('educational_attainments', []), true))
                                                <option value="{{ $applicant->educational_attainment }}" selected>
                                                    {{ $applicant->educational_attainment }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Hiring Company <span
                                                class="required-mark">*</span></label>
                                        <input type="text" name="hiring_company" class="form-control"
                                            value="{{ $applicant->hiring_company }}" placeholder="e.g. Tech Corp" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Position Hired <span
                                                class="required-mark">*</span></label>
                                        <input type="text" name="position_hired" class="form-control"
                                            value="{{ $applicant->position_hired }}" placeholder="e.g. Software Engineer"
                                            required>
                                    </div>
                                </div>
                            </section>


                            <div class="profile-action-bar">

                                <button type="submit" class="btn btn-success px-5 py-2">
                                    <i class="fa-solid fa-check me-2"></i>
                                    Update Applicant Profile
                                </button>

                                <a href="{{ route('applicants.index') }}" class="btn btn-light border px-4 py-2">
                                    Cancel
                                </a>

                            </div>
                        </form>
                    </div>
                </div>

                <!-- ===================================================== -->
                <!-- PERMIT -->
                <!-- ===================================================== -->

                <div class="tab-pane fade" id="permit">
                    <form action="{{ route('permits.update', $applicant->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @php
                            $permit = optional($applicant->permit);
                        @endphp

                        @php
                            $permit = optional($applicant->permit);
                            $isImusResident = stripos($applicant->city, 'City of Imus') !== false;
                        @endphp

                        <h6 class="section-title text-primary">Mayor’s Permit to Work Requirements</h6>

                        <div class="row g-3">
                            {{-- 1. NBI / Police Clearance --}}
                            <div class="col-md-3">
                                <div class="document-upload-card">
                                    <label class="form-label">Clearance Type (NBI or Police)<span
                                            class="required-mark">*</span></label>
                                    <select name="clearance_type" id="clearance_type"
                                        class="form-select form-select-sm mb-3">
                                        <option value="">Select Type</option>
                                        <option value="nbi" {{ old('clearance_type', $permit->clearance_type ?? '') == 'nbi' ? 'selected' : '' }}>
                                            NBI Clearance
                                        </option>
                                        <option value="police" {{ old('clearance_type', $permit->clearance_type ?? '') == 'police' ? 'selected' : '' }}>
                                            Police Clearance
                                        </option>
                                    </select>

                                    <div class="gap-2" id="nbi_section" style="display:none">
                                        <!-- FILE INPUT (HIDDEN BUT CLICKABLE VIA LABEL) -->
                                        <input type="file" id="nbi_input" name="permit_nbi_clearance" class="d-none"
                                            onchange="showFileName(this, 'nbi_name')">

                                        <!-- USE LABEL INSTEAD OF BUTTON -->
                                        <label for="nbi_input" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>

                                        <small id="nbi_name" class="file-name-text">
                                            {{ !empty($permit->permit_nbi_clearance) ? basename($permit->permit_nbi_clearance) : 'No file selected' }}
                                        </small>

                                        @if(!empty($permit->permit_nbi_clearance))
                                            <a href="{{ asset('storage/' . $permit->permit_nbi_clearance) }}" target="_blank"
                                                class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>


                                    <div class="gap-2" id="police_section" style="display:none">

                                        <!-- FILE INPUT (HIDDEN BUT CLICKABLE VIA LABEL) -->
                                        <input type="file" id="police_input" name="permit_police_clearance" class="d-none"
                                            onchange="showFileName(this, 'police_name')">

                                        <!-- USE LABEL INSTEAD OF BUTTON -->
                                        <label for="police_input" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>

                                        <!-- FILE NAME -->
                                        <small id="police_name" class="file-name-text">
                                            {{ !empty($permit->permit_police_clearance) ? basename($permit->permit_police_clearance) : 'No file selected' }}
                                        </small>

                                        <!-- VIEW FILE -->
                                        @if(!empty($permit->permit_police_clearance))
                                            <a href="{{ asset('storage/' . $permit->permit_police_clearance) }}" target="_blank"
                                                class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            {{-- 2. Health Card --}}
                            <div class="col-md-3">
                                <div class="document-upload-card">
                                    <label class="form-label">Health Card <span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="health_card_input" name="health_card" style="display:none"
                                            onchange="showFileName(this, 'health_card_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('health_card_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="health_card_name" class="file-name-text">
                                            {{ !empty($permit->health_card) ? basename($permit->health_card) : 'No file selected' }}
                                        </small>
                                        @if(!empty($permit->health_card))
                                            <a href="{{ asset('storage/' . $permit->health_card) }}" target="_blank"
                                                class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- 3. Cedula --}}
                            <div class="col-md-3">
                                <div class="document-upload-card">
                                    <label class="form-label">Cedula <span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="cedula_input" name="cedula" style="display:none"
                                            onchange="showFileName(this, 'cedula_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('cedula_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="cedula_name" class="file-name-text">
                                            {{ !empty($permit->cedula) ? basename($permit->cedula) : 'No file selected' }}
                                        </small>
                                        @if(!empty($permit->cedula))
                                            <a href="{{ asset('storage/' . $permit->cedula) }}" target="_blank"
                                                class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- 4. Referral Letter --}}
                            <div class="col-md-3">
                                <div class="document-upload-card {{ $isImusResident ? 'upload-disabled' : '' }}">
                                    <label class="form-label">
                                        Referral Letter
                                        @if(!$isImusResident)<span class="required-mark">*</span>@endif
                                    </label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="referral_input" name="referral_letter" style="display:none"
                                            onchange="showFileName(this, 'referral_name')" {{ $isImusResident ? 'disabled' : '' }}>

                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('referral_input').click()" {{ $isImusResident ? 'disabled' : '' }}>
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>

                                        <small id="referral_name" class="file-name-text">
                                            {{ !empty($permit->referral_letter) ? basename($permit->referral_letter) : 'No file selected' }}
                                        </small>
                                        @if(!empty($permit->referral_letter))
                                            <a href="{{ asset('storage/' . $permit->referral_letter) }}" target="_blank"
                                                class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif

                                        @if($isImusResident)
                                            <div class="badge bg-success-soft text-success p-2 mt-1" style="font-size: 11px;">
                                                Not required for Imus residents
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>


                        <h6 class="section-title text-primary mt-4">Permit to Work ID Details</h6>

                        <div class="row g-3 mt-3">

                            {{-- Peso ID No --}}
                            <div class="col-md-2">
                                <label class="form-label">Peso ID No. (Auto Generate)<span
                                        class="required-mark">*</span></label>
                                <input type="text" name="peso_id_no" class="form-control" style="text-align: center"
                                    value="{{ $permit->peso_id_no ?? '' }}" placeholder="Will generate after save when complete" readonly>
                            </div>

                            {{-- OR NUMBER --}}
                            <div class="col-md-2">
                                <label class="form-label">O.R No. <span class="required-mark">*</span></label>
                                <input type="text" name="permit_or_no" value="{{ $permit->permit_or_no }}"
                                    class="form-control" required>
                            </div>

                            {{-- Community Tax No --}}
                            <div class="col-md-2">
                                <label class="form-label">Community Tax No.<span class="required-mark">*</span></label>
                                <input type="text" name="community_tax_no" class="form-control"
                                    value="{{$permit->community_tax_no}}" required>
                            </div>

                            {{-- Issued On --}}
                            <div class="col-md-2">
                                <label class="form-label">Permit Issued On<span class="required-mark">*</span></label>
                                <input type="date" name="permit_issued_on" class="form-control"
                                    value="{{$permit->permit_issued_on}}" required>
                            </div>

                            {{-- Permit Issued At --}}
                            <div class="col-md-2">
                                <label class="form-label">Permit Issued At<span class="required-mark">*</span></label>
                                <select type="text" name="permit_issued_at" id="permitIssuedAtSelect" class="form-select"
                                    required>
                                    <option value="{{ old('permit_issued_at', $permit->permit_issued_at ?? '') }}" selected>
                                        {{ old('permit_issued_at', $permit->permit_issued_at ?? 'Select City Government') }}
                                    </option>
                                </select>
                            </div>

                            {{-- Permit Date --}}
                            <div class="col-md-2">
                                <label class="form-label">Permit Date<span class="required-mark">*</span></label>
                                <input type="date" id="permit_date" name="permit_date" class="form-control"
                                    value="{{$permit->permit_date}}" required>
                            </div>

                            {{-- Expiration --}}
                            <div class="col-md-2">
                                <label class="form-label">Expires On<span class="required-mark">*</span></label>
                                <input type="date" id="expires_on" name="expires_on" class="form-control"
                                    value="{{$permit->expires_on}}" readonly>
                            </div>

                            {{-- Documentary Stamp --}}
                            <div class="col-md-2">
                                <label class="form-label">Documentary Stamp Control No.<span
                                        class="required-mark">*</span></label>
                                <input type="text" name="permit_doc_stamp_control_no" class="form-control"
                                    value="{{$permit->permit_doc_stamp_control_no}}" required>
                            </div>
                            {{-- Date of Payment --}}
                            <div class="col-md-2">
                                <label class="form-label">Date of Payment<span class="required-mark">*</span></label>
                                <input type="date" name="permit_date_of_payment" class="form-control"
                                    value="{{$permit->permit_date_of_payment}}">
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-4">
                            {{-- Action: Save/Update --}}
                            @if(auth()->user()->hasPermission('update_permit'))
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>Save Permit
                                </button>
                            @else
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip"
                                    title="No permission to update">
                                    <button type="button" class="btn btn-outline-secondary px-4" disabled>
                                        Save Permit
                                    </button>
                                </span>
                            @endif

                            {{-- Action: Print/Generate --}}
                            @if(auth()->user()->hasPermission('generate_permit') && $permit && $permit->isComplete())
                                <a href="{{ route('permits.printId', $applicant->id) }}" target="_blank"
                                    class="btn btn-success px-4 shadow-sm">
                                    <i class="fa-solid fa-id-card me-2"></i>Print Permit ID
                                </a>
                            @else
                                @php
                                    // Logic para sa error message
                                    $reason = !auth()->user()->hasPermission('generate_permit')
                                        ? 'No permission to generate ID'
                                        : 'Complete all requirements first';
                                @endphp

                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{ $reason }}">
                                    <button class="btn btn-outline-secondary px-4" disabled>
                                        <i class="fa-solid fa-id-card me-2 text-muted"></i>View Permit to Work ID
                                    </button>
                                </span>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- ===================================================== -->
                <!-- CLEARANCE -->
                <!-- ===================================================== -->

                <div class="tab-pane fade" id="clearance">
                    <form action="{{ route('clearances.update', $applicant->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php $clearance = optional($applicant->clearance); @endphp

                        <h6 class="section-title text-primary">Mayor's Clearance Requirements</h6>

                        <div class="clearance-upload-row">

                            <div class="clearance-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Prosecutor Clearance<span
                                            class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="prosecutor_input" name="prosecutor_clearance"
                                            style="display:none" onchange="showFileName(this, 'prosecutor_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('prosecutor_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="prosecutor_name" class="file-name-text">
                                            {{ !empty($clearance->prosecutor_clearance) ? basename($clearance->prosecutor_clearance) : 'No file selected' }}
                                        </small>
                                        @if(!empty($clearance->prosecutor_clearance))
                                            <a href="{{ asset('storage/' . $clearance->prosecutor_clearance) }}" target="_blank"
                                                class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="clearance-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Municipal Trial Court Clearance<span
                                            class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="mtc_input" name="mtc_clearance" style="display:none"
                                            onchange="showFileName(this, 'mtc_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('mtc_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="mtc_name" class="file-name-text">
                                            {{ !empty($clearance->mtc_clearance) ? basename($clearance->mtc_clearance) : 'No file selected' }}
                                        </small>
                                        @if(!empty($clearance->mtc_clearance))
                                            <a href="{{ asset('storage/' . $clearance->mtc_clearance) }}" target="_blank"
                                                class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="clearance-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Regional Trial Court Clearance<span
                                            class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="rtc_input" name="rtc_clearance" style="display:none"
                                            onchange="showFileName(this, 'rtc_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('rtc_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="rtc_name" class="file-name-text">
                                            {{ !empty($clearance->rtc_clearance) ? basename($clearance->rtc_clearance) : 'No file selected' }}
                                        </small>
                                        @if(!empty($clearance->rtc_clearance))
                                            <a href="{{ asset('storage/' . $clearance->rtc_clearance) }}" target="_blank"
                                                class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="clearance-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">NBI Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="c_nbi_input" name="nbi_clearance" style="display:none"
                                            onchange="showFileName(this, 'c_nbi_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('c_nbi_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="c_nbi_name" class="file-name-text">
                                            {{ !empty($clearance->nbi_clearance) ? basename($clearance->nbi_clearance) : 'No file selected' }}
                                        </small>
                                        @if(!empty($clearance->nbi_clearance))
                                            <a href="{{ asset('storage/' . $clearance->nbi_clearance) }}" target="_blank"
                                                class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="clearance-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Barangay Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="brgy_input" name="barangay_clearance" style="display:none"
                                            onchange="showFileName(this, 'brgy_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('brgy_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="brgy_name" class="file-name-text">
                                            {{ !empty($clearance->barangay_clearance) ? basename($clearance->barangay_clearance) : 'No file selected' }}
                                        </small>
                                        @if(!empty($clearance->barangay_clearance))
                                            <a href="{{ asset('storage/' . $clearance->barangay_clearance) }}" target="_blank"
                                                class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        <h6 class="section-title text-primary mb-0 mt-4">Mayor’s Clearance Letter Details</h6>
                        <div class="row g-3 mt-3">
                            {{-- PESO Control No --}}
                            <div class="col-md-2">
                                <label class="form-label">Peso Control No. (Auto Generate)<span
                                        class="required-mark">*</span></label>
                                <input type="text" name="peso_id_no" class="form-control" style="text-align: center"
                                    value="{{ $clearance->clearance_peso_control_no }}" placeholder="Will generate after save when complete" readonly>
                            </div>

                            {{-- Official Receipt No --}}
                            <div class="col-md-2">
                                <label class="form-label">O.R. No.<span class="required-mark">*</span></label>
                                <input type="text" name="clearance_or_no" class="form-control"
                                    value="{{$clearance->clearance_or_no}}" required>
                            </div>
                            
                            {{-- Hired Company --}}
                            <div class="col-md-2">
                                <label class="form-label">Hired Company<span class="required-mark">*</span></label>
                                <input type="text" name="clearance_hired_company" class="form-control"
                                    value="{{$clearance->clearance_hired_company}}" required>
                            </div>

                            {{-- Issued On --}}
                            <div class="col-md-2">
                                <label class="form-label">Issued On<span class="required-mark">*</span></label>
                                <input type="date" name="clearance_issued_on" class="form-control"
                                    value="{{$clearance->clearance_issued_on}}" required>
                            </div>

                            {{-- Documentary Stamp Control No --}}
                            <div class="col-md-2">
                                <label class="form-label">Documentary Stamp Control No.<span
                                        class="required-mark">*</span></label>
                                <input type="text" name="clearance_doc_stamp_control_no" class="form-control"
                                    value="{{$clearance->clearance_doc_stamp_control_no}}" required>
                            </div>
                            {{-- Date of Payment --}}
                            <div class="col-md-2">
                                <label class="form-label">Date of Payment<span class="required-mark">*</span></label>
                                <input type="date" name="clearance_date_of_payment" class="form-control"
                                    value="{{$clearance->clearance_date_of_payment}}" required>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-3 mt-4">
                            {{-- Update/Save Section --}}
                            @if(auth()->user()->hasPermission('update_clearance'))
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fa-solid fa-check-circle me-2"></i>Save Clearance
                                </button>
                            @else
                                <span class="d-inline-block" data-bs-toggle="tooltip" title="No permission to update">
                                    <button type="button" class="btn btn-outline-secondary px-4" disabled>
                                        Save Clearance
                                    </button>
                                </span>
                            @endif

                            {{-- Print Section --}}
                            @if(auth()->user()->hasPermission('generate_clearance') && $clearance && $clearance->isComplete())
                                <a href="{{ route('clearances.printLetter', $applicant->id) }}" target="_blank"
                                    class="btn btn-success px-4 shadow-sm">
                                    <i class="fa-solid fa-print me-2"></i>Print Clearance Letter
                                </a>
                            @else
                                @php
                                    $reason = !auth()->user()->hasPermission('generate_clearance')
                                        ? 'No permission to generate letter'
                                        : 'Requirements incomplete';
                                @endphp

                                <span class="d-inline-block" data-bs-toggle="tooltip" title="{{ $reason }}">
                                    <button class="btn btn-outline-secondary px-4" disabled>
                                        <i class="fa-solid fa-print me-2 text-muted"></i>Print Clearance Letter
                                    </button>
                                </span>
                            @endif
                        </div>

                    </form>

                </div>

                <!-- ===================================================== -->
                <!-- REFERRAL -->
                <!-- ===================================================== -->

                <div class="tab-pane fade" id="referral">

                    <form action="{{ route('referrals.update', $applicant->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php $referral = optional($applicant->referral); @endphp

                        <h6 class="section-title text-primary">Mayor's Referral Requirements</h6>

                        <div class="mb-3">
                            <div class="document-upload-card-resume">
                                <label class="form-label">Resume / Bio-data<span class="required-mark">*</span></label>
                                <div class="d-grid gap-2">
                                    <input type="file" id="resume_input" name="resume" style="display:none"
                                        onchange="showFileName(this, 'resume_name')">
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="document.getElementById('resume_input').click()">
                                        <i class="fas fa-upload me-1"></i> Upload File
                                    </button>
                                    <small id="resume_name" class="file-name-text">
                                        {{ !empty($referral->resume) ? basename($referral->resume) : 'No file selected' }}
                                    </small>
                                    @if(!empty($referral->resume))
                                        <a href="{{ asset('storage/' . $referral->resume) }}" target="_blank"
                                            class="btn btn-light btn-sm text-primary border">
                                            <i class="fas fa-eye me-1"></i> View Current
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <h4 class="section-title-c text-primary">Choose at least one of the following:</h6>
                            <div class="d-flex justify-content-center overflow-auto pb-2">

                                <div class="col-md-4">
                                    <div class="document-upload-card">
                                        <label class="form-label">Barangay Clearance<span
                                                class="required-mark">*</span></label>
                                        <div class="d-grid gap-2">
                                            <input type="file" id="ref_brgy_input" name="ref_barangay_clearance"
                                                style="display:none" onchange="showFileName(this, 'ref_brgy_name')">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="document.getElementById('ref_brgy_input').click()">
                                                <i class="fas fa-upload me-1"></i> Upload File
                                            </button>
                                            <small id="ref_brgy_name" class="file-name-text">
                                                {{ !empty($referral->ref_barangay_clearance) ? basename($referral->ref_barangay_clearance) : 'No file selected' }}
                                            </small>
                                            @if(!empty($referral->ref_barangay_clearance))
                                                <a href="{{ asset('storage/' . $referral->ref_barangay_clearance) }}"
                                                    target="_blank" class="btn btn-light btn-sm text-primary border">
                                                    <i class="fas fa-eye me-1"></i> View Current
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="document-upload-card">
                                        <label class="form-label">Police Clearance<span
                                                class="required-mark">*</span></label>
                                        <div class="d-grid gap-2">
                                            <input type="file" id="ref_police_input" name="ref_police_clearance"
                                                style="display:none" onchange="showFileName(this, 'ref_police_name')">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="document.getElementById('ref_police_input').click()">
                                                <i class="fas fa-upload me-1"></i> Upload File
                                            </button>
                                            <small id="ref_police_name" class="file-name-text">
                                                {{ !empty($referral->ref_police_clearance) ? basename($referral->ref_police_clearance) : 'No file selected' }}
                                            </small>
                                            @if(!empty($referral->ref_police_clearance))
                                                <a href="{{ asset('storage/' . $referral->ref_police_clearance) }}"
                                                    target="_blank" class="btn btn-light btn-sm text-primary border">
                                                    <i class="fas fa-eye me-1"></i> View Current
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="document-upload-card">
                                        <label class="form-label">NBI Clearance<span class="required-mark">*</span></label>
                                        <div class="d-grid gap-2">
                                            <input type="file" id="ref_nbi_input" name="ref_nbi_clearance"
                                                style="display:none" onchange="showFileName(this, 'ref_nbi_name')">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="document.getElementById('ref_nbi_input').click()">
                                                <i class="fas fa-upload me-1"></i> Upload File
                                            </button>
                                            <small id="ref_nbi_name" class="file-name-text">
                                                {{ !empty($referral->ref_nbi_clearance) ? basename($referral->ref_nbi_clearance) : 'No file selected' }}
                                            </small>
                                            @if(!empty($referral->ref_nbi_clearance))
                                                <a href="{{ asset('storage/' . $referral->ref_nbi_clearance) }}" target="_blank"
                                                    class="btn btn-light btn-sm text-primary border">
                                                    <i class="fas fa-eye me-1"></i> View Current
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-4">
                                <h6 class="section-title text-primary">Mayor's Referral Letter Details</h6>
                                @php
                                    $selectedReferralType = old(
                                        'referral_type',
                                        $referral->referral_type ?? \App\Models\MayorsReferral::TYPE_PESO_OFFICE
                                    );
                                @endphp

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Referral Letter Type</label>
                                    <select name="referral_type" id="referralTypeSelect" class="form-select">
                                        <option value="{{ \App\Models\MayorsReferral::TYPE_PESO_OFFICE }}" {{ $selectedReferralType === \App\Models\MayorsReferral::TYPE_PESO_OFFICE ? 'selected' : '' }}>
                                            Referral Within Imus
                                        </option>
                                        <option value="{{ \App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT }}" {{ $selectedReferralType === \App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT ? 'selected' : '' }}>
                                            Referral Outside Imus (Other City Government)
                                        </option>
                                    </select>
                                </div>

                                <div class="border p-4 rounded-4 bg-white">
                                    <div id="pesoOfficeFields" data-referral-group="peso"
                                        class="{{ $selectedReferralType === \App\Models\MayorsReferral::TYPE_PESO_OFFICE ? '' : 'd-none' }}">
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label class="form-label">Peso OCRL (Auto Generate)<span
                                                        class="required-mark">*</span></label>
                                                <input type="text" name="ref_imus_ocrl" class="form-control"
                                                    style="text-align: center"
                                                    value="{{ old('ref_imus_ocrl', $referral->ref_imus_ocrl ?? '') }}"
                                                    readonly placeholder="Will generate after save when complete">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">O.R CR<span class="required-mark">*</span></label>
                                                <input type="text" name="ref_or_no" class="form-control"
                                                    value="{{ old('ref_or_no', $referral->ref_or_no ?? '') }}">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Employer Name<span
                                                        class="required-mark">*</span></label>
                                                <input type="text" name="ref_employer_name" class="form-control"
                                                    value="{{ old('ref_employer_name', $referral->ref_employer_name ?? '') }}">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Employer Position<span
                                                        class="required-mark">*</span></label>
                                                <input type="text" name="ref_position" class="form-control"
                                                    value="{{ old('ref_position', $referral->ref_position ?? '') }}">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label"> City Address<span
                                                        class="required-mark">*</span></label>
                                                <input type="text" name="ref_place" id="refPlaceInput" class="form-control"
                                                    list="refPlaceList" autocomplete="off"
                                                    placeholder="Type to search city address"
                                                    value="{{ old('ref_place', $referral->ref_place ?? '') }}">
                                                <datalist id="refPlaceList"></datalist>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Hired Company<span
                                                        class="required-mark">*</span></label>
                                                <input type="text" name="ref_hired_company" class="form-control"
                                                    value="{{ old('ref_hired_company', $referral->ref_hired_company ?? '') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="otherCityFields" data-referral-group="other-city"
                                        class="{{ $selectedReferralType === \App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT ? '' : 'd-none' }}">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Peso Imus OCRL (Auto Generate)<span
                                                        class="required-mark">*</span></label>
                                                <input type="text" name="ref_ocrl" class="form-control"
                                                    style="text-align: center"
                                                    value="{{ old('ref_ocrl', $referral->ref_ocrl ?? '') }}" readonly
                                                    placeholder="Will generate after save when complete">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">O.R No.<span
                                                        class="required-mark">*</span></label>
                                                <input type="text" name="ref_peso_or_no" class="form-control"
                                                    value="{{ old('ref_peso_or_no', $referral->ref_peso_or_no ?? '') }}">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Mayor's Name<span
                                                        class="required-mark">*</span></label>
                                                <select name="ref_recipient" id="refRecipientSelect" class="form-select">
                                                    <option value=""></option>
                                                    @foreach(config('philippine_mayors', []) as $mayor)
                                                        <option value="{{ $mayor['recipient'] }}"
                                                            data-city-government="{{ $mayor['city_government'] }}"
                                                            data-company-address="{{ $mayor['company_address'] }}" {{ old('ref_recipient', $referral->ref_recipient ?? '') === $mayor['recipient'] ? 'selected' : '' }}>
                                                            {{ $mayor['recipient'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">City Government<span
                                                        class="required-mark">*</span></label>
                                                <select name="ref_city_gov" id="cityGovernment" class="form-select">
                                                    <option value="">Select City Government</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">City Address<span
                                                        class="required-mark">*</span></label>
                                                <input type="text" name="ref_company_address" id="refCompanyAddressInput"
                                                    class="form-control" list="refCompanyAddressList" autocomplete="off"
                                                    value="{{ old('ref_company_address', $referral->ref_company_address ?? '') }}">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-3 mt-4">
                                @if(auth()->user()->hasPermission('update_referral'))
                                    <button type="submit" class="btn btn-primary px-5">
                                        Save Referral
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary px-5" disabled>
                                        No permission to update referral
                                    </button>
                                @endif

                                @if(auth()->user()->hasPermission('generate_referral') && $referral && $referral->canPrint())
                                    <a href="{{ route('referrals.printLetter', $applicant->id) }}" id="printReferralPesoButton"
                                        class="btn btn-outline-primary px-4 ms-2 {{ ($referral->referral_type ?? null) === \App\Models\MayorsReferral::TYPE_PESO_OFFICE ? '' : 'd-none' }}"
                                        target="_blank">
                                        Print Referral Within Imus
                                    </a>
                                    <a href="{{ route('referrals.printLetter', $applicant->id) }}"
                                        id="printReferralOtherCityButton"
                                        class="btn btn-outline-primary px-4 ms-2 {{ ($referral->referral_type ?? null) === \App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT ? '' : 'd-none' }}"
                                        target="_blank">
                                        Print Referral Outside Imus
                                    </a>
                                @elseif(!auth()->user()->hasPermission('generate_referral'))
                                    <button type="button" class="btn btn-secondary px-4 ms-2" disabled>
                                        No permission to generate referral letter
                                    </button>
                                @endif
                            </div>

                    </form>

                </div>



            </div>
        </div>
    </div>
@endsection
{{-- City Government--}}
<script>

    document.addEventListener("DOMContentLoaded", function () {
        const referralTypeSelect = document.getElementById("referralTypeSelect");
        const pesoOfficeFields = document.getElementById("pesoOfficeFields");
        const otherCityFields = document.getElementById("otherCityFields");
        const printReferralPesoButton = document.getElementById("printReferralPesoButton");
        const printReferralOtherCityButton = document.getElementById("printReferralOtherCityButton");
        const savedReferralType = `{{ $referral->referral_type ?? '' }}`;
        const referralForm = referralTypeSelect ? referralTypeSelect.closest("form") : null;

        if (referralTypeSelect && pesoOfficeFields && otherCityFields) {
            const setGroupDisabledState = (container, shouldDisable) => {
                container.querySelectorAll("input, select, textarea").forEach(field => {
                    field.disabled = shouldDisable;
                });
            };

            const toggleReferralFields = () => {
                const selectedType = referralTypeSelect.value;
                const isPesoOffice =
                    selectedType === "{{ \App\Models\MayorsReferral::TYPE_PESO_OFFICE }}";
                const isOtherCity =
                    selectedType === "{{ \App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT }}";

                pesoOfficeFields.classList.toggle(
                    "d-none",
                    !isPesoOffice
                );
                otherCityFields.classList.toggle(
                    "d-none",
                    !isOtherCity
                );

                if (printReferralPesoButton) {
                    printReferralPesoButton.classList.toggle(
                        "d-none",
                        !isPesoOffice || savedReferralType !== "{{ \App\Models\MayorsReferral::TYPE_PESO_OFFICE }}"
                    );
                }

                if (printReferralOtherCityButton) {
                    printReferralOtherCityButton.classList.toggle(
                        "d-none",
                        !isOtherCity || savedReferralType !== "{{ \App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT }}"
                    );
                }

                setGroupDisabledState(pesoOfficeFields, !isPesoOffice);
                setGroupDisabledState(otherCityFields, !isOtherCity);
            };

            toggleReferralFields();
            referralTypeSelect.addEventListener("change", toggleReferralFields);

            if (referralForm) {
                referralForm.addEventListener("submit", toggleReferralFields);
            }
        }

        const cityDropdown = document.getElementById("cityGovernment");
        const permitIssuedAtDropdown = document.getElementById("permitIssuedAtSelect");
        const refRecipientDropdown = document.getElementById("refRecipientSelect");
        const refPlaceInput = document.getElementById("refPlaceInput");
        const refPlaceList = document.getElementById("refPlaceList");
        const refCompanyAddressInput = document.getElementById("refCompanyAddressInput");
        const refCompanyAddressList = document.getElementById("refCompanyAddressList");
        const selectedPermitIssuedAt = `{{ old('permit_issued_at', $permit->permit_issued_at ?? '') }}`;
        const selectedCityGovernment = `{{ old('ref_city_gov', $referral->ref_city_gov ?? '') }}`;
        const selectedRefRecipient = `{{ old('ref_recipient', $referral->ref_recipient ?? '') }}`;
        const selectedRefPlace = `{{ old('ref_place', $referral->ref_place ?? '') }}`;
        const selectedRefCompanyAddress = `{{ old('ref_company_address', $referral->ref_company_address ?? '') }}`;
        const referralRecipientSearchUrl = `{{ route('referrals.recipients.search') }}`;
        const configuredMayors = @json(config('philippine_mayors', []));

        const appendOptionIfMissing = (select, value, label, dataAttributes = {}) => {
            if (!select || !value) {
                return;
            }

            const hasExisting = Array.from(select.options).some(option => option.value === value);

            if (!hasExisting) {
                const option = document.createElement("option");
                option.value = value;
                option.text = label;
                option.selected = true;

                Object.entries(dataAttributes).forEach(([key, attributeValue]) => {
                    if (attributeValue) {
                        option.dataset[key] = attributeValue;
                    }
                });

                select.appendChild(option);
            }
        };

        const formatCityGovernmentLabel = cityName => {
            if (!cityName) {
                return "";
            }

            const normalizedCityName = cityName
                .replace(/^City Government of\s+/i, "")
                .replace(/^City of\s+/i, "");

            return `City Government of ${normalizedCityName}`;
        };

        const normalizeCityGovernmentValue = cityName => {
            if (!cityName) {
                return "";
            }

            return cityName
                .replace(/^City Government of\s+/i, "")
                .replace(/^City of\s+/i, "")
                .trim()
                .toLowerCase();
        };

        const ensurePsgcCityData = (() => {
            let cityDataPromise = null;

            return () => {
                if (!cityDataPromise) {
                    cityDataPromise = fetch("https://psgc.gitlab.io/api/cities-municipalities/")
                        .then(response => response.json())
                        .then(data => data.sort((a, b) => a.name.localeCompare(b.name)))
                        .catch(error => {
                            console.error("Error loading cities:", error);
                            return [];
                        });
                }

                return cityDataPromise;
            };
        })();

        const setRecipientDetails = (cityGovernment, companyAddress) => {
            if (cityDropdown && cityGovernment) {
                appendOptionIfMissing(
                    cityDropdown,
                    cityGovernment,
                    formatCityGovernmentLabel(cityGovernment)
                );
                cityDropdown.value = cityGovernment;

                if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
                    window.jQuery(cityDropdown).trigger("change.select2");
                }
            }

            if (refCompanyAddressInput && companyAddress) {
                refCompanyAddressInput.value = companyAddress;
            }
        };

        appendOptionIfMissing(
            cityDropdown,
            selectedCityGovernment,
            formatCityGovernmentLabel(selectedCityGovernment)
        );
        appendOptionIfMissing(permitIssuedAtDropdown, selectedPermitIssuedAt, selectedPermitIssuedAt);
        appendOptionIfMissing(refRecipientDropdown, selectedRefRecipient, selectedRefRecipient, {
            cityGovernment: selectedCityGovernment,
            companyAddress: selectedRefCompanyAddress
        });

        const syncRecipientDetailsFromSelectedOption = () => {
            if (!refRecipientDropdown) {
                return;
            }

            const selectedRecipientOption = refRecipientDropdown.options[refRecipientDropdown.selectedIndex];

            if (!selectedRecipientOption) {
                return;
            }

            setRecipientDetails(
                selectedRecipientOption.dataset.cityGovernment || "",
                selectedRecipientOption.dataset.companyAddress || ""
            );
        };

        const syncRecipientFromCityGovernment = () => {
            if (!cityDropdown || !refRecipientDropdown) {
                return;
            }

            const normalizedSelectedCity = normalizeCityGovernmentValue(cityDropdown.value);

            if (!normalizedSelectedCity) {
                refRecipientDropdown.value = "";

                if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
                    window.jQuery(refRecipientDropdown).val(null).trigger("change");
                }

                if (refCompanyAddressInput) {
                    refCompanyAddressInput.value = "";
                }

                return;
            }

            const matchingMayorOption = Array.from(refRecipientDropdown.options).find(option =>
                normalizeCityGovernmentValue(option.dataset.cityGovernment || "") === normalizedSelectedCity
            );

            if (!matchingMayorOption) {
                return;
            }

            matchingMayorOption.selected = true;
            refRecipientDropdown.value = matchingMayorOption.value;

            if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
                window.jQuery(refRecipientDropdown).trigger("change.select2");
            }

            setRecipientDetails(
                matchingMayorOption.dataset.cityGovernment || "",
                matchingMayorOption.dataset.companyAddress || ""
            );
        };

        if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
            if (permitIssuedAtDropdown) {
                window.jQuery(permitIssuedAtDropdown).select2({
                    placeholder: "Select City Government",
                    allowClear: true,
                    width: "100%",
                    dropdownAutoWidth: true,
                    minimumResultsForSearch: 0
                });
            }

            if (cityDropdown) {
                window.jQuery(cityDropdown).select2({
                    placeholder: "Select City Government",
                    allowClear: true,
                    width: "100%",
                    dropdownAutoWidth: true,
                    minimumResultsForSearch: 0
                });
            }

            if (refRecipientDropdown) {
                window.jQuery(refRecipientDropdown).select2({
                    placeholder: "Search or type mayor's name",
                    allowClear: true,
                    width: "100%",
                    dropdownAutoWidth: true,
                    minimumInputLength: 0,
                    ajax: {
                        url: referralRecipientSearchUrl,
                        dataType: "json",
                        delay: 250,
                        data: params => {
                            const cityGovernmentValue = cityDropdown ? cityDropdown.value : "";

                            return {
                                q: params.term || "",
                                city_government: cityGovernmentValue && cityGovernmentValue !== "Select City Government"
                                    ? cityGovernmentValue
                                    : ""
                            };
                        },
                        processResults: data => ({
                            results: (data.results || []).map(item => ({
                                id: item.id,
                                text: item.text,
                                city_government: item.city_government,
                                company_address: item.company_address
                            }))
                        })
                    },
                    templateSelection: item => item.text || item.id || ""
                });

                window.jQuery(refRecipientDropdown).on("select2:select", function (event) {
                    const selectedMayor = event.params.data;

                    const selectedOption = refRecipientDropdown.options[refRecipientDropdown.selectedIndex];

                    if (selectedOption) {
                        if (selectedMayor.city_government) {
                            selectedOption.dataset.cityGovernment = selectedMayor.city_government;
                        }

                        if (selectedMayor.company_address) {
                            selectedOption.dataset.companyAddress = selectedMayor.company_address;
                        }
                    }

                    setRecipientDetails(
                        selectedMayor.city_government || "",
                        selectedMayor.company_address || ""
                    );
                });

                window.jQuery(refRecipientDropdown).on("select2:clear", function () {
                    if (refCompanyAddressInput) {
                        refCompanyAddressInput.value = "";
                    }
                });
            }
        }

        if (refRecipientDropdown) {
            refRecipientDropdown.addEventListener("change", syncRecipientDetailsFromSelectedOption);
        }

        if (cityDropdown) {
            cityDropdown.addEventListener("change", syncRecipientFromCityGovernment);
        }

        const configuredCityGovernments = [...new Set(
            configuredMayors
                .map(mayor => mayor.city_government)
                .filter(Boolean)
        )].sort((a, b) => a.localeCompare(b));

        const configuredCompanyAddresses = [...new Set(
            configuredMayors
                .map(mayor => mayor.company_address)
                .filter(Boolean)
        )].sort((a, b) => a.localeCompare(b));

        configuredCityGovernments.forEach(cityGovernmentValue => {
            if (cityDropdown && !Array.from(cityDropdown.options).some(option => option.value === cityGovernmentValue)) {
                const option = document.createElement("option");
                option.value = cityGovernmentValue;
                option.text = cityGovernmentValue;

                if (cityGovernmentValue === selectedCityGovernment) {
                    option.selected = true;
                }

                cityDropdown.appendChild(option);
            }
        });

        configuredCompanyAddresses.forEach(companyAddressValue => {
            if (refCompanyAddressList && !Array.from(refCompanyAddressList.options).some(option => option.value === companyAddressValue)) {
                const option = document.createElement("option");
                option.value = companyAddressValue;
                refCompanyAddressList.appendChild(option);
            }
        });

        if (refCompanyAddressInput && selectedRefCompanyAddress) {
            refCompanyAddressInput.value = selectedRefCompanyAddress;
        }

        syncRecipientDetailsFromSelectedOption();

        const populatePsgcCityData = () => {
            ensurePsgcCityData().then(cities => {
                cities.forEach(city => {
                    const cityValue = city.name;

                    if (permitIssuedAtDropdown && !Array.from(permitIssuedAtDropdown.options).some(option => option.value === cityValue)) {
                        const option = document.createElement("option");
                        option.value = cityValue;
                        option.text = cityValue;

                        if (cityValue === selectedPermitIssuedAt) {
                            option.selected = true;
                        }

                        permitIssuedAtDropdown.appendChild(option);
                    }

                    if (refPlaceList && !Array.from(refPlaceList.options).some(option => option.value === cityValue)) {
                        const option = document.createElement("option");
                        option.value = cityValue;
                        refPlaceList.appendChild(option);
                    }
                });

                if (refPlaceInput && selectedRefPlace) {
                    refPlaceInput.value = selectedRefPlace;
                }

                if (window.jQuery && typeof window.jQuery.fn.select2 === "function" && permitIssuedAtDropdown) {
                    window.jQuery(permitIssuedAtDropdown).trigger("change.select2");
                }
            });
        };

        if (permitIssuedAtDropdown) {
            permitIssuedAtDropdown.addEventListener("focus", populatePsgcCityData, { once: true });
        }

        if (refPlaceInput) {
            refPlaceInput.addEventListener("focus", populatePsgcCityData, { once: true });
        }

        if (selectedPermitIssuedAt || selectedRefPlace) {
            populatePsgcCityData();
        }

        if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
            if (cityDropdown) {
                window.jQuery(cityDropdown).trigger("change.select2");
            }

            if (refRecipientDropdown) {
                window.jQuery(refRecipientDropdown).trigger("change.select2");
            }
        }

    });

</script>
{{-- Upload file name --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {

        document.querySelectorAll(".file-input").forEach(input => {
            input.addEventListener("change", function () {

                const previewId = this.dataset.preview;
                const previewContainer = document.getElementById(previewId);

                if (!previewContainer) return;

                if (this.files && this.files[0]) {

                    const file = this.files[0];
                    const fileURL = URL.createObjectURL(file);

                    previewContainer.innerHTML = `
                    <a href="${fileURL}" 
                       target="_blank"
                       class="badge bg-success text-white border px-3 py-2">
                        <i class="bi bi-file-earmark"></i>
                        ${file.name}
                    </a>
                `;
                }
            });

        });

    });
</script>
{{-- City Address --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');


        // SAVED VALUES
        let savedProvince = "{{ $applicant->province }}";
        let savedCity = "{{ $applicant->city }}";
        let savedBarangay = "{{ $applicant->barangay }}";



        // ---------- LOAD PROVINCES ----------
        function loadProvinces() {

            provinceSelect.innerHTML = '<option>Loading provinces...</option>';

            fetch('https://psgc.gitlab.io/api/provinces/')
                .then(response => response.json())
                .then(data => {

                    provinceSelect.innerHTML = '<option value="">Select Province</option>';

                    data.sort((a, b) => a.name.localeCompare(b.name));

                    data.forEach(province => {

                        let option = document.createElement('option');

                        option.value = province.name;
                        option.textContent = province.name;
                        option.dataset.code = province.code;

                        if (province.name === savedProvince) {
                            option.selected = true;
                            loadCities(province.code);
                        }

                        provinceSelect.appendChild(option);

                    });

                });

        }



        // ---------- LOAD CITIES ----------
        function loadCities(provinceCode) {

            citySelect.innerHTML = '<option>Loading cities...</option>';

            fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`)
                .then(response => response.json())
                .then(data => {

                    citySelect.innerHTML = '<option value="">Select City</option>';

                    data.sort((a, b) => a.name.localeCompare(b.name));

                    data.forEach(city => {

                        let option = document.createElement('option');

                        option.value = city.name;
                        option.textContent = city.name;
                        option.dataset.code = city.code;

                        if (city.name === savedCity) {
                            option.selected = true;
                            loadBarangays(city.code);
                        }

                        citySelect.appendChild(option);

                    });

                });

        }



        // ---------- LOAD BARANGAYS ----------
        function loadBarangays(cityCode) {

            barangaySelect.innerHTML = '<option>Loading barangays...</option>';

            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
                .then(response => response.json())
                .then(data => {

                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                    data.sort((a, b) => a.name.localeCompare(b.name));

                    data.forEach(barangay => {

                        let option = document.createElement('option');

                        option.value = barangay.name;
                        option.textContent = barangay.name;

                        if (barangay.name === savedBarangay) {
                            option.selected = true;
                        }

                        barangaySelect.appendChild(option);

                    });

                });

        }



        // ---------- EVENTS ----------
        provinceSelect.addEventListener('change', function () {

            let code = this.options[this.selectedIndex].dataset.code;

            if (code) {

                loadCities(code);

            } else {

                citySelect.innerHTML = '<option>Select City</option>';
                barangaySelect.innerHTML = '<option>Select Barangay</option>';

            }

        });


        citySelect.addEventListener('change', function () {

            let code = this.options[this.selectedIndex].dataset.code;

            if (code) {

                loadBarangays(code);

            } else {

                barangaySelect.innerHTML = '<option>Select Barangay</option>';

            }

        });



        // ---------- INIT ----------
        loadProvinces();

    });

</script>
{{-- Archived File--}}
<script>
    function clearFile(applicantId, field) {

        if (!confirm("Remove this file?")) return;

        fetch(`/permit/${applicantId}/${field}/delete`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Server error");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById("file-" + field).remove();
                } else {
                    alert("Error removing file.");
                }
            })
            .catch(error => {
                console.error(error);
                alert("Error removing file.");
            });
    }
</script>
{{-- Expires On --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const permitDate = document.getElementById("permit_date");
        const expiresOn = document.getElementById("expires_on");

        permitDate.addEventListener("change", function () {

            if (!this.value) return;

            let date = new Date(this.value);

            // Add 6 months
            date.setMonth(date.getMonth() + 6);

            // Fix date format (YYYY-MM-DD)
            let formatted = date.toISOString().split('T')[0];

            expiresOn.value = formatted;

        });

    });
</script>
{{-- nbi or police --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const dropdown = document.getElementById("clearance_type");
        const nbi = document.getElementById("nbi_section");
        const police = document.getElementById("police_section");

        function toggleFields() {
            const value = dropdown.value;

            if (value === "nbi") {
                nbi.style.display = "grid";
                police.style.display = "none";
            }
            else if (value === "police") {
                nbi.style.display = "none";
                police.style.display = "grid";
            }
            else {
                nbi.style.display = "none";
                police.style.display = "none";
            }
        }

        // Run on page load (edit mode support)
        toggleFields();

        // Run when changed
        dropdown.addEventListener("change", toggleFields);

    });
</script>

<script>
    function showFileName(input, displayId) {
        const fileName = input.files.length ? input.files[0].name : '';
        document.getElementById(displayId).textContent = fileName;
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const educationalAttainmentSelect = document.getElementById('educationalAttainmentSelect');

        if (!educationalAttainmentSelect || !window.jQuery || typeof window.jQuery.fn.select2 !== 'function') {
            return;
        }

        window.jQuery(educationalAttainmentSelect).select2({
            placeholder: 'Select educational attainment',
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true,
            minimumResultsForSearch: 0,
            ajax: {
                url: '{{ url('/api/educational-attainments') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    q: params.term || ''
                }),
                processResults: data => ({
                    results: data.results || []
                })
            }
        });
    });
</script>