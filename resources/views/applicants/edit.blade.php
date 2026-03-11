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
        color: var(--text-main);
        line-height: 1.6;
    }

    .applicant-wrapper {
        max-width: 1700px;
        margin: 40px auto;
        padding-bottom: 100px;
    }

    .page-header h2 {
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
    }

    /* Main Card Container */
    .main-card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.1);
        background: #ffffff;
        overflow: hidden;
    }

    /* Requirements Section Styling */
    .requirements-container {
        background-color: #f8fafc;
        border-radius: var(--border-radius);
        border: 1px solid #e2e8f0;
        margin-bottom: 2.5rem;
    }

    /* Modern Tabs */
    .nav-tabs {
        border-bottom: 2px solid #e2e8f0;
        gap: 5px;
    }

    .nav-tabs .nav-link {
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s ease;
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        background-color: #ffffff;
        border-bottom: 3px solid var(--primary-color);
    }

    .nav-tabs .nav-link:hover:not(.active) {
        background-color: #f1f5f9;
        color: var(--primary-hover);
    }

    /* Form Styling */
    .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.15);
    }

    /* File Preview Box - The Blue Accent */
    .file-status-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f0f9ff;
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid #bae6fd;
        margin-top: 8px;
    }

    .file-name {
        font-size: 0.8rem;
        color: #0369a1;
        font-weight: 500;
    }

    /* Buttons */
    .btn-success {
        background-color: var(--success-color);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 8px;
        transition: transform 0.1s ease, background-color 0.2s ease;
    }

    .btn-success:hover {
        background-color: var(--success-hover);
        transform: translateY(-1px);
    }

    .btn-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
        font-weight: 500;
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* Section Headings */
    .section-title {
        position: relative;
        padding-left: 15px;
        border-left: 4px solid var(--primary-color);
        margin-bottom: 1.5rem;
        font-weight: 700;
        color: #0f172a;
    }

    /* Special text colors */
    .text-primary { color: var(--primary-color) !important; }
    .text-success { color: var(--success-color) !important; }

    .btn-generate {
        background-color: #0284c7;
        border-color: #0284c7;
        color: white;
        transition: all 0.2s ease;
    }

    .btn-generate:hover {
        background-color: #0369a1;
        border-color: #0369a1;
        color: white;
        transform: translateY(-1px);
    }

    .btn-generate:active {
        background-color: #0369a1;
    }

    /* Health Card */
    .upload-group .file-label{
    max-width:700px;
    overflow:hidden;
    text-overflow:ellipsis;
    white-space:nowrap;
    font-size:13px;
    }

    .upload-group .btn{
        border-radius:0 6px 6px 0;
    }

    .upload-group input[type="file"]{
        cursor:pointer;
    }

    /* Responsive Fixes */
    @media (max-width: 768px) {
        .nav-tabs {
            flex-direction: column;
        }
        .btn-generate {
            width: 100%;
            margin-top: 15px;
        }
    }
    </style>

    {{-- <style>
    :root {
        /* New Refined Palette: Indigo & Slate */
        --primary: #6366f1; 
        --primary-hover: #4f46e5;
        --success: #10b981;
        --bg-body: #f8fafc;
        --bg-card: #ffffff;
        --text-heading: #0f172a;
        --text-body: #334155;
        --text-light: #64748b;
        --border-soft: #e2e8f0;
        --radius: 12px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
        --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.05);
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: var(--text-body);
        -webkit-font-smoothing: antialiased;
    }

    .applicant-wrapper {
        max-width: 1400px; /* More contained for better readability */
        margin: 2rem auto;
        padding: 0 1.5rem 5rem 1.5rem;
    }

    /* Page Header */
    .page-header h2 {
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--text-heading);
    }

    /* Modern Card Overhaul */
    .main-card {
        border: 1px solid var(--border-soft);
        border-radius: var(--radius);
        background: var(--bg-card);
        box-shadow: var(--shadow-md);
        padding: 1.5rem;
    }

    /* Requirements Container - "The Feature Box" */
    .requirements-container {
        background-color: #f1f5f9;
        border-radius: var(--radius);
        border: 1px dashed #cbd5e1; /* Dashed looks better for lists/notes */
        padding: 1.5rem;
        transition: border-color 0.3s ease;
    }

    /* Nav Tabs - Underline Style */
    .nav-tabs {
        border-bottom: 2px solid var(--border-soft);
        gap: 2rem;
    }

    .nav-tabs .nav-link {
        border: none;
        color: var(--text-light);
        font-weight: 600;
        font-size: 0.95rem;
        padding: 1rem 0;
        background: transparent;
        position: relative;
        transition: color 0.2s ease;
    }

    .nav-tabs .nav-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--primary);
        transition: width 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        color: var(--primary);
        background: transparent;
    }

    .nav-tabs .nav-link.active::after {
        width: 100%;
    }

    /* Form Controls - Minimalist */
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-heading);
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .form-control, .form-select {
        border: 1.5px solid var(--border-soft);
        padding: 0.6rem 0.8rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    /* File Status Box - Modernized blue */
    .file-status-box {
        background: #eef2ff;
        border: 1px solid #c7d2fe;
        color: #4338ca;
        padding: 12px;
        border-radius: 8px;
        font-weight: 500;
    }

    /* Buttons - Elevated Primary */
    .btn-success {
        background: var(--success);
        border: none;
        box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.3);
        padding: 10px 24px;
        font-weight: 600;
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.4);
    }

    .btn-generate {
        background: var(--primary);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
    }

    /* Section Title - Cleaner indicator */
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-heading);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title::before {
        content: '';
        width: 4px;
        height: 18px;
        background: var(--primary);
        border-radius: 10px;
        display: inline-block;
    }

    /* Animations */
    .tab-pane {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .nav-tabs { gap: 1rem; }
        .nav-tabs .nav-link { padding: 0.5rem 0; }
    }
    </style> --}}
    {{-- <style>
        :root {
            /* Professional Palette: Deep Slates & Refined Cobalt */
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-600: #475569;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;
            
            --brand-primary: #2563eb; /* Professional Cobalt */
            --brand-success: #059669;
            
            --radius-sm: 6px;
            --radius-md: 8px;
            --font-main: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #f4f7fa;
            font-family: var(--font-main);
            color: var(--neutral-800);
            font-size: 14px; /* Standard enterprise font size */
        }

        /* Container Refinement */
        .applicant-wrapper {
            max-width: 1440px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header: Clean & Authoritative */
        .page-header h2 {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--neutral-900);
            letter-spacing: -0.02em;
        }

        /* Main Card: Flat with subtle border (The "System" Look) */
        .main-card {
            background: #ffffff;
            border: 1px solid var(--neutral-200);
            border-radius: var(--radius-md);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        /* Requirements Section: Information Density */
        .requirements-container {
            background-color: var(--neutral-50);
            border: 1px solid var(--neutral-200);
            border-left: 4px solid var(--brand-primary); /* Indication of importance */
            border-radius: var(--radius-sm);
            padding: 1.25rem;
        }

        /* Tabs: Minimalist Navigation */
        .nav-tabs {
            border-bottom: 1px solid var(--neutral-200);
            gap: 0;
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: var(--neutral-600);
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0;
            margin-bottom: -1px;
            transition: all 0.2s;
        }

        .nav-tabs .nav-link:hover {
            background: var(--neutral-100);
            color: var(--neutral-900);
        }

        .nav-tabs .nav-link.active {
            color: var(--brand-primary);
            background: transparent;
            border-bottom: 2px solid var(--brand-primary);
            font-weight: 600;
        }

        /* Inputs: Focused & Sharp */
        .form-label {
            font-weight: 500;
            color: var(--neutral-600);
            margin-bottom: 0.4rem;
        }

        .form-control, .form-select {
            border: 1px solid var(--neutral-200);
            border-radius: var(--radius-sm);
            padding: 0.5rem 0.75rem;
            transition: border-color 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 1px var(--brand-primary); /* Sharp focus, no glow */
        }

        /* File Status: Information Alert Style */
        .file-status-box {
            background: #f0f7ff;
            border: 1px solid #dbeafe;
            color: #1e40af;
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Action Buttons: Solid & Trustworthy */
        .btn-success {
            background-color: var(--brand-success);
            border: 1px solid #047857;
            font-weight: 600;
            border-radius: var(--radius-sm);
            padding: 0.6rem 1.5rem;
            font-size: 14px;
        }

        .btn-success:hover {
            background-color: #059669;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-generate {
            background: white;
            color: var(--brand-primary);
            border: 1px solid var(--brand-primary);
            font-weight: 500;
        }

        .btn-generate:hover {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #1d4ed8;
        }

        /* Section Titles: Clear Hierarchy */
        .section-title {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--neutral-600);
            margin-bottom: 1.25rem;
            display: block;
            border-bottom: 1px solid var(--neutral-100);
            padding-bottom: 0.5rem;
        }
    </style> --}}

    <div class="container applicant-wrapper">
        <div class="page-header d-md-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-user-pen me-2 text-primary"></i>Update Applicant</h2>
            <a href="{{ route('applicants.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <form action="{{ route('applicants.update', $applicant->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="requirements-container p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-1">Document Compliance</h5>
                        <p class="text-muted small mb-0">Manage Mayor's Permit, Clearance, and Referral Requirements.</p>
                    </div>
                </div>
                    
                <ul class="nav nav-tabs mb-3" id="mayorTabs" role="tablist">    
                    <li class="nav-item">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                            data-bs-target="#personal" type="button" role="tab">
                            Personal Information
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" id="permit-tab" data-bs-toggle="tab"
                            data-bs-target="#permit" type="button" role="tab">
                            Mayor's Permit to Work
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" id="clearance-tab" data-bs-toggle="tab"
                            data-bs-target="#clearance" type="button" role="tab">
                            Mayor's Clearance
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" id="referral-tab" data-bs-toggle="tab"
                            data-bs-target="#referral" type="button" role="tab">
                            Mayor's Referral
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content bg-white p-4 rounded-3 border shadow-sm">                        
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <h6 class="section-title text-primary">Personal Information</h6>
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" value="{{ $applicant->first_name }}" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name"
                                    value="{{ $applicant->middle_name }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name"
                                    value="{{ $applicant->last_name }}"
                                    class="form-control" required>
                               </div>

                            <div class="col-md-4">
                                <label class="form-label">Suffix</label>
                                <select name="suffix" class="form-select">
                                    <option value="">None</option>
                                    <option value="Jr." {{ $applicant->suffix == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                    <option value="Sr." {{ $applicant->suffix == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                    <option value="II" {{ $applicant->suffix == 'II' ? 'selected' : '' }}>II</option>
                                    <option value="III" {{ $applicant->suffix == 'III' ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ $applicant->suffix == 'IV' ? 'selected' : '' }}>IV</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sex/Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">None</option>
                                    <option value="Male" {{ $applicant->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $applicant->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Contact No *</label>
                                <input type="text" name="contact_no" value="{{ $applicant->contact_no }}" class="form-control" placeholder="09XXXXXXXXX" required>
                            </div>
                        </div>
                          
                        <h6 class="section-title text-primary">Residential Address</h6>
                        <div class="mb-4">
                            <label class="form-label">Street Address / House No. *</label>
                            <input type="text" name="address_line" value="{{ $applicant->address_line }}" class="form-control" required>
                        </div>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <label class="form-label">Province *</label>
                                <select name="province" id="province" class="form-select" required>
                                    <option>Loading provinces...</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">City *</label>
                                <select name="city" id="city" class="form-select" required>
                                    <option>Select City</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Barangay *</label>
                                <select name="barangay" id="barangay" class="form-select" required>
                                    <option>Select Barangay</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="permit" role="tabpanel">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                            <h6 class="section-title text-primary mb-0">Mayor’s Permit to Work Requirements</h6>
                            @if($applicant->isPermitComplete())
                                <a href="{{ route('applicants.generatePermit', $applicant->id) }}"
                                    class="btn btn-generate btn-sm shadow-sm">
                                    <i class="fa-solid fa-file-pdf me-2"></i>
                                    Generate Mayor's Permit to Work ID
                                    </a>
                            @else
                            <button class="btn btn-secondary btn-sm opacity-75" disabled style="cursor:not-allowed;"> <i class="fa-solid fa-circle-exclamation me-1"></i> 
                                Incomplete Mayor's Permit to Work Requirements
                            </button>
                            @endif
                        </div>

                        @php 
                            $permit = optional($applicant->permit);
                            $isImusResident = stripos($applicant->city, 'City of Imus') !== false;
                        @endphp

                        <div class="row g-4">
                            {{-- HEALTH CARD --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-id-card text-primary me-1"></i>
                                    1. Health Card
                                </label>
                                <div class="input-group upload-group">
                                    <input type="file" name="health_card" class="form-control" >
                                        @if($permit->health_card)
                                        <span class="input-group-text file-name">{{ basename($permit->health_card) }}</span>
                                        <a href="{{ Storage::url($permit->health_card) }}" target="_blank" class="btn btn-outline-primary">
                                            View
                                        </a>
                                        @endif
                                </div>
                            </div>

                                {{-- NBI / POLICE --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-id-card text-primary me-1"></i>
                                    2. NBI Clearance / Police Clearance
                                </label>
                                <div class="input-group upload-group">
                                    <input type="file" name="nbi_or_police_clearance" class="form-control">
                                    @if($permit->nbi_or_police_clearance)
                                        <span class="input-group-text file-name">
                                            {{ basename($permit->nbi_or_police_clearance) }}
                                        </span>
                                        <a href="{{ Storage::url($permit->nbi_or_police_clearance) }}" target="_blank" class="btn btn-outline-primary">
                                        View
                                        </a>
                                    @endif
                                </div>
                            </div>


                                {{-- CEDULA --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-id-card text-primary me-1"></i>
                                    3. Cedula
                                </label>
                                <div class="input-group upload-group">
                                    <input type="file" name="cedula" class="form-control">
                                    @if($permit->cedula)
                                        <span class="input-group-text file-name">
                                            {{ basename($permit->cedula) }}
                                        </span>
                                        <a href="{{ Storage::url($permit->cedula) }}" target="_blank" class="btn btn-outline-primary">
                                        View
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- REFERRAL LETTER --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    4. Referral Letter
                                    @if($isImusResident)
                                        <span class="text-success">(Not Required - Imus Resident)</span>
                                    @else
                                        <span class="text-danger">(Required if not from Imus)</span>
                                    @endif
                                </label>
                                <div class="input-group upload-group">
                                    <input type="file" name="referral_letter" class="form-control" {{ $isImusResident ? 'disabled' : '' }}>
                                    @if($permit->referral_letter)
                                        <span class="input-group-text file-name">
                                            {{ basename($permit->referral_letter) }}
                                        </span>
                                        <a href="{{ Storage::url($permit->referral_letter) }}" target="_blank" class="btn btn-outline-primary">
                                        View
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <h6 class="section-title text-primary mb-0">Mayor’s Permit to Work ID Details</h6>
                            {{-- ================= PERMIT DETAILS ================= --}}
                            <div class="row g-3 mt-3">
                                {{-- Peso ID No --}}
                                <div class="col-md-3">
                                    <label class="form-label">Peso ID No.</label>
                                    <input type="text" name="peso_id_no" class="form-control" value="{{$permit->peso_id_no}}">
                                </div>

                                {{-- Employer's Name / Address --}}
                                <div class="col-md-3">
                                    <label class="form-label">Employer's Name / Address</label>
                                    <input type="text" name="employers_name_or_address" class="form-control" value="{{$permit->employers_name_or_address}}"> 
                                </div>

                                {{-- Community Tax No --}}
                                <div class="col-md-3">
                                    <label class="form-label">Community Tax No.</label>
                                    <input type="text" name="community_tax_no" class="form-control" value="{{$permit->community_tax_no}}">
                                </div>

                                {{-- Issued On --}}
                                <div class="col-md-3">
                                    <label class="form-label">Permit Issued On</label>
                                    <input type="date" name="permit_issued_on" class="form-control" value="{{$permit->permit_issued_on}}">
                                </div>

                                {{-- Issued In --}}
                                <div class="col-md-3">
                                    <label class="form-label">Permit Issued In</label>
                                    <input type="text" name="permit_issued_in" class="form-control" value="{{$permit->permit_issued_in}}">
                                </div>

                                {{-- Official Receipt --}}
                                <div class="col-md-3">
                                    <label class="form-label">Official Receipt No.</label>
                                    <input type="text" name="or_no" class="form-control" value="{{$permit->or_no}}">
                                </div>

                                {{-- Permit Date --}}
                                <div class="col-md-3">
                                    <label class="form-label">Permit Date</label>
                                    <input type="date" name="permit_date" class="form-control" value="{{$permit->permit_date}}">
                                </div>

                                {{-- Expiration --}}
                                <div class="col-md-3">
                                    <label class="form-label">Expires On</label>
                                    <input type="date" name="expires_on" class="form-control" value="{{$permit->expires_on}}">
                                </div>

                                {{-- Documentary Stamp --}}
                                <div class="col-md-3">
                                    <label class="form-label">Documentary Stamp Control No.</label>
                                    <input type="text" name="permit_doc_stamp_control_no" class="form-control" value="{{$permit->permit_doc_stamp_control_no}}">
                                </div>

                                {{-- GOR Serial No --}}
                                <div class="col-md-3">
                                    <label class="form-label">GOR Serial No.</label>
                                    <input type="text" name="permit_gor_serial_no" class="form-control" value="{{$permit->permit_gor_serial_no}}">
                                </div>

                                {{-- Date of Payment --}}
                                <div class="col-md-3">
                                    <label class="form-label">Date of Payment</label>
                                    <input type="date" name="permit_date_of_payment" class="form-control" value="{{$permit->permit_date_of_payment}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="clearance" role="tabpanel">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                            <h6 class="section-title text-primary mb-0">Mayor’s Clearance Requirements</h6>
                            @if($applicant->isClearanceComplete())
                                <a href="{{ route('applicants.generateClearance', $applicant->id) }}" class="btn btn-generate btn-sm shadow-sm"> <i class="fa-solid fa-file-pdf me-2"></i>
                                        Generate Mayor's Clearance Letter
                                </a>
                            @else
                            <button class="btn btn-secondary btn-sm opacity-75"
                                disabled
                                style="cursor:not-allowed;">
                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                Incomplete Mayor's Clearance Requirements
                            </button>
                            @endif
                        </div>
                        @php $clearance = optional($applicant->clearance); @endphp
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-scale-balanced text-primary me-1"></i>
                                    1. Prosecutor’s Clearance
                                </label>
                                <div class="input-group ">
                                    <input type="file" name="prosecutor_clearance" class="form-control">
                                    @if($clearance->prosecutor_clearance)
                                    <span class="input-group-text file-name">
                                        {{ basename($clearance->prosecutor_clearance) }}
                                        </span>
                                        <a href="{{ Storage::url($clearance->prosecutor_clearance) }}" target="_blank" class="btn btn-outline-primary">
                                            View
                                        </a>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-scale-balanced text-primary me-1"></i>
                                    2. Municipal Trial Court Clearance
                                </label>
                                <div class="input-group">
                                    <input type="file" name="mtc_clearance" class="form-control">
                                    @if($clearance->mtc_clearance)
                                        <span class="input-group-text file-name">
                                            {{ basename($clearance->mtc_clearance) }}
                                        </span>
                                        <a href="{{ Storage::url($clearance->mtc_clearance) }}" target="_blank" class="btn btn-outline-primary">
                                            View
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-scale-balanced text-primary me-1"></i>
                                    3. Regional Trial Court Clearance
                                </label>
                                <div class="input-group">
                                    <input type="file" name="rtc_clearance" class="form-control">
                                    @if($clearance->rtc_clearance)
                                        <span class="input-group-text file-name">
                                            {{ basename($clearance->rtc_clearance) }}
                                        </span>
                                        <a href="{{ Storage::url($clearance->rtc_clearance) }}" target="_blank" class="btn btn-outline-primary">
                                            View
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-scale-balanced text-primary me-1"></i>
                                    4. National Bureau of Investigation (NBI) Clearance
                                </label>
                                <div class="input-group">
                                    <input type="file" name="nbi_clearance" class="form-control">
                                    @if($clearance->nbi_clearance)
                                        <span class="input-group-text file-name">
                                            {{ basename($clearance->nbi_clearance) }}
                                        </span>
                                        <a href="{{ Storage::url($clearance->nbi_clearance) }}" target="_blank" class="btn btn-outline-primary">
                                            View
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-scale-balanced text-primary me-1"></i>
                                    5. Barangay Clearance
                                </label>
                                <div class="input-group">
                                    <input type="file" name="barangay_clearance" class="form-control">
                                    @if($clearance->barangay_clearance)
                                        <span class="input-group-text file-name">
                                            {{ basename($clearance->barangay_clearance) }}
                                        </span>
                                        <a href="{{ Storage::url($clearance->barangay_clearance) }}" target="_blank" class="btn btn-outline-primary">
                                            View
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <h6 class="section-title text-primary mb-0 mt-4">Mayor’s Clearance Letter Details</h6>
                        <div class="row g-3 mt-3">
                            {{-- Hired Company --}}
                            <div class="col-md-3">
                                <label class="form-label">Hired Company</label>
                                <input type="text" name="hired_company" class="form-control" value="{{$clearance->hired_company}}">
                            </div>
                            {{-- Official Receipt No --}}
                            <div class="col-md-3">
                                <label class="form-label">O.R. No.</label>
                                <input type="text" name="clearance_or_no" class="form-control" value="{{$clearance->clearance_or_no}}">
                            </div>
                            {{-- Issued On --}}
                            <div class="col-md-3">
                                <label class="form-label">Issued On</label>
                                <input type="date" name="clearance_issued_on" class="form-control" value="{{$clearance->clearance_issued_on}}">
                            </div>
                            {{-- Issued In --}}
                            <div class="col-md-3">
                                <label class="form-label">Issued In</label>
                                <input type="text" name="clearance_issued_in" class="form-control" value="{{$clearance->clearance_issued_in}}">
                            </div>
                            {{-- PESO Control No --}}
                            <div class="col-md-3">
                                <label class="form-label">PESO Control No.</label>
                                <input type="text" name="peso_control_no" class="form-control" value="{{$clearance->peso_control_no}}">
                            </div>
                            {{-- Documentary Stamp Control No --}}
                            <div class="col-md-3">
                                <label class="form-label">Documentary Stamp Control No.</label>
                                <input type="text" name="clearance_doc_stamp_control_no" class="form-control" value="{{$clearance->clearance_doc_stamp_control_no}}">
                            </div>
                            {{-- GOR Control No --}}
                            <div class="col-md-3">
                                <label class="form-label">GOR Control No.</label>
                                <input type="text" name="clearance_gor_control_no" class="form-control" value="{{$clearance->clearance_gor_control_no}}">
                            </div>
                            {{-- Date of Payment --}}
                            <div class="col-md-3">
                                <label class="form-label">Date of Payment</label>
                                <input type="date" name="date_of_payment" class="form-control" value="{{$clearance->date_of_payment}}">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="referral" role="tabpanel">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                            <h6 class="section-title text-primary mb-0">Mayor’s Referral Requirements</h6>
                            @if($applicant->isReferralComplete())
                            <a href="{{ route('applicants.generateReferral', $applicant->id) }}"
                            class="btn btn-generate btn-sm shadow-sm">
                                <i class="fa-solid fa-file-pdf me-2"></i>
                                Generate Mayor's Referral Letter
                            </a>
                            @else
                            <button class="btn btn-secondary btn-sm opacity-75"
                                disabled
                                style="cursor:not-allowed;">
                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                    Incomplete Mayor's Referral Requirements
                            </button>
                            @endif
                        </div>
                        @php $referral = optional($applicant->referral); @endphp
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-file-user text-primary me-1"></i>1. Resume or Bio-Data<span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="file" name="resume" class="form-control">
                                @if($referral->resume)
                                    <span class="input-group-text file-name">
                                        {{ basename($referral->resume) }}
                                    </span>
                                    <a href="{{ Storage::url($referral->resume) }}" target="_blank" class="btn btn-outline-primary">
                                    View
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="p-3 bg-light rounded-3 border">
                            <label class="form-label fw-bold d-block mb-3">2. Choose at least one:</label>
                            <div class="row g-3">
                                <!-- Barangay Clearance -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">
                                        <i class="fa-solid fa-building-columns text-primary me-1"></i>
                                        Barangay Clearance
                                    </label>
                                    <div class="input-group">
                                        <input type="file" name="ref_barangay_clearance" class="form-control">
                                        @if($referral->ref_barangay_clearance)
                                            <span class="input-group-text file-name">
                                                {{ basename($referral->ref_barangay_clearance) }}
                                            </span>
                                            <a href="{{ Storage::url($referral->ref_barangay_clearance) }}" target="_blank" class="btn btn-outline-primary">
                                            View
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Police Clearance -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">
                                        <i class="fa-solid fa-shield-halved text-primary me-1"></i>
                                        Police Clearance
                                    </label>
                                    <div class="input-group">
                                        <input type="file" name="ref_police_clearance" class="form-control">
                                        @if($referral->ref_police_clearance)
                                            <span class="input-group-text file-name">
                                                {{ basename($referral->ref_police_clearance) }}
                                            </span>
                                            <a href="{{ Storage::url($referral->ref_police_clearance) }}" target="_blank" class="btn btn-outline-primary">
                                            View
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- NBI Clearance -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">
                                        <i class="fa-solid fa-id-card text-primary me-1"></i>
                                        NBI Clearance
                                    </label>
                                    <div class="input-group">
                                        <input type="file" name="ref_nbi_clearance" class="form-control">
                                        @if($referral->ref_nbi_clearance)
                                            <span class="input-group-text file-name">
                                                {{ basename($referral->ref_nbi_clearance) }}
                                            </span>
                                            <a href="{{ Storage::url($referral->ref_nbi_clearance) }}" target="_blank" class="btn btn-outline-primary">
                                            View
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-3 pt-4 border-top">
                    <button type="submit" class="btn btn-success shadow-sm px-5 py-2">
                        <i class="fa-solid fa-check me-2"></i>
                        Update Applicant Profile
                    </button>
                    <a href="{{ route('applicants.index') }}" class="btn btn-light border px-4 py-2">Cancel</a>
                </div>    
            </div>        
        </form>
    </div>        
@endsection
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