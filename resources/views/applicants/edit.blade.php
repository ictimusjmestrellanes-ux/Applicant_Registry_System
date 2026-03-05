@extends('layouts.app')

@section('content')

    @if(session('created_success'))

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                Swal.fire({
                    title: 'Applicant Created!',
                    text: 'Do you want to continue editing requirements?',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Continue',
                    cancelButtonText: 'No, Go Back'
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
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --success-color: #10b981;
            --bg-light: #f8fafc;
            --border-radius: 12px;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1e293b;
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
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            overflow: hidden;
        }

        /* Requirements Section Styling */
        .requirements-container {
            background-color: #f1f5f9;
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
            color: #64748b;
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
            background-color: #e2e8f0;
            color: #334155;
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
            padding: 0.625rem 0.875rem;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        /* File Preview Box */
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
        }

        .btn-success:hover {
            background-color: #059669;
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
        }

        /* Responsive Fixes */
        @media (max-width: 768px) {
            .nav-tabs {
                flex-direction: column;
            }

            .nav-item {
                width: 100%;
            }

            .btn-generate {
                width: 100%;
                margin-top: 15px;
            }
        }
    </style>
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

            <div class="card main-card p-4 p-md-5">

                <div class="requirements-container p-4">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-1">Document Compliance</h5>
                            <p class="text-muted small mb-0">Manage Mayor's Permit, Clearance, and Referral Requirements.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if($applicant->isPermitComplete())
                                <a href="{{ route('applicants.generatePermit', $applicant->id) }}"
                                    class="btn btn-success btn-generate shadow-sm">
                                    <i class="fa-solid fa-file-pdf me-2"></i>Generate Permit
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fa-solid fa-circle-exclamation me-1"></i> Incomplete Docs
                                </button>
                            @endif
                            @if($applicant->isClearanceComplete())
                                <a href="{{ route('applicants.generateClearance', $applicant->id) }}"
                                    class="btn btn-success btn-generate shadow-sm">
                                    <i class="fa-solid fa-file-pdf me-2"></i>Generate Clearance
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fa-solid fa-circle-exclamation me-1"></i> Incomplete Docs
                                </button>
                            @endif
                            @if($applicant->isReferralComplete())
                                <a href="{{ route('applicants.generateReferral', $applicant->id) }}"
                                    class="btn btn-success btn-generate shadow-sm">
                                    <i class="fa-solid fa-file-pdf me-2"></i>Generate Referral
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fa-solid fa-circle-exclamation me-1"></i> Incomplete Docs
                                </button>
                            @endif
                        </div>
                    </div>

                    <ul class="nav nav-tabs mb-4" id="mayorTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="permit-tab" data-bs-toggle="tab" data-bs-target="#permit"
                                type="button" role="tab">
                                Permit to Work
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="clearance-tab" data-bs-toggle="tab" data-bs-target="#clearance"
                                type="button" role="tab">
                                Mayor's Clearance
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="referral-tab" data-bs-toggle="tab" data-bs-target="#referral"
                                type="button" role="tab">
                                Mayor's Referral
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content bg-white p-4 rounded-3 border shadow-sm">

                        <div class="tab-pane fade show active" id="permit" role="tabpanel">
                            <h6 class="section-title text-primary">Mayor’s Permit to Work Requirements</h6>

                            @php 
                                $permit = optional($applicant->permit);
                                $isImusResident = stripos($applicant->city, 'City of Imus') !== false;
                            @endphp

                            <div class="row g-4">

                                {{-- HEALTH CARD --}}
                                <div class="col-md-6">
                                    <label class="form-label">Health Card</label>
                                    <input type="file" name="health_card" class="form-control">

                                    @if($permit->health_card)
                                        <div class="file-status-box d-flex justify-content-between align-items-center mt-2"
                                            id="file-health_card">
                                            
                                            <span class="file-name text-truncate">
                                                <i class="fa-solid fa-paperclip me-2"></i>
                                                {{ basename($permit->health_card) }}
                                            </span>

                                            <div>
                                                <a href="{{ asset('storage/' . $permit->health_card) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>

                                                <form action="{{ route('permit.deleteFile', [$applicant->id, 'health_card']) }}"
                                                    method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Remove this file?')">
                                                        Clear
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>


                                {{-- NBI / POLICE --}}
                                <div class="col-md-6">
                                    <label class="form-label">NBI or Police Clearance</label>
                                    <input type="file" name="nbi_or_police_clearance" class="form-control">

                                    @if($permit->nbi_or_police_clearance)
                                        <div class="file-status-box d-flex justify-content-between align-items-center mt-2">
                                            <span class="file-name text-truncate">
                                                <i class="fa-solid fa-paperclip me-2"></i>
                                                {{ basename($permit->nbi_or_police_clearance) }}
                                            </span>

                                            <div>
                                                <a href="{{ asset('storage/' . $permit->nbi_or_police_clearance) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>

                                                <form action="{{ route('permit.deleteFile', [$applicant->id, 'nbi_or_police_clearance']) }}"
                                                    method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Remove this file?')">
                                                        Clear
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>


                                {{-- CEDULA --}}
                                <div class="col-md-6">
                                    <label class="form-label">Cedula</label>
                                    <input type="file" name="cedula" class="form-control">

                                    @if($permit->cedula)
                                        <div class="file-status-box d-flex justify-content-between align-items-center mt-2">
                                            <span class="file-name text-truncate">
                                                <i class="fa-solid fa-paperclip me-2"></i>
                                                {{ basename($permit->cedula) }}
                                            </span>

                                            <div>
                                                <a href="{{ asset('storage/' . $permit->cedula) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>

                                                <form action="{{ route('permit.deleteFile', [$applicant->id, 'cedula']) }}"
                                                    method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Remove this file?')">
                                                        Clear
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>


                                {{-- REFERRAL LETTER --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Referral Letter
                                        @if($isImusResident)
                                            <span class="text-success">(Not Required - Imus Resident)</span>
                                        @else
                                            <span class="text-danger">(Required if not from Imus)</span>
                                        @endif
                                    </label>

                                    <input type="file"
                                        name="referral_letter"
                                        class="form-control"
                                        {{ $isImusResident ? 'disabled' : '' }}>

                                    @if($permit->referral_letter)
                                        <div class="file-status-box d-flex justify-content-between align-items-center mt-2">
                                            <span class="file-name text-truncate">
                                                <i class="fa-solid fa-paperclip me-2"></i>
                                                {{ basename($permit->referral_letter) }}
                                            </span>

                                            <div>
                                                <a href="{{ asset('storage/' . $permit->referral_letter) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>

                                                <form action="{{ route('permit.deleteFile', [$applicant->id, 'referral_letter']) }}"
                                                    method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Remove this file?')">
                                                        Clear
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="clearance" role="tabpanel">
                            <h6 class="section-title text-primary">Legal Clearance Documents</h6>
                            @php $clearance = optional($applicant->clearance); @endphp
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Prosecutor’s Clearance</label>
                                    <input type="file" name="prosecutor_clearance" class="form-control">
                                    @if($clearance->prosecutor_clearance)
                                        <div class="file-status-box"><span
                                                class="file-name">{{ basename($clearance->prosecutor_clearance) }}</span><a
                                                href="{{ asset('storage/' . $clearance->prosecutor_clearance) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary py-0">View</a></div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Municipal Trial Court Clearance</label>
                                    <input type="file" name="mtc_clearance" class="form-control">
                                    @if($clearance->mtc_clearance)
                                        <div class="file-status-box"><span
                                                class="file-name">{{ basename($clearance->mtc_clearance) }}</span><a
                                                href="{{ asset('storage/' . $clearance->mtc_clearance) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary py-0">View</a></div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Regional Trial Court Clearance</label>
                                    <input type="file" name="rtc_clearance" class="form-control">
                                    @if($clearance->rtc_clearance)
                                        <div class="file-status-box"><span
                                                class="file-name">{{ basename($clearance->rtc_clearance) }}</span><a
                                                href="{{ asset('storage/' . $clearance->rtc_clearance) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary py-0">View</a></div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">National Bureau of Investigation (NBI) Clearance</label>
                                    <input type="file" name="nbi_clearance" class="form-control">
                                    @if($clearance->nbi_clearance)
                                        <div class="file-status-box"><span
                                                class="file-name">{{ basename($clearance->nbi_clearance) }}</span><a
                                                href="{{ asset('storage/' . $clearance->nbi_clearance) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary py-0">View</a></div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Barangay Clearance</label>
                                    <input type="file" name="barangay_clearance" class="form-control">
                                    @if($clearance->barangay_clearance)
                                        <div class="file-status-box"><span
                                                class="file-name">{{ basename($clearance->barangay_clearance) }}</span><a
                                                href="{{ asset('storage/' . $clearance->barangay_clearance) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary py-0">View</a></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="referral" role="tabpanel">
                            <h6 class="section-title text-primary">Referral Information</h6>
                            @php $referral = optional($applicant->referral); @endphp
                            <div class="mb-4">
                                <label class="form-label">1. Resume or Bio-Data <span class="text-danger">*</span></label>
                                <input type="file" name="resume" class="form-control">
                                @if($referral->resume)
                                    <div class="file-status-box"><span
                                            class="file-name">{{ basename($referral->resume) }}</span><a
                                            href="{{ asset('storage/' . $referral->resume) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary py-0">View</a></div>
                                @endif
                            </div>
                            <div class="p-3 bg-light rounded-3 border">
                                <label class="form-label fw-bold d-block mb-3">2. Choose at least one (Alin man sa mga
                                    sumusunod):</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label small">Barangay Clearance</label>
                                        <input type="file" name="barangay_clearance" class="form-control">
                                        @if($referral->barangay_clearance)
                                            <div class="file-status-box"><span
                                                    class="file-name">{{ basename($referral->barangay_clearance) }}</span><a
                                                    href="{{ asset('storage/' . $referral->barangay_clearance) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary py-0">View</a></div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">Police Clearance</label>
                                        <input type="file" name="police_clearance" class="form-control">
                                        @if($referral->police_clearance)
                                            <div class="file-status-box"><span
                                                    class="file-name">{{ basename($referral->police_clearance) }}</span><a
                                                    href="{{ asset('storage/' . $referral->police_clearance) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary py-0">View</a></div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">NBI Clearance</label>
                                        <input type="file" name="nbi_clearance" class="form-control">
                                        @if($referral->nbi_clearance)
                                            <div class="file-status-box"><span
                                                    class="file-name">{{ basename($referral->nbi_clearance) }}</span><a
                                                    href="{{ asset('storage/' . $referral->nbi_clearance) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary py-0">View</a></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="section-title">Personal Information</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" value="{{ $applicant->first_name }}" class="form-control"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ $applicant->middle_name }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" value="{{ $applicant->last_name }}" class="form-control"
                            required>
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
                        <label class="form-label">Contact No *</label>
                        <input type="text" name="contact_no" value="{{ $applicant->contact_no }}" class="form-control"
                            placeholder="09XXXXXXXXX" required>
                    </div>
                </div>

                <h5 class="section-title">Residential Address</h5>
                <div class="mb-4">
                    <label class="form-label">Street Address / House No. *</label>
                    <input type="text" name="address_line" value="{{ $applicant->address_line }}" class="form-control"
                        required>
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

                <div class="d-flex flex-wrap gap-3 pt-4 border-top">
                    <button type="submit" class="btn btn-success shadow-sm px-5 py-2">
                        <i class="fa-solid fa-check me-2"></i>Update Applicant Profile
                    </button>
                    <a href="{{ route('applicants.index') }}" class="btn btn-light border px-4 py-2">
                        Cancel
                    </a>
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