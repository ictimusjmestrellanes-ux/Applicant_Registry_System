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
    <div class="container">

        <h3 class="mb-4">Update Applicant</h3>

        <form action="{{ route('applicants.update', $applicant->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card p-4">

                <div class="card shadow-sm p-4">


                    <!-- TABS -->
                    <!-- NAV TABS -->
                    <ul class="nav nav-tabs mb-3" id="mayorTabs" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="permit-tab" data-bs-toggle="tab" data-bs-target="#permit"
                                type="button" role="tab">
                                Mayor's Permit to Work
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="clearance-tab" data-bs-toggle="tab" data-bs-target="#clearance"
                                type="button" role="tab">
                                Mayor's Clearance
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="referral-tab" data-bs-toggle="tab" data-bs-target="#referral"
                                type="button" role="tab">
                                Mayor's Referral
                            </button>
                        </li>

                    </ul>


                    <!-- TAB CONTENT -->
                    <div class="tab-content">

                        {{-- ================================= --}}
                        {{-- TAB 1 : MAYOR'S PERMIT TO WORK --}}
                        {{-- ================================= --}}
                        <div class="tab-pane fade show active" id="permit" role="tabpanel">

                            <div class="card shadow-sm border-0">
                                <div class="card-body">

                                    <h5 class="mb-4">Mayor’s Permit to Work Requirements</h5>

                                    @php $permit = optional($applicant->permit); @endphp

                                    {{-- Health Card --}}
                                    <div class="mb-3">
                                        <label class="form-label">Health Card</label>
                                        <input type="file" name="health_card" class="form-control">

                                        @if($permit->health_card)
                                            <div class="mt-2">
                                                <a href="{{ route('applicants.view-file', [$applicant->id, 'health_card']) }}"
                                                target="_blank"
                                                class="badge bg-light text-dark border px-3 py-2">
                                                    <i class="bi bi-file-earmark"></i>
                                                    {{ basename($permit->health_card) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- NBI / Police Clearance --}}
                                    <div class="mb-3">
                                        <label class="form-label">NBI or Police Clearance</label>
                                        <input type="file" name="nbi_or_police_clearance" class="form-control">

                                        @if($permit->nbi_or_police_clearance)
                                            <div class="mt-2">
                                                <a href="{{ route('applicants.view-file', [$applicant->id, 'nbi_or_police_clearance']) }}"
                                                target="_blank"
                                                class="badge bg-light text-dark border px-3 py-2">
                                                    <i class="bi bi-file-earmark"></i>
                                                    {{ basename($permit->nbi_or_police_clearance) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Cedula --}}
                                    <div class="mb-3">
                                        <label class="form-label">Cedula</label>
                                        <input type="file" name="cedula" class="form-control">

                                        @if($permit->cedula)
                                            <div class="mt-2">
                                                <a href="{{ route('applicants.view-file', [$applicant->id, 'cedula']) }}"
                                                target="_blank"
                                                class="badge bg-light text-dark border px-3 py-2">
                                                    <i class="bi bi-file-earmark"></i>
                                                    {{ basename($permit->cedula) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Referral Letter --}}
                                    <div class="mb-3">
                                        <label class="form-label">Referral Letter</label>
                                        <input type="file" name="referral_letter" class="form-control">

                                        @if($permit->referral_letter)
                                            <div class="mt-2">
                                                <a href="{{ route('applicants.view-file', [$applicant->id, 'referral_letter']) }}"
                                                target="_blank"
                                                class="badge bg-light text-dark border px-3 py-2">
                                                    <i class="bi bi-file-earmark"></i>
                                                    {{ basename($permit->referral_letter) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- ================================= --}}
                        {{-- TAB 2 : MAYOR'S CLEARANCE --}}
                        {{-- ================================= --}}
                        <div class="tab-pane fade" id="clearance" role="tabpanel">

                            <div class="card shadow-sm border-0">
                                <div class="card-body">

                                    <h5 class="mb-4">Mayor’s Clearance Requirements</h5>

                                    @php $clearance = optional($applicant->clearance); @endphp

                                    @foreach([
                                        'prosecutor_clearance' => 'Prosecutor’s Clearance',
                                        'mtc_clearance' => 'Municipal Trial Court Clearance',
                                        'rtc_clearance' => 'Regional Trial Court Clearance',
                                        'nbi_clearance' => 'NBI Clearance',
                                        'barangay_clearance' => 'Barangay Clearance'
                                    ] as $field => $label)

                                    <div class="mb-3">
                                        <label>{{ $label }}</label>
                                        <input type="file" name="{{ $field }}" class="form-control">

                                        @if($clearance->$field)
                                            <div class="mt-2">
                                                <a href="{{ route('applicants.view-file', [$applicant->id, 'nbi_or_police_clearance']) }}"
                                                target="_blank"
                                                class="badge bg-light text-dark border px-3 py-2">
                                                    <i class="bi bi-file-earmark"></i>
                                                    {{ basename($clearance->$field) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- ================================= --}}
                        {{-- TAB 3 : MAYOR'S REFERRAL --}}
                        {{-- ================================= --}}
                        <div class="tab-pane fade" id="referral" role="tabpanel">

                            <div class="card shadow-sm border-0">
                                <div class="card-body">

                                    <h5 class="mb-4">Mayor’s Referral Requirements</h5>

                                    @php $referral = optional($applicant->referral); @endphp

                                    {{-- Resume --}}
                                    <div class="mb-3">
                                        <label>Resume or Bio-Data</label>
                                        <input type="file" name="resume" class="form-control">

                                        @if($referral->resume)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $referral->resume) }}"
                                                target="_blank"
                                                class="badge bg-light text-dark border px-3 py-2">
                                                    <i class="bi bi-file-earmark"></i>
                                                    {{ basename($referral->resume) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <hr>
                                    <p class="fw-semibold mb-3">Any of the following:</p>

                                    @foreach([
                                        'ref_barangay_clearance' => 'Barangay Clearance',
                                        'ref_police_clearance' => 'Police Clearance',
                                        'ref_nbi_clearance' => 'NBI Clearance'
                                    ] as $field => $label)

                                    <div class="mb-3">
                                        <label>{{ $label }}</label>
                                        <input type="file" name="{{ $field }}" class="form-control">

                                        @if($referral->$field)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $referral->$field) }}"
                                                target="_blank"
                                                class="badge bg-light text-dark border px-3 py-2">
                                                    <i class="bi bi-file-earmark"></i>
                                                    {{ basename($referral->$field) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    @endforeach

                                </div>
                            </div>
                        </div>

                    </div>

                                <h5 class="mb-3">Personal Information</h5>

                                <div class="row mb-3">

                                    <div class="col-md-4">
                                        <label>First Name *</label>
                                        <input type="text" name="first_name" value="{{ $applicant->first_name }}" class="form-control"
                                            required>
                                    </div>


                                    <div class="col-md-4">
                                        <label>Middle Name</label>
                                        <input type="text" name="middle_name" value="{{ $applicant->middle_name }}"
                                            class="form-control">
                                    </div>


                                    <div class="col-md-4">
                                        <label>Last Name *</label>
                                        <input type="text" name="last_name" value="{{ $applicant->last_name }}" class="form-control"
                                            required>
                                    </div>

                                </div>



                                <div class="row mb-3">

                                    <div class="col-md-4">

                                        <label>Suffix</label>

                                        <select name="suffix" class="form-control">

                                            <option value="">None</option>

                                            <option value="Jr." {{ $applicant->suffix == 'Jr.' ? 'selected' : '' }}>
                                                Jr.
                                            </option>

                                            <option value="Sr." {{ $applicant->suffix == 'Sr.' ? 'selected' : '' }}>
                                                Sr.
                                            </option>

                                            <option value="II" {{ $applicant->suffix == 'II' ? 'selected' : '' }}>
                                                II
                                            </option>

                                            <option value="III" {{ $applicant->suffix == 'III' ? 'selected' : '' }}>
                                                III
                                            </option>

                                            <option value="IV" {{ $applicant->suffix == 'IV' ? 'selected' : '' }}>
                                                IV
                                            </option>

                                        </select>

                                    </div>



                                    <div class="col-md-4">

                                        <label>Contact No *</label>

                                        <input type="text" name="contact_no" value="{{ $applicant->contact_no }}" class="form-control"
                                            required>

                                    </div>

                                </div>



                                <hr>

                                <h5 class="mb-3">Address</h5>


                                <div class="mb-3">

                                    <label>Input Address *</label>

                                    <input type="text" name="address_line" value="{{ $applicant->address_line }}" class="form-control"
                                        required>

                                </div>



                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label>Province *</label>
                                        <select name="province" id="province" class="form-control" required>
                                            <option>Loading provinces...</option>
                                        </select>
                                    </div>


                                    <div class="col-md-4 mb-3">
                                        <label>City *</label>
                                        <select name="city" id="city" class="form-control" required>
                                            <option>Select City</option>
                                        </select>
                                    </div>



                                    <div class="col-md-4 mb-3">
                                        <label>Barangay *</label>
                                        <select name="barangay" id="barangay" class="form-control" required>
                                            <option>Select Barangay</option>
                                        </select>
                                    </div>

                                </div>

                            </div>


                            <div class="mt-4">

                                <button class="btn btn-success">
                                    Update Applicant
                                </button>


                                <a href="{{ route('applicants.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>

                            </div>


                        </div>




                    </div>

                </form>
                
            </div>
@endsection

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