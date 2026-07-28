@extends('layouts.app')

@php
    $pageTitle =
        auth()->check() && auth()->user()?->role === \App\Models\User::ROLE_USER
            ? 'Update Profile Information'
            : 'Update Applicant';
@endphp

@section('title', 'ARS | ' . $pageTitle)

@section('content')

    @php
        $timeGreeting =
            now()->hour >= 0 && now()->hour <= 11
                ? 'Good Morning'
                : (now()->hour >= 12 && now()->hour <= 17
                    ? 'Good Afternoon'
                    : 'Good Evening');
    @endphp

    <style>
        .hero-panel {
            background: white;
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 0.85rem;
            padding: 0.4rem 0.8rem;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: clamp(1.9rem, 3vw, 2.75rem);
            font-weight: 800;
            color: #0f172a;
        }

        .hero-copy {
            color: #64748b;
        }

        html[data-theme="night"] .hero-panel {
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
        }

        html[data-theme="night"] .hero-title {
            color: #f8fafc;
        }

        html[data-theme="night"] .hero-copy {
            color: #94a3b8;
        }
    </style>

    @if (auth()->check() && auth()->user()?->role === \App\Models\User::ROLE_USER)
        <section class="hero-panel mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <h2 class="hero-title mb-2">{{ $timeGreeting }}, {{ Auth::user()->name }}</h2>
                    <p class="hero-copy mb-0">
                        Update your profile details, complete your requirements, and keep your applicant records up to date.
                    </p>
                </div>
            </div>
        </section>
    @endif

    @if (session('created_success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                Swal.fire({
                    title: 'Applicant Successfully Created',
                    html: `
                                                <div style="font-size:14px;">
                                                    <p class="mb-2">The applicant profile has been saved successfully.</p>
                                                    @if (session('applicant_id'))
                                                        <p class="mb-2"><strong>Record ID:</strong> #{{ session('applicant_id') }}</p>
                                                    @endif
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

    @if (session('permit_saved_prompt'))
        @php
            $permitSavedPrompt = session('permit_saved_prompt');
        @endphp

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const permitPrompt = @json($permitSavedPrompt);
                const canViewPermit = Boolean(permitPrompt.can_view);

                clearAllUploadLabels();

                Swal.fire({
                    title: 'Permit Saved',
                    text: canViewPermit ?
                        'Do you want to view the Permit to Work ID?' :
                        'Permit details were saved, but the Permit to Work ID is not ready to view yet.',
                    icon: 'success',
                    showCancelButton: canViewPermit,
                    confirmButtonText: canViewPermit ? 'View Permit to Work ID' : 'OK',
                    cancelButtonText: 'Stay on Page',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then((result) => {
                    if (canViewPermit && result.isConfirmed && permitPrompt.view_url) {
                        window.open(permitPrompt.view_url, '_blank');
                    }
                });
            });
        </script>
    @endif

    @if (session('clearance_saved_prompt'))
        @php
            $clearanceSavedPrompt = session('clearance_saved_prompt');
        @endphp

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const clearancePrompt = @json($clearanceSavedPrompt);
                const canViewClearance = Boolean(clearancePrompt.can_view);

                clearAllUploadLabels();

                Swal.fire({
                    title: 'Clearance Saved',
                    text: canViewClearance ?
                        'Do you want to view the Mayor\'s Clearance Letter?' :
                        'Clearance details were saved, but the letter is not ready to view yet.',
                    icon: 'success',
                    showCancelButton: canViewClearance,
                    confirmButtonText: canViewClearance ? 'View Clearance Letter' : 'OK',
                    cancelButtonText: 'Stay on Page',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then((result) => {
                    if (canViewClearance && result.isConfirmed && clearancePrompt.view_url) {
                        window.open(clearancePrompt.view_url, '_blank');
                    }
                });
            });
        </script>
    @endif

    @if (session('referral_saved_prompt'))
        @php
            $referralSavedPrompt = session('referral_saved_prompt');
        @endphp

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const referralPrompt = @json($referralSavedPrompt);

                clearAllUploadLabels();

                const options = referralPrompt.options || [];
                let html = '<div style="font-size:14px;text-align:left;">';
                html += '<p class="mb-2">Referral details were saved successfully.</p>';
                if (options.length > 0) {
                    html += '<p class="mb-1 fw-bold">Available referral letters:</p>';
                    options.forEach(function(opt) {
                        html += '<p class="mb-1"><a href="' + opt.url +
                            '" target="_blank"><i class="bi bi-file-earmark-text me-1"></i>' + opt.label +
                            '</a></p>';
                    });
                } else {
                    html += '<p class="text-muted">No referral letters are ready to view yet.</p>';
                }
                html += '</div>';

                Swal.fire({
                    title: 'Referral Saved',
                    html: html,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    @php
        $fullName = trim(
            $applicant->first_name .
                ' ' .
                ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') .
                $applicant->last_name .
                ' ' .
                ($applicant->suffix ?? ''),
        );
        $isApplicantUser = auth()->check() && auth()->user()?->role === \App\Models\User::ROLE_USER;
        $isAdminUser = auth()->check() && auth()->user()?->isAdmin();
        $isFirstTimeJobSeeker = strtoupper(trim((string) ($applicant->first_time_job_seeker ?? ''))) === 'YES';
        $disapproveRequirement = session('disapprove_requirement');
        $disapproveRequirementId = session('disapprove_requirement_id');

        $permitModel = $applicant->permit;
        $permit = optional($permitModel);
        $isPermitRenewalDue = $permitModel ? $permitModel->isRenewalDue() : false;
        $isImusResident =
            $applicant->city &&
            (stripos($applicant->city, 'IMUS CITY') !== false || stripos($applicant->city, 'CITY OF IMUS') !== false);
        $hasPermitClearance =
            ($permit->clearance_type === 'nbi' && !empty($permit->permit_nbi_clearance)) ||
            ($permit->clearance_type === 'police' && !empty($permit->permit_police_clearance));
        $permitRequirements = [!empty($permit->health_card), !empty($permit->cedula), $hasPermitClearance];

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
        $hasReferralClearance =
            collect([$referral->ref_barangay_clearance, $referral->ref_police_clearance, $referral->ref_nbi_clearance])
                ->filter()
                ->count() > 0;
        $referralUploaded = ($hasResume ? 1 : 0) + ($hasReferralClearance ? 1 : 0);
        $referralPercent = round(($referralUploaded / 2) * 100);

        $permitSubmitLocked =
            $isApplicantUser &&
            $permit &&
            ($permit->approval_status ?? null) !== \App\Models\MayorsPermit::APPROVAL_DISAPPROVED;
        $clearanceSubmitLocked =
            $isApplicantUser &&
            $clearance &&
            ($clearance->approval_status ?? null) !== \App\Models\MayorsClearance::APPROVAL_DISAPPROVED;
        $referralSubmitLocked =
            $isApplicantUser &&
            $referral &&
            ($referral->approval_status ?? null) !== \App\Models\MayorsReferral::APPROVAL_DISAPPROVED;
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
            max-width: 2000px;
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

        .transaction-panel {
            margin-top: 1.5rem;
            border: 1px solid #dfe8f3;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.05), 0 1px 3px rgba(15, 23, 42, 0.04);
            overflow-x: auto;
        }

        .transaction-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.85rem 1.15rem;
            background: linear-gradient(135deg, #f0f7ff 0%, #f8fbff 100%);
            border-bottom: 1px solid #e2ecf7;
        }

        .transaction-panel-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }

        .transaction-panel-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #ffffff;
            flex: 0 0 auto;
            font-size: 0.85rem;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.3);
        }

        .transaction-panel-title h5 {
            margin: 0;
            color: #1e293b;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .transaction-panel-title p {
            margin: 0.1rem 0 0;
            color: #64748b;
            font-size: 0.78rem;
        }

        .transaction-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.7rem;
            border: 1px solid #c7d9f2;
            border-radius: 999px;
            background: linear-gradient(135deg, #eff6ff 0%, #e0edff 100%);
            color: #2563eb;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
            letter-spacing: 0.01em;
        }

        .transaction-section-row {
            margin: 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .transaction-section-row:last-child {
            border-bottom: none;
        }

        .transaction-row-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            background: #f8fafc;
            border-bottom: 1px solid #e5edf5;
        }

        .transaction-row-head h6 {
            margin: 0;
            font-weight: 800;
            font-size: 0.88rem;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .transaction-row-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .transaction-row-icon.clearance {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .transaction-row-icon.referral {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .transaction-row-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 7px;
            border-radius: 999px;
            background: #e0e7ff;
            color: #3730a3;
            font-size: 0.72rem;
            font-weight: 800;
            margin-left: auto;
        }

        .transaction-section-row .transaction-table-wrap {
            background: #ffffff;
        }

        .transaction-section-row .transaction-empty-state {
            padding: 1.2rem 20px;
        }

        .transaction-row-head[data-bs-toggle="collapse"] {
            cursor: pointer;
            user-select: none;
            transition: background 0.15s;
        }

        .transaction-row-head[data-bs-toggle="collapse"]:hover {
            background: #eef2f7;
        }

        .transaction-chevron {
            font-size: 0.8rem;
            color: #64748b;
            transition: transform 0.25s ease;
            margin-left: auto;
        }

        .transaction-row-head[aria-expanded="true"] .transaction-chevron {
            transform: rotate(180deg);
        }

        html[data-theme="night"] .transaction-row-head[data-bs-toggle="collapse"]:hover {
            background: #1e293b;
        }

        .hide-actions .transaction-table th:last-child,
        .hide-actions .transaction-table td:last-child {
            display: none !important;
            border-top: none;
            border-radius: 0 0 16px 16px;
        }

        .transaction-table-wrap {
            overflow-x: auto;
        }

        .transaction-table {
            min-width: 1120px;
            margin: 0;
            font-size: 0.82rem;
        }

        .transaction-table thead th {
            padding: 0.65rem 0.9rem;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            color: #475569;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .transaction-table tbody td {
            padding: 0.7rem 0.9rem;
            border-top: 1px solid #f1f5f9;
            color: #334155;
            vertical-align: middle;
            white-space: nowrap;
        }

        .transaction-table tbody tr {
            transition: background 0.12s;
        }

        .transaction-table tbody tr:hover td {
            background: #f0fdf8;
        }

        .transaction-row-index {
            width: 40px;
            color: #94a3b8 !important;
            font-weight: 700;
            font-size: 0.78rem;
        }

        .transaction-id-link,
        .transaction-id-text {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.28rem 0.6rem;
            border-radius: 8px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            font-weight: 700;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.15s;
        }

        .transaction-id-link:hover {
            background: #d1fae5;
            border-color: #6ee7b7;
            color: #047857;
            box-shadow: 0 1px 4px rgba(6, 95, 70, 0.12);
        }

        .transaction-empty-value {
            color: #cbd5e1;
            font-weight: 500;
        }

        .transaction-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            min-width: 36px;
            min-height: 30px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.78rem;
            transition: all 0.15s;
        }

        .btn-light-green {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .btn-light-green:hover {
            background: #a7f3d0;
            color: #047857;
        }

        .btn-light-blue {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid rgba(59, 130, 246, 0.25);
        }

        .btn-light-blue:hover {
            background: #bfdbfe;
            color: #1d4ed8;
        }

        .btn-light-amber {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .btn-light-amber:hover {
            background: #fde68a;
            color: #b45309;
        }

        .dropdown-menu {
            border-radius: 12px;
            border: 1px solid #e2ebf4;
            padding: 0.35rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.45rem 0.75rem;
            font-size: 0.82rem;
            font-weight: 600;
            transition: background 0.12s;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
        }

        .dropdown-item-text {
            padding: 0.45rem 0.75rem;
            font-size: 0.82rem;
        }

        .transaction-empty-state {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 1.2rem 1.15rem;
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .transaction-empty-state i {
            color: #cbd5e1;
            font-size: 1.15rem;
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
            justify-content: center;
            flex-wrap: wrap;
            text-align: center;
            font-size: 16px;
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

        .nav-tabs .nav-item {
            flex: 1 1 0;
            min-width: 0;
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
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .modal {
            z-index: 2050;
        }

        .modal-backdrop {
            z-index: 2040;
        }

        .modal-dialog,
        .modal-content,
        .modal-body {
            pointer-events: auto;
        }

        .form-card {
            padding: 4px 2px 0;
        }

        .personal-pane .form-section+.form-section {
            margin-top: 1.5rem !important;
        }

        .personal-pane .row {
            --bs-gutter-x: 1.25rem;
        }

        .permit-pane .row,
        .clearance-pane .row {
            --bs-gutter-x: 1rem;
        }

        .permit-pane .permit-upload-row,
        .clearance-pane .clearance-upload-row {
            flex-wrap: wrap;
            overflow: visible;
        }

        .permit-pane .permit-upload-col,
        .clearance-pane .clearance-upload-col {
            min-width: 0;
        }

        .permit-pane .permit-action-bar,
        .clearance-pane .clearance-action-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
        }

        .referral-pane .row {
            --bs-gutter-x: 1rem;
        }

        .referral-pane .referral-upload-row {
            display: flex;
            flex-wrap: wrap;
        }

        .referral-pane .referral-upload-row>.col-md-4 {
            display: flex;
        }

        .referral-pane .referral-upload-row .document-upload-card {
            width: 100%;
        }

        .referral-pane .referral-letter-shell {
            border: 1px solid #e4edf7;
        }


        .referral-details-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .referral-details-head .section-copy {
            max-width: 720px;
        }

        .referral-details-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .peso-extra-detail-card .row {
            --bs-gutter-x: 0.85rem;
            --bs-gutter-y: 0.85rem;
        }

        .referral-pane .referral-action-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
        }

        .referral-pane .referral-action-bar>* {
            margin-left: 0 !important;
        }

        .referral-pane .referral-letter-shell {
            overflow: hidden;
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
            gap: 10px;
            margin-bottom: 18px;
            color: var(--edit-ink);
            font-size: 0.96rem;
            font-weight: 800;
            letter-spacing: 0.01em;
            flex-wrap: wrap;
            overflow: visible;
        }

        .section-title-c {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            color: black;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }
        
        .section-title-d {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
            color: black;
            font-size: 0.9rem;
            font-weight: 700;
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

        .section-title-d::before {
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

        @media (max-width: 992px) {
            .tab-shell {
                padding: 10px;
                border-radius: 22px;
            }

            .nav-tabs .nav-item {
                flex: 1 1 calc(50% - 10px);
            }

            .nav-tabs .nav-link {
                min-height: 64px;
                padding: 12px 16px;
            }

            .personal-pane .row {
                --bs-gutter-y: 1rem;
            }

            .personal-pane .col-md-1,
            .personal-pane .col-md-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .permit-pane .row,
            .clearance-pane .row {
                --bs-gutter-y: 1rem;
            }

            .permit-pane .permit-upload-col {
                flex: 0 0 calc(50% - 0.5rem);
                max-width: calc(50% - 0.5rem);
            }

            .permit-pane .col-md-2 {
                flex: 0 0 33.3333%;
                max-width: 33.3333%;
            }

            .clearance-pane .clearance-upload-col {
                flex: 0 0 calc(50% - 0.5rem);
                max-width: calc(50% - 0.5rem);
            }

            .clearance-pane .col-md-2 {
                flex: 0 0 33.3333%;
                max-width: 33.3333%;
            }

            .referral-pane .row {
                --bs-gutter-y: 1rem;
            }

            .referral-pane .referral-upload-row>.col-md-4 {
                width: calc(50% - 0.5rem);
            }

            .referral-pane #pesoOfficeFields .col-md-2,
            .referral-pane #otherCityFields .col-md-2,
            .referral-pane #otherCityFields .col-md-3,
            .referral-pane>form>.mt-4>.col-md-4 {
                flex: 0 0 50%;
                max-width: 50%;
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

            .transaction-panel-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .page-header-actions {
                margin-top: 1rem;
            }

            .tab-shell {
                padding: 8px;
                border-radius: 20px;
            }

            .nav-tabs {
                gap: 0.75rem;
            }

            .nav-tabs .nav-item {
                flex: 0 0 100%;
            }

            .nav-tabs .nav-link {
                min-height: auto;
                padding: 12px 14px;
                border-radius: 16px;
                justify-content: center;
            }

            .nav-tab-label {
                justify-content: center;
                text-align: center;
            }

            .tab-content {
                padding: 18px;
            }

            .personal-pane .form-card {
                padding: 0;
            }

            .personal-pane .row {
                --bs-gutter-x: 0.9rem;
                --bs-gutter-y: 0.9rem;
            }

            .personal-pane .form-section+.form-section {
                margin-top: 1.25rem !important;
            }

            .personal-pane .section-title {
                margin-bottom: 14px;
            }

            .permit-pane .row,
            .clearance-pane .row {
                --bs-gutter-x: 0.9rem;
                --bs-gutter-y: 0.9rem;
            }

            .permit-pane .permit-upload-col,
            .clearance-pane .clearance-upload-col {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .permit-pane .document-upload-card,
            .clearance-pane .document-upload-card {
                padding: 16px;
            }

            .permit-pane .file-name-text,
            .clearance-pane .file-name-text {
                white-space: normal;
                overflow: visible;
                text-overflow: unset;
                word-break: break-word;
            }

            .permit-pane .permit-action-bar>*,
            .clearance-pane .clearance-action-bar>* {
                width: 100%;
            }

            .referral-pane .row {
                --bs-gutter-x: 0.9rem;
                --bs-gutter-y: 0.9rem;
            }

            .referral-pane .document-upload-card,
            .referral-pane .document-upload-card-resume {
                padding: 16px;
            }

            .referral-pane .file-name-text {
                white-space: normal;
                overflow: visible;
                text-overflow: unset;
                word-break: break-word;
            }

            .referral-pane .referral-upload-row>.col-md-4,
            .referral-pane #pesoOfficeFields .col-md-2,
            .referral-pane #otherCityFields .col-md-2,
            .referral-pane #otherCityFields .col-md-3,
            .referral-pane>form>.mt-4>.col-md-4 {
                width: 100%;
                max-width: 100%;
                flex: 0 0 100%;
            }

            .referral-pane #otherCityFields .col-12 .d-flex {
                justify-content: center !important;
            }

            .peso-extra-detail-card {
                padding: 1rem !important;
            }

            .peso-extra-detail-card .d-flex.align-items-center.justify-content-between {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .peso-extra-detail-card .js-remove-peso-detail {
                align-self: flex-end;
            }

            .peso-extra-detail-card .btn,
            .peso-extra-detail-card .btn-outline-primary,
            .peso-extra-detail-card .btn-outline-secondary {
                width: 100%;
            }

            .referral-pane .referral-letter-shell {
                padding: 1rem !important;
            }

            .referral-pane .referral-action-bar {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .referral-pane .referral-action-bar>* {
                width: auto;
            }

            .referral-pane .referral-type-field {
                margin-bottom: 1rem !important;
            }

            .referral-pane .referral-type-select {
                width: 100%;
                max-width: 100%;
                min-height: 46px;
                padding: 10px 12px;
                font-size: 0.95rem;
            }

            .profile-action-bar>* {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .personal-pane .row {
                --bs-gutter-x: 0.75rem;
                --bs-gutter-y: 0.85rem;
            }

            .personal-pane .col-md-1,
            .personal-pane .col-md-2,
            .personal-pane .col-md-3,
            .personal-pane .col-md-4,
            .personal-pane .col-md-5,
            .personal-pane .col-md-6,
            .personal-pane .col-md-8 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .personal-pane .form-control,
            .personal-pane .form-select {
                min-height: 46px;
                padding: 10px 12px;
            }

            .personal-pane .profile-action-bar {
                gap: 0.75rem;
                margin-top: 1.25rem;
                padding-top: 1rem;
            }

            .personal-pane .profile-action-bar .btn {
                width: 100%;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .permit-pane .row,
            .clearance-pane .row {
                --bs-gutter-x: 0.75rem;
                --bs-gutter-y: 0.85rem;
            }

            .permit-pane .col-md-2,
            .clearance-pane .col-md-2 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .permit-pane .permit-action-bar .btn,
            .clearance-pane .clearance-action-bar .btn {
                width: 100%;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .referral-pane .row {
                --bs-gutter-x: 0.75rem;
                --bs-gutter-y: 0.85rem;
            }

            .referral-pane .referral-action-bar .btn {
                width: auto;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

        }

        html[data-theme="night"] .requirements-container,
        html[data-theme="night"] .content-intro,
        html[data-theme="night"] .transaction-panel,
        html[data-theme="night"] .tab-shell,
        html[data-theme="night"] .tab-content,
        html[data-theme="night"] .form-card,
        html[data-theme="night"] .document-upload-card,
        html[data-theme="night"] .document-upload-card-resume,
        html[data-theme="night"] .referral-letter-shell,
        html[data-theme="night"] .peso-extra-detail-card,
        html[data-theme="night"] .activity-log-card {
            background: #0f172a;
            border-color: rgba(148, 163, 184, 0.16) !important;
        }

        html[data-theme="night"] .transaction-panel-head {
            background: #0c1425;
            border-bottom-color: rgba(148, 163, 184, 0.14);
        }

        html[data-theme="night"] .transaction-panel-title h5 {
            color: #f1f5f9;
        }

        html[data-theme="night"] .transaction-panel-title p {
            color: #94a3b8;
        }

        html[data-theme="night"] .transaction-panel-icon {
            background: rgba(16, 185, 129, 0.18);
            color: #34d399;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.15);
        }

        html[data-theme="night"] .transaction-count-badge {
            background: rgba(59, 130, 246, 0.14);
            border-color: rgba(96, 165, 250, 0.22);
            color: #93c5fd;
        }

        html[data-theme="night"] .transaction-row-head {
            background: #0f172a;
            border-bottom-color: rgba(148, 163, 184, 0.14);
        }

        html[data-theme="night"] .transaction-row-head h6 {
            color: #e2e8f0;
        }

        html[data-theme="night"] .transaction-row-count {
            background: rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
        }

        html[data-theme="night"] .transaction-section-row {
            border-bottom-color: rgba(148, 163, 184, 0.1);
        }

        html[data-theme="night"] .transaction-section-row .transaction-empty-state {
            color: #64748b;
        }

        html[data-theme="night"] .transaction-table-wrap {
            overflow-x: auto;
        }

        html[data-theme="night"] .transaction-table thead th {
            background: #0c1425;
            border-bottom-color: rgba(148, 163, 184, 0.14);
            color: #94a3b8;
        }

        html[data-theme="night"] .transaction-table tbody td {
            background: #0f172a;
            border-top-color: rgba(148, 163, 184, 0.1);
            color: #cbd5e1;
        }

        html[data-theme="night"] .transaction-table tbody tr:hover td {
            background: rgba(16, 185, 129, 0.06);
        }

        html[data-theme="night"] .transaction-row-index {
            color: #475569 !important;
        }

        html[data-theme="night"] .transaction-id-link,
        html[data-theme="night"] .transaction-id-text {
            background: rgba(16, 185, 129, 0.14);
            border-color: rgba(52, 211, 153, 0.22);
            color: #6ee7b7;
        }

        html[data-theme="night"] .transaction-id-link:hover {
            background: rgba(16, 185, 129, 0.22);
            border-color: rgba(52, 211, 153, 0.35);
            color: #a7f3d0;
        }

        html[data-theme="night"] .transaction-empty-value {
            color: #475569;
        }

        html[data-theme="night"] .transaction-empty-state {
            color: #64748b;
        }

        html[data-theme="night"] .transaction-empty-state i {
            color: #475569;
        }

        html[data-theme="night"] .referral-pane .peso-extra-detail-card.bg-light {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.16) !important;
        }

        html[data-theme="night"] .referral-pane .peso-extra-detail-card.bg-light .badge.bg-primary-subtle {
            background: rgba(59, 130, 246, 0.18) !important;
            color: #bfdbfe !important;
        }

        html[data-theme="night"] .referral-pane .peso-extra-detail-card.bg-light .btn-light {
            background: #111827 !important;
            color: #e2e8f0 !important;
            border-color: rgba(148, 163, 184, 0.18) !important;
        }

        html[data-theme="night"] .referral-pane .peso-extra-detail-card.bg-light .btn-light:hover {
            background: #1f2937 !important;
            color: #f8fafc !important;
        }

        html[data-theme="night"] .content-intro,
        html[data-theme="night"] .tab-content {
            color: #e2e8f0;
        }

        html[data-theme="night"] .fw-bold,
        html[data-theme="night"] .section-title,
        html[data-theme="night"] .section-title-c,
        html[data-theme="night"] .section-title-d,
        html[data-theme="night"] .nav-tab-label,
        html[data-theme="night"] h5,
        html[data-theme="night"] h6 {
            color: #f8fafc;
        }

        html[data-theme="night"] .section-copy,
        html[data-theme="night"] .content-intro p,
        html[data-theme="night"] .file-name-text,
        html[data-theme="night"] .text-muted,
        html[data-theme="night"] .small {
            color: #94a3b8 !important;
        }

        html[data-theme="night"] .badge.text-bg-light {
            background: rgba(15, 23, 42, 0.9) !important;
            color: #cbd5e1 !important;
            border-color: rgba(148, 163, 184, 0.16) !important;
        }

        html[data-theme="night"] .nav-tabs {
            border-bottom-color: rgba(148, 163, 184, 0.16);
        }

        html[data-theme="night"] .nav-tabs .nav-link {
            color: #94a3b8;
            background: transparent;
        }

        html[data-theme="night"] .nav-tabs .nav-link.active {
            color: #f8fafc;
            background: #111827;
            border-color: rgba(148, 163, 184, 0.16);
        }

        html[data-theme="night"] .form-label {
            color: #94a3b8;
        }

        html[data-theme="night"] .form-control,
        html[data-theme="night"] .form-select,
        html[data-theme="night"] .select2-container .select2-selection--single,
        html[data-theme="night"] input[type=file] {
            background: #0b1220;
            border-color: rgba(148, 163, 184, 0.18);
            color: #e2e8f0;
        }

        html[data-theme="night"] .form-control::placeholder {
            color: #64748b;
        }

        html[data-theme="night"] .form-control:hover,
        html[data-theme="night"] .form-select:hover,
        html[data-theme="night"] .select2-container .select2-selection--single:hover {
            background: #111827;
            border-color: rgba(96, 165, 250, 0.28);
        }

        html[data-theme="night"] .form-control:focus,
        html[data-theme="night"] .form-select:focus,
        html[data-theme="night"] .select2-container--default.select2-container--focus .select2-selection--single,
        html[data-theme="night"] .select2-container--open .select2-selection--single {
            background: #111827;
            border-color: rgba(96, 165, 250, 0.5);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.18);
        }

        html[data-theme="night"] .select2-container--default .select2-selection--single .select2-selection__rendered,
        html[data-theme="night"] .select2-results__option {
            color: #e2e8f0;
        }

        html[data-theme="night"] .select2-dropdown {
            background: #0f172a;
            border-color: rgba(148, 163, 184, 0.18);
        }

        html[data-theme="night"] .select2-search--dropdown .select2-search__field {
            background: #111827;
            color: #e2e8f0;
            border-color: rgba(148, 163, 184, 0.18);
        }

        html[data-theme="night"] .select2-results__option--highlighted[aria-selected] {
            background: rgba(59, 130, 246, 0.22);
            color: #f8fafc;
        }

        html[data-theme="night"] .document-upload-card:hover,
        html[data-theme="night"] .document-upload-card-resume:hover {
            border-color: rgba(96, 165, 250, 0.28);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.32);
        }

        html[data-theme="night"] .upload-disabled {
            background: linear-gradient(180deg, #111827 0%, #0f172a 100%);
            opacity: 0.82;
        }

        html[data-theme="night"] .btn-primary,
        html[data-theme="night"] .btn-success,
        html[data-theme="night"] .btn-secondary,
        html[data-theme="night"] .btn-outline-primary,
        html[data-theme="night"] .btn-outline-secondary,
        html[data-theme="night"] .btn-light.border,
        html[data-theme="night"] .btn-back-list {
            background: #111827 !important;
            border-color: rgba(148, 163, 184, 0.18) !important;
            color: #e2e8f0 !important;
        }

        html[data-theme="night"] .btn-primary:hover,
        html[data-theme="night"] .btn-success:hover,
        html[data-theme="night"] .btn-secondary:hover,
        html[data-theme="night"] .btn-outline-primary:hover,
        html[data-theme="night"] .btn-outline-secondary:hover,
        html[data-theme="night"] .btn-light.border:hover,
        html[data-theme="night"] .btn-back-list:hover {
            background: #1f2937 !important;
            border-color: rgba(96, 165, 250, 0.28) !important;
            color: #f8fafc !important;
        }

        html[data-theme="night"] .btn:disabled,
        html[data-theme="night"] .btn.disabled {
            background: #0b1220 !important;
            border-color: rgba(148, 163, 184, 0.12) !important;
            color: #64748b !important;
        }

        /* Flatpickr Theme */
        .flatpickr-calendar {
            border: 1px solid #e5edf5;
            border-radius: 16px;
            box-shadow: 0 12px 36px rgba(15, 23, 42, 0.12);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        .flatpickr-months {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            border-radius: 16px 16px 0 0;
        }

        .flatpickr-months .flatpickr-month {
            background: transparent;
            color: #fff;
            height: 44px;
            fill: #fff;
        }

        .flatpickr-current-month {
            color: #fff;
            font-weight: 700;
            font-size: 0.92rem;
            padding-top: 6px;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-weight: 700;
            border: none;
            appearance: auto;
            -webkit-appearance: auto;
            padding: 2px 22px 2px 8px;
            border-radius: 8px;
            cursor: pointer;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #1d4ed8;
            color: #000000;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            background: rgba(255, 255, 255, 0.22);
        }

        .flatpickr-current-month input.cur-year {
            color: #fff;
            font-weight: 800;
            background: transparent;
            border: none;
            appearance: auto;
            -webkit-appearance: auto;
        }

        .flatpickr-current-month input.cur-year option {
            background: #1d4ed8;
            color: #fff;
        }

        .flatpickr-current-month .flatpickr-next-month,
        .flatpickr-current-month .flatpickr-prev-month {
            color: #fff;
            fill: #fff;
            height: 44px;
            top: 0;
        }

        .flatpickr-current-month .flatpickr-next-month:hover,
        .flatpickr-current-month .flatpickr-prev-month:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .flatpickr-current-month .flatpickr-next-month svg,
        .flatpickr-current-month .flatpickr-prev-month svg {
            fill: #fff;
        }

        span.flatpickr-weekday {
            color: #1d4ed8;
            font-weight: 800;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background: #f0f4ff;
            padding: 8px 0;
        }

        .flatpickr-day {
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            height: 38px;
            line-height: 38px;
            margin: 1px;
            border: none;
            color: #334155;
            transition: all 0.15s ease;
        }

        .flatpickr-day:hover {
            background: #dbeafe;
            color: #1d4ed8;
            border-color: transparent;
            box-shadow: 0 2px 8px rgba(29, 78, 216, 0.1);
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.3);
        }

        .flatpickr-day.today {
            background: #fef3c7;
            color: #92400e;
            border-color: transparent;
        }

        .flatpickr-day.today:hover {
            background: #fde68a;
        }

        .flatpickr-day.today.selected {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #fff;
        }

        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            color: #cbd5e1;
            background: transparent;
        }

        span.flatpickr-weekNumber {
            color: #94a3b8;
            font-weight: 600;
            border-right: 1px solid #e5edf5;
        }

        .flatpickr-day.inRange {
            background: #dbeafe;
            box-shadow: none;
            color: #1d4ed8;
            border-color: transparent;
        }

        html[data-theme="night"] .flatpickr-calendar {
            background: #1e293b;
            border-color: rgba(148, 163, 184, 0.18);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.35);
        }

        html[data-theme="night"] .flatpickr-months {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
        }

        html[data-theme="night"] .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
        }

        html[data-theme="night"] .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #1e40af;
            color: #fff;
        }

        html[data-theme="night"] .flatpickr-current-month input.cur-year {
            color: #fff;
        }

        html[data-theme="night"] .flatpickr-current-month input.cur-year option {
            background: #1e40af;
            color: #fff;
        }

        html[data-theme="night"] span.flatpickr-weekday {
            background: rgba(30, 64, 175, 0.12);
            color: #60a5fa;
        }

        html[data-theme="night"] .flatpickr-day {
            color: #cbd5e1;
        }

        html[data-theme="night"] .flatpickr-day:hover {
            background: rgba(59, 130, 246, 0.18);
            color: #93bbfd;
        }

        html[data-theme="night"] .flatpickr-day.selected,
        html[data-theme="night"] .flatpickr-day.selected:hover {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #fff;
        }

        html[data-theme="night"] .flatpickr-day.today {
            background: rgba(245, 158, 11, 0.18);
            color: #fbbf24;
        }

        html[data-theme="night"] .flatpickr-day.today:hover {
            background: rgba(245, 158, 11, 0.28);
        }

        html[data-theme="night"] span.flatpickr-weekNumber {
            color: #64748b;
            border-right-color: rgba(148, 163, 184, 0.18);
        }

        html[data-theme="night"] .flatpickr-day.inRange {
            background: rgba(59, 130, 246, 0.18);
            color: #93bbfd;
            box-shadow: none;
        }

        /* Transaction Table Responsive */
        @media (max-width: 991.98px) {
            .transaction-table {
                min-width: unset;
            }

            .transaction-table thead {
                display: none;
            }

            .transaction-table,
            .transaction-table tbody,
            .transaction-table tr,
            .transaction-table td {
                display: block;
                width: 100%;
            }

            .transaction-table tbody tr {
                background: #fff;
                border: 1px solid #e5edf5;
                border-radius: 16px;
                padding: 14px 16px;
                margin-bottom: 12px;
                box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
                transition: box-shadow 0.2s;
            }

            .transaction-table tbody tr:hover {
                box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            }

            .transaction-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 6px 0;
                border: none;
                border-bottom: 1px solid #f1f5f9;
                white-space: normal;
                text-align: right;
                gap: 12px;
            }

            .transaction-table tbody td:last-child {
                border-bottom: none;
                padding-top: 10px;
                justify-content: center;
            }

            .transaction-table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                font-size: 0.72rem;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                flex-shrink: 0;
                text-align: left;
                min-width: 110px;
            }

            .transaction-table tbody td.text-center::before {
                display: none;
            }

            .transaction-row-index {
                display: none !important;
            }

            .transaction-table tbody td[data-label="#"] {
                display: none !important;
            }

            .transaction-id-link,
            .transaction-id-text {
                font-size: 0.78rem;
            }

            .transaction-action {
                min-width: auto;
            }
        }

        html[data-theme="night"] .transaction-table tbody tr {
            background: #1e293b;
            border-color: rgba(148, 163, 184, 0.14);
        }

        html[data-theme="night"] .transaction-table tbody td {
            border-bottom-color: rgba(148, 163, 184, 0.1);
        }

        html[data-theme="night"] .transaction-table tbody td::before {
            color: #64748b;
        }

        @media (max-width: 1279.98px) {
            .transaction-table .hide-below-1280 {
                display: none !important;
            }
        }

        @media (max-width: 991.98px) {
            .transaction-panel-head {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .transaction-row-head {
                padding: 10px 14px;
            }

            .transaction-row-head h6 {
                font-size: 0.8rem;
            }

            .transaction-section-row .transaction-empty-state {
                padding: 1rem 14px;
            }
        }
    </style>

    <div class="container applicant-wrapper py-0 px-md-4 px-xl-0">
        <div class="requirements-container">
            <div class="content-intro">
                <h5 class="fw-bold mb-1">Document Compliance</h5>
                <p class="small">Manage permit, clearance, and referral requirements with a cleaner workflow.</p>
                <span class="badge rounded-pill text-bg-light border px-3 py-2">
                    <i class="bi bi-upc-scan me-1"></i> Record ID: #{{ $applicant->id }}
                </span>
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

                <div class="tab-pane fade show active personal-pane" id="personal">

                    <div class="form-card">

                        <form action="{{ route('applicants.update', $applicant->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <section class="form-section">
                                <h6 class="section-title">Personal Information</h6>
                                <div class="row g-4">
                                    <div class="col-md-2">
                                        <label class="form-label">First Time Jobseeker? <span
                                                class="required-mark">*</span></label>
                                        <select name="first_time_job_seeker" class="form-select" required>
                                            <option value="NO"
                                                {{ $applicant->first_time_job_seeker == 'NO' ? 'selected' : '' }}>NO
                                            </option>
                                            <option value="YES"
                                                {{ $applicant->first_time_job_seeker == 'YES' ? 'selected' : '' }}>YES
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">First Name <span class="required-mark">*</span></label>
                                        <input type="text" name="first_name" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            value="{{ $applicant->first_name }}" placeholder="e.g. John" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            value="{{ $applicant->middle_name }}" placeholder="Optional">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Last Name <span class="required-mark">*</span></label>
                                        <input type="text" name="last_name" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            value="{{ $applicant->last_name }}" placeholder="e.g. Doe" required>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Suffix</label>
                                        <select name="suffix" class="form-select">
                                            <option value="">None</option>
                                            <option value="JR." {{ $applicant->suffix == 'JR.' ? 'selected' : '' }}>JR.
                                            </option>
                                            <option value="SR." {{ $applicant->suffix == 'SR.' ? 'selected' : '' }}>SR.
                                            </option>
                                            <option value="II" {{ $applicant->suffix == 'II' ? 'selected' : '' }}>II
                                            </option>
                                            <option value="III" {{ $applicant->suffix == 'III' ? 'selected' : '' }}>III
                                            </option>
                                            <option value="IV" {{ $applicant->suffix == 'IV' ? 'selected' : '' }}>IV
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Birthdate</label>
                                        <input type="date" name="birthdate" id="birthdate"
                                            class="form-control"
                                            value="{{ old('birthdate', optional($applicant->birthdate)->format('Y-m-d')) }}"
                                            max="{{ date('Y-m-d') }}" min="1920-01-01">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Age (Auto)</label>
                                        <input type="number" name="age" id="age" class="form-control"
                                            value="{{ $applicant->age }}" placeholder="Auto-calculated" min="0"
                                            readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Sex <span class="required-mark">*</span></label>
                                        <select name="gender" class="form-select" required>
                                            <option value="">Select Sex</option>
                                            <option value="MALE" {{ $applicant->gender == 'MALE' ? 'selected' : '' }}>
                                                MALE
                                            </option>
                                            <option value="FEMALE" {{ $applicant->gender == 'FEMALE' ? 'selected' : '' }}>
                                                FEMALE</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Civil Status <span
                                                class="required-mark">*</span></label>
                                        @php
                                            $cs = strtoupper(trim((string) ($applicant->civil_status ?? '')));
                                        @endphp
                                        <select name="civil_status" class="form-select" required>
                                            <option value="" {{ $cs === '' ? 'selected' : '' }}>Select Status
                                            </option>
                                            <option value="SINGLE" {{ $cs === 'SINGLE' ? 'selected' : '' }}>SINGLE
                                            </option>
                                            <option value="MARRIED" {{ $cs === 'MARRIED' ? 'selected' : '' }}>MARRIED
                                            </option>
                                            <option value="WIDOWED" {{ $cs === 'WIDOWED' ? 'selected' : '' }}>WIDOWED
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">PWD?<span class="required-mark">*</span></label>
                                        <select name="pwd" class="form-select" required>
                                            <option value="NO" {{ $applicant->pwd == 'NO' ? 'selected' : '' }}>NO
                                            </option>
                                            <option value="YES" {{ $applicant->pwd == 'YES' ? 'selected' : '' }}>YES
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">4Ps?<span class="required-mark">*</span></label>
                                        <select name="four_ps" class="form-select" required>
                                            <option value="NO" {{ $applicant->four_ps == 'NO' ? 'selected' : '' }}>NO
                                            </option>
                                            <option value="YES" {{ $applicant->four_ps == 'YES' ? 'selected' : '' }}>
                                                YES
                                            </option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <label class="form-label">Email Address<span class="required-mark">*</span></label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ $applicant->email }}" placeholder="name@example.com" required>
                                    </div> --}}
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
                                            oninput="this.value = this.value.toUpperCase()"
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
                                            @foreach (config('educational_attainments', []) as $attainment)
                                                <option value="{{ $attainment }}"
                                                    {{ $applicant->educational_attainment === $attainment ? 'selected' : '' }}>
                                                    {{ $attainment }}
                                                </option>
                                            @endforeach
                                            @if (
                                                $applicant->educational_attainment &&
                                                    !in_array($applicant->educational_attainment, config('educational_attainments', []), true))
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
                                            oninput="this.value = this.value.toUpperCase()"
                                            value="{{ $applicant->hiring_company }}" placeholder="e.g. Tech Corp"
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Position Hired <span
                                                class="required-mark">*</span></label>
                                        <input type="text" name="position_hired" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            value="{{ $applicant->position_hired }}" placeholder="e.g. Software Engineer"
                                            required>
                                    </div>
                                </div>
                            </section>


                            <div class="profile-action-bar">

                                <button type="submit" class="btn btn-success px-5 py-2">
                                    <i class="fa-solid fa-check me-2"></i>
                                    {{ auth()->user()?->role === 'user' ? 'Update Profile Info' : 'Update Applicant Profile' }}
                                </button>

                                @unless (auth()->user()?->role === 'user')
                                    <a href="{{ route('applicants.index') }}" class="btn btn-light border px-4 py-2">
                                        Cancel
                                    </a>
                                @endunless

                            </div>
                        </form>
                    </div>
                </div>

                <!-- ===================================================== -->
                <!-- PERMIT -->
                <!-- ===================================================== -->

                <div class="tab-pane fade permit-pane" id="permit">
                    <form action="{{ route('permits.update', $applicant->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @php
                            $permit = optional($applicant->permit);
                        @endphp

                        @php
                            $permit = optional($applicant->permit);
                            $isImusResident =
                                stripos($applicant->city, 'IMUS CITY') !== false ||
                                stripos($applicant->city, 'CITY OF IMUS') !== false;
                            $selectedClearanceType = old(
                                'clearance_type',
                                $permit->clearance_type ??
                                    ($permit->permit_police_clearance
                                        ? 'police'
                                        : ($permit->permit_nbi_clearance
                                            ? 'nbi'
                                            : '')),
                            );
                        @endphp

                        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-4">
                            <h6 class="section-title text-primary mb-1">Mayor’s Permit to Work Requirements</h6>
                            @if ($applicant->permit)
                                <div class="d-flex flex-column align-items-end gap-1" style="text-transform: uppercase;">
                                    <span class="badge rounded-pill {{ $permit->approvalStatusClass() }}">
                                        {{ $permit->approvalStatusLabel() }}
                                    </span>
                                    @if (
                                        $permit->approval_status === \App\Models\MayorsPermit::APPROVAL_DISAPPROVED &&
                                            trim((string) ($permit->disapproval_reason ?? '')) !== '')
                                        <small class="text-danger text-end">
                                            Reason: {{ $permit->disapproval_reason }}
                                        </small>
                                    @endif
                                </div>
                            @else
                                <span class="badge rounded-pill text-bg-secondary">Not submitted</span>
                            @endif
                        </div>

                        @if ($isPermitRenewalDue)
                            <div class="alert alert-warning border-0 shadow-sm mb-3">
                                <strong>Renewal due:</strong> this permit has reached its 6-month renewal cycle.
                                The current upload set is refreshed when renewal is due, and new files are required before
                                saving.
                            </div>
                        @endif

                        <div class="row g-3 permit-upload-row">
                            {{-- 1. NBI / Police Clearance --}}
                            <div class="col-md-3 permit-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Clearance Type (NBI or Police)<span
                                            class="required-mark">*</span></label>
                                    <select name="clearance_type" id="clearance_type"
                                        class="form-select form-select-sm mb-3" required>
                                        <option value="">Select Type</option>
                                        <option value="nbi" {{ $selectedClearanceType == 'nbi' ? 'selected' : '' }}>
                                            NBI Clearance
                                        </option>
                                        <option value="police" {{ $selectedClearanceType == 'police' ? 'selected' : '' }}>
                                            Police Clearance
                                        </option>
                                    </select>

                                    <div class="gap-2" id="nbi_section" style="display:none">
                                        <!-- FILE INPUT (HIDDEN BUT CLICKABLE VIA LABEL) -->
                                        <input type="file" id="nbi_input" name="permit_nbi_clearance" class="d-none"
                                            onchange="showFileName(this, 'nbi_name')"
                                            {{ empty($permit->permit_nbi_clearance) || $isPermitRenewalDue ? 'required' : '' }}>

                                        <!-- USE LABEL INSTEAD OF BUTTON -->
                                        <label for="nbi_input" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>

                                        <small id="nbi_name" class="file-name-text">
                                            {{ old('nbi_name', 'No file selected') }}
                                        </small>

                                    </div>


                                    <div class="gap-2" id="police_section" style="display:none">

                                        <!-- FILE INPUT (HIDDEN BUT CLICKABLE VIA LABEL) -->
                                        <input type="file" id="police_input" name="permit_police_clearance"
                                            class="d-none" onchange="showFileName(this, 'police_name')"
                                            {{ empty($permit->permit_police_clearance) || $isPermitRenewalDue ? 'required' : '' }}>

                                        <!-- USE LABEL INSTEAD OF BUTTON -->
                                        <label for="police_input" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>

                                        <!-- FILE NAME -->
                                        <small id="police_name" class="file-name-text">
                                            {{ old('police_name', 'No file selected') }}
                                        </small>


                                    </div>
                                </div>
                            </div>

                            {{-- 2. Health Card --}}
                            <div class="col-md-3 permit-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Health Card <span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="health_card_input" name="health_card"
                                            style="display:none" onchange="showFileName(this, 'health_card_name')"
                                            {{ empty($permit->health_card) ? 'required' : '' }}>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('health_card_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="health_card_name" class="file-name-text">
                                            {{ old('health_card_name', 'No file selected') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. Cedula --}}
                            <div class="col-md-3 permit-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Cedula <span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="cedula_input" name="cedula" style="display:none"
                                            onchange="showFileName(this, 'cedula_name')"
                                            {{ empty($permit->cedula) ? 'required' : '' }}>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('cedula_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="cedula_name" class="file-name-text">
                                            {{ old('cedula_name', 'No file selected') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. Referral Letter --}}
                            <div class="col-md-3 permit-upload-col">
                                <div class="document-upload-card {{ $isImusResident ? 'upload-disabled' : '' }}">
                                    <label class="form-label">
                                        Referral Letter
                                        @if (!$isImusResident)
                                            <span class="required-mark">*</span>
                                        @endif
                                    </label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="referral_input" name="referral_letter"
                                            style="display:none" onchange="showFileName(this, 'referral_name')"
                                            {{ $isImusResident ? 'disabled' : '' }}
                                            {{ $isImusResident || !empty($permit->referral_letter) ? '' : 'required' }}>

                                        <button type="button" id="referral_upload_btn"
                                            class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('referral_input').click()"
                                            {{ $isImusResident ? 'disabled' : '' }}>
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>

                                        <small id="referral_name" class="file-name-text">
                                            {{ old('referral_name', 'No file selected') }}
                                        </small>

                                        <div id="referral_imus_badge"
                                            class="badge bg-success-soft text-success p-2 mt-1 {{ $isImusResident ? '' : 'd-none' }}"
                                            style="font-size: 11px;">
                                            Not required for Imus residents
                                        </div>

                                        <div id="referral_imus_badge"
                                            class="badge bg-danger-soft text-danger p-2 mt-1 {{ !$isImusResident ? '' : 'd-none' }}"
                                            style="font-size: 11px;">
                                            Required for Not Imus residents
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @unless ($isApplicantUser)
                            <h6 class="section-title text-primary mt-4">Permit to Work ID Details</h6>

                            <div class="row g-3 mt-3">

                                {{-- Peso ID No --}}
                                <div class="col-md-2">
                                    <label class="form-label">Peso ID No. (Auto Generate)<span
                                            class="required-mark">*</span></label>
                                    <input type="text" name="peso_id_no" class="form-control" style="text-align: center"
                                        value="{{ old('peso_id_no') }}" placeholder="Auto generate when complete" disabled>
                                </div>

                                {{-- OR NUMBER --}}
                                <div class="col-md-2">
                                    <label class="form-label">O.R No. <span class="required-mark">*</span></label>
                                    <input type="text" name="permit_or_no" value="{{ old('permit_or_no') }}"
                                        class="form-control" placeholder="e.g. RA11261"
                                        {{ $isFirstTimeJobSeeker ? 'readonly' : 'required' }}>
                                </div>

                                {{-- Community Tax No --}}
                                <div class="col-md-2">
                                    <label class="form-label">Community Tax No.<span class="required-mark">*</span></label>
                                    <input type="text" name="community_tax_no" class="form-control"
                                        value="{{ old('community_tax_no') }}" placeholder="Enter community tax no." required>
                                </div>

                                {{-- Issued On --}}
                                <div class="col-md-2">
                                    <label class="form-label">Permit Issued On<span class="required-mark">*</span></label>
                                    <input type="date" name="permit_issued_on" class="form-control"
                                        value="{{ old('permit_issued_on') }}" required>
                                </div>

                                {{-- Permit Issued At --}}
                                <div class="col-md-2">
                                    <label class="form-label">Permit Issued At<span class="required-mark">*</span></label>
                                    <select type="text" name="permit_issued_at" id="permitIssuedAtSelect"
                                        class="form-select" required>
                                        @php
                                            $permitIssuedAtValue = strtoupper(trim((string) old('permit_issued_at')));
                                            $permitIssuedAtOptions = collect(
                                                config('permit_issued_at.city_governments', []),
                                            )
                                                ->map(fn($value) => strtoupper(trim((string) $value)))
                                                ->filter()
                                                ->unique()
                                                ->sort()
                                                ->values();
                                        @endphp
                                        <option value="">Select City / Municipality</option>
                                        @foreach ($permitIssuedAtOptions as $permitIssuedAtOption)
                                            <option value="{{ $permitIssuedAtOption }}"
                                                {{ $permitIssuedAtValue === $permitIssuedAtOption ? 'selected' : '' }}>
                                                {{ $permitIssuedAtOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Permit Date --}}
                                <div class="col-md-2">
                                    <label class="form-label">Permit Date<span class="required-mark">*</span></label>
                                    <input type="date" id="permit_date" name="permit_date" class="form-control"
                                        value="{{ old('permit_date') }}" required>
                                </div>

                                {{-- Expiration --}}
                                <div class="col-md-2">
                                    <label class="form-label">Expires On<span class="required-mark">*</span></label>
                                    <input type="date" id="expires_on" name="expires_on" class="form-control"
                                        value="{{ old('expires_on') }}" readonly>
                                </div>

                                {{-- Documentary Stamp --}}
                                <div class="col-md-2">
                                    <label class="form-label">Documentary Stamp Control No.<span
                                            class="required-mark">*</span></label>
                                    <input type="text" name="permit_doc_stamp_control_no" class="form-control"
                                        value="{{ old('permit_doc_stamp_control_no') }}" placeholder="e.g. DOC-001"
                                        {{ $isFirstTimeJobSeeker ? 'readonly' : 'required' }}>
                                </div>
                                {{-- Date of Payment --}}
                                <div class="col-md-2">
                                    <label class="form-label">Date of Payment<span class="required-mark">*</span></label>
                                    <input type="date" name="permit_date_of_payment" class="form-control"
                                        value="{{ old('permit_date_of_payment') }}" required>
                                </div>
                            </div>
                        @endunless

                        <div class="permit-action-bar mt-4">
                            {{-- Action: Save/Update --}}
                            @if ($isApplicantUser || auth()->user()->hasPermission('update_permit'))
                                <button type="submit" class="btn btn-primary px-4 shadow-sm"
                                    @if ($isApplicantUser && $permitSubmitLocked) disabled title="Submission is locked until staff or admin disapproves this request." @endif>
                                    <i
                                        class="fa-solid fa-floppy-disk me-2"></i>{{ $isApplicantUser ? ($permitSubmitLocked ? 'Submitted' : 'Submit Upload File') : 'Save Permit' }}
                                </button>
                            @else
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip"
                                    title="No permission to update">
                                    <button type="button" class="btn btn-outline-secondary px-4" disabled>
                                        Save Permit
                                    </button>
                                </span>
                            @endif

                            @unless ($isApplicantUser)
                                @if (auth()->user()->hasPermission('approve_document') && $permit && $permit->canReview())
                                    <button type="submit" form="permit-approve-form-{{ $applicant->id }}"
                                        class="btn btn-success px-4 shadow-sm" formnovalidate>
                                        <i class="fa-solid fa-circle-check me-2"></i>Approve Permit Requirements
                                    </button>
                                @endif
                            @endunless

                            @unless ($isApplicantUser)
                                @if (auth()->user()->hasPermission('approve_document') && $permit && $permit->canReview())
                                    <button type="button" class="btn btn-outline-danger px-4 shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#disapprovePermitModal-{{ $applicant->id }}">
                                        <i class="fa-solid fa-circle-xmark me-2"></i>
                                        Disapprove Permit Requirements
                                    </button>
                                @endif
                            @endunless

                        </div>
                    </form>
                    <form id="permit-approve-form-{{ $applicant->id }}"
                        action="{{ route('permits.approve', $applicant->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('PUT')
                    </form>
                </div>

                <!-- ===================================================== -->
                <!-- CLEARANCE -->
                <!-- ===================================================== -->

                <div class="tab-pane fade clearance-pane" id="clearance">
                    <form action="{{ route('clearances.update', $applicant->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php $clearance = optional($applicant->clearance); @endphp

                        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-4">
                            <h6 class="section-title text-primary mb-1">Mayor's Clearance Requirements</h6>
                            @if ($applicant->clearance)
                                <div class="d-flex flex-column align-items-end gap-1" style="text-transform: uppercase;">
                                    <span class="badge rounded-pill {{ $clearance->approvalStatusClass() }}">
                                        {{ $clearance->approvalStatusLabel() }}
                                    </span>
                                    @if (
                                        $clearance->approval_status === \App\Models\MayorsClearance::APPROVAL_DISAPPROVED &&
                                            trim((string) ($clearance->disapproval_reason ?? '')) !== '')
                                        <small class="text-danger text-end">
                                            Reason: {{ $clearance->disapproval_reason }}
                                        </small>
                                    @endif
                                </div>
                            @else
                                <span class="badge rounded-pill text-bg-secondary">Not submitted</span>
                            @endif
                        </div>

                        <div class="clearance-upload-row">

                            <div class="clearance-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Prosecutor Clearance<span
                                            class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="prosecutor_input" name="prosecutor_clearance"
                                            style="display:none" onchange="showFileName(this, 'prosecutor_name')"
                                            {{ empty($clearance->prosecutor_clearance) ? 'required' : '' }}>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('prosecutor_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="prosecutor_name" class="file-name-text">
                                            {{ old('prosecutor_name', 'No file selected') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="clearance-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Municipal Trial Court Clearance<span
                                            class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="mtc_input" name="mtc_clearance" style="display:none"
                                            onchange="showFileName(this, 'mtc_name')"
                                            {{ empty($clearance->mtc_clearance) ? 'required' : '' }}>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('mtc_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="mtc_name" class="file-name-text">
                                            {{ old('mtc_name', 'No file selected') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="clearance-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Regional Trial Court Clearance<span
                                            class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="rtc_input" name="rtc_clearance" style="display:none"
                                            onchange="showFileName(this, 'rtc_name')"
                                            {{ empty($clearance->rtc_clearance) ? 'required' : '' }}>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('rtc_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="rtc_name" class="file-name-text">
                                            {{ old('rtc_name', 'No file selected') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="clearance-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">NBI Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="c_nbi_input" name="nbi_clearance" style="display:none"
                                            onchange="showFileName(this, 'c_nbi_name')"
                                            {{ empty($clearance->nbi_clearance) ? 'required' : '' }}>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('c_nbi_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="c_nbi_name" class="file-name-text">
                                            {{ old('c_nbi_name', 'No file selected') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="clearance-upload-col">
                                <div class="document-upload-card">
                                    <label class="form-label">Barangay Clearance<span
                                            class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="brgy_input" name="barangay_clearance"
                                            style="display:none" onchange="showFileName(this, 'brgy_name')"
                                            {{ empty($clearance->barangay_clearance) ? 'required' : '' }}>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('brgy_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="brgy_name" class="file-name-text">
                                            {{ old('brgy_name', 'No file selected') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </div>

                        @unless ($isApplicantUser)
                            <h6 class="section-title text-primary mb-0 mt-4">Mayor’s Clearance Letter Details</h6>
                            <div class="row g-3 mt-3">
                                {{-- PESO Control No --}}
                                <div class="col-md-2">
                                    <label class="form-label">Peso Control No. (Auto Generate)<span
                                            class="required-mark">*</span></label>
                                    <input type="text" name="clearance_peso_control_no" class="form-control"
                                        style="text-align: center" value="{{ old('clearance_peso_control_no') }}"
                                        placeholder="Auto generate when complete" readonly>
                                </div>

                                {{-- Official Receipt No --}}
                                <div class="col-md-2">
                                    <label class="form-label">O.R. No.<span class="required-mark">*</span></label>
                                    <input type="text" name="clearance_or_no" class="form-control"
                                        value="{{ old('clearance_or_no') }}" required>
                                </div>

                                {{-- Hired Company --}}
                                <div class="col-md-2">
                                    <label class="form-label">Hired Company<span class="required-mark">*</span></label>
                                    <input type="text" name="clearance_hired_company" class="form-control"
                                        value="{{ old('clearance_hired_company') }}" required>
                                </div>

                                {{-- Issued On --}}
                                <div class="col-md-2">
                                    <label class="form-label">Issued On<span class="required-mark">*</span></label>
                                    <input type="date" name="clearance_issued_on" class="form-control"
                                        value="{{ old('clearance_issued_on') }}" required>
                                </div>

                                {{-- Documentary Stamp Control No --}}
                                <div class="col-md-2">
                                    <label class="form-label">Documentary Stamp Control No.<span
                                            class="required-mark">*</span></label>
                                    <input type="text" name="clearance_doc_stamp_control_no" class="form-control"
                                        value="{{ old('clearance_doc_stamp_control_no') }}" required>
                                </div>
                                {{-- Date of Payment --}}
                                <div class="col-md-2">
                                    <label class="form-label">Date of Payment<span class="required-mark">*</span></label>
                                    <input type="date" name="clearance_date_of_payment" class="form-control"
                                        value="{{ old('clearance_date_of_payment') }}" required>
                                </div>
                            </div>
                        @endunless

                        <div class="clearance-action-bar mt-4">
                            {{-- Update/Save Section --}}
                            @if ($isApplicantUser || auth()->user()->hasPermission('update_clearance'))
                                <button type="submit" class="btn btn-primary px-4 shadow-sm"
                                    @if ($isApplicantUser && $clearanceSubmitLocked) disabled title="Submission is locked until staff or admin disapproves this request." @endif>
                                    <i
                                        class="fa-solid fa-certificate me-2"></i>{{ $isApplicantUser ? ($clearanceSubmitLocked ? 'Submitted' : 'Submit Upload File') : 'Save Clearance' }}
                                </button>
                            @else
                                <span class="d-inline-block" data-bs-toggle="tooltip" title="No permission to update">
                                    <button type="button" class="btn btn-outline-secondary px-4" disabled>
                                        Save Clearance
                                    </button>
                                </span>
                            @endif

                            @unless ($isApplicantUser)
                                @if (auth()->user()->hasPermission('approve_document') && $clearance && $clearance->canReview())
                                    <button type="submit" form="clearance-approve-form-{{ $applicant->id }}"
                                        class="btn btn-success px-4 shadow-sm" formnovalidate>
                                        <i class="fa-solid fa-circle-check me-2"></i>Approve Clearance Requirements
                                    </button>
                                @endif
                            @endunless

                            @unless ($isApplicantUser)
                                @if (auth()->user()->hasPermission('approve_document') && $clearance && $clearance->canReview())
                                    <button type="button" class="btn btn-outline-danger px-4 shadow-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#disapproveClearanceModal-{{ $applicant->id }}">
                                        <i class="fa-solid fa-circle-xmark me-2"></i>
                                        Disapprove Clearance Requirements
                                    </button>
                                @endif
                            @endunless

                        </div>

                    </form>
                </div>

                <!-- ===================================================== -->
                <!-- REFERRAL -->
                <!-- ===================================================== -->

                <div class="tab-pane fade referral-pane" id="referral">

                    <form action="{{ route('referrals.update', $applicant->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php $referral = optional($applicant->referral); @endphp
                        @php
                            $selectedReferralType = old(
                                'referral_type',
                                $referral->referral_type ?? \App\Models\MayorsReferral::TYPE_PESO_OFFICE,
                            );
                            $storedPesoReferralDetails = $referral->referral_details ?? [];
                            if (!is_array($storedPesoReferralDetails)) {
                                $storedPesoReferralDetails = [];
                            }
                            $pesoReferralDetails = old('referral_details', array_slice($storedPesoReferralDetails, 1));
                            if (!is_array($pesoReferralDetails)) {
                                $pesoReferralDetails = [];
                            }
                        @endphp

                        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-4">
                            <h6 class="section-title text-primary mb-1">Mayor's Referral Requirements</h6>
                            @if ($applicant->referral)
                                <div class="d-flex flex-column align-items-end gap-1" style="text-transform: uppercase;">
                                    <span class="badge rounded-pill {{ $referral->approvalStatusClass() }}">
                                        {{ $referral->approvalStatusLabel() }}
                                    </span>
                                    @if (
                                        $referral->approval_status === \App\Models\MayorsReferral::APPROVAL_DISAPPROVED &&
                                            trim((string) ($referral->disapproval_reason ?? '')) !== '')
                                        <small class="text-danger text-end">
                                            Reason: {{ $referral->disapproval_reason }}
                                        </small>
                                    @endif
                                </div>
                            @else
                                <span class="badge rounded-pill text-bg-secondary">Not submitted</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="document-upload-card-resume">
                                <label class="form-label">Resume / Bio-data<span class="required-mark">*</span></label>
                                <div class="d-grid gap-2">
                                    <input type="file" id="resume_input" name="resume" style="display:none"
                                        onchange="showFileName(this, 'resume_name')"
                                        {{ empty($referral->resume) ? 'required' : '' }}>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="document.getElementById('resume_input').click()">
                                        <i class="fas fa-upload me-1"></i> Upload File
                                    </button>
                                    <small id="resume_name" class="file-name-text">
                                        {{ old('resume_name', 'No file selected') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <h4 class="section-title-c text-primary">Choose at least one of the following:</h4>
                        <div class="referral-upload-row pb-2">

                            <div class="col-md-4">
                                <div class="document-upload-card">
                                    <label class="form-label">Barangay Clearance<span
                                            class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="ref_brgy_input" name="ref_barangay_clearance"
                                            style="display:none"
                                            onchange="handleReferralClearanceChange(this, 'ref_brgy_name', ['ref_police_input', 'ref_nbi_input'], ['ref_police_name', 'ref_nbi_name'])">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('ref_brgy_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="ref_brgy_name" class="file-name-text">
                                            {{ old('ref_brgy_name', 'No file selected') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="document-upload-card">
                                    <label class="form-label">Police Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="ref_police_input" name="ref_police_clearance"
                                            style="display:none"
                                            onchange="handleReferralClearanceChange(this, 'ref_police_name', ['ref_brgy_input', 'ref_nbi_input'], ['ref_brgy_name', 'ref_nbi_name'])">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('ref_police_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="ref_police_name" class="file-name-text">
                                            {{ old('ref_police_name', 'No file selected') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="document-upload-card">
                                    <label class="form-label">NBI Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="ref_nbi_input" name="ref_nbi_clearance"
                                            style="display:none"
                                            onchange="handleReferralClearanceChange(this, 'ref_nbi_name', ['ref_brgy_input', 'ref_police_input'], ['ref_brgy_name', 'ref_police_name'])">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('ref_nbi_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="ref_nbi_name" class="file-name-text">
                                            {{ old('ref_nbi_name', 'No file selected') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </div>

                        @unless ($isApplicantUser)
                            <div class="mt-2">
                                <div class="referral-details-head mb-3">
                                    <div>
                                        <h6 class="section-title text-primary mb-1">Mayor's Referral Letter Details</h6>
                                        <p class="section-copy mb-0">Choose the referral type, then fill in the matching
                                            letter details below.</p>
                                    </div>


                                </div>

                                <div class="col-12 col-md-2 mb-3 referral-type-field">
                                    <label class="form-label">Referral Letter Type</label>
                                    <select name="referral_type" id="referralTypeSelect"
                                        class="form-select referral-type-select">
                                        <option value="{{ \App\Models\MayorsReferral::TYPE_PESO_OFFICE }}"
                                            {{ $selectedReferralType === \App\Models\MayorsReferral::TYPE_PESO_OFFICE ? 'selected' : '' }}>
                                            Referral Within Imus
                                        </option>
                                        <option value="{{ \App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT }}"
                                            {{ $selectedReferralType === \App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT ? 'selected' : '' }}>
                                            Referral Outside Imus
                                        </option>
                                    </select>
                                </div>


                                <div id="pesoOfficeFields" data-referral-group="peso"
                                    class="{{ $selectedReferralType === \App\Models\MayorsReferral::TYPE_PESO_OFFICE ? '' : 'd-none' }}">
                                    <div class="js-peso-extra-details mt-4 d-grid gap-3">
                                        <div class="peso-extra-detail-card border rounded-4 p-3 bg-light js-peso-extra-detail">
                                            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                                <span class="badge bg-primary-subtle text-primary">Employer Detail 1</span>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-2">
                                                    <label class="form-label">Peso OCRL (Auto Generate)<span
                                                            class="required-mark">*</span></label>
                                                    <input type="text" name="ref_imus_ocrl" class="form-control"
                                                        style="text-align: center" value="{{ old('ref_imus_ocrl') }}"
                                                        placeholder="Auto generate when complete" readonly>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Employer Name<span
                                                            class="required-mark">*</span></label>
                                                    <input type="text" name="ref_employer_name" class="form-control"
                                                        oninput="this.value = this.value.toUpperCase()"
                                                        value="{{ old('ref_employer_name') }}" required>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Employer Position<span
                                                            class="required-mark">*</span></label>
                                                    <input type="text" name="ref_position" class="form-control"
                                                        oninput="this.value = this.value.toUpperCase()"
                                                        value="{{ old('ref_position') }}" required>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label"> City Address<span
                                                            class="required-mark">*</span></label>
                                                    <select name="ref_place" id="refPlaceInput"
                                                        class="form-select" required>
                                                        <option value="">Select City Address</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Province</label>
                                                    <input type="text" name="ref_province" id="refProvinceInput"
                                                        class="form-control" oninput="this.value = this.value.toUpperCase()"
                                                        value="{{ old('ref_province') }}" placeholder="Enter Province"
                                                        required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Hired Company<span
                                                            class="required-mark">*</span></label>
                                                    <input type="text" name="ref_hired_company" class="form-control"
                                                        oninput="this.value = this.value.toUpperCase()"
                                                        value="{{ old('ref_hired_company') }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        @foreach ($pesoReferralDetails as $extraIndex => $extraDetail)
                                            <div
                                                class="peso-extra-detail-card border rounded-4 p-3 bg-light js-peso-extra-detail">
                                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                                    <span class="badge bg-primary-subtle text-primary">Employer Detail
                                                        {{ $extraIndex + 2 }}</span>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label class="form-label">Peso OCRL (Auto Generated)<span
                                                                class="required-mark">*</span></label>
                                                        <input type="text" class="form-control" style="text-align: center"
                                                            name="referral_details[{{ $extraIndex }}][ref_imus_ocrl]"
                                                            value="{{ old('referral_details.' . $extraIndex . '.ref_imus_ocrl') }}"
                                                            placeholder="Auto generate when saved" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Employer Name<span
                                                                class="required-mark">*</span></label>
                                                        <input type="text" class="form-control"
                                                            oninput="this.value = this.value.toUpperCase()"
                                                            name="referral_details[{{ $extraIndex }}][ref_employer_name]"
                                                            required
                                                            value="{{ old('referral_details.' . $extraIndex . '.ref_employer_name') }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Employer Position<span
                                                                class="required-mark">*</span></label>
                                                        <input type="text" class="form-control"
                                                            oninput="this.value = this.value.toUpperCase()"
                                                            name="referral_details[{{ $extraIndex }}][ref_position]"
                                                            required
                                                            value="{{ old('referral_details.' . $extraIndex . '.ref_position') }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">City Address<span
                                                                class="required-mark">*</span></label>
                                                        <select class="form-select js-peso-ref-place-select"
                                                            name="referral_details[{{ $extraIndex }}][ref_place]" required
                                                            data-selected-value="{{ old('referral_details.' . $extraIndex . '.ref_place') }}">
                                                            <option value="">Select City Address</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Province<span
                                                                class="required-mark">*</span></label>
                                                        <input type="text" class="form-control js-peso-ref-province-input"
                                                            name="referral_details[{{ $extraIndex }}][ref_province]"
                                                            required
                                                            value="{{ old('referral_details.' . $extraIndex . '.ref_province') }}"
                                                            oninput="this.value = this.value.toUpperCase()"
                                                            placeholder="Enter Province">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Hired Company<span
                                                                class="required-mark">*</span></label>
                                                        <input type="text" class="form-control"
                                                            oninput="this.value = this.value.toUpperCase()"
                                                            name="referral_details[{{ $extraIndex }}][ref_hired_company]"
                                                            required
                                                            value="{{ old('referral_details.' . $extraIndex . '.ref_hired_company') }}">
                                                    </div>
                                                </div>
                                                <div class="mt-3 d-flex flex-wrap gap-2">
                                                    <button type="button"
                                                        class="btn btn-link text-danger text-decoration-none p-0 js-remove-peso-detail">
                                                        <i class="fas fa-trash-alt me-1"></i>Remove
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-3 d-flex justify-content-end">
                                        <button type="button" id="addPesoDetailButton"
                                            class="btn btn-outline-primary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center text-primary {{ $selectedReferralType === \App\Models\MayorsReferral::TYPE_PESO_OFFICE ? '' : 'd-none' }}"
                                            style="width: 40px; height: 40px;" aria-label="Add More Employer Details"
                                            title="Add More Employer Details">
                                            <i class="bi bi-plus-lg" style="font-size: 1.25rem; line-height: 1;"></i>
                                        </button>
                                    </div>

                                    <template id="pesoDetailTemplate">
                                        <div class="peso-extra-detail-card border rounded-4 p-3 bg-light js-peso-extra-detail">
                                            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                                <span class="badge bg-primary-subtle text-primary">Employer
                                                    Detail </span>

                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-2">
                                                    <label class="form-label">Peso OCRL (Auto Generated)<span
                                                            class="required-mark">*</span></label>
                                                    <input type="text" class="form-control" style="text-align: center"
                                                        name="referral_details[__INDEX__][ref_imus_ocrl]"
                                                        placeholder="Auto generate when saved" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Employer Name<span
                                                            class="required-mark">*</span></label>
                                                    <input type="text" class="form-control"
                                                        oninput="this.value = this.value.toUpperCase()" required
                                                        name="referral_details[__INDEX__][ref_employer_name]">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Employer Position<span
                                                            class="required-mark">*</span></label>
                                                    <input type="text" class="form-control"
                                                        oninput="this.value = this.value.toUpperCase()" required
                                                        name="referral_details[__INDEX__][ref_position]">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">City Address<span
                                                            class="required-mark">*</span></label>
                                                    <select class="form-select js-peso-ref-place-select"
                                                        name="referral_details[__INDEX__][ref_place]"
                                                        required>
                                                        <option value="">Select City Address</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Province<span
                                                            class="required-mark">*</span></label>
                                                    <input type="text" class="form-control js-peso-ref-province-input"
                                                        name="referral_details[__INDEX__][ref_province]"
                                                        oninput="this.value = this.value.toUpperCase()" required
                                                        placeholder="Enter Province">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Hired Company<span
                                                            class="required-mark">*</span></label>
                                                    <input type="text" class="form-control"
                                                        oninput="this.value = this.value.toUpperCase()" required
                                                        name="referral_details[__INDEX__][ref_hired_company]">
                                                </div>
                                            </div>
                                            <div class="mt-3 d-flex flex-wrap gap-2">
                                                <button type="button"
                                                    class="btn btn-link text-danger text-decoration-none p-0 js-remove-peso-detail">
                                                    <i class="fas fa-trash-alt me-1"></i>Remove
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div id="otherCityFields" data-referral-group="other-city"
                                    class="{{ $selectedReferralType === \App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT ? '' : 'd-none' }}">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Peso Imus OCRL (Auto Generate)<span
                                                    class="required-mark">*</span></label>
                                            <input type="text" name="ref_ocrl" class="form-control"
                                                style="text-align: center" value="{{ old('ref_ocrl') }}"
                                                placeholder="Auto generate when complete" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Mayor's Name<span
                                                    class="required-mark">*</span></label>
                                            <select name="ref_recipient" id="refRecipientSelect" class="form-select"
                                                required>
                                                <option value="">Select City Mayor</option>
                                                @foreach (config('philippine_mayors', []) as $mayor)
                                                    <option value="{{ $mayor['recipient'] }}"
                                                        data-city-government="{{ $mayor['city_government'] }}"
                                                        data-company-address="{{ $mayor['company_address'] }}"
                                                        {{ old('ref_recipient') === $mayor['recipient'] ? 'selected' : '' }}>
                                                        {{ $mayor['recipient'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">City Government<span
                                                    class="required-mark">*</span></label>
                                            <select name="ref_city_gov" id="cityGovernment" class="form-select" required>
                                                <option value="">Select City Government</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">City Address<span
                                                    class="required-mark">*</span></label>
                                            <input type="text" name="ref_company_address" id="refCompanyAddressInput"
                                                class="form-control" list="refCompanyAddressList" autocomplete="off"
                                                required>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        @endunless

                        <div class="referral-action-bar mt-4">
                            @if ($isApplicantUser || auth()->user()->hasPermission('update_referral'))
                                <button type="submit" class="btn btn-primary px-4 shadow-sm"
                                    @if ($isApplicantUser && $referralSubmitLocked) disabled title="Submission is locked until staff or admin disapproves this request." @endif>
                                    <i
                                        class="fa-solid fa-file-export me-2"></i>{{ $isApplicantUser ? ($referralSubmitLocked ? 'Submitted' : 'Submit Upload File') : 'Save Referral' }}
                                </button>
                            @else
                                <span class="d-inline-block" data-bs-toggle="tooltip" title="No permission to update">
                                    <button type="button" class="btn btn-outline-secondary px-4" disabled>
                                        Save Referral
                                    </button>
                                </span>
                            @endif

                            @unless ($isApplicantUser)
                                @if (auth()->user()->hasPermission('approve_document') && $referral && $referral->canReview())
                                    <button type="submit" form="referral-approve-form-{{ $applicant->id }}"
                                        class="btn btn-success px-4 shadow-sm" formnovalidate>
                                        <i class="fa-solid fa-circle-check me-2"></i>Approve Referral Requirements
                                    </button>
                                @endif
                            @endunless

                            @unless ($isApplicantUser)
                                @if (auth()->user()->hasPermission('approve_document') && $referral && $referral->canReview())
                                    <button type="button" class="btn btn-outline-danger px-4 shadow-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#disapproveReferralModal-{{ $applicant->id }}">
                                        <i class="fa-solid fa-circle-xmark me-2"></i>
                                        Disapprove Referral Requirements
                                    </button>
                                @endif
                            @endunless

                        </div>
                    </form>
                </div>
            </div>
        </div>

        @php
            $allPermits = $applicant->permits->sortByDesc('id');
            $allClearances = $applicant->clearances->sortByDesc('id');
            $allReferrals = $applicant->referrals->sortByDesc('id');
            $transactionClearance = $applicant->clearance;
            $transactionReferral = $applicant->referral;
            $transactionReferralExtraDetails =
                $transactionReferral && is_array($transactionReferral->referral_details ?? null)
                    ? array_values(array_slice($transactionReferral->referral_details, 1))
                    : [];
            $transactionReferralHasWithinImus =
                $transactionReferral &&
                collect([
                    $transactionReferral->ref_imus_ocrl,
                    $transactionReferral->ref_employer_name,
                    $transactionReferral->ref_position,
                    $transactionReferral->ref_place,
                    $transactionReferral->ref_province,
                    $transactionReferral->ref_hired_company,
                ])->contains(fn($value) => trim((string) $value) !== '');
            $transactionReferralHasOutsideImus =
                $transactionReferral &&
                collect([
                    $transactionReferral->ref_ocrl,
                    $transactionReferral->ref_recipient,
                    $transactionReferral->ref_company_address,
                    $transactionReferral->ref_city_gov,
                ])->contains(fn($value) => trim((string) $value) !== '');
            $referralControlSortValue = function ($value) {
                if (preg_match('/(\d{4})-(\d{5})/', (string) $value, $matches)) {
                    return (int) $matches[1] * 100000 + (int) $matches[2];
                }

                return 0;
            };

            $transactionReferralCount = 0;
            $allReferralRows = collect();

            foreach ($allReferrals as $refEntry) {
                $refHasWithinImus = collect([
                    $refEntry->ref_imus_ocrl,
                    $refEntry->ref_employer_name,
                    $refEntry->ref_position,
                    $refEntry->ref_place,
                    $refEntry->ref_province,
                    $refEntry->ref_hired_company,
                ])->contains(fn($value) => trim((string) $value) !== '');

                $refHasOutsideImus = collect([
                    $refEntry->ref_ocrl,
                    $refEntry->ref_recipient,
                    $refEntry->ref_company_address,
                    $refEntry->ref_city_gov,
                ])->contains(fn($value) => trim((string) $value) !== '');

                $refExtraDetails = is_array($refEntry->referral_details ?? null)
                    ? array_values(array_slice($refEntry->referral_details, 1))
                    : [];

                if ($refHasWithinImus) {
                    $allReferralRows->push([
                        'type' => 'Referral Within Imus',
                        'control_no' => \App\Models\MayorsReferral::formatWithinImusControlNo($refEntry->ref_imus_ocrl),
                        'raw_control_no' => $refEntry->ref_imus_ocrl,
                        'employer_recipient' => $refEntry->ref_employer_name,
                        'position_city_gov' => $refEntry->ref_position,
                        'address' => $refEntry->ref_place,
                        'province' => $refEntry->ref_province,
                        'hired_company' => $refEntry->ref_hired_company,
                        'status_class' => $refEntry->approvalStatusClass(),
                        'status_label' => $refEntry->approvalStatusLabel(),
                        'can_view' => $refEntry->canPrintType(\App\Models\MayorsReferral::TYPE_PESO_OFFICE),
                        'route' => route('referrals.printLetter', [
                            'id' => $applicant->id,
                            'type' => \App\Models\MayorsReferral::TYPE_PESO_OFFICE,
                        ]),
                        'referral_id' => $refEntry->id,
                        'detail_index' => null,
                        'sort_control' => $referralControlSortValue($refEntry->ref_imus_ocrl ?? ''),
                        'ref_entry' => $refEntry,
                        'resume' => $refEntry->resume ?? '',
                        'brgy' => $refEntry->ref_barangay_clearance ?? '',
                        'police' => $refEntry->ref_police_clearance ?? '',
                        'nbi' => $refEntry->ref_nbi_clearance ?? '',
                        'edit_data' => [
                            'type' => 'peso_office',
                            'employer-name' => $refEntry->ref_employer_name ?? '',
                            'position' => $refEntry->ref_position ?? '',
                            'place' => $refEntry->ref_place ?? '',
                            'province' => $refEntry->ref_province ?? '',
                            'hired-company' => $refEntry->ref_hired_company ?? '',
                        ],
                    ]);
                }

                foreach ($refExtraDetails as $detailIndex => $refExtraDetail) {
                    $refExtraDetail = is_array($refExtraDetail) ? $refExtraDetail : [];
                    $allReferralRows->push([
                        'type' => 'Referral Within Imus',
                        'control_no' => \App\Models\MayorsReferral::formatWithinImusControlNo(
                            $refExtraDetail['ref_imus_ocrl'] ?? '',
                        ),
                        'raw_control_no' => $refExtraDetail['ref_imus_ocrl'] ?? '',
                        'employer_recipient' => $refExtraDetail['ref_employer_name'] ?? '',
                        'position_city_gov' => $refExtraDetail['ref_position'] ?? '',
                        'address' => $refExtraDetail['ref_place'] ?? '',
                        'province' => $refExtraDetail['ref_province'] ?? '',
                        'hired_company' => $refExtraDetail['ref_hired_company'] ?? '',
                        'status_class' => $refEntry->approvalStatusClass(),
                        'status_label' => $refEntry->approvalStatusLabel(),
                        'can_view' =>
                            $refEntry->canPrintType(\App\Models\MayorsReferral::TYPE_PESO_OFFICE) &&
                            \App\Models\MayorsReferral::hasPrintablePesoDetail($refExtraDetail),
                        'route' => route('referrals.printLetter', [
                            'id' => $applicant->id,
                            'type' => \App\Models\MayorsReferral::TYPE_PESO_OFFICE,
                            'detail' => $detailIndex,
                        ]),
                        'referral_id' => $refEntry->id,
                        'detail_index' => $detailIndex,
                        'sort_control' => $referralControlSortValue($refExtraDetail['ref_imus_ocrl'] ?? ''),
                        'ref_entry' => $refEntry,
                        'resume' => $refEntry->resume ?? '',
                        'brgy' => $refEntry->ref_barangay_clearance ?? '',
                        'police' => $refEntry->ref_police_clearance ?? '',
                        'nbi' => $refEntry->ref_nbi_clearance ?? '',
                        'edit_data' => [
                            'type' => 'peso_office',
                            'detail-index' => $detailIndex,
                            'employer-name' => $refExtraDetail['ref_employer_name'] ?? '',
                            'position' => $refExtraDetail['ref_position'] ?? '',
                            'place' => $refExtraDetail['ref_place'] ?? '',
                            'province' => $refExtraDetail['ref_province'] ?? '',
                            'hired-company' => $refExtraDetail['ref_hired_company'] ?? '',
                        ],
                    ]);
                }

                if ($refHasOutsideImus) {
                    $allReferralRows->push([
                        'type' => 'Referral Outside Imus',
                        'control_no' => \App\Models\MayorsReferral::formatOutsideImusControlNo($refEntry->ref_ocrl),
                        'raw_control_no' => $refEntry->ref_ocrl,
                        'employer_recipient' => $refEntry->ref_recipient,
                        'position_city_gov' => $refEntry->ref_city_gov,
                        'address' => $refEntry->ref_company_address,
                        'province' => '',
                        'hired_company' => '',
                        'status_class' => $refEntry->approvalStatusClass(),
                        'status_label' => $refEntry->approvalStatusLabel(),
                        'can_view' => $refEntry->canPrintType(\App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT),
                        'route' => route('referrals.printLetter', [
                            'id' => $applicant->id,
                            'type' => \App\Models\MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT,
                        ]),
                        'referral_id' => $refEntry->id,
                        'detail_index' => null,
                        'sort_control' => $referralControlSortValue($refEntry->ref_ocrl ?? ''),
                        'ref_entry' => $refEntry,
                        'resume' => $refEntry->resume ?? '',
                        'brgy' => $refEntry->ref_barangay_clearance ?? '',
                        'police' => $refEntry->ref_police_clearance ?? '',
                        'nbi' => $refEntry->ref_nbi_clearance ?? '',
                        'edit_data' => [
                            'type' => 'other_city_government',
                            'recipient' => $refEntry->ref_recipient ?? '',
                            'city-gov' => $refEntry->ref_city_gov ?? '',
                            'company-address' => $refEntry->ref_company_address ?? '',
                        ],
                    ]);
                }
            }

            $allReferralRows = $allReferralRows->sortByDesc('sort_control')->values();
            $transactionReferralCount = $allReferralRows->count();
            $transactionCount = $allPermits->count() + $allClearances->count() + $transactionReferralCount;
        @endphp
        @if ($transactionCount > 0)
            <section class="transaction-panel">
                <div class="transaction-panel-head">
                    <div class="transaction-panel-title">
                        <div>
                            <h5 class="fw-bold" style="font-size: 20px;">Transaction Details</h5>
                            <p>Permit, clearance, and referral document history</p>
                        </div>
                    </div>
                    <span class="transaction-count-badge">
                        <i class="fa-solid fa-layer-group"></i>
                        {{ $transactionCount }} {{ \Illuminate\Support\Str::plural('transaction', $transactionCount) }}
                    </span>
                </div>

                <div class="transaction-section-row">
                    <div class="transaction-row-head" data-bs-toggle="collapse" data-bs-target="#permitCollapse"
                        role="button" aria-expanded="false">
                        <i class="bi bi-patch-check-fill text-primary"></i>
                        <h6>Permit to Work ID</h6>
                        <span class="transaction-row-count">{{ $allPermits->count() }}</span>
                        <i class="bi bi-chevron-down ms-auto transaction-chevron"></i>
                    </div>
                    <div id="permitCollapse" class="collapse">
                        @if ($allPermits->isNotEmpty())
                            <div class="transaction-table-wrap">
                                <table class="table transaction-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Permit to Work ID</th>
                                            <th>O.R No.</th>
                                            <th>Community Tax No.</th>
                                            <th>Permit Issued On</th>
                                            <th>Permit Issued At</th>
                                            <th>Permit Date</th>
                                            <th>Expires On</th>
                                            <th>Doc Stamp Control No.</th>
                                            <th>Date of Payment</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allPermits as $entry)
                                            <tr data-transaction-sort="{{ $entry->id }}">
                                                <td class="transaction-row-index" data-label="#">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td data-label="Permit to Work ID">
                                                    @if ($entry->peso_id_no)
                                                        <span class="transaction-id-text">
                                                            <i class="fa-solid fa-id-card"></i>
                                                            {{ 'OP' . strtoupper($entry->peso_id_no) }}
                                                        </span>
                                                    @else
                                                        <span class="transaction-empty-value">—</span>
                                                    @endif
                                                </td>
                                                <td data-label="O.R No.">{{ $entry->permit_or_no ?: '—' }}</td>
                                                <td data-label="Community Tax No.">{{ $entry->community_tax_no ?: '—' }}
                                                </td>
                                                <td data-label="Permit Issued On">{{ $entry->permit_issued_on ?: '—' }}
                                                </td>
                                                <td data-label="Permit Issued At">{{ $entry->permit_issued_at ?: '—' }}
                                                </td>
                                                <td data-label="Permit Date">{{ $entry->permit_date ?: '—' }}</td>
                                                <td data-label="Expires On">{{ $entry->expires_on ?: '—' }}</td>
                                                <td data-label="Doc Stamp Control No.">
                                                    {{ $entry->permit_doc_stamp_control_no ?: '—' }}</td>
                                                <td data-label="Date of Payment">
                                                    {{ $entry->permit_date_of_payment ?: '—' }}
                                                </td>
                                                <td data-label="Status" style="text-transform: uppercase;">
                                                    <span class="badge {{ $entry->approvalStatusClass() }}">
                                                        {{ $entry->approvalStatusLabel() }}
                                                    </span>
                                                </td>
                                                <td class="text-center" data-label="Action">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm dropdown-toggle transaction-action"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                                                style="min-width:170px;">
                                                                @if ($isApplicantUser)
                                                                    <li>
                                                                        <button class="dropdown-item" type="button"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#permitReqModal-{{ $entry->id }}">
                                                                            <i
                                                                                class="bi bi-paperclip me-2 text-info"></i>View Requirements
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li>
                                                                        <button class="dropdown-item js-edit-permit-uploads-btn"
                                                                            type="button"
                                                                            data-permit-id="{{ $entry->id }}"
                                                                            data-nbi="{{ $entry->permit_nbi_clearance ?? '' }}"
                                                                            data-police="{{ $entry->permit_police_clearance ?? '' }}"
                                                                            data-health="{{ $entry->health_card ?? '' }}"
                                                                            data-cedula="{{ $entry->cedula ?? '' }}"
                                                                            data-referral="{{ $entry->referral_letter ?? '' }}"
                                                                            data-clearance-type="{{ $entry->clearance_type ?? '' }}"
                                                                            data-imus-resident="{{ $isImusResident ? '1' : '0' }}">
                                                                            <i class="bi bi-upload me-2 text-warning"></i>Edit Uploads
                                                                        </button>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <button class="dropdown-item" type="button"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#permitReqModal-{{ $entry->id }}">
                                                                            <i
                                                                                class="bi bi-paperclip me-2 text-info"></i>View Requirements
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    @if (auth()->user()->hasPermission('generate_permit') && $entry->isComplete())
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('permits.printId', ['id' => $applicant->id, 'permit' => $entry->id]) }}"
                                                                                target="_blank">
                                                                                <i
                                                                                    class="bi bi-eye me-2 text-success"></i>View Permit to Work ID
                                                                            </a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <span class="dropdown-item-text text-muted"
                                                                                style="opacity:0.5;">
                                                                                <i class="bi bi-eye-slash me-2"></i>View Permit to Work ID
                                                                            </span>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                     @if (auth()->user()->hasPermission('update_permit'))
                                                                        <li>
                                                                            <button class="dropdown-item js-edit-permit-btn"
                                                                                type="button"
                                                                                data-permit-id="{{ $entry->id }}"
                                                                                data-or-no="{{ $entry->permit_or_no ?? '' }}"
                                                                                data-community-tax-no="{{ $entry->community_tax_no ?? '' }}"
                                                                                data-doc-stamp-no="{{ $entry->permit_doc_stamp_control_no ?? '' }}"
                                                                                data-issued-on="{{ $entry->permit_issued_on ?? '' }}"
                                                                                data-issued-at="{{ $entry->permit_issued_at ?? '' }}"
                                                                                data-permit-date="{{ $entry->permit_date ?? '' }}"
                                                                                data-expires-on="{{ $entry->expires_on ?? '' }}"
                                                                                data-date-payment="{{ $entry->permit_date_of_payment ?? '' }}">
                                                                                <i
                                                                                    class="bi bi-pencil-square me-2 text-primary"></i>Edit Details
                                                                            </button>
                                                                        </li>
                                                                        <li>
                                                                            <button class="dropdown-item js-edit-permit-uploads-btn"
                                                                                type="button"
                                                                                data-permit-id="{{ $entry->id }}"
                                                                                data-nbi="{{ $entry->permit_nbi_clearance ?? '' }}"
                                                                                data-police="{{ $entry->permit_police_clearance ?? '' }}"
                                                                                data-health="{{ $entry->health_card ?? '' }}"
                                                                                data-cedula="{{ $entry->cedula ?? '' }}"
                                                                                data-referral="{{ $entry->referral_letter ?? '' }}"
                                                                                data-clearance-type="{{ $entry->clearance_type ?? '' }}"
                                                                                data-imus-resident="{{ $isImusResident ? '1' : '0' }}">
                                                                                <i class="bi bi-upload me-2 text-warning"></i>Edit Uploads
                                                                            </button>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="transaction-empty-state">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>No Permit to Work ID transaction yet.</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="transaction-section-row">
                    <div class="transaction-row-head" data-bs-toggle="collapse" data-bs-target="#clearanceCollapse"
                        role="button" aria-expanded="false">
                        <i class="bi bi-shield-fill-check text-success"></i>
                        <h6>Clearance Letter</h6>
                        <span class="transaction-row-count">{{ $allClearances->count() }}</span>
                        <i class="bi bi-chevron-down ms-auto transaction-chevron"></i>
                    </div>
                    <div id="clearanceCollapse" class="collapse">
                        @if ($allClearances->isNotEmpty())
                            <div class="transaction-table-wrap">
                                <table class="table transaction-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>PESO Control No.</th>
                                            <th>O.R No.</th>
                                            <th>Issued On</th>
                                            <th>Doc Stamp Control No.</th>
                                            <th>Date of Payment</th>
                                            <th>Hired Company</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allClearances as $transactionClearance)
                                            <tr data-transaction-sort="{{ $transactionClearance->id }}">
                                                <td class="transaction-row-index" data-label="#">
                                                    {{ $loop->iteration }}</td>
                                                <td data-label="PESO Control No.">
                                                    <span class="transaction-id-text">
                                                        <i class="fa-solid fa-file-lines"></i>
                                                        PESO-OCMC{{ $transactionClearance->clearance_peso_control_no ?: '—' }}
                                                    </span>
                                                </td>
                                                <td data-label="O.R No.">
                                                    {{ $transactionClearance->clearance_or_no ?: '—' }}
                                                </td>
                                                <td data-label="Issued On">
                                                    {{ $transactionClearance->clearance_issued_on ?: '—' }}</td>
                                                <td data-label="Doc Stamp Control No.">
                                                    {{ $transactionClearance->clearance_doc_stamp_control_no ?: '—' }}
                                                </td>
                                                <td data-label="Date of Payment">
                                                    {{ $transactionClearance->clearance_date_of_payment ?: '—' }}</td>
                                                <td data-label="Hired Company">
                                                    {{ $transactionClearance->clearance_hired_company ?: '—' }}</td>
                                                <td data-label="Status" style="text-transform: uppercase;">
                                                    <span
                                                        class="badge {{ $transactionClearance->approvalStatusClass() }}">
                                                        {{ $transactionClearance->approvalStatusLabel() }}
                                                    </span>
                                                </td>
                                                <td class="text-center" data-label="Action">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm dropdown-toggle transaction-action"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                                                style="min-width:170px;">
                                                                @if ($isApplicantUser)
                                                                    <li>
                                                                        <button class="dropdown-item" type="button"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#clearanceReqModal-{{ $transactionClearance->id }}">
                                                                            <i
                                                                                class="bi bi-paperclip me-2 text-info"></i>View Requirements
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li>
                                                                        <button class="dropdown-item js-edit-clearance-uploads-btn"
                                                                            type="button"
                                                                            data-clearance-id="{{ $transactionClearance->id }}"
                                                                            data-prosecutor="{{ $transactionClearance->prosecutor_clearance ?? '' }}"
                                                                            data-mtc="{{ $transactionClearance->mtc_clearance ?? '' }}"
                                                                            data-rtc="{{ $transactionClearance->rtc_clearance ?? '' }}"
                                                                            data-nbi="{{ $transactionClearance->nbi_clearance ?? '' }}"
                                                                            data-barangay="{{ $transactionClearance->barangay_clearance ?? '' }}">
                                                                            <i class="bi bi-upload me-2 text-warning"></i>Edit Uploads
                                                                        </button>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <button class="dropdown-item" type="button"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#clearanceReqModal-{{ $transactionClearance->id }}">
                                                                            <i class="bi bi-paperclip me-2 text-info"></i>View Requirements
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    @if (auth()->user()->hasPermission('generate_clearance') && $transactionClearance->isComplete())
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('clearances.printLetter', $applicant->id) }}"
                                                                                target="_blank">
                                                                                <i
                                                                                    class="bi bi-eye me-2 text-success"></i>View Clearance Letter
                                                                            </a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <span class="dropdown-item-text text-muted"
                                                                                style="opacity:0.5;">
                                                                                <i class="bi bi-eye-slash me-2"></i>View Clearance Letter
                                                                            </span>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    @if (auth()->user()->hasPermission('update_clearance'))
                                                                        <li>
                                                                            <button
                                                                                class="dropdown-item js-edit-clearance-btn"
                                                                                type="button"
                                                                                data-clearance-id="{{ $transactionClearance->id }}"
                                                                                data-or-no="{{ $transactionClearance->clearance_or_no ?? '' }}"
                                                                                data-issued-on="{{ $transactionClearance->clearance_issued_on ?? '' }}"
                                                                                data-doc-stamp-no="{{ $transactionClearance->clearance_doc_stamp_control_no ?? '' }}"
                                                                                data-date-payment="{{ $transactionClearance->clearance_date_of_payment ?? '' }}"
                                                                                data-hired-company="{{ $transactionClearance->clearance_hired_company ?? '' }}">
                                                                                <i
                                                                                    class="bi bi-pencil-square me-2 text-primary"></i>Edit Details
                                                                            </button>
                                                                        </li>
                                                                        <li>
                                                                            <button class="dropdown-item js-edit-clearance-uploads-btn"
                                                                                type="button"
                                                                                data-clearance-id="{{ $transactionClearance->id }}"
                                                                                data-prosecutor="{{ $transactionClearance->prosecutor_clearance ?? '' }}"
                                                                                data-mtc="{{ $transactionClearance->mtc_clearance ?? '' }}"
                                                                                data-rtc="{{ $transactionClearance->rtc_clearance ?? '' }}"
                                                                                data-nbi="{{ $transactionClearance->nbi_clearance ?? '' }}"
                                                                                data-barangay="{{ $transactionClearance->barangay_clearance ?? '' }}">
                                                                                <i class="bi bi-upload me-2 text-warning"></i>Edit Uploads
                                                                            </button>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="transaction-empty-state">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>No Clearance Letter transaction yet.</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="transaction-section-row">
                    <div class="transaction-row-head" data-bs-toggle="collapse" data-bs-target="#referralCollapse"
                        role="button" aria-expanded="false">
                        <i class="bi bi-send-fill text-warning"></i>
                        <h6>Referral Letter</h6>
                        <span class="transaction-row-count">{{ $transactionReferralCount }}</span>
                        <i class="bi bi-chevron-down ms-auto transaction-chevron"></i>
                    </div>
                    <div id="referralCollapse" class="collapse">
                        @if ($allReferralRows->isNotEmpty())
                            @php
                                $referralTransactionRow = 1;
                            @endphp
                            <div class="transaction-table-wrap">
                                <table class="table transaction-table">
                                    <thead style="font-weight: 700">
                                        <tr>
                                            <th>#</th>
                                            <th>Referral Type</th>
                                            <th>Peso OCRL / Peso Imus OCRL</th>
                                            <th>Employer / Recipient</th>
                                            <th>Position / City Gov</th>
                                            <th>City Address / Company Address</th>
                                            <th>Province</th>
                                            <th>Hired Company</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allReferralRows as $refRow)
                                            <tr data-transaction-sort="{{ $refRow['sort_control'] }}">
                                                <td class="transaction-row-index" data-label="#">
                                                    {{ $referralTransactionRow++ }}</td>
                                                <td data-label="Referral Type">{{ $refRow['type'] }}</td>
                                                <td data-label="Peso OCRL">
                                                    <span class="transaction-id-text">
                                                        <i
                                                            class="fa-solid fa-file-export"></i>{{ $refRow['control_no'] }}
                                                    </span>
                                                </td>
                                                <td data-label="Employer / Recipient"
                                                    style="text-transform: uppercase;">
                                                    {{ $refRow['employer_recipient'] ?: '—' }}</td>
                                                <td data-label="Position / City Gov" style="text-transform: uppercase;">
                                                    {{ $refRow['position_city_gov'] ?: '—' }}</td>
                                                <td data-label="Address" style="text-transform: uppercase;">
                                                    {{ $refRow['address'] ?: '—' }}</td>
                                                <td data-label="Province">{{ $refRow['province'] ?: '—' }}</td>
                                                <td data-label="Hired Company">{{ $refRow['hired_company'] ?: '—' }}
                                                </td>
                                                <td data-label="Status" style="text-transform: uppercase;">
                                                    <span class="badge {{ $refRow['status_class'] }}">
                                                        {{ $refRow['status_label'] }}
                                                    </span>
                                                </td>
                                                <td class="text-center" data-label="Action">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm dropdown-toggle transaction-action"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                                                style="min-width:170px;">
                                                                @if ($isApplicantUser)
                                                                    <li>
                                                                        <button class="dropdown-item" type="button"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#referralReqModal-{{ $refRow['referral_id'] }}">
                                                                            <i
                                                                                class="bi bi-paperclip me-2 text-info"></i>View Requirements
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li>
                                                                        <button class="dropdown-item js-edit-referral-uploads-btn"
                                                                            type="button"
                                                                            data-referral-id="{{ $refRow['referral_id'] }}"
                                                                            data-resume="{{ $refRow['resume'] ?? '' }}"
                                                                            data-brgy="{{ $refRow['brgy'] ?? '' }}"
                                                                            data-police="{{ $refRow['police'] ?? '' }}"
                                                                            data-nbi="{{ $refRow['nbi'] ?? '' }}">
                                                                            <i class="bi bi-upload me-2 text-warning"></i>Edit Uploads
                                                                        </button>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <button class="dropdown-item" type="button"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#referralReqModal-{{ $refRow['referral_id'] }}">
                                                                            <i
                                                                                class="bi bi-paperclip me-2 text-info"></i>View Requirements
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    @if (auth()->user()->canViewReferralLetter() && $refRow['can_view'])
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                href="{{ $refRow['route'] }}"
                                                                                target="_blank">
                                                                                <i
                                                                                    class="bi bi-eye me-2 text-success"></i>View Referral Letter
                                                                            </a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <span class="dropdown-item-text text-muted"
                                                                                style="opacity:0.5;">
                                                                                <i class="bi bi-eye-slash me-2"></i>View Referral Letter
                                                                            </span>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    @if (auth()->user()->hasPermission('update_referral'))
                                                                        <li>
                                                                            <button class="dropdown-item js-edit-referral-btn"
                                                                                type="button"
                                                                                data-referral-id="{{ $refRow['referral_id'] }}"
                                                                                @foreach ($refRow['edit_data'] as $editKey => $editVal)
                                                                                data-{{ $editKey }}="{{ $editVal }}" @endforeach>
                                                                                <i
                                                                                    class="bi bi-pencil-square me-2 text-primary"></i>Edit Details
                                                                            </button>
                                                                        </li>
                                                                        <li>
                                                                            <button class="dropdown-item js-edit-referral-uploads-btn"
                                                                                type="button"
                                                                                data-referral-id="{{ $refRow['referral_id'] }}"
                                                                                data-resume="{{ $refRow['resume'] ?? '' }}"
                                                                                data-brgy="{{ $refRow['brgy'] ?? '' }}"
                                                                                data-police="{{ $refRow['police'] ?? '' }}"
                                                                                data-nbi="{{ $refRow['nbi'] ?? '' }}">
                                                                                <i class="bi bi-upload me-2 text-warning"></i>Edit Uploads
                                                                            </button>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="transaction-empty-state">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>No Referral Letter transaction yet.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
    </div>

    @foreach ($allPermits as $permitReq)
        <div class="modal fade" id="permitReqModal-{{ $permitReq->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header" style="background:#d3d3d3; color: #2c2c2c;">
                        <div>
                            <h5 class="modal-title mb-1"><i class="bi bi-paperclip me-2"></i>Permit Requirements</h5>
                            <div class="small" style="opacity:0.85;">
                                {{ 'OP' . strtoupper($permitReq->peso_id_no ?? '—') }}</div>
                            <div class="small" style="opacity:0.7;"><i class="bi bi-date me-1"></i>Uploaded:
                                {{ $permitReq->created_at ? $permitReq->created_at->format('M d, Y h:i A') : '—' }}
                                <span class="text-muted">({{ $permitReq->created_at->diffForHumans() }})</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="filter: brightness(0) invert(1); opacity: 0.7;"></button>
                    </div>
                    <div class="modal-body">
                        <div class="list-group list-group-flush">
                            @php
                                $permitFiles = [
                                    'Health Card' => $permitReq->health_card,
                                    'NBI Clearance' => $permitReq->permit_nbi_clearance,
                                    'Police Clearance' => $permitReq->permit_police_clearance,
                                    'Cedula' => $permitReq->cedula,
                                    'Referral Letter' => $permitReq->referral_letter,
                                ];
                            @endphp
                            @foreach ($permitFiles as $label => $file)
                                <div class="list-group-item d-flex align-items-center justify-content-between py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            style="width:36px;height:36px;border-radius:10px;background:{{ $file ? 'linear-gradient(135deg,#d1fae5,#a7f3d0)' : '#f1f5f9' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i
                                                class="bi {{ $file ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="font-size:0.9rem;">{{ $label }}</div>
                                            <div class="text-muted" style="font-size:0.78rem;">
                                                {{ $file ? basename($file) : 'Not uploaded' }}</div>
                                        </div>
                                    </div>
                                    @if ($file)
                                        <a href="{{ route('storage.view', ['filename' => $file]) }}" target="_blank"
                                            class="btn btn-sm btn-outline-success rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($allClearances as $clearanceReq)
        <div class="modal fade" id="clearanceReqModal-{{ $clearanceReq->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header" style="background:#d3d3d3; color: #2c2c2c;">
                        <div>
                            <h5 class="modal-title mb-1"><i class="bi bi-paperclip me-2"></i>Clearance Requirements</h5>
                            <div class="small" style="opacity:0.85;">
                                PESO-OCMC{{ $clearanceReq->clearance_peso_control_no ?? '—' }}</div>
                            <div class="small" style="opacity:0.7;"><i class="bi bi-clock me-1"></i>Uploaded:
                                {{ $clearanceReq->created_at ? $clearanceReq->created_at->format('M d, Y h:i A') : '—' }}
                                <span class="text-muted">({{ $clearanceReq->created_at->diffForHumans() }})</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="filter: brightness(0) invert(1); opacity: 0.7;"></button>
                    </div>
                    <div class="modal-body">
                        <div class="list-group list-group-flush">
                            @php
                                $clearanceFiles = [
                                    'Prosecutor Clearance' => $clearanceReq->prosecutor_clearance,
                                    'MTC Clearance' => $clearanceReq->mtc_clearance,
                                    'RTC Clearance' => $clearanceReq->rtc_clearance,
                                    'NBI Clearance' => $clearanceReq->nbi_clearance,
                                    'Barangay Clearance' => $clearanceReq->barangay_clearance,
                                ];
                            @endphp
                            @foreach ($clearanceFiles as $label => $file)
                                <div class="list-group-item d-flex align-items-center justify-content-between py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            style="width:36px;height:36px;border-radius:10px;background:{{ $file ? 'linear-gradient(135deg,#d1fae5,#a7f3d0)' : '#f1f5f9' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i
                                                class="bi {{ $file ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="font-size:0.9rem;">{{ $label }}</div>
                                            <div class="text-muted" style="font-size:0.78rem;">
                                                {{ $file ? basename($file) : 'Not uploaded' }}</div>
                                        </div>
                                    </div>
                                    @if ($file)
                                        <a href="{{ route('storage.view', ['filename' => $file]) }}" target="_blank"
                                            class="btn btn-sm btn-outline-success rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($allReferrals as $referralReq)
        <div class="modal fade" id="referralReqModal-{{ $referralReq->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header" style="background:#d3d3d3; color: #2c2c2c;">
                        <div>
                            <h5 class="modal-title mb-1"><i class="bi bi-paperclip me-2"></i>Referral Requirements</h5>
                            <div class="small" style="opacity:0.85;">
                                {{ $referralReq->referral_type === 'peso_office' ? 'Within Imus' : 'Outside Imus' }}</div>
                            <div class="small" style="opacity:0.7;"><i class="bi bi-clock me-1"></i>Uploaded:
                                {{ $referralReq->created_at ? $referralReq->created_at->format('M d, Y h:i A') : '—' }}
                                <span class="text-muted">({{ $referralReq->created_at->diffForHumans() }})</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="filter: brightness(0) invert(1); opacity: 0.7;"></button>
                    </div>
                    <div class="modal-body">
                        <div class="list-group list-group-flush">
                            @php
                                $referralFiles = [
                                    'Resume / Bio-data' => $referralReq->resume,
                                    'Barangay Clearance' => $referralReq->ref_barangay_clearance,
                                    'Police Clearance' => $referralReq->ref_police_clearance,
                                    'NBI Clearance' => $referralReq->ref_nbi_clearance,
                                ];
                            @endphp
                            @foreach ($referralFiles as $label => $file)
                                <div class="list-group-item d-flex align-items-center justify-content-between py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            style="width:36px;height:36px;border-radius:10px;background:{{ $file ? 'linear-gradient(135deg,#d1fae5,#a7f3d0)' : '#f1f5f9' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i
                                                class="bi {{ $file ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="font-size:0.9rem;">{{ $label }}</div>
                                            <div class="text-muted" style="font-size:0.78rem;">
                                                {{ $file ? basename($file) : 'Not uploaded' }}</div>
                                        </div>
                                    </div>
                                    @if ($file)
                                        <a href="{{ route('storage.view', ['filename' => $file]) }}" target="_blank"
                                            class="btn btn-sm btn-outline-success rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="disapprovePermitModal-{{ $applicant->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('permits.disapprove', $applicant->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">Disapprove Permit</h5>
                            <div class="text-muted small">{{ auth()->user()->name ?? $fullName }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-3">Please provide the reason for disapproving this requirement.</p>
                        @if ($errors->any())
                            <div class="alert alert-danger border-0">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                            <textarea name="disapproval_reason" class="form-control" rows="4" autofocus required>{{ old('disapproval_reason', $permit->disapproval_reason ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fa-solid fa-circle-xmark me-1"></i>Confirm Disapprove
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="clearance-approve-form-{{ $applicant->id }}"
        action="{{ route('clearances.approve', $applicant->id) }}" method="POST" class="d-none">
        @csrf
        @method('PUT')
    </form>

    <div class="modal fade" id="disapproveClearanceModal-{{ $applicant->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('clearances.disapprove', $applicant->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">Disapprove Clearance</h5>
                            <div class="text-muted small">{{ auth()->user()->name ?? $fullName }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-3">Please provide the reason for disapproving this requirement.</p>
                        @if ($errors->any())
                            <div class="alert alert-danger border-0">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                            <textarea name="disapproval_reason" class="form-control" rows="4" autofocus required>{{ old('disapproval_reason') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fa-solid fa-circle-xmark me-1"></i>Confirm Disapprove
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="referral-approve-form-{{ $applicant->id }}" action="{{ route('referrals.approve', $applicant->id) }}"
        method="POST" class="d-none">
        @csrf
        @method('PUT')
    </form>

    <div class="modal fade" id="disapproveReferralModal-{{ $applicant->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('referrals.disapprove', $applicant->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">Disapprove Referral</h5>
                            <div class="text-muted small">{{ auth()->user()->name ?? $fullName }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-3">Please provide the reason for disapproving this requirement.</p>
                        @if ($errors->any())
                            <div class="alert alert-danger border-0">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                            <textarea name="disapproval_reason" class="form-control" rows="4" autofocus required>{{ old('disapproval_reason') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fa-solid fa-circle-xmark me-1"></i>Confirm Disapprove
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPermitDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form id="editPermitDetailsForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background:#d3d3d3; color: #2c2c2c;">
                        <div>
                            <h5 class="modal-title mb-1"><i class="bi bi-pencil-square me-2"></i>Edit Permit to Work ID
                                Details</h5>
                            <div class="small" style="opacity:0.85;">Update the transaction details for this permit
                                record</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="color: #2c2c2c;"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">O.R No. <span class="text-danger">*</span></label>
                                <input type="text" name="permit_or_no" id="modal_permit_or_no"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Community Tax No. <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="community_tax_no" id="modal_community_tax_no"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Permit Issued On <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="permit_issued_on" id="modal_permit_issued_on"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Permit Issued At <span
                                        class="text-danger">*</span></label>
                                <select name="permit_issued_at" id="modal_permit_issued_at" class="form-select"
                                    required>
                                    <option value="">Select City</option>
                                    @foreach (config('permit_issued_at.city_governments', []) as $city)
                                        <option value="{{ $city }}">{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Permit Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="permit_date" id="modal_permit_date" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Expires On <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="expires_on" id="modal_expires_on" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Doc Stamp Control No. <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="permit_doc_stamp_control_no"
                                    id="modal_permit_doc_stamp_control_no" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date of Payment <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="permit_date_of_payment" id="modal_permit_date_of_payment"
                                    class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="btnSavePermitDetails">
                            <i class="bi bi-check-circle-fill me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editClearanceDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form id="editClearanceDetailsForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background:#d3d3d3; color: #2c2c2c;">
                        <div>
                            <h5 class="modal-title mb-1"><i class="bi bi-pencil-square me-2"></i>Edit Clearance Letter
                                Details</h5>
                            <div class="small" style="opacity:0.85;">Update the clearance transaction details</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="color: #2c2c2c;"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">O.R. No. <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="clearance_or_no" id="modal_clearance_or_no"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Issued On <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="clearance_issued_on" id="modal_clearance_issued_on"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Doc Stamp Control No. <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="clearance_doc_stamp_control_no"
                                    id="modal_clearance_doc_stamp_control_no" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date of Payment <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="clearance_date_of_payment"
                                    id="modal_clearance_date_of_payment" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Hired Company <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="clearance_hired_company"
                                    id="modal_clearance_hired_company" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle-fill me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editReferralDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form id="editReferralDetailsForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="referral_type" id="modal_referral_type" value="peso_office">
                    <input type="hidden" name="detail_index" id="modal_referral_detail_index" value="">
                    <div class="modal-header" style="background:#d3d3d3; color: #2c2c2c;">
                        <div>
                            <h5 class="modal-title mb-1"><i class="bi bi-pencil-square me-2"></i>Edit Referral Letter
                                Details</h5>
                            <div class="small" style="opacity:0.85;" id="modalReferralTypeLabel">Referral Within Imus
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="color: #2c2c2c;"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modalPesoOfficeFields">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Employer Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="ref_employer_name" id="modal_ref_employer_name"
                                        class="form-control text-uppercase" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Position <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="ref_position" id="modal_ref_position"
                                        class="form-control text-uppercase" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">City Address <span
                                            class="text-danger">*</span></label>
                                    <select name="ref_place" id="modal_ref_place"
                                        class="form-select" required>
                                        <option value="">Select City Address</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Province <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="ref_province" id="modal_ref_province"
                                        class="form-control text-uppercase" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Hired Company <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="ref_hired_company" id="modal_ref_hired_company"
                                        class="form-control text-uppercase" required>
                                </div>
                            </div>
                        </div>
                        <div id="modalOtherCityFields" style="display:none;">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Mayor's Name <span
                                            class="text-danger">*</span></label>
                                    <select name="ref_recipient" id="modal_ref_recipient" class="form-select"
                                        required>
                                        <option value="">Select City Mayor</option>
                                        @foreach (config('philippine_mayors', []) as $mayor)
                                            <option value="{{ $mayor['recipient'] }}"
                                                data-city-government="{{ $mayor['city_government'] }}"
                                                data-company-address="{{ $mayor['company_address'] }}">
                                                {{ $mayor['recipient'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">City Government <span
                                            class="text-danger">*</span></label>
                                    <select name="ref_city_gov" id="modal_ref_city_gov" class="form-select" required>
                                        <option value="">Select City Government</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">City Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="ref_company_address" id="modal_ref_company_address"
                                        class="form-control text-uppercase" list="modalRefCompanyAddressList"
                                        autocomplete="off" required>
                                    <datalist id="modalRefCompanyAddressList"></datalist>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle-fill me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPermitUploadsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form id="editPermitUploadsForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background:#d3d3d3; color: #2c2c2c;">
                        <div>
                            <h5 class="modal-title mb-1"><i class="bi bi-upload me-2"></i>Edit Permit Uploads</h5>
                            <div class="small" style="opacity:0.85;">Replace uploaded files for this permit record</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="color: #2c2c2c;"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="section-title text-primary mb-1">Mayor’s Permit to Work Requirements</h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Clearance Type</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="clearance_type"
                                        id="modal_permit_ct_nbi" value="nbi" checked>
                                    <label class="form-check-label" for="modal_permit_ct_nbi">NBI</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="clearance_type"
                                        id="modal_permit_ct_police" value="police">
                                    <label class="form-check-label" for="modal_permit_ct_police">Police</label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6" id="modal_permit_nbi_group">
                                <div class="document-upload-card">
                                    <label class="form-label">NBI Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="permit_nbi_clearance" class="d-none"
                                            id="modal_permit_nbi_file"
                                            onchange="showFileName(this, 'modal_permit_nbi_name')">
                                        <label for="modal_permit_nbi_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_permit_nbi_name" class="file-name-text">
                                            {{ old('modal_permit_nbi_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_permit_nbi_status"></div>
                                </div>
                            </div>
                            <div class="col-md-6" id="modal_permit_police_group">
                                <div class="document-upload-card">
                                    <label class="form-label">Police Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="permit_police_clearance" class="d-none"
                                            id="modal_permit_police_file"
                                            onchange="showFileName(this, 'modal_permit_police_name')">
                                        <label for="modal_permit_police_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_permit_police_name" class="file-name-text">
                                            {{ old('modal_permit_police_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_permit_police_status"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="document-upload-card">
                                    <label class="form-label">Health Card<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="health_card" class="d-none"
                                            id="modal_permit_health_file"
                                            onchange="showFileName(this, 'modal_permit_health_name')">
                                        <label for="modal_permit_health_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_permit_health_name" class="file-name-text">
                                            {{ old('modal_permit_health_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_permit_health_status"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="document-upload-card">
                                    <label class="form-label">Cedula<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="cedula" class="d-none"
                                            id="modal_permit_cedula_file"
                                            onchange="showFileName(this, 'modal_permit_cedula_name')">
                                        <label for="modal_permit_cedula_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_permit_cedula_name" class="file-name-text">
                                            {{ old('modal_permit_cedula_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_permit_cedula_status"></div>
                                </div>
                            </div>
                            <div class="col-md-6" id="modal_permit_referral_group">
                                <div class="document-upload-card" id="modal_permit_referral_card">
                                    <label class="form-label">Referral Letter<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="referral_letter" class="d-none"
                                            id="modal_permit_referral_file"
                                            onchange="showFileName(this, 'modal_permit_referral_name')">
                                        <label for="modal_permit_referral_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_permit_referral_name" class="file-name-text">
                                            {{ old('modal_permit_referral_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_permit_referral_status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle-fill me-1"></i>Save Uploads
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editClearanceUploadsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form id="editClearanceUploadsForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background:#d3d3d3; color: #2c2c2c;">
                        <div>
                            <h5 class="modal-title mb-1"><i class="bi bi-upload me-2"></i>Edit Clearance Uploads</h5>
                            <div class="small" style="opacity:0.85;">Replace uploaded files for this clearance record</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="color: #2c2c2c;"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="section-title text-primary mb-1">Mayor's Clearance Requirements</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="document-upload-card">
                                    <label class="form-label">Prosecutor Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="prosecutor_clearance" class="d-none"
                                            id="modal_cl_prosecutor_file"
                                            onchange="showFileName(this, 'modal_cl_prosecutor_name')">
                                        <label for="modal_cl_prosecutor_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_cl_prosecutor_name" class="file-name-text">
                                            {{ old('modal_cl_prosecutor_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_cl_prosecutor_status"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="document-upload-card">
                                    <label class="form-label">MTC Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="mtc_clearance" class="d-none"
                                            id="modal_cl_mtc_file"
                                            onchange="showFileName(this, 'modal_cl_mtc_name')">
                                        <label for="modal_cl_mtc_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_cl_mtc_name" class="file-name-text">
                                            {{ old('modal_cl_mtc_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_cl_mtc_status"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="document-upload-card">
                                    <label class="form-label">RTC Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="rtc_clearance" class="d-none"
                                            id="modal_cl_rtc_file"
                                            onchange="showFileName(this, 'modal_cl_rtc_name')">
                                        <label for="modal_cl_rtc_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_cl_rtc_name" class="file-name-text">
                                            {{ old('modal_cl_rtc_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_cl_rtc_status"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="document-upload-card">
                                    <label class="form-label">NBI Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="nbi_clearance" class="d-none"
                                            id="modal_cl_nbi_file"
                                            onchange="showFileName(this, 'modal_cl_nbi_name')">
                                        <label for="modal_cl_nbi_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_cl_nbi_name" class="file-name-text">
                                            {{ old('modal_cl_nbi_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_cl_nbi_status"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="document-upload-card">
                                    <label class="form-label">Barangay Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="barangay_clearance" class="d-none"
                                            id="modal_cl_brgy_file"
                                            onchange="showFileName(this, 'modal_cl_brgy_name')">
                                        <label for="modal_cl_brgy_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_cl_brgy_name" class="file-name-text">
                                            {{ old('modal_cl_brgy_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_cl_brgy_status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle-fill me-1"></i>Save Uploads
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editReferralUploadsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form id="editReferralUploadsForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background:#d3d3d3; color: #2c2c2c;">
                        <div>
                            <h5 class="modal-title mb-1"><i class="bi bi-upload me-2"></i>Edit Referral Uploads</h5>
                            <div class="small" style="opacity:0.85;">Replace uploaded files for this referral record</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="color: #2c2c2c;"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="section-title text-primary mb-1">Mayor's Referral Requirements</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="document-upload-card-resume">
                                    <label class="form-label">Resume</label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="resume" class="d-none"
                                            id="modal_ref_resume_file"
                                            onchange="showFileName(this, 'modal_ref_resume_name')">
                                        <label for="modal_ref_resume_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_ref_resume_name" class="file-name-text">
                                            {{ old('modal_ref_resume_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_ref_resume_status"></div>
                                </div>
                            </div>
                            <h4 class="section-title-d text-primary">Choose at least one of the following:</h4>
                            <div class="col-md-4">
                                <div class="document-upload-card">
                                    <label class="form-label">Barangay Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="ref_barangay_clearance" class="d-none"
                                            id="modal_ref_brgy_file"
                                            onchange="showFileName(this, 'modal_ref_brgy_name')">
                                        <label for="modal_ref_brgy_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_ref_brgy_name" class="file-name-text">
                                            {{ old('modal_ref_brgy_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_ref_brgy_status"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="document-upload-card">
                                    <label class="form-label">Police Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="ref_police_clearance" class="d-none"
                                            id="modal_ref_police_file"
                                            onchange="showFileName(this, 'modal_ref_police_name')">
                                        <label for="modal_ref_police_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_ref_police_name" class="file-name-text">
                                            {{ old('modal_ref_police_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_ref_police_status"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="document-upload-card">
                                    <label class="form-label">NBI Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" name="ref_nbi_clearance" class="d-none"
                                            id="modal_ref_nbi_file"
                                            onchange="showFileName(this, 'modal_ref_nbi_name')">
                                        <label for="modal_ref_nbi_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </label>
                                        <small id="modal_ref_nbi_name" class="file-name-text">
                                            {{ old('modal_ref_nbi_name', 'No file selected') }}
                                        </small>
                                    </div>
                                    <div class="small mt-2" id="modal_ref_nbi_status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle-fill me-1"></i>Save Uploads
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const referralTypeSelect = document.getElementById("referralTypeSelect");
        const pesoOfficeFields = document.getElementById("pesoOfficeFields");
        const otherCityFields = document.getElementById("otherCityFields");
        const addPesoDetailButton = document.getElementById("addPesoDetailButton");
        const pesoDetailTemplate = document.getElementById("pesoDetailTemplate");
        const pesoExtraDetails = pesoOfficeFields ? pesoOfficeFields.querySelector(".js-peso-extra-details") :
            null;
        const referralForm = referralTypeSelect ? referralTypeSelect.closest("form") : null;
        let nextPesoDetailIndex = pesoExtraDetails ? pesoExtraDetails.querySelectorAll(".js-peso-extra-detail")
            .length : 0;

        const activateTabFromHash = (hash) => {
            if (window.location.hash !== hash) {
                return;
            }

            const tabTrigger = document.querySelector(`[data-bs-target="${hash}"]`);

            if (tabTrigger && window.bootstrap && window.bootstrap.Tab) {
                window.bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
            }
        };

        activateTabFromHash("#referral");
        activateTabFromHash("#clearance");
        activateTabFromHash("#permit");

        document.querySelectorAll('.js-edit-permit-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var d = btn.dataset;
                document.getElementById('editPermitDetailsForm').action =
                    '{{ url('permits') }}/' + d.permitId + '/details';
                document.getElementById('modal_permit_or_no').value = d.orNo;
                document.getElementById('modal_community_tax_no').value = d.communityTaxNo;
                document.getElementById('modal_permit_doc_stamp_control_no').value = d
                    .docStampNo;
                document.getElementById('modal_permit_issued_on').value = d.issuedOn;
                document.getElementById('modal_permit_issued_at').value = d.issuedAt;
                document.getElementById('modal_permit_date').value = d.permitDate;
                document.getElementById('modal_expires_on').value = d.expiresOn;
                document.getElementById('modal_permit_date_of_payment').value = d.datePayment;
                var modal = new bootstrap.Modal(document.getElementById(
                    'editPermitDetailsModal'));
                modal.show();
            });
        });

        document.querySelectorAll('.js-edit-clearance-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var d = btn.dataset;
                document.getElementById('editClearanceDetailsForm').action =
                    '{{ url('clearances') }}/' + d.clearanceId + '/details';
                document.getElementById('modal_clearance_or_no').value = d.orNo;
                document.getElementById('modal_clearance_issued_on').value = d.issuedOn;
                document.getElementById('modal_clearance_doc_stamp_control_no').value = d
                    .docStampNo;
                document.getElementById('modal_clearance_date_of_payment').value = d
                    .datePayment;
                document.getElementById('modal_clearance_hired_company').value = d.hiredCompany;
                var modal = new bootstrap.Modal(document.getElementById(
                    'editClearanceDetailsModal'));
                modal.show();
            });
        });

        document.querySelectorAll('.js-edit-referral-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var d = btn.dataset;
                var type = d.type;
                document.getElementById('editReferralDetailsForm').action =
                    '{{ url('referrals') }}/' + d.referralId + '/details';
                document.getElementById('modal_referral_type').value = type;
                document.getElementById('modal_referral_detail_index').value = d.detailIndex ||
                    '';
                var pesoFields = document.getElementById('modalPesoOfficeFields');
                var otherFields = document.getElementById('modalOtherCityFields');
                var typeLabel = document.getElementById('modalReferralTypeLabel');
                var setModalReferralGroupState = function(activeGroup) {
                    [pesoFields, otherFields].forEach(function(group) {
                        if (!group) {
                            return;
                        }

                        var isActive = group === activeGroup;
                        group.querySelectorAll('input, select, textarea').forEach(
                            function(field) {
                                field.disabled = !isActive;
                                field.required = isActive && field.hasAttribute(
                                    'data-required');
                            });
                    });
                };

                [pesoFields, otherFields].forEach(function(group) {
                    if (!group) {
                        return;
                    }

                    group.querySelectorAll('[required]').forEach(function(field) {
                        field.dataset.required = 'true';
                    });
                });

                if (type === 'peso_office') {
                    pesoFields.style.display = '';
                    otherFields.style.display = 'none';
                    typeLabel.textContent = 'Referral Within Imus';
                    document.getElementById('modal_ref_employer_name').value = d.employerName;
                    document.getElementById('modal_ref_position').value = d.position;
                    document.getElementById('modal_ref_province').value = d.province;
                    document.getElementById('modal_ref_hired_company').value = d.hiredCompany;
                    ensurePsgcCityData().then(function(cities) {
                        var modalSelect = document.getElementById('modal_ref_place');
                        populateCityAddressOptions(modalSelect, cities, d.place || '');
                    });
                    setModalReferralGroupState(pesoFields);
                } else {
                    pesoFields.style.display = 'none';
                    otherFields.style.display = '';
                    typeLabel.textContent = 'Referral Outside Imus';
                    setModalReferralGroupState(otherFields);
                    if (typeof populateModalOtherCityReferralFields === 'function') {
                        populateModalOtherCityReferralFields(d.recipient, d.cityGov, d
                            .companyAddress);
                    }
                }
                var modal = new bootstrap.Modal(document.getElementById(
                    'editReferralDetailsModal'));
                modal.show();
            });
        });

        function getFileName(path) {
            if (!path) return 'No file uploaded';
            return path.split('/').pop();
        }

        document.querySelectorAll('.js-edit-permit-uploads-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var d = btn.dataset;
                document.getElementById('editPermitUploadsForm').action =
                    '{{ url('permits') }}/' + d.permitId + '/files';
                var ct = d.clearanceType || 'nbi';
                document.getElementById('modal_permit_ct_nbi').checked = ct === 'nbi';
                document.getElementById('modal_permit_ct_police').checked = ct === 'police';
                document.getElementById('modal_permit_nbi_group').style.display = ct === 'nbi' ? '' : 'none';
                document.getElementById('modal_permit_police_group').style.display = ct === 'police' ? '' : 'none';
                document.getElementById('modal_permit_nbi_name').textContent = 'No file selected';
                document.getElementById('modal_permit_police_name').textContent = 'No file selected';
                document.getElementById('modal_permit_health_name').textContent = 'No file selected';
                document.getElementById('modal_permit_cedula_name').textContent = 'No file selected';
                document.getElementById('modal_permit_referral_name').textContent = 'No file selected';
                document.getElementById('modal_permit_nbi_status').innerHTML = d.nbi
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.nbi)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_permit_police_status').innerHTML = d.police
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.police)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_permit_health_status').innerHTML = d.health
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.health)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_permit_cedula_status').innerHTML = d.cedula
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.cedula)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_permit_referral_status').innerHTML = d.referral
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.referral)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                var radios = document.querySelectorAll('input[name="clearance_type"]');
                radios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        document.getElementById('modal_permit_nbi_group').style.display =
                            this.value === 'nbi' ? '' : 'none';
                        document.getElementById('modal_permit_police_group').style.display =
                            this.value === 'police' ? '' : 'none';
                    });
                });
                document.getElementById('editPermitUploadsModal').querySelectorAll('input[type="file"]').forEach(function(f) {
                    f.value = '';
                });
                var isImus = d.imusResident === '1';
                var referralCard = document.getElementById('modal_permit_referral_card');
                var referralFile = document.getElementById('modal_permit_referral_file');
                var referralLabel = document.querySelector('label[for="modal_permit_referral_file"]');
                var referralStatus = document.getElementById('modal_permit_referral_status');
                if (isImus) {
                    referralCard.classList.add('upload-disabled');
                    referralFile.disabled = true;
                    referralFile.value = '';
                    if (referralLabel) referralLabel.classList.add('d-none');
                    referralStatus.innerHTML = '<i class="bi bi-info-circle text-muted me-1"></i>Not required for Imus residents';
                } else {
                    referralCard.classList.remove('upload-disabled');
                    referralFile.disabled = false;
                    if (referralLabel) referralLabel.classList.remove('d-none');
                }
                new bootstrap.Modal(document.getElementById('editPermitUploadsModal')).show();
            });
        });

        document.querySelectorAll('.js-edit-clearance-uploads-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var d = btn.dataset;
                document.getElementById('editClearanceUploadsForm').action =
                    '{{ url('clearances') }}/' + d.clearanceId + '/files';
                document.getElementById('modal_cl_prosecutor_name').textContent = 'No file selected';
                document.getElementById('modal_cl_mtc_name').textContent = 'No file selected';
                document.getElementById('modal_cl_rtc_name').textContent = 'No file selected';
                document.getElementById('modal_cl_nbi_name').textContent = 'No file selected';
                document.getElementById('modal_cl_brgy_name').textContent = 'No file selected';
                document.getElementById('modal_cl_prosecutor_status').innerHTML = d.prosecutor
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.prosecutor)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_cl_mtc_status').innerHTML = d.mtc
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.mtc)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_cl_rtc_status').innerHTML = d.rtc
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.rtc)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_cl_nbi_status').innerHTML = d.nbi
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.nbi)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_cl_brgy_status').innerHTML = d.barangay
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.barangay)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('editClearanceUploadsModal').querySelectorAll('input[type="file"]').forEach(function(f) {
                    f.value = '';
                });
                new bootstrap.Modal(document.getElementById('editClearanceUploadsModal')).show();
            });
        });

        document.querySelectorAll('.js-edit-referral-uploads-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var d = btn.dataset;
                document.getElementById('editReferralUploadsForm').action =
                    '{{ url('referrals') }}/' + d.referralId + '/files';
                document.getElementById('modal_ref_resume_name').textContent = 'No file selected';
                document.getElementById('modal_ref_brgy_name').textContent = 'No file selected';
                document.getElementById('modal_ref_police_name').textContent = 'No file selected';
                document.getElementById('modal_ref_nbi_name').textContent = 'No file selected';
                document.getElementById('modal_ref_resume_status').innerHTML = d.resume
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.resume)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_ref_brgy_status').innerHTML = d.brgy
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.brgy)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_ref_police_status').innerHTML = d.police
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.police)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('modal_ref_nbi_status').innerHTML = d.nbi
                    ? '<i class="bi bi-check-circle-fill text-success me-1"></i>' + getFileName(d.nbi)
                    : '<i class="bi bi-x-circle text-muted me-1"></i>No file uploaded';
                document.getElementById('editReferralUploadsModal').querySelectorAll('input[type="file"]').forEach(function(f) {
                    f.value = '';
                });
                new bootstrap.Modal(document.getElementById('editReferralUploadsModal')).show();
            });
        });

        if (referralTypeSelect && pesoOfficeFields && otherCityFields) {
            const setGroupDisabledState = (container, shouldDisable) => {
                container.querySelectorAll("input, select, textarea").forEach(field => {
                    const forceDisabled = field.dataset.forceDisabled === "true";
                    field.disabled = shouldDisable || forceDisabled;

                    if (forceDisabled) {
                        field.setAttribute("readonly", "readonly");
                    }
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

                setGroupDisabledState(pesoOfficeFields, !isPesoOffice);
                setGroupDisabledState(otherCityFields, !isOtherCity);

                if (addPesoDetailButton) {
                    addPesoDetailButton.classList.toggle("d-none", !isPesoOffice);
                }
            };

            toggleReferralFields();
            referralTypeSelect.addEventListener("change", toggleReferralFields);

            if (referralForm) {
                referralForm.addEventListener("submit", function(e) {
                    toggleReferralFields();

                    // Validate that at least one clearance file is selected or exists
                    const resumeInput = document.getElementById('resume_input');
                    const brgyInput = document.getElementById('ref_brgy_input');
                    const policeInput = document.getElementById('ref_police_input');
                    const nbiInput = document.getElementById('ref_nbi_input');

                    const hasResumeFile = resumeInput && resumeInput.files.length > 0;
                    const brgyName = document.getElementById('ref_brgy_name');
                    const policeName = document.getElementById('ref_police_name');
                    const nbiName = document.getElementById('ref_nbi_name');
                    const hasExistingResume = brgyName && brgyName.textContent !== 'No file selected';
                    const hasExistingBrgy = brgyName && brgyName.textContent !== 'No file selected';
                    const hasExistingPolice = policeName && policeName.textContent !==
                        'No file selected';
                    const hasExistingNbi = nbiName && nbiName.textContent !== 'No file selected';

                    const hasResume = hasResumeFile || hasExistingResume;
                    const hasClearance = (brgyInput?.files.length > 0) || (policeInput?.files.length >
                            0) || (nbiInput?.files.length > 0) ||
                        hasExistingBrgy || hasExistingPolice || hasExistingNbi;

                    if (!hasClearance) {
                        e.preventDefault();
                        alert('Please upload at least one clearance document (Barangay/Police/NBI)');
                        return false;
                    }
                });
            }



            if (addPesoDetailButton && pesoDetailTemplate && pesoExtraDetails) {
                const addPesoDetail = () => {
                    const templateHtml = pesoDetailTemplate.innerHTML.trim();
                    const detailHtml = templateHtml.replace(/__INDEX__/g, nextPesoDetailIndex);
                    nextPesoDetailIndex += 1;
                    const wrapper = document.createElement("div");
                    wrapper.innerHTML = detailHtml;
                    const detailCard = wrapper.firstElementChild;

                    if (!detailCard) {
                        return;
                    }

                    pesoExtraDetails.appendChild(detailCard);
                    populatePsgcCityData();
                };

                addPesoDetailButton.addEventListener("click", addPesoDetail);

                pesoOfficeFields.addEventListener("click", function(event) {
                    const removeButton = event.target.closest(".js-remove-peso-detail");

                    if (!removeButton) {
                        return;
                    }

                    const detailCard = removeButton.closest(".js-peso-extra-detail");

                    if (detailCard) {
                        detailCard.remove();
                    }
                });
            }
        }

        const cityDropdown = document.getElementById("cityGovernment");
        const permitIssuedAtDropdown = document.getElementById("permitIssuedAtSelect");
        const refRecipientDropdown = document.getElementById("refRecipientSelect");
        const refPlaceInput = document.getElementById("refPlaceInput");
        const refProvinceInput = document.getElementById("refProvinceInput");
        const refCompanyAddressInput = document.getElementById("refCompanyAddressInput");
        const refCompanyAddressList = document.getElementById("refCompanyAddressList");
        const modalRefRecipientDropdown = document.getElementById("modal_ref_recipient");
        const modalCityDropdown = document.getElementById("modal_ref_city_gov");
        const modalRefCompanyAddressInput = document.getElementById("modal_ref_company_address");
        const modalRefCompanyAddressList = document.getElementById("modalRefCompanyAddressList");
        const editReferralDetailsModal = document.getElementById("editReferralDetailsModal");
        const selectedPermitIssuedAt = `{{ old('permit_issued_at') }}`;
        const permitIssuedAtApiUrl = `{{ route('api.permit-issued-at.city-governments') }}`;
        const selectedCityGovernment = `{{ old('ref_city_gov') }}`;
        const selectedRefRecipient = `{{ old('ref_recipient') }}`;
        const selectedRefPlace = `{{ old('ref_place', $referral->ref_place ?? '') }}`;
        const selectedRefCompanyAddress = `{{ old('ref_company_address') }}`;
        const referralRecipientSearchUrl = `{{ route('referrals.recipients.search') }}`;
        const configuredMayors = @json(config('philippine_mayors', []));
        let permitIssuedAtOptionsPromise = null;
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

        const ensurePermitIssuedAtOptions = () => {
            if (!permitIssuedAtOptionsPromise) {
                permitIssuedAtOptionsPromise = fetch(permitIssuedAtApiUrl)
                    .then(response => response.json())
                    .then(data => Array.isArray(data?.results) ? data.results : [])
                    .catch(error => {
                        console.error("Error loading permit issued-at options:", error);
                        return [];
                    });
            }

            return permitIssuedAtOptionsPromise;
        };

        const populateCityAddressOptions = (selectEl, cities, currentValue = "") => {
            if (!selectEl) {
                return;
            }

            const normalizePlaceValue = value => String(value || "").toUpperCase().trim();
            const selectedValue = normalizePlaceValue(currentValue);

            const defaultOption = selectEl.querySelector('option[value=""]');
            selectEl.innerHTML = "";
            if (defaultOption) {
                selectEl.appendChild(defaultOption);
            } else {
                const opt = document.createElement("option");
                opt.value = "";
                opt.textContent = "Select City Address";
                selectEl.appendChild(opt);
            }

            cities.forEach(city => {
                const rawName = city.name || city.description || "";
                const cleaned = String(rawName).replace(/^\s*(city of|municipality of)\s+/i, "");
                let name = String(cleaned).toUpperCase().trim();
                const isCity = /city/i.test(rawName);
                const provinceCode = city.provinceCode || city.province_code || city.province
                    ?.code || city.province?.provinceCode || "";
                const provinceName = city.province?.name || city.province?.description || (
                    typeof city.province === "string" ? city.province : "");

                if (isCity && !/^CITY\s+OF\s+/.test(name)) {
                    name = ("CITY OF " + name).trim();
                }

                const option = document.createElement("option");
                option.value = name;
                option.textContent = name;
                if (provinceCode) {
                    option.dataset.provinceCode = provinceCode;
                }
                if (provinceName) {
                    option.dataset.provinceName = String(provinceName).toUpperCase().trim();
                }
                selectEl.appendChild(option);
            });

            if (selectedValue) {
                const hasMatch = Array.from(selectEl.options).some(option => option.value === selectedValue);
                if (!hasMatch) {
                    const extra = document.createElement("option");
                    extra.value = selectedValue;
                    extra.textContent = selectedValue;
                    selectEl.appendChild(extra);
                }
                selectEl.value = selectedValue;
            }
        };

        const populateModalCityAddressOptions = (cities) => {
            const modalSelect = document.getElementById("modal_ref_place");
            if (!modalSelect) {
                return;
            }
            populateCityAddressOptions(modalSelect, cities, modalSelect.value || "");
        };

        const ensurePsgcProvinceData = (() => {
            let provinceDataPromise = null;

            return () => {
                if (!provinceDataPromise) {
                    provinceDataPromise = fetch("https://psgc.gitlab.io/api/provinces/")
                        .then(response => response.json())
                        .then(data => data.sort((a, b) => a.name.localeCompare(b.name)))
                        .catch(error => {
                            console.error("Error loading provinces:", error);
                            return [];
                        });
                }

                return provinceDataPromise;
            };
        })();

        const getProvinceNameFromCityOption = (option, provinces = []) => {
            if (!option) {
                return "";
            }

            const directProvinceName = option.dataset.provinceName || "";
            if (directProvinceName) {
                return String(directProvinceName).toUpperCase().trim();
            }

            const provinceCode = option.dataset.provinceCode || "";
            if (!provinceCode) {
                return "";
            }

            const matchingProvince = provinces.find(province => String(province.code || "") === String(
                provinceCode));

            return String(
                matchingProvince?.name ||
                matchingProvince?.province ||
                matchingProvince?.description ||
                ""
            ).toUpperCase().trim();
        };

        const getCityOptionFromValue = (value) => {
            if (!refPlaceInput) {
                return null;
            }

            const normalizedValue = String(value || "").toUpperCase().trim();

            return Array.from(refPlaceInput.options).find(option => option.value === normalizedValue) ||
                null;
        };

        const syncProvinceInputFromCityValue = (value, provinceInput, provinces = []) => {
            if (!provinceInput) {
                return;
            }

            const selectedOption = getCityOptionFromValue(value);

            if (!selectedOption || !selectedOption.value) {
                provinceInput.value = "";
                return;
            }

            provinceInput.value = (getProvinceNameFromCityOption(selectedOption, provinces) || provinceInput
                .value || "").toUpperCase();
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

            const selectedRecipientOption = refRecipientDropdown.options[refRecipientDropdown
                .selectedIndex];

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
                normalizeCityGovernmentValue(option.dataset.cityGovernment || "") ===
                normalizedSelectedCity
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

        function initSelect2PesoRefPlace(selectEl, isModal) {
            if (!selectEl || window.jQuery(selectEl).data('select2')) return;
            var opts = {
                placeholder: "Search City Address",
                allowClear: true,
                width: "100%",
                dropdownAutoWidth: true,
                minimumResultsForSearch: 0
            };
            if (isModal) {
                opts.dropdownParent = editReferralDetailsModal ?
                    window.jQuery(editReferralDetailsModal) :
                    window.jQuery(document.body);
            }
            window.jQuery(selectEl).select2(opts);
        }

        var modalRefPlaceSelect = document.getElementById("modal_ref_place");

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
                                city_government: cityGovernmentValue && cityGovernmentValue !==
                                    "Select City Government" ?
                                    cityGovernmentValue : ""
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

                window.jQuery(refRecipientDropdown).on("select2:select", function(event) {
                    const selectedMayor = event.params.data;

                    const selectedOption = refRecipientDropdown.options[refRecipientDropdown
                        .selectedIndex];

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

                window.jQuery(refRecipientDropdown).on("select2:clear", function() {
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
            if (cityDropdown && !Array.from(cityDropdown.options).some(option => option.value ===
                    cityGovernmentValue)) {
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
            if (refCompanyAddressList && !Array.from(refCompanyAddressList.options).some(option =>
                    option.value === companyAddressValue)) {
                const option = document.createElement("option");
                option.value = companyAddressValue;
                refCompanyAddressList.appendChild(option);
            }

            if (modalRefCompanyAddressList && !Array.from(modalRefCompanyAddressList.options).some(
                    option =>
                    option.value === companyAddressValue)) {
                const option = document.createElement("option");
                option.value = companyAddressValue;
                modalRefCompanyAddressList.appendChild(option);
            }
        });

        const setModalRecipientDetails = (cityGovernment, companyAddress) => {
            if (modalCityDropdown && cityGovernment) {
                appendOptionIfMissing(
                    modalCityDropdown,
                    cityGovernment,
                    formatCityGovernmentLabel(cityGovernment)
                );
                modalCityDropdown.value = cityGovernment;

                if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
                    window.jQuery(modalCityDropdown).trigger("change.select2");
                }
            }

            if (modalRefCompanyAddressInput && companyAddress) {
                modalRefCompanyAddressInput.value = companyAddress;
            }
        };

        const populateModalOtherCityReferralFields = (recipient, cityGov, companyAddress) => {
            if (!modalRefRecipientDropdown || !modalCityDropdown) {
                return;
            }

            appendOptionIfMissing(modalRefRecipientDropdown, recipient, recipient, {
                cityGovernment: cityGov,
                companyAddress: companyAddress
            });
            appendOptionIfMissing(
                modalCityDropdown,
                cityGov,
                formatCityGovernmentLabel(cityGov)
            );

            modalRefRecipientDropdown.value = recipient || "";
            modalCityDropdown.value = cityGov || "";

            if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
                window.jQuery(modalRefRecipientDropdown).val(recipient || null).trigger("change.select2");
                window.jQuery(modalCityDropdown).val(cityGov || null).trigger("change.select2");
            }

            if (modalRefCompanyAddressInput) {
                modalRefCompanyAddressInput.value = companyAddress || "";
            }
        };

        const syncModalRecipientDetailsFromSelectedOption = () => {
            if (!modalRefRecipientDropdown) {
                return;
            }

            const selectedRecipientOption = modalRefRecipientDropdown.options[modalRefRecipientDropdown
                .selectedIndex];

            if (!selectedRecipientOption) {
                return;
            }

            setModalRecipientDetails(
                selectedRecipientOption.dataset.cityGovernment || "",
                selectedRecipientOption.dataset.companyAddress || ""
            );
        };

        const syncModalRecipientFromCityGovernment = () => {
            if (!modalCityDropdown || !modalRefRecipientDropdown) {
                return;
            }

            const normalizedSelectedCity = normalizeCityGovernmentValue(modalCityDropdown.value);

            if (!normalizedSelectedCity) {
                modalRefRecipientDropdown.value = "";

                if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
                    window.jQuery(modalRefRecipientDropdown).val(null).trigger("change");
                }

                if (modalRefCompanyAddressInput) {
                    modalRefCompanyAddressInput.value = "";
                }

                return;
            }

            const matchingMayorOption = Array.from(modalRefRecipientDropdown.options).find(option =>
                normalizeCityGovernmentValue(option.dataset.cityGovernment || "") ===
                normalizedSelectedCity
            );

            if (!matchingMayorOption) {
                return;
            }

            matchingMayorOption.selected = true;
            modalRefRecipientDropdown.value = matchingMayorOption.value;

            if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
                window.jQuery(modalRefRecipientDropdown).trigger("change.select2");
            }

            setModalRecipientDetails(
                matchingMayorOption.dataset.cityGovernment || "",
                matchingMayorOption.dataset.companyAddress || ""
            );
        };

        configuredCityGovernments.forEach(cityGovernmentValue => {
            if (modalCityDropdown && !Array.from(modalCityDropdown.options).some(option => option
                    .value ===
                    cityGovernmentValue)) {
                const option = document.createElement("option");
                option.value = cityGovernmentValue;
                option.text = cityGovernmentValue;
                modalCityDropdown.appendChild(option);
            }
        });

        if (modalRefRecipientDropdown) {
            modalRefRecipientDropdown.addEventListener("change", syncModalRecipientDetailsFromSelectedOption);
        }

        if (modalCityDropdown) {
            modalCityDropdown.addEventListener("change", syncModalRecipientFromCityGovernment);
        }

        if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
            const modalDropdownParent = editReferralDetailsModal ?
                window.jQuery(editReferralDetailsModal) :
                window.jQuery(document.body);

            if (modalCityDropdown) {
                window.jQuery(modalCityDropdown).select2({
                    placeholder: "Select City Government",
                    allowClear: true,
                    width: "100%",
                    dropdownAutoWidth: true,
                    minimumResultsForSearch: 0,
                    dropdownParent: modalDropdownParent
                });
            }

            if (modalRefRecipientDropdown) {
                window.jQuery(modalRefRecipientDropdown).select2({
                    placeholder: "Search or type mayor's name",
                    allowClear: true,
                    width: "100%",
                    dropdownAutoWidth: true,
                    minimumInputLength: 0,
                    dropdownParent: modalDropdownParent,
                    ajax: {
                        url: referralRecipientSearchUrl,
                        dataType: "json",
                        delay: 250,
                        data: params => {
                            const cityGovernmentValue = modalCityDropdown ? modalCityDropdown
                                .value : "";

                            return {
                                q: params.term || "",
                                city_government: cityGovernmentValue && cityGovernmentValue !==
                                    "Select City Government" ?
                                    cityGovernmentValue : ""
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

                window.jQuery(modalRefRecipientDropdown).on("select2:select", function(event) {
                    const selectedMayor = event.params.data;
                    const selectedOption = modalRefRecipientDropdown.options[modalRefRecipientDropdown
                        .selectedIndex];

                    if (selectedOption) {
                        if (selectedMayor.city_government) {
                            selectedOption.dataset.cityGovernment = selectedMayor.city_government;
                        }

                        if (selectedMayor.company_address) {
                            selectedOption.dataset.companyAddress = selectedMayor.company_address;
                        }
                    }

                    setModalRecipientDetails(
                        selectedMayor.city_government || "",
                        selectedMayor.company_address || ""
                    );
                });

                window.jQuery(modalRefRecipientDropdown).on("select2:clear", function() {
                    if (modalRefCompanyAddressInput) {
                        modalRefCompanyAddressInput.value = "";
                    }
                });
            }
        }

        if (refCompanyAddressInput && selectedRefCompanyAddress) {
            refCompanyAddressInput.value = selectedRefCompanyAddress;
        }

        syncRecipientDetailsFromSelectedOption();

        const populatePermitIssuedAtOptions = () => {
            if (!permitIssuedAtDropdown) {
                return;
            }

            return ensurePermitIssuedAtOptions().then(options => {
                const currentValue = (permitIssuedAtDropdown.value || selectedPermitIssuedAt || "")
                    .toUpperCase().trim();
                const permitIssuedAtValues = [...new Set(
                    options
                    .map(item => String(item?.id || item?.text || "").toUpperCase().trim())
                    .filter(Boolean)
                )].sort((a, b) => a.localeCompare(b, undefined, {
                    sensitivity: "base"
                }));

                permitIssuedAtDropdown.innerHTML = "";

                const placeholderOption = document.createElement("option");
                placeholderOption.value = "";
                placeholderOption.text = "Select City / Municipality";
                placeholderOption.selected = currentValue === "";
                permitIssuedAtDropdown.appendChild(placeholderOption);

                permitIssuedAtValues.forEach(value => {
                    const option = document.createElement("option");
                    option.value = value;
                    option.text = value;
                    option.selected = value === currentValue;
                    permitIssuedAtDropdown.appendChild(option);
                });

                if (currentValue && !permitIssuedAtValues.includes(currentValue)) {
                    appendOptionIfMissing(permitIssuedAtDropdown, currentValue, currentValue);
                }

                if (window.jQuery && typeof window.jQuery.fn.select2 === "function") {
                    window.jQuery(permitIssuedAtDropdown).trigger("change.select2");
                }
            });
        };

        const populatePsgcCityData = () => {
            Promise.all([ensurePsgcCityData(), ensurePsgcProvinceData()]).then(([cities, provinces]) => {
                populatePermitIssuedAtOptions();

                if (refPlaceInput) {
                    populateCityAddressOptions(refPlaceInput, cities, selectedRefPlace || "");
                    syncProvinceInputFromCityValue(refPlaceInput.value ||
                        selectedRefPlace || "", refProvinceInput, provinces);
                    initSelect2PesoRefPlace(refPlaceInput);
                }

                document.querySelectorAll(".js-peso-ref-place-select").forEach(select => {
                    const savedVal = select.dataset.selectedValue || select.value || "";
                    populateCityAddressOptions(select, cities, savedVal);
                    syncProvinceInputFromCityValue(select.value || "", select.closest(
                        ".peso-extra-detail-card")?.querySelector(
                        ".js-peso-ref-province-input"), provinces);
                    initSelect2PesoRefPlace(select);
                });

                populateModalCityAddressOptions(cities);
                if (modalRefPlaceSelect) {
                    initSelect2PesoRefPlace(modalRefPlaceSelect, true);
                }
            });
        };

        populatePermitIssuedAtOptions();

        if (permitIssuedAtDropdown) {
            permitIssuedAtDropdown.addEventListener("focus", populatePsgcCityData, {
                once: true
            });
        }

        if (refPlaceInput) {
            refPlaceInput.addEventListener("change", function() {
                ensurePsgcProvinceData().then(provinces => {
                    syncProvinceInputFromCityValue(this.value, refProvinceInput, provinces);
                });
            });
        }

        document.addEventListener("change", function(event) {
            const citySelect = event.target.closest(".js-peso-ref-place-select");

            if (!citySelect) {
                return;
            }

            const provinceInput = citySelect.closest(".peso-extra-detail-card")?.querySelector(
                ".js-peso-ref-province-input");

            ensurePsgcProvinceData().then(provinces => {
                syncProvinceInputFromCityValue(citySelect.value, provinceInput, provinces);
            });
        });

        if (refPlaceInput || document.querySelector(".js-peso-ref-place-select") || modalRefPlaceSelect) {
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
    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll(".file-input").forEach(input => {
            input.addEventListener("change", function() {

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
    document.addEventListener("DOMContentLoaded", function() {

        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');


        // SAVED VALUES
        let savedProvince = "{{ $applicant->province }}";
        let savedCity = "{{ $applicant->city }}";
        let savedBarangay = "{{ $applicant->barangay }}";

        // Local barangay mappings
        const localBarangays = {
            'IMUS': [
                'ALAPAN I-A', 'ALAPAN I-B', 'ALAPAN I-C', 'ALAPAN II-A', 'ALAPAN II-B',
                'BUCANDALA I', 'BUCANDALA II', 'BUCANDALA III', 'BUCANDALA IV', 'BUCANDALA V',
                'CARSADANG BAGO I', 'CARSADANG BAGO II',
                'MALAGASANG I-A', 'MALAGASANG I-B', 'MALAGASANG I-C', 'MALAGASANG I-D',
                'MALAGASANG I-E', 'MALAGASANG I-F', 'MALAGASANG I-G',
                'MALAGASANG II-A', 'MALAGASANG II-B', 'MALAGASANG II-C', 'MALAGASANG II-D',
                'MALAGASANG II-E', 'MALAGASANG II-F', 'MALAGASANG II-G',
                'MEDICION I-A', 'MEDICION I-B', 'MEDICION I-C', 'MEDICION I-D',
                'MEDICION II-A', 'MEDICION II-B', 'MEDICION II-C', 'MEDICION II-D',
                'MEDICION II-E', 'MEDICION II-F',
                'PAG-ASA I', 'PAG-ASA II', 'PAG-ASA III',
                'POBLACION I-A', 'POBLACION I-B', 'POBLACION I-C',
                'POBLACION II-A', 'POBLACION II-B',
                'POBLACION III-A', 'POBLACION III-B',
                'POBLACION IV-A', 'POBLACION IV-B', 'POBLACION IV-C', 'POBLACION IV-D',
                'TOCLONG I-A', 'TOCLONG I-B', 'TOCLONG I-C', 'TOCLONG II-A', 'TOCLONG II-B',
                'ANABU I-A', 'ANABU I-B', 'ANABU I-C', 'ANABU I-D', 'ANABU I-E', 'ANABU I-F',
                'ANABU I-G',
                'ANABU II-A', 'ANABU II-B', 'ANABU II-C', 'ANABU II-D', 'ANABU II-E', 'ANABU II-F',
                'BAGONG SILANG', 'BAYAN LUMA I', 'BAYAN LUMA II', 'BAYAN LUMA III',
                'BAYAN LUMA IV', 'BAYAN LUMA V', 'BAYAN LUMA VI', 'BAYAN LUMA VII',
                'BAYAN LUMA VIII', 'BAYAN LUMA IX',
                'BUHAY NA TUBIG', 'MAGDALO', 'MAHARLIKA',
                'MARIANO ESPELETA I', 'MARIANO ESPELETA II', 'MARIANO ESPELETA III',
                'PALICO I', 'PALICO II', 'PALICO III', 'PALICO IV',
                'PASONG BUAYA I', 'PASONG BUAYA II', 'PINAGBUKLOD',
                'TANZANG LUMA I', 'TANZANG LUMA II', 'TANZANG LUMA III',
                'TANZANG LUMA IV', 'TANZANG LUMA V', 'TANZANG LUMA VI'
            ]
        };

        const localCities = {
            'NCR': [
                'CITY OF MANILA', 'CITY OF QUEZON', 'CITY OF CALOOCAN',
                'CITY OF LAS PINAS', 'CITY OF MAKATI', 'CITY OF MALABON',
                'CITY OF MANDALUYONG', 'CITY OF MARIKINA', 'CITY OF MUNTINLUPA',
                'CITY OF NAVOTAS', 'CITY OF PARANAQUE', 'CITY OF PASAY',
                'CITY OF PASIG', 'CITY OF SAN JUAN', 'CITY OF TAGUIG',
                'CITY OF VALENZUELA', 'PATEROS'
            ]
        };

        function normalizeName(value) {
            return String(value || '').replace(/^\s*(city of|municipality of)\s+/i, '').trim().toUpperCase();
        }

        function isBacoorCity(value) {
            return normalizeName(value).includes('BACOOR');
        }

        function remapBarangayName(rawName, cityName = '') {
            const name = String(rawName || '').replace(/\(\s*POB\.?\s*\)/ig, '').trim().toUpperCase();
            const normalizedCity = normalizeName(cityName);

            if (isBacoorCity(normalizedCity) && /^P\.F\. ESPIRITU\b/.test(name)) {
                return name.replace(/^P\.F\. ESPIRITU\b/, 'PANAPAAN');
            }

            return name;
        }

        function isBarangayMatch(savedName, optionName, cityName = '') {
            const normalizedSaved = String(savedName || '').trim().toUpperCase();
            const normalizedOption = String(optionName || '').trim().toUpperCase();
            const normalizedCity = normalizeName(cityName);

            if (isBacoorCity(normalizedCity)) {
                return normalizedSaved === normalizedOption ||
                    (normalizedSaved.startsWith('P.F. ESPIRITU') && normalizedOption.startsWith('PANAPAAN')) ||
                    (normalizedSaved.startsWith('PANAPAAN') && normalizedOption.startsWith('P.F. ESPIRITU'));
            }

            return normalizedSaved === normalizedOption;
        }

        function setBarangayOptions(items, selectedBarangay = '', cityName = '') {
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            const normalizedSelectedBarangay = String(selectedBarangay || '').toUpperCase();
            items.slice().sort((a, b) => a.localeCompare(b, undefined, {
                sensitivity: 'base'
            })).forEach(item => {
                const option = document.createElement('option');
                option.value = item;
                option.textContent = item;
                if (normalizedSelectedBarangay && isBarangayMatch(normalizedSelectedBarangay, item,
                        cityName)) {
                    option.selected = true;
                }
                barangaySelect.appendChild(option);
            });
        }

        function loadLocalBarangays(cityName) {
            const normalizedCity = normalizeName(cityName);
            if (!normalizedCity || !localBarangays[normalizedCity]) {
                return false;
            }
            setBarangayOptions(localBarangays[normalizedCity], savedBarangay, cityName);
            return true;
        }

        function setCityOptions(items, selectedCity = '') {
            citySelect.innerHTML = '<option value="">Select City</option>';
            const normalizedSelected = String(selectedCity || '').toUpperCase();
            const selectedBase = normalizeName(normalizedSelected).replace(/\s*CITY\s*$/, '');
            items.slice().sort((a, b) => a.localeCompare(b, undefined, {
                sensitivity: 'base'
            })).forEach(item => {
                const option = document.createElement('option');
                option.value = item;
                option.textContent = item;
                if (normalizedSelected && String(item).toUpperCase() === normalizedSelected) {
                    option.selected = true;
                } else if (selectedBase && normalizeName(item).replace(/\s*CITY\s*$/, '') ===
                    selectedBase) {
                    option.selected = true;
                }
                citySelect.appendChild(option);
            });
        }

        function loadLocalCities(provinceIdentifier) {
            const normalized = normalizeName(provinceIdentifier);

            function loadSelectedCityBarangays() {
                const selectedCity = citySelect.options[citySelect.selectedIndex];
                if (selectedCity && selectedCity.value) {
                    loadBarangays(selectedCity.value, selectedCity.dataset.code);
                }
            }
            if (normalized && localCities[normalized]) {
                setCityOptions(localCities[normalized], savedCity);
                loadSelectedCityBarangays();
                return true;
            }
            const ncrCode = window._provinceCodeMap && (window._provinceCodeMap['NCR'] || window
                ._provinceCodeMap['METRO MANILA']);
            if (ncrCode && String(provinceIdentifier) === String(ncrCode)) {
                setCityOptions(localCities['NCR'], savedCity);
                loadSelectedCityBarangays();
                return true;
            }
            return false;
        }

        // ---------- LOAD PROVINCES ----------
        function loadProvinces() {
            provinceSelect.innerHTML = '<option value="">Loading provinces...</option>';
            fetch('https://psgc.gitlab.io/api/provinces/')
                .then(res => res.json())
                .then(data => {
                    let provinces = Array.isArray(data) ? data : [];
                    provinceSelect.innerHTML = '<option value="">Select Province</option>';
                    window._provinceCodeMap = window._provinceCodeMap || {};

                    provinces.push({
                        name: 'NCR',
                        code: '130000000'
                    });

                    provinces.sort((a, b) => {
                        const an = (a.name || a.province || a.description || '').toString();
                        const bn = (b.name || b.province || b.description || '').toString();
                        return an.localeCompare(bn, undefined, {
                            sensitivity: 'base'
                        });
                    });

                    provinces.forEach(p => {
                        const rawName = p.name || p.province || p.description || '';
                        const name = String(rawName).toUpperCase();
                        const code = p.code || '';
                        let option = document.createElement('option');
                        option.value = name;
                        option.textContent = name;
                        option.dataset.code = code;

                        if (savedProvince && name === String(savedProvince).toUpperCase()) {
                            option.selected = true;
                        }

                        provinceSelect.appendChild(option);
                        if (name && code) window._provinceCodeMap[name] = code;
                    });

                    const selected = provinceSelect.options[provinceSelect.selectedIndex];
                    if (selected) {
                        const code = selected.dataset.code || window._provinceCodeMap[selected.value];
                        if (code) loadCities(code);
                    }
                })
                .catch(() => {
                    provinceSelect.innerHTML = '<option value="">Unable to load provinces</option>';
                });
        }

        // ---------- LOAD CITIES ----------
        async function loadCities(provinceIdentifier) {
            citySelect.innerHTML = '<option>Loading cities...</option>';
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

            let provinceCode = provinceIdentifier;
            if (isNaN(Number(provinceCode))) provinceCode = window._provinceCodeMap && window
                ._provinceCodeMap[provinceCode] ? window._provinceCodeMap[provinceCode] : provinceCode;

            if (loadLocalCities(provinceIdentifier) || (provinceCode && window._provinceCodeMap && window
                    ._provinceCodeMap['NCR'] && String(provinceCode) === String(window._provinceCodeMap[
                        'NCR']))) {
                return;
            }

            try {
                const res = await fetch(
                    `https://psgc.gitlab.io/api/provinces/${encodeURIComponent(provinceCode)}/cities-municipalities/`
                );
                if (!res.ok) throw new Error('no-cities');
                const data = await res.json();
                citySelect.innerHTML = '<option value="">Select City</option>';
                data.sort((a, b) => (a.name || '').localeCompare(b.name || ''));

                data.forEach(city => {
                    const rawName = city.name || city.description || '';
                    const cleaned = normalizeName(rawName);
                    const isCity = /city/i.test(rawName);
                    const name = isCity ? `CITY OF ${cleaned.replace(/\s*CITY\s*$/, '')}` : cleaned;

                    const option = document.createElement('option');
                    option.value = name;
                    option.textContent = name;
                    option.dataset.code = city.code || '';

                    if (savedCity) {
                        const savedNorm = normalizeName(savedCity).replace(/\s*CITY\s*$/, '');
                        const optionNorm = normalizeName(name).replace(/\s*CITY\s*$/, '');
                        if (savedNorm === optionNorm) {
                            option.selected = true;
                        }
                    }

                    citySelect.appendChild(option);
                });

                // Batangas extras
                (function appendBatangasExtras() {
                    let provinceNameUpper = '';
                    if (isNaN(Number(provinceIdentifier))) provinceNameUpper = String(
                        provinceIdentifier).toUpperCase();
                    else if (window._provinceCodeMap) {
                        for (const k in window._provinceCodeMap) {
                            if (window._provinceCodeMap[k] === provinceCode) {
                                provinceNameUpper = k;
                                break;
                            }
                        }
                    }

                    if (provinceNameUpper && provinceNameUpper.includes('BATANGAS')) {
                        const extras = ['BATANGAS PROVINCE', 'BATANGAS STATE UNIVERSITY',
                            'UNIVERSITY OF BATANGAS-MAIN', 'RIZAL COLLEGE OF TAAL'
                        ];
                        extras.forEach(raw => {
                            const name = String(raw).toUpperCase().trim();
                            if (!Array.from(citySelect.options).some(o => o.value === name)) {
                                const option = document.createElement('option');
                                option.value = name;
                                option.textContent = name;
                                option.dataset.code = '';
                                if (savedCity) {
                                    const savedNorm = normalizeName(savedCity).replace(
                                        /\s*CITY\s*$/, '');
                                    const optionNorm = normalizeName(name).replace(
                                        /\s*CITY\s*$/, '');
                                    if (savedNorm === optionNorm) option.selected = true;
                                }
                                citySelect.appendChild(option);
                            }
                        });
                    }

                    if (provinceNameUpper && provinceNameUpper.includes('CAVITE')) {
                        const caviteExtras = [
                            'ALFONSO', 'AMADEO', 'BACOOR CITY', 'CARMONA', 'CAVITE CITY',
                            'DASMARIÑAS CITY', 'GENERAL EMILIO AGUINALDO',
                            'GENERAL MARIANO ALVAREZ', 'CITY OF GENERAL TRIAS', 'CITY OF IMUS',
                            'INDANG', 'KAWIT', 'MAGALLANES', 'MARAGONDON', 'MENDEZ', 'NAIC',
                            'NOVELETA', 'ROSARIO', 'SILANG', 'TAGAYTAY CITY', 'TANZA', 'TERNATE',
                            'TRECE MARTIRES CITY', 'CAVITE PROVINCE'
                        ];
                        caviteExtras.forEach(raw => {
                            const cleaned = normalizeName(raw);
                            const isCityExtra = /city/i.test(raw);
                            const name = isCityExtra ?
                                `CITY OF ${cleaned.replace(/\s*CITY\s*$/, '')}` : cleaned;
                            if (!Array.from(citySelect.options).some(o => o.value === name)) {
                                const option = document.createElement('option');
                                option.value = name;
                                option.textContent = name;
                                option.dataset.code = '';
                                if (savedCity) {
                                    const savedNorm = normalizeName(savedCity).replace(
                                        /\s*CITY\s*$/, '');
                                    const optionNorm = normalizeName(name).replace(
                                        /\s*CITY\s*$/, '');
                                    if (savedNorm === optionNorm) option.selected = true;
                                }
                                citySelect.appendChild(option);
                            }
                        });
                    }
                })();

                const selectedCity = citySelect.options[citySelect.selectedIndex];
                if (selectedCity && selectedCity.dataset.code && selectedCity.value) {
                    loadBarangays(selectedCity.value, selectedCity.dataset.code);
                }
            } catch (e) {
                citySelect.innerHTML = '<option value="">Unable to load cities</option>';
            }
        }

        // ---------- LOAD BARANGAYS ----------
        function loadBarangays(cityName, cityCode) {
            if (loadLocalBarangays(cityName)) {
                return;
            }

            barangaySelect.innerHTML = '<option>Loading barangays...</option>';

            if (!cityCode) {
                const rawProvince = provinceSelect.value || '';
                const selectedProvince = normalizeName(rawProvince);
                const knownNcrCodes = [];
                if (window._provinceCodeMap) {
                    if (window._provinceCodeMap['NCR']) knownNcrCodes.push(String(window._provinceCodeMap[
                        'NCR']));
                    if (window._provinceCodeMap['METRO MANILA']) knownNcrCodes.push(String(window
                        ._provinceCodeMap['METRO MANILA']));
                }
                knownNcrCodes.push('130000000');
                const selectedOption = provinceSelect.options[provinceSelect.selectedIndex];
                const selectedOptionCode = selectedOption && selectedOption.dataset ? String(selectedOption
                    .dataset.code || '') : '';
                const provinceIsNcr = (
                    selectedProvince === 'NCR' ||
                    selectedProvince.includes('NATIONAL CAPITAL') ||
                    selectedProvince.includes('METRO MANILA') ||
                    (selectedOptionCode && knownNcrCodes.includes(selectedOptionCode)) ||
                    knownNcrCodes.includes(String(rawProvince))
                );

                if (provinceIsNcr) {
                    fetch(`/api/ncr/barangays?city=${encodeURIComponent(cityName)}`)
                        .then(res => res.json())
                        .then(data => {
                            const barangays = Array.isArray(data) ? data : [];
                            setBarangayOptions(barangays, savedBarangay, cityName);
                        })
                        .catch(() => {
                            barangaySelect.innerHTML = '<option value="">Unable to load barangays</option>';
                        });
                    return;
                }

                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                return;
            }

            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${encodeURIComponent(cityCode)}/barangays/`)
                .then(res => res.json())
                .then(data => {
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    data.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
                    data.forEach(b => {
                        const name = remapBarangayName(b.name || '', cityName);
                        const option = document.createElement('option');
                        option.value = name;
                        option.textContent = name;
                        if (savedBarangay && isBarangayMatch(savedBarangay, name, normalizeName(
                                cityName)))
                            option.selected = true;
                        barangaySelect.appendChild(option);
                    });
                })
                .catch(() => barangaySelect.innerHTML = '<option value="">Unable to load barangays</option>');
        }

        // ---------- EVENTS ----------
        provinceSelect.addEventListener('change', function() {
            let selected = this.options[this.selectedIndex];
            let code = selected?.dataset.code;
            let val = selected?.value;

            if (code) {
                loadCities(code);
            } else if (val) {
                loadCities(val);
            } else {
                citySelect.innerHTML = '<option>Select City</option>';
                barangaySelect.innerHTML = '<option>Select Barangay</option>';
            }
        });

        citySelect.addEventListener('change', function() {
            let selected = this.options[this.selectedIndex];
            let code = selected?.dataset.code;
            let val = selected?.value;

            if (code) {
                loadBarangays(val, code);
            } else if (val) {
                loadBarangays(val);
            } else {
                barangaySelect.innerHTML = '<option>Select Barangay</option>';
            }
        });

        // ---------- INIT ----------
        loadProvinces();
    });
</script>
{{-- Expires On --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const permitDate = document.getElementById("permit_date");
        const expiresOn = document.getElementById("expires_on");

        const updateExpiryToMonthEnd = function() {
            if (!permitDate || !expiresOn || !permitDate.value) {
                return;
            }

            const selectedDate = new Date(`${permitDate.value}T00:00:00`);
            const expiryBase = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 6, 1);
            const monthEnd = new Date(expiryBase.getFullYear(), expiryBase.getMonth() + 1, 0);
            const formatted = [
                monthEnd.getFullYear(),
                String(monthEnd.getMonth() + 1).padStart(2, '0'),
                String(monthEnd.getDate()).padStart(2, '0'),
            ].join('-');

            expiresOn.value = formatted;
        };

        permitDate?.addEventListener("change", updateExpiryToMonthEnd);
        updateExpiryToMonthEnd();

    });
</script>
{{-- nbi or police --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const dropdown = document.getElementById("clearance_type");
        const nbi = document.getElementById("nbi_section");
        const police = document.getElementById("police_section");
        const nbiInput = document.getElementById("nbi_input");
        const policeInput = document.getElementById("police_input");
        const hasNbiFile = @json(!empty($permit->permit_nbi_clearance));
        const hasPoliceFile = @json(!empty($permit->permit_police_clearance));
        const selectedClearanceType = @json($selectedClearanceType);
        const isPermitRenewalDue = @json($isPermitRenewalDue);

        function toggleFields() {
            const value = dropdown.value || selectedClearanceType || (hasPoliceFile ? "police" : (hasNbiFile ?
                "nbi" : ""));

            if (value === "nbi") {
                nbi.style.display = "grid";
                police.style.display = "none";
                if (nbiInput) nbiInput.required = !hasNbiFile || isPermitRenewalDue;
                if (policeInput) policeInput.required = false;
            } else if (value === "police") {
                nbi.style.display = "none";
                police.style.display = "grid";
                if (nbiInput) nbiInput.required = false;
                if (policeInput) policeInput.required = !hasPoliceFile || isPermitRenewalDue;
            } else {
                nbi.style.display = "none";
                police.style.display = "none";
                if (nbiInput) nbiInput.required = false;
                if (policeInput) policeInput.required = false;
            }
        }

        // Run on page load (edit mode support)
        toggleFields();

        // Run when changed
        dropdown.addEventListener("change", toggleFields);

    });
</script>

<script>
    function clearAllUploadLabels() {
        const allFileLabels = [
            'nbi_name', 'police_name', 'health_card_name', 'cedula_name', 'referral_name',
            'prosecutor_name', 'mtc_name', 'rtc_name', 'c_nbi_name', 'brgy_name',
            'resume_name', 'ref_brgy_name', 'ref_police_name', 'ref_nbi_name'
        ];
        allFileLabels.forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.textContent = 'No file selected';
        });

        document.querySelectorAll('.file-name-text + a.btn').forEach(function(link) {
            if (link.textContent.includes('View Current')) link.style.display = 'none';
        });
    }

    function showFileName(input, displayId) {
        const fileName = input.files.length ? input.files[0].name : '';
        document.getElementById(displayId).textContent = fileName;
    }

    function handleReferralClearanceChange(input, displayId, otherInputIds = [], otherDisplayIds = []) {
        showFileName(input, displayId);

        otherInputIds.forEach(id => {
            const otherInput = document.getElementById(id);
            if (otherInput) {
                otherInput.value = '';
            }
        });

        otherDisplayIds.forEach(id => {
            const otherDisplay = document.getElementById(id);
            if (otherDisplay) {
                otherDisplay.textContent = 'No file selected';
            }
        });

        const currentLinkMap = {
            ref_brgy_name: 'ref_brgy_current_link',
            ref_police_name: 'ref_police_current_link',
            ref_nbi_name: 'ref_nbi_current_link',
        };

        Object.entries(currentLinkMap).forEach(([keepDisplayId, linkId]) => {
            const link = document.getElementById(linkId);
            if (!link) {
                return;
            }

            link.style.display = keepDisplayId === displayId ? '' : 'none';
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const educationalAttainmentSelect = document.getElementById('educationalAttainmentSelect');

        if (!educationalAttainmentSelect || !window.jQuery || typeof window.jQuery.fn.select2 !== 'function') {
            return;
        }

        window.jQuery(educationalAttainmentSelect).select2({
            placeholder: 'Select educational attainment',
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true,
            minimumResultsForSearch: 0
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const birthdateInput = document.getElementById('birthdate');
        const ageInput = document.getElementById('age');

        if (!birthdateInput) return;

        function calcAge(dateStr) {
            if (!dateStr) return '';
            const birthDate = new Date(dateStr + 'T00:00:00');
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            const dayDiff = today.getDate() - birthDate.getDate();
            if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) age -= 1;
            return age >= 0 ? age : '';
        }

        birthdateInput.addEventListener('change', function() {
            if (ageInput) ageInput.value = calcAge(this.value);
        });

        if (ageInput && birthdateInput.value) {
            ageInput.value = calcAge(birthdateInput.value);
        }
    });
</script>

@if ($disapproveRequirement && $disapproveRequirementId)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalId = @json('disapprove' . ucfirst($disapproveRequirement) . 'Modal-' . $disapproveRequirementId);
            const modalEl = document.getElementById(modalId);

            if (modalEl && window.bootstrap && window.bootstrap.Modal) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        });
    </script>
@endif
