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
                            <div class="row g-4 mb-7">
                                <div class="col-md-1">
                                <label class="form-label">O.R No. <span class="required-mark">*</span></label>
                                <input type="text" name="or_no" value="{{ $applicant->or_no }}" class="form-control form-input"
                                    placeholder="O.R No." required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">First Time Job Seeker? <span class="required-mark">*</span></label>
                                <select name="first_time_job_seeker" class="form-select form-input" required>
                                    <option value="0"{{ $applicant->first_time_job_seeker == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1"{{ $applicant->first_time_job_seeker == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">First Name <span class="required-mark">*</span></label>
                                <input type="text" name="first_name" value="{{ $applicant->first_name }}" class="form-control" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Middle Name (Optional)</label>
                                <input type="text" name="middle_name" value="{{ $applicant->middle_name }}" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Last Name <span class="required-mark">*</span></label>
                                <input type="text" name="last_name" value="{{ $applicant->last_name }}" class="form-control" required>
                               </div>

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
                            <div class="col-md-1">
                                <label class="form-label">Age <span class="required-mark">*</span></label>
                                <input type="number" name="age" value="{{ $applicant->age }}" class="form-control form-input" placeholder="e.g. 25" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sex/Gender <span class="required-mark">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="">None</option>
                                    <option value="Male" {{ $applicant->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $applicant->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <!-- Civil Status -->
                            <div class="col-md-2">
                                <label class="form-label">Civil Status <span class="required-mark">*</span></label>
                                <select name="civil_status" class="form-select form-input" required>
                                    <option value="">Select Status</option>
                                    <option value="Single" {{ $applicant->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ $applicant->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ $applicant->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                            <!-- PWD -->
                            <div class="col-md-1">
                                <label class="form-label">PWD? <span class="required-mark">*</span></label>
                                <select name="pwd" class="form-select form-input" required>
                                    <option value="0"{{ $applicant->pwd == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $applicant->pwd == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            <!-- 4Ps -->
                            <div class="col-md-1">
                                <label class="form-label">4Ps? <span class="required-mark">*</span></label>
                                <select name="four_ps" class="form-select form-input" required>
                                    <option value="0"{{ $applicant->four_ps == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $applicant->four_ps == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Contact No <span class="required-mark">*</span></label>
                                <input type="text" name="contact_no" value="{{ $applicant->contact_no }}" class="form-control" placeholder="09XXXXXXXXX" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Complete Address <span class="required-mark">*</span></label>
                                <input type="text" name="address_line" value="{{ $applicant->address_line }}" class="form-control" placeholder="House No. / Street / Phase / Block" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Province <span class="required-mark">*</span></label>
                                <select name="province" id="province" class="form-select" required>
                                    <option>Loading provinces...</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">City <span class="required-mark">*</span></label>
                                <select name="city" id="city" class="form-select" required>
                                    <option>Select City</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Barangay <span class="required-mark">*</span></label>
                                <select name="barangay" id="barangay" class="form-select" required>
                                    <option>Select Barangay</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Educational Attainment <span class="required-mark">*</span></label>
                                <input type="text" name="educational_attainment" value="{{ $applicant->educational_attainment }}" class="form-control form-input" placeholder="e.g. Bachelor's Degree" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hiring Company <span class="required-mark">*</span></label>
                                <input type="text" name="hiring_company" value="{{ $applicant->hiring_company }}" class="form-control form-input" placeholder="e.g. Tech Corp" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Position Hired <span class="required-mark">*</span></label>
                                <input type="text" name="position_hired" value="{{ $applicant->position_hired }}" class="form-control form-input" placeholder="e.g. Software Engineer" required>
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
                                    <label class="form-label">Permit Issued At</label>
                                    <input type="text" name="permit_issued_in" class="form-control" value="{{$permit->permit_issued_in}}">
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
                                {{-- Date of Payment --}}
                                <div class="col-md-3">
                                    <label class="form-label">Date of Payment</label>
                                    <input type="date" name="permit_date_of_payment" class="form-control" value="{{$permit->permit_date_of_payment}}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Payment Status <span class="required-mark">*</span></label>
                                    <select name="is_paid" class="form-select form-input" required>
                                        <option value="0"{{ $permit->is_paid == 0 ? 'selected' : '' }}>Not Paid</option>
                                        <option value="1"{{ $permit->is_paid == 1 ? 'selected' : '' }}>Paid</option>
                                    </select>
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
                                <input type="text" name="clearance_hired_company" class="form-control" value="{{$clearance->clearance_hired_company}}">
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
                                <input type="text" name="clearance_peso_control_no" class="form-control" value="{{$clearance->clearance_peso_control_no}}">
                            </div>
                            {{-- Documentary Stamp Control No --}}
                            <div class="col-md-3">
                                <label class="form-label">Documentary Stamp Control No.</label>
                                <input type="text" name="clearance_doc_stamp_control_no" class="form-control" value="{{$clearance->clearance_doc_stamp_control_no}}">
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