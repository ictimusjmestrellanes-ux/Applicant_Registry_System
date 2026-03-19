@extends('layouts.app')

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

    <style>
        /* Document Grid Styling */
        .document-upload-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.25rem;
            height: 100%;
            transition: all 0.2s ease;
        }

        .document-upload-card:hover {
            border-color: #2563eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* File Name Display refined */
        .file-name-text {
            font-size: 0.8rem;
            color: #64748b;
            display: block;
            margin-top: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        /* Specialized label for required marks */
        .required-mark {
            color: #ef4444;
            margin-left: 3px;
            font-weight: bold;
        }

        .activity-log-card {
            background: linear-gradient(180deg, #fbfdff, #f3f7fc);
            border: 1px solid #dbe7f3;
            border-radius: 14px;
            padding: 1rem 1.25rem;
        }

        .activity-log-item + .activity-log-item {
            border-top: 1px solid #e6edf5;
            margin-top: 1rem;
            padding-top: 1rem;
        }

        .activity-log-meta {
            font-size: 0.82rem;
            color: #64748b;
        }

        .activity-log-badge {
            background: #e0ecff;
            color: #2952a3;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.7rem;
            font-size: 0.78rem;
            font-weight: 600;
            margin: 0.2rem 0.35rem 0 0;
        }

        /* Disabled State Styling */
        .upload-disabled {
            background-color: #f8fafc;
            opacity: 0.7;
            cursor: not-allowed;
        }
        /* ================================
                                        GLOBAL STYLES
                                        ================================ */

        body {
            background: linear-gradient(135deg, #eef2f7, #e8edf5);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ================================
                                        MAIN WRAPPER
                                        ================================ */

        .applicant-wrapper {
            max-width: 1700px;
        }

        /* ================================
                                        PAGE HEADER
                                        ================================ */

        .page-header {
            background: linear-gradient(135deg, #ffffff, #f6f9ff);
            padding: 20px 25px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e6ecf5;
        }

        .page-header h2 {
            font-weight: 700;
            color: #2c3e50;
        }

        /* ================================
                                        MAIN CARD CONTAINER
                                        ================================ */

        .requirements-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        /* ================================
                                        SECTION TITLES
                                        ================================ */

        .section-title {
            font-weight: 700;
            font-size: 15px;
            color: #3b4a6b;
            letter-spacing: .3px;
            margin-bottom: 15px;
        }

        /* ================================
                                        NAV TABS
                                        ================================ */

        .nav-tabs {
            border: none;
            gap: 10px;
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            background: #f4f6fb;
            color: #5b6b8b;
            font-weight: 600;
            transition: all .3s ease;
        }

        .nav-tabs .nav-link:hover {
            background: #e9efff;
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #4a7dff, #5fa8ff);
            color: white;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
        }

        /* ================================
                                        TAB CONTENT
                                        ================================ */

        .tab-content {
            border-radius: 14px;
            background: white;
            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        /* ================================
                                        FORM CARD
                                        ================================ */

        .form-card {
            padding: 10px 5px;
        }

        /* ================================
                                        FORM LABEL
                                        ================================ */

        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #44526f;
        }

        /* ================================
                                        INPUTS
                                        ================================ */

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #dce3ef;
            padding: 10px 12px;
            font-size: 14px;
            transition: all .25s ease;
            background: #f9fbff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #5fa8ff;
            background: white;
            box-shadow: 0 0 0 3px rgba(90, 150, 255, 0.15);
        }

        /* ================================
                                        FILE INPUTS
                                        ================================ */

        input[type=file] {
            background: #f5f8ff;
            border: 1px dashed #c9d5f2;
        }

        /* ================================
                                        BUTTONS
                                        ================================ */

        .btn {
            border-radius: 10px;
            font-weight: 600;
            transition: all .3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a7dff, #6aa8ff);
            border: none;
            box-shadow: 0 6px 15px rgba(74, 125, 255, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(74, 125, 255, 0.35);
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border: none;
            box-shadow: 0 6px 15px rgba(39, 174, 96, 0.35);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(39, 174, 96, 0.45);
        }

        .btn-outline-secondary {
            border-radius: 10px;
        }

        /* ================================
                                        FORM GRID RESPONSIVE
                                        ================================ */

        @media(max-width:1200px) {

            .col-md-2 {
                flex: 0 0 33%;
                max-width: 33%;
            }

        }

        @media(max-width:768px) {

            .col-md-2,
            .col-md-3,
            .col-md-4,
            .col-md-5,
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .page-header {
                flex-direction: column;
                gap: 10px;
            }

        }

        /* ================================
                                        3D CARD EFFECT
                                        ================================ */

        .requirements-container:hover {
            transform: translateY(-2px);
            box-shadow:
                0 18px 50px rgba(0, 0, 0, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: .4s;
        }

        /* ================================
                                        REQUIRED MARK
                                        ================================ */

        .required-mark {
            color: #e74c3c;
            font-weight: bold;
        }

        /* ================================
                                        ANIMATIONS
                                        ================================ */

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

        /* ================================
                                        SCROLL SMOOTH
                                        ================================ */

        html {
            scroll-behavior: smooth;
        }
    </style>

    <div class="container applicant-wrapper">
        <div class="page-header d-md-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-user-pen me-2 text-primary"></i>Update Applicant</h2>
            <a href="{{ route('applicants.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="requirements-container p-4">

            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-1">Document Compliance</h5>
                    <p class="text-muted small mb-0">Manage Mayor's Permit, Clearance, and Referral Requirements.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if($applicant->activityLogs->isNotEmpty())
                <div class="activity-log-card mb-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <h6 class="fw-bold text-primary mb-1">Recent Activity Log</h6>
                            <p class="text-muted small mb-0">Latest changes made to this applicant's documents.</p>
                        </div>
                        <span class="badge text-bg-light border">{{ $applicant->activityLogs->count() }} record(s)</span>
                    </div>

                    @foreach($applicant->activityLogs->take(10) as $log)
                        <div class="activity-log-item">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <div>
                                    <div class="fw-semibold text-dark">{{ $log->description }}</div>
                                    <div class="activity-log-meta mt-1">
                                        {{ $log->created_at->format('F d, Y h:i A') }}
                                        by {{ $log->causer->name ?? 'System' }}
                                    </div>
                                </div>
                                <span class="badge {{ $log->action === 'created' ? 'text-bg-success' : 'text-bg-primary' }}">
                                    {{ strtoupper($log->action) }}
                                </span>
                            </div>

                            @if(!empty($log->changes))
                                <div class="mt-2">
                                    @foreach($log->changes as $change)
                                        <span class="activity-log-badge">{{ $change['label'] }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <ul class="nav nav-tabs mb-3" id="mayorTabs">

                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal">
                        Personal Information
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#permit">
                        Mayor's Permit to Work
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#clearance">
                        Mayor's Clearance
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#referral">
                        Mayor's Referral
                    </button>
                </li>

            </ul>

            <div class="tab-content bg-white p-4 rounded-3 border shadow-sm">

                <!-- ===================================================== -->
                <!-- PERSONAL INFORMATION -->
                <!-- ===================================================== -->

                <div class="tab-pane fade show active" id="personal">

                    <div class="form-card">

                        <form action="{{ route('applicants.update', $applicant->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <h6 class="section-title  text-primary mb-4">Personal Information</h6>

                            <div class="row g-4">

                                {{-- FIRST TIME JOB SEEKER --}}
                                <div class="col-md-2">
                                    <label class="form-label">First Time Job Seeker?</label>
                                    <select name="first_time_job_seeker" class="form-select">
                                        <option value="No" {{ $applicant->first_time_job_seeker == "No" ? 'selected' : '' }}>
                                            No
                                        </option>
                                        <option value="Yes" {{ $applicant->first_time_job_seeker == "Yes" ? 'selected' : '' }}>Yes
                                        </option>
                                    </select>
                                </div>

                                {{-- FIRST NAME --}}
                                <div class="col-md-2">
                                    <label class="form-label">First Name <span class="required-mark">*</span></label>
                                    <input type="text" name="first_name" value="{{ $applicant->first_name }}"
                                        class="form-control" required>
                                </div>

                                {{-- MIDDLE NAME --}}
                                <div class="col-md-2">
                                    <label class="form-label">Middle Name (Optional)</label>
                                    <input type="text" name="middle_name" value="{{ $applicant->middle_name }}"
                                        class="form-control">
                                </div>

                                {{-- LAST NAME --}}
                                <div class="col-md-2">
                                    <label class="form-label">Last Name <span class="required-mark">*</span></label>
                                    <input type="text" name="last_name" value="{{ $applicant->last_name }}"
                                        class="form-control" required>
                                </div>

                                {{-- SUFFIX --}}
                                <div class="col-md-2">
                                    <label class="form-label">Suffix (Optional)</label>
                                    <select name="suffix" class="form-select">
                                        <option value="">None</option>
                                        <option value="Jr." {{ $applicant->suffix == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                        <option value="Sr." {{ $applicant->suffix == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                        <option value="II" {{ $applicant->suffix == 'II' ? 'selected' : '' }}>II</option>
                                        <option value="III" {{ $applicant->suffix == 'III' ? 'selected' : '' }}>III</option>
                                        <option value="IV" {{ $applicant->suffix == 'IV' ? 'selected' : '' }}>IV</option>
                                    </select>
                                </div>

                                {{-- AGE --}}
                                <div class="col-md-2">
                                    <label class="form-label">Age <span class="required-mark">*</span></label>
                                    <input type="number" name="age" value="{{ $applicant->age }}" class="form-control"
                                        required>
                                </div>

                                {{-- GENDER --}}
                                <div class="col-md-2">
                                    <label class="form-label">Gender <span class="required-mark">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="Male" {{ $applicant->gender == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female" {{ $applicant->gender == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                </div>

                                {{-- CIVIL STATUS --}}
                                <div class="col-md-2">
                                    <label class="form-label">Civil Status<span class="required-mark">*</span></label>
                                    <select name="civil_status" class="form-select" required>
                                        <option value="Single" {{ $applicant->civil_status == 'Single' ? 'selected' : '' }}>
                                            Single</option>
                                        <option value="Married" {{ $applicant->civil_status == 'Married' ? 'selected' : '' }}>
                                            Married</option>
                                        <option value="Widowed" {{ $applicant->civil_status == 'Widowed' ? 'selected' : '' }}>
                                            Widowed</option>
                                    </select>
                                </div>

                                {{-- PWD --}}
                                <div class="col-md-1">
                                    <label class="form-label">PWD<span class="required-mark">*</span></label>
                                    <select name="pwd" class="form-select" required>
                                        <option value="No" {{ $applicant->pwd == "No" ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ $applicant->pwd == "Yes" ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>

                                {{-- 4PS --}}
                                <div class="col-md-1">
                                    <label class="form-label">4Ps<span class="required-mark">*</span></label>
                                    <select name="four_ps" class="form-select" required>
                                        <option value="No" {{ $applicant->four_ps == "No" ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ $applicant->four_ps == "Yes" ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>

                                {{-- CONTACT --}}
                                <div class="col-md-3">
                                    <label class="form-label">Contact No<span class="required-mark">*</span></label>
                                    <input type="text" name="contact_no" value="{{ $applicant->contact_no }}"
                                        class="form-control" required>
                                </div>

                                {{-- ADDRESS --}}
                                <div class="col-md-5">
                                    <label class="form-label">Complete Address<span class="required-mark">*</span></label>
                                    <input type="text" name="address_line" value="{{ $applicant->address_line }}"
                                        class="form-control" required>
                                </div>

                                {{-- PROVINCE --}}
                                <div class="col-md-2">
                                    <label class="form-label">Province<span class="required-mark">*</span></label>
                                    <select name="province" id="province" class="form-select" required></select>
                                </div>

                                {{-- CITY --}}
                                <div class="col-md-2">
                                    <label class="form-label">City<span class="required-mark">*</span></label>
                                    <select name="city" id="city" class="form-select" required></select>
                                </div>

                                {{-- BARANGAY --}}
                                <div class="col-md-2">
                                    <label class="form-label">Barangay<span class="required-mark">*</span></label>
                                    <select name="barangay" id="barangay" class="form-select" required></select>
                                </div>

                                {{-- EDUCATION --}}
                                <div class="col-md-4">
                                    <label class="form-label">Educational Attainment<span
                                            class="required-mark">*</span></label>
                                    <input type="text" name="educational_attainment"
                                        value="{{ $applicant->educational_attainment }}" class="form-control" required>
                                </div>

                                {{-- COMPANY --}}
                                <div class="col-md-4">
                                    <label class="form-label">Hiring Company<span class="required-mark">*</span></label>
                                    <input type="text" name="hiring_company" value="{{ $applicant->hiring_company }}"
                                        class="form-control" required>
                                </div>

                                {{-- POSITION --}}
                                <div class="col-md-4">
                                    <label class="form-label">Position Hired<span class="required-mark">*</span></label>
                                    <input type="text" name="position_hired" value="{{ $applicant->position_hired }}"
                                        class="form-control" required>
                                </div>

                            </div>


                            <div class="d-flex gap-3 pt-4 mt-4 border-top">

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
                    <form action="{{ route('permits.update', $applicant->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @php
                            $permit = optional($applicant->permit);
                        @endphp

                        @php
                            $permit = optional($applicant->permit);
                            $isImusResident = stripos($applicant->city, 'City of Imus') !== false;
                        @endphp

                        <h6 class="section-title mb-4">Mayor’s Permit to Work Requirements</h6>

                        <div class="row g-3">
                            {{-- 1. NBI / Police Clearance --}}
                            <div class="col-md-3">
                                <div class="document-upload-card">
                                    <label class="form-label">Clearance Type <span class="required-mark">*</span></label>
                                    <select name="clearance_type" id="clearance_type" class="form-select form-select-sm mb-3">
                                        <option value="">Select Type</option>
                                        <option value="nbi" {{ old('clearance_type', $permit->clearance_type ?? '') == 'nbi' ? 'selected' : '' }}>
                                            NBI Clearance
                                        </option>
                                        <option value="police" {{ old('clearance_type', $permit->clearance_type ?? '') == 'police' ? 'selected' : '' }}>
                                            Police Clearance
                                        </option>
                                    </select>

                                    <div class="gap-2"  id="nbi_section" style="display:none">
                                        <input type="file" id="nbi_input" name="permit_nbi_clearance" style="display:none" onchange="showFileName(this, 'nbi_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('nbi_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        
                                        <small id="nbi_name" class="file-name-text">
                                            {{ !empty($permit->permit_nbi_clearance) ? basename($permit->permit_nbi_clearance) : 'No file selected' }}
                                        </small>

                                        @if(!empty($permit->permit_nbi_clearance))
                                            <a href="{{ asset('storage/'.$permit->permit_nbi_clearance) }}" target="_blank" class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                    <div class="gap-2"  id="police_section" style="display:none">
                                        <input type="file" id="police_input" name="permit_police_clearance" style="display:none" onchange="showFileName(this, 'police_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('police_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        
                                        <small id="police_name" class="file-name-text">
                                            {{ !empty($permit->permit_police_clearance) ? basename($permit->permit_police_clearance) : 'No file selected' }}
                                        </small>

                                        @if(!empty($permit->permit_police_clearance))
                                            <a href="{{ asset('storage/'.$permit->permit_police_clearance) }}" target="_blank" class="btn btn-light btn-sm text-primary border">
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
                                        <input type="file" id="health_card_input" name="health_card" style="display:none" onchange="showFileName(this, 'health_card_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('health_card_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="health_card_name" class="file-name-text">
                                            {{ !empty($permit->health_card) ? basename($permit->health_card) : 'No file selected' }}
                                        </small>
                                        @if(!empty($permit->health_card))
                                            <a href="{{ asset('storage/'.$permit->health_card) }}" target="_blank" class="btn btn-light btn-sm text-primary border">
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
                                        <input type="file" id="cedula_input" name="cedula" style="display:none" onchange="showFileName(this, 'cedula_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('cedula_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="cedula_name" class="file-name-text">
                                            {{ !empty($permit->cedula) ? basename($permit->cedula) : 'No file selected' }}
                                        </small>
                                        @if(!empty($permit->cedula))
                                            <a href="{{ asset('storage/'.$permit->cedula) }}" target="_blank" class="btn btn-light btn-sm text-primary border">
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
                                            onchange="showFileName(this, 'referral_name')"
                                            {{ $isImusResident ? 'disabled' : '' }}>
                                        
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                onclick="document.getElementById('referral_input').click()"
                                                {{ $isImusResident ? 'disabled' : '' }}>
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>

                                        <small id="referral_name" class="file-name-text">
                                            {{ !empty($permit->referral_letter) ? basename($permit->referral_letter) : 'No file selected' }}
                                        </small>

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

                            {{-- OR NUMBER --}}
                            <div class="col-md-2">
                                <label class="form-label">O.R No. <span class="required-mark">*</span></label>
                                <input type="text" name="permit_or_no" value="{{ $permit->permit_or_no }}"
                                    class="form-control" required>
                            </div>

                            {{-- Peso ID No --}}
                            <div class="col-md-2">
                                <label class="form-label">Peso ID No. (Auto Generate)<span class="required-mark">*</span></label>
                                <input type="text" name="peso_id_no" class="form-control" style="text-align: center"
                                    value="{{ $permit->peso_id_no ?? '' }}" readonly>
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
                            {{-- Permit Date --}}
                            <div class="col-md-2">
                                <label class="form-label">Permit Date<span class="required-mark">*</span></label>
                                <input type="date" id="permit_date" name="permit_date" class="form-control" value="{{$permit->permit_date}}"
                                    required>
                            </div>

                            {{-- Expiration --}}
                            <div class="col-md-2">
                                <label class="form-label">Expires On<span class="required-mark">*</span></label>
                                <input type="date" id="expires_on" name="expires_on" class="form-control" value="{{$permit->expires_on}}"
                                    readonly>
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

                        <div class="pt-3 border-top mt-4 d-flex gap-2">

                            <button type="submit" class="btn btn-primary px-4">
                                Save Permit
                            </button>

                            <div class="pt-3">

                                @if($permit && $permit->isComplete())
                                    <a href="{{ route('permits.printId', $applicant->id) }}"
                                    target="_blank"
                                    class="btn btn-success px-4">
                                        <i class="fa-solid fa-id-card me-2"></i>
                                        Print Permit ID
                                    </a>
                                @else
                                    <button class="btn btn-secondary px-4" disabled>
                                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                                        Complete all requirements to print ID
                                    </button>
                                @endif

                            </div>
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

                        <div class="d-flex gap-3 overflow-auto pb-2">

                            <div class="col-md-2">
                                <div class="document-upload-card">
                                    <label class="form-label">Prosecutor Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="prosecutor_input" name="prosecutor_clearance" style="display:none" onchange="showFileName(this, 'prosecutor_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('prosecutor_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="prosecutor_name" class="file-name-text">
                                            {{ !empty($clearance->prosecutor_clearance) ? basename($clearance->prosecutor_clearance) : 'No file selected' }}
                                        </small>
                                        @if(!empty($clearance->prosecutor_clearance))
                                            <a href="{{ asset('storage/'.$clearance->prosecutor_clearance) }}" target="_blank" class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="document-upload-card">
                                    <label class="form-label">Municipal Trial Court Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="mtc_input" name="mtc_clearance" style="display:none" onchange="showFileName(this, 'mtc_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('mtc_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="mtc_name" class="file-name-text">
                                            {{ !empty($clearance->mtc_clearance) ? basename($clearance->mtc_clearance) : 'No file selected' }}
                                        </small>
                                        @if(!empty($clearance->mtc_clearance))
                                            <a href="{{ asset('storage/'.$clearance->mtc_clearance) }}" target="_blank" class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="document-upload-card">
                                    <label class="form-label">Regional Trial Court Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="rtc_input" name="rtc_clearance" style="display:none" onchange="showFileName(this, 'rtc_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('rtc_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="rtc_name" class="file-name-text">
                                            {{ !empty($clearance->rtc_clearance) ? basename($clearance->rtc_clearance) : 'No file selected' }}
                                        </small>
                                        @if(!empty($clearance->rtc_clearance))
                                            <a href="{{ asset('storage/'.$clearance->rtc_clearance) }}" target="_blank" class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="document-upload-card">
                                    <label class="form-label">NBI Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="c_nbi_input" name="nbi_clearance" style="display:none" onchange="showFileName(this, 'c_nbi_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('c_nbi_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="c_nbi_name" class="file-name-text">
                                            {{ !empty($clearance->nbi_clearance) ? basename($clearance->nbi_clearance) : 'No file selected' }}
                                        </small>
                                        @if(!empty($clearance->nbi_clearance))
                                            <a href="{{ asset('storage/'.$clearance->nbi_clearance) }}" target="_blank" class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="document-upload-card">
                                    <label class="form-label">Barangay Clearance<span class="required-mark">*</span></label>
                                    <div class="d-grid gap-2">
                                        <input type="file" id="brgy_input" name="barangay_clearance" style="display:none" onchange="showFileName(this, 'brgy_name')">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('brgy_input').click()">
                                            <i class="fas fa-upload me-1"></i> Upload File
                                        </button>
                                        <small id="brgy_name" class="file-name-text">
                                            {{ !empty($clearance->barangay_clearance) ? basename($clearance->barangay_clearance) : 'No file selected' }}
                                        </small>
                                        @if(!empty($clearance->barangay_clearance))
                                            <a href="{{ asset('storage/'.$clearance->barangay_clearance) }}" target="_blank" class="btn btn-light btn-sm text-primary border">
                                                <i class="fas fa-eye me-1"></i> View Current
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        <h6 class="section-title text-primary mb-0 mt-4">Mayor’s Clearance Letter Details</h6>
                        <div class="row g-3 mt-3">
                            {{-- Official Receipt No --}}
                            <div class="col-md-2">
                                <label class="form-label">O.R. No.<span class="required-mark">*</span></label>
                                <input type="text" name="clearance_or_no" class="form-control"
                                    value="{{$clearance->clearance_or_no}}" required>
                            </div>
                            {{-- PESO Control No --}}
                            <div class="col-md-2">
                                <label class="form-label">PESO Control No.<span class="required-mark">*</span></label>
                                <input type="text" name="peso_id_no" class="form-control" value="{{ $clearance->clearance_peso_control_no }}" readonly>
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
                            {{-- Issued In --}}
                            <div class="col-md-2">
                                <label class="form-label">Issued In<span class="required-mark">*</span></label>
                                <input type="text" name="clearance_issued_in" class="form-control"
                                    value="{{$clearance->clearance_issued_in}}" required>
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

                        <div class="pt-4 border-top mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                Save Clearance
                            </button>

                            <div class="mt-3">

                                @if($clearance && $clearance->isComplete())
                                    <a href="{{ route('clearances.printLetter', $applicant->id) }}"
                                    target="_blank"
                                    class="btn btn-success">
                                        <i class="fa-solid fa-print me-1"></i>
                                        Print Clearance Letter
                                    </a>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        Complete requirements to print letter
                                    </button>
                                @endif

                            </div>
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

                        <div class="mb-4">
                            <label class="form-label">Resume / Bio-data</label>
                            <input type="file" name="resume" class="form-control">
                        </div>

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">Barangay Clearance</label>
                                <input type="file" name="ref_barangay_clearance" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Police Clearance</label>
                                <input type="file" name="ref_police_clearance" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">NBI Clearance</label>
                                <input type="file" name="ref_nbi_clearance" class="form-control">
                            </div>

                        </div>

                        <!-- INNER TABS -->
                        <div class="mt-4">
                            <h6 class="section-title text-primary">Mayor's Referral Requirements</h6>

                            <ul class="nav nav-tabs" id="referralTabs" role="tablist">


                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="peso-office-tab" data-bs-toggle="tab"
                                        data-bs-target="#peso-office" type="button" role="tab">
                                        PESO Office
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="other-city-tab" data-bs-toggle="tab"
                                        data-bs-target="#other-city" type="button" role="tab">
                                        Referral for Other City Government
                                    </button>
                                </li>

                            </ul>


                            <div class="tab-content border border-top-0 p-4">

                                <!-- TAB 1 -->
                                <div class="tab-pane fade show active" id="peso-office" role="tabpanel">

                                    <div class="row g-3">

                                        <div class="col-md-2">
                                            <label class="form-label">O.R No.</label>
                                            <input type="text" name="ref_or_no" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Mayor's First Name</label>
                                            <input type="text" name="ref_mayor_recipient_firstname" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Mayor's Middle Name</label>
                                            <input type="text" name="ref_mayor_recipient_middlename" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Mayor's Last Name</label>
                                            <input type="text" name="ref_mayor_recipient_lastname" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">City Government</label>
                                            <select name="ref_city_gov" id="cityGovernment" class="form-select">
                                                <option value="">Select City Government</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label"> City Address</label>
                                            <input type="text" name="ref_place" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Hired Company</label>
                                            <input type="text" name="ref_hired_company" class="form-control">
                                        </div>

                                    </div>

                                </div>

                                <!-- TAB 2 -->

                                <div class="tab-pane fade" id="other-city" role="tabpanel">

                                    <div class="row g-3">

                                        <div class="col-md-3">
                                            <label class="form-label">O.R No.</label>
                                            <input type="text" name="ref_peso_or_no" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Recipient Name</label>
                                            <input type="text" name="ref_recipient" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Company Address</label>
                                            <input type="text" name="ref_place" class="form-control">
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="pt-4 border-top mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                Save Referral
                            </button>
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

        const cityDropdown = document.getElementById("cityGovernment");

        const allowedRegions = [
            "130000000", // NCR
            "040000000"  // Region 4A (CALABARZON)
        ];

        fetch("https://psgc.gitlab.io/api/cities-municipalities/")
            .then(response => response.json())
            .then(data => {

                data.forEach(city => {

                    if (allowedRegions.includes(city.regionCode)) {

                        const option = document.createElement("option");

                        option.value = "City Government of " + city.name;
                        option.text = "City Government of " + city.name;

                        cityDropdown.appendChild(option);

                    }

                });

            })
            .catch(error => console.error("Error loading cities:", error));

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
