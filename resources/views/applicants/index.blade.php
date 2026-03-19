@extends('layouts.app')

@section('content')

    <style>
        /* ===================================
        GLOBAL LAYOUT
        =================================== */

        body {
            background: #f3f6fb;
            font-family: "Inter", "Segoe UI", sans-serif;
            color: #2d3748;
        }

        /* ===================================
        PAGE HEADER
        =================================== */

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
        }

        .container-fluid {
            max-width: 1600px;
        }

        /* ===================================
        ADD APPLICANT BUTTON
        =================================== */

        .btn-save {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 8px;
            transition: all .25s ease;
        }

        .btn-save:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, .25);
        }

        /* ===================================
        FILTER PANEL
        =================================== */

        .card {
            background: #ffffff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .card-body {
            padding: 25px;
        }

        /* ===================================
        SEARCH FIELD
        =================================== */

        .input-group {
            border-radius: 8px;
            overflow: hidden;
        }

        .input-group-text {
            background: #f1f5f9;
            border: none;
        }

        .form-control,
        .form-select {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 14px;
            padding: 10px 12px;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, .15);
        }

        /* ===================================
        TABLE CARD
        =================================== */

        .table-card {
            border-radius: 12px;
            overflow: hidden;
        }

        /* ===================================
        TABLE
        =================================== */

        .table {
            font-size: 14px;
        }

        .table thead {
            background: #f8fafc;
        }

        .table thead th {
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            font-size: 13px;
            color: #475569;
            padding: 14px;
        }

        .table tbody td {
            padding: 14px;
            vertical-align: middle;
        }

        /* ===================================
        ROW HOVER
        =================================== */

        .table-hover tbody tr {
            transition: all .2s ease;
        }

        .table-hover tbody tr:hover {
            background: #f9fbff;
        }

        /* ===================================
        CONTACT BADGE
        =================================== */

        .badge {
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: 500;
        }

        /* ===================================
        PROGRESS BAR
        =================================== */

        .progress {
            height: 6px;
            border-radius: 10px;
            background: #e2e8f0;
        }

        .progress-bar {
            border-radius: 10px;
        }

        /* ===================================
        ACTION BUTTONS
        =================================== */

        .btn-outline-primary {
            border-radius: 6px;
        }

        .btn-outline-primary:hover {
            background: #2563eb;
            color: white;
        }

        .btn-outline-danger {
            border-radius: 6px;
        }

        .btn-outline-danger:hover {
            background: #dc2626;
            color: white;
        }

        /* ===================================
        ALERT
        =================================== */

        .alert-success {
            border-radius: 10px;
            font-size: 14px;
        }

        /* ===================================
        EMPTY STATE
        =================================== */

        tbody td i {
            opacity: .6;
        }

        /* ===================================
        PAGINATION
        =================================== */

        .pagination {
            gap: 6px;
        }

        .page-link {
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            color: #475569;
        }

        .page-item.active .page-link {
            background: #2563eb;
            border-color: #2563eb;
        }

        /* ===================================
        RESPONSIVE
        =================================== */

        @media(max-width:992px) {

            .page-title {
                font-size: 22px;
            }

            .table {
                font-size: 13px;
            }

        }

        @media(max-width:768px) {

            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .btn-save {
                width: 100%;
            }

        }
    </style>
    {{-- <style>
        /* ===============================
            GLOBAL PAGE STYLE
            ================================ */

        body {
            background: linear-gradient(135deg, #eef2f8, #e8eef6);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ===============================
            PAGE TITLE
            ================================ */

        .page-title {
            font-size: 26px;
            font-weight: 700;
            color: #2c3e50;
        }

        .container-fluid {
            font-size: 14px;
            max-width: 1900px;
        }


        /* ===============================
            SAVE BUTTON
            ================================ */

        .btn-save {
            background: linear-gradient(135deg, #4a7dff, #6aa8ff);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 10px;
            box-shadow: 0 8px 18px rgba(74, 125, 255, 0.35);
            transition: all .3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(74, 125, 255, 0.45);
        }

        /* ===============================
            FILTER CARD
            ================================ */

        .card {
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        /* ===============================
            FORM LABEL
            ================================ */

        .form-label {
            font-size: 12px;
            letter-spacing: .5px;
        }

        /* ===============================
            SEARCH INPUT
            ================================ */

        .input-group {
            border-radius: 10px;
            overflow: hidden;
        }

        .input-group-text {
            background: #f3f6fb;
        }

        .form-control,
        .form-select {
            background: #f9fbff;
            border-radius: 10px;
            border: 1px solid #dce3ef;
            font-size: 14px;
            padding: 9px 12px;
            transition: all .25s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #5fa8ff;
            box-shadow: 0 0 0 3px rgba(95, 168, 255, 0.15);
            background: white;
        }

        /* ===============================
            TABLE CARD
            ================================ */

        .table-card {
            border-radius: 18px;
            overflow: hidden;
        }

        /* ===============================
            TABLE STYLE
            ================================ */

        .table {
            font-size: 14px;
        }

        .table thead {
            background: linear-gradient(135deg, #f7f9fd, #eef2f7);
        }

        .table thead th {
            font-weight: 600;
            font-size: 13px;
            color: #4a5875;
            border: none;
            padding: 14px;
        }

        .table tbody td {
            padding: 14px;
            vertical-align: middle;
            border-top: 1px solid #eef2f7;
        }

        /* ===============================
            ROW HOVER EFFECT
            ================================ */

        .table-hover tbody tr {
            transition: all .25s ease;
        }

        .table-hover tbody tr:hover {
            background: #f7faff;
            transform: scale(1.005);
        }

        /* ===============================
            CONTACT BADGE
            ================================ */

        .badge {
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 8px;
        }

        /* ===============================
            PROGRESS BAR
            ================================ */

        .progress {
            border-radius: 20px;
            background: #edf1f7;
        }

        .progress-bar {
            border-radius: 20px;
            transition: width .6s ease;
        }

        /* ===============================
            ACTION BUTTONS
            ================================ */

        .btn-outline-primary,
        .btn-outline-danger {
            border-radius: 8px;
            transition: all .25s ease;
        }

        .btn-outline-primary:hover {
            background: #4a7dff;
            color: white;
            transform: translateY(-1px);
        }

        .btn-outline-danger:hover {
            background: #e74c3c;
            color: white;
            transform: translateY(-1px);
        }

        /* ===============================
            SUCCESS ALERT
            ================================ */

        .alert-success {
            background: linear-gradient(135deg, #eafaf1, #e2f7eb);
            color: #2c7a4b;
            border-radius: 10px;
        }

        /* ===============================
            EMPTY STATE
            ================================ */

        tbody td i {
            opacity: .6;
        }

        /* ===============================
            PAGINATION
            ================================ */

        .pagination {
            gap: 5px;
        }

        .page-link {
            border-radius: 8px;
            border: none;
            color: #4a5875;
        }

        .page-item.active .page-link {
            background: #4a7dff;
            color: white;
        }

        /* ===============================
            RESPONSIVE
            ================================ */

        @media (max-width:992px) {

            .table {
                font-size: 13px;
            }

            .page-title {
                font-size: 22px;
            }

        }

        @media (max-width:768px) {

            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .btn-save {
                width: 100%;
                text-align: center;
            }

        }

        /* ===============================
            SUBTLE CARD ANIMATION
            ================================ */

        .card {
            animation: fadeUp .35s ease;
        }

        @keyframes fadeUp {

            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }
    </style> --}}
    {{-- <style>
        :root {
            /* Primary Theme: Ocean Blue */
            --primary-color: #0284c7;
            --primary-hover: #0369a1;
            /* Success Theme: Emerald Green */
            --success-color: #10b981;
            --success-hover: #059669;
            /* UI Neutrals */
            --bg-light: #f0f4f8;
            --border-radius: 12px;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1e293b;
        }

        .page-title {
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0;
        }

        /* Custom Button Save */
        .btn-save {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s;
            border: none;
        }

        .btn-save:hover {
            background-color: var(--primary-hover);
            color: white;
            transform: translateY(-1px);
        }

        /* Table Card Styling */
        .table-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8fafc;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        /* Progress Bar Customization */
        .progress {
            background-color: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        /* Status Colors */
        .bg-success {
            background-color: #10b981 !important;
        }

        .bg-warning {
            background-color: #f59e0b !important;
        }

        .bg-danger {
            background-color: #ef4444 !important;
        }

        /* Search Bar Styling */
        .input-group-text {
            border-right: none;
        }

        .form-control:focus {
            box-shadow: none;
            background-color: #fff !important;
            border: 1px solid var(--primary-color);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style> --}}

    <body>

        <div class="container-fluid py-4 px-md-5">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="page-title">Applicants</h3>
                    <p class="text-muted small mb-0">Manage and monitor registered applicants and their requirements.</p>
                </div>

                <a href="{{ route('applicants.create') }}" class="btn btn-save shadow-sm">
                    <i class="bi bi-person-plus-fill me-2"></i>Add Applicant
                </a>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('applicants.index') }}">
                        <div class="row g-3 align-items-end">

                            {{-- Search --}}
                            <div class="col-lg-5 col-md-6">
                                <label class="form-label fw-bold text-secondary small">SEARCH APPLICANT</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-0 bg-light py-2"
                                        placeholder="Enter name or contact number..." value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- Status Filter --}}
                            <div class="col-lg-3 col-md-3">
                                <label class="form-label fw-bold text-secondary small">STATUS</label>
                                <select name="status" class="form-select bg-light border-0 py-2">
                                    <option value="">All Applicants</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived
                                    </option>
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="col-lg-4 col-md-3">
                                <div class="d-flex gap-2 justify-content-lg-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-funnel me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('applicants.index') }}" class="btn btn-outline-secondary px-4">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <div class="card table-card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="align-middle">
                                <th class="text-center">ID</th>
                                <th class="text-center">Applicant Name</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">PWD</th>
                                <th class="text-center">4Ps</th>
                                <th class="text-center">Contact</th>
                                <th class="text-center" style="min-width: 200px;">Address</th>
                                <th class="text-center">Mayor's Permit</th>
                                <th class="text-center">Mayor's Clearance</th>
                                <th class="text-center">Mayor's Referral</th>
                                <th class="text-center">Date Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applicants as $applicant)
                                            <tr>
                                                <h5>
                                                    <td class="text-muted fw-medium">#{{ $applicant->id }}</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">
                                                            {{ trim($applicant->first_name . ' ' . ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') . $applicant->last_name . ' ' . ($applicant->suffix ?? '')) }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark text-center   ">
                                                            {{ $applicant->gender }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark text-center">
                                                            {{ $applicant->pwd }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark text-center   ">
                                                            {{ $applicant->four_ps }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border"><i
                                                                class="bi bi-telephone me-1"></i>{{ $applicant->contact_no }}</span>
                                                    </td>
                                                    <td class="text-muted small">
                                                        {{ $applicant->address_line }}, {{ $applicant->barangay }}, {{ $applicant->city }}
                                                    </td>

                                                    {{-- Mayor's Permit Column --}}
                                                    <td class="text-center">
                                                        @php
                                                            $permit = optional($applicant->permit);

                                                            // Detect Imus resident safely
                                                            $isImusResident = $applicant->city && stripos($applicant->city, 'City of Imus') !== false;

                                                            // CLEARANCE LOGIC (NEW)
                                                            $hasClearance =
                                                                ($permit->clearance_type === 'nbi' && !empty($permit->permit_nbi_clearance)) ||
                                                                ($permit->clearance_type === 'police' && !empty($permit->permit_police_clearance));

                                                            // REQUIREMENTS LIST (UPDATED)
                                                            $pReqs = [
                                                                !empty($permit->health_card),
                                                                !empty($permit->cedula),
                                                                $hasClearance,
                                                            ];

                                                            // Add referral ONLY if NOT Imus
                                                            if (!$isImusResident) {
                                                                $pReqs[] = !empty($permit->referral_letter);
                                                            }

                                                            $pTotal = count($pReqs);
                                                            $pUploaded = collect($pReqs)->filter()->count();
                                                            $pPerc = $pTotal > 0 ? ($pUploaded / $pTotal) * 100 : 0;
                                                        @endphp

                                                        <div class="progress mb-1" style="height: 6px;">
                                                            <div class="progress-bar 
                                {{ $pPerc == 100 ? 'bg-success' : ($pPerc > 0 ? 'bg-warning' : 'bg-danger') }}"
                                                                style="width: {{ $pPerc }}%;">
                                                            </div>
                                                        </div>

                                                        <div class="text-center" style="font-size: 0.75rem;">
                                                            <small class="text-muted">
                                                                {{ $pUploaded }} / {{ $pTotal }} Submitted
                                                            </small>
                                                        </div>
                                                    </td>

                                                    {{-- Mayor's Clearance Column --}}
                                                    <td>
                                                        @php
                                                            $clearance = optional($applicant->clearance);
                                                            $cReqs = [$clearance->prosecutor_clearance, $clearance->mtc_clearance, $clearance->rtc_clearance, $clearance->nbi_clearance, $clearance->barangay_clearance];
                                                            $cUploaded = collect($cReqs)->filter()->count();
                                                            $cTotal = count($cReqs);
                                                            $cPerc = $cTotal > 0 ? ($cUploaded / $cTotal) * 100 : 0;
                                                        @endphp
                                                        <div class="progress mb-1" style="height: 6px;">
                                                            <div class="progress-bar {{ $cPerc == 100 ? 'bg-success' : ($cPerc > 0 ? 'bg-warning' : 'bg-danger') }}"
                                                                style="width: {{ $cPerc }}%;"></div>
                                                        </div>
                                                        <div class="text-center" style="font-size: 0.7rem;"><small class="text-muted">
                                                                {{ $cUploaded }} / {{ $cTotal }} Submitted
                                                            </small></div>
                                                    </td>

                                                    {{-- Mayor's Referral Column --}}
                                                    <td>
                                                        @php
                                                            $referral = optional($applicant->referral);
                                                            $hasResume = !empty($referral->resume);
                                                            $hasOneClearance = collect([$referral->ref_barangay_clearance, $referral->ref_police_clearance, $referral->ref_nbi_clearance])->filter()->count() > 0;
                                                            $rUploaded = ($hasResume ? 1 : 0) + ($hasOneClearance ? 1 : 0);
                                                            $rPerc = ($rUploaded / 2) * 100;
                                                        @endphp
                                                        <div class="progress mb-1" style="height: 6px;">
                                                            <div class="progress-bar {{ $rPerc == 100 ? 'bg-success' : ($rPerc > 0 ? 'bg-warning' : 'bg-danger') }}"
                                                                style="width: {{ $rPerc }}%;"></div>
                                                        </div>
                                                        <div class="text-center" style="font-size: 0.7rem;"><small class="text-muted">
                                                                {{ $rUploaded }} / 2 Submitted
                                                            </small></div>
                                                    </td>

                                                    <td class="text-muted small text-center">
                                                        {{ $applicant->created_at->format('M d, Y') }}
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <a href="{{ route('applicants.edit', $applicant->id) }}"
                                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                                <i class="bi bi-eye-fill"></i>
                                                            </a>

                                                            <form action="{{ route('applicants.destroy', $applicant->id) }}" method="POST"
                                                                onsubmit="return confirm('Are you sure you want to archive this applicant?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Archive">
                                                                    <i class="bi bi-archive"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </h5>
                                            </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center p-5">
                                        <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3">No applicants found matching your criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $applicants->firstItem() }} to {{ $applicants->lastItem() }} of {{ $applicants->total() }}
                    applicants
                </div>
                <div>
                    {{ $applicants->links() }}
                </div>
            </div>

        </div>
@endsection