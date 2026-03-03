@extends('layouts.app')

@section('content')
    <div class="container">

        <h3 class="mb-4">Update Applicant</h3>

        <form action="{{ route('applicants.update', $applicant->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card p-4">

                <div class="card shadow-sm p-4">


                    <!-- TABS -->
                    <!-- NAV TABS -->
                <ul class="nav nav-tabs mb-3" id="mayorTabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active"
                            id="permit-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#permit"
                            type="button"
                            role="tab">
                            Mayor's Permit to Work
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                            id="clearance-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#clearance"
                            type="button"
                            role="tab">
                            Mayor's Clearance
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                            id="referral-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#referral"
                            type="button"
                            role="tab">
                            Mayor's Referral
                        </button>
                    </li>

                </ul>


                <!-- TAB CONTENT -->
                <div class="tab-content">

                    <!-- ================================= -->
                    <!-- TAB 1 : MAYOR'S PERMIT TO WORK -->
                    <!-- ================================= -->
                    <div class="tab-pane fade show active" id="permit" role="tabpanel">

                        <div class="card shadow-sm border-0">
                            <div class="card-body">

                                <h5 class="mb-4">Mayor’s Permit to Work Requirements</h5>

                                @php $permit = optional($applicant->permit); @endphp

                                <input type="hidden" name="health_card" value="0">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                        name="health_card" value="1"
                                        {{ $permit->health_card ? 'checked' : '' }}>
                                    <label class="form-check-label">Health Card</label>
                                </div>

                                <input type="hidden" name="nbi_or_police_clearance" value="0">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                        name="nbi_or_police_clearance" value="1"
                                        {{ $permit->nbi_or_police_clearance ? 'checked' : '' }}>
                                    <label class="form-check-label">NBI or Police Clearance</label>
                                </div>

                                <input type="hidden" name="cedula" value="0">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                        name="cedula" value="1"
                                        {{ $permit->cedula ? 'checked' : '' }}>
                                    <label class="form-check-label">Cedula</label>
                                </div>

                                <input type="hidden" name="referral_letter" value="0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="referral_letter" value="1"
                                        {{ $permit->referral_letter ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Referral Letter (For non-residents of Imus City)
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- ================================= -->
                    <!-- TAB 2 : MAYOR'S CLEARANCE -->
                    <!-- ================================= -->
                    <div class="tab-pane fade" id="clearance" role="tabpanel">

                        <div class="card shadow-sm border-0">
                            <div class="card-body">

                                <h5 class="mb-4">Mayor’s Clearance Requirements</h5>

                                @php $clearance = optional($applicant->clearance); @endphp

                                <input type="hidden" name="prosecutor_clearance" value="0">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                        name="prosecutor_clearance" value="1"
                                        {{ $clearance->prosecutor_clearance ? 'checked' : '' }}>
                                    <label class="form-check-label">Prosecutor’s Clearance</label>
                                </div>

                                <input type="hidden" name="mtc_clearance" value="0">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                        name="mtc_clearance" value="1"
                                        {{ $clearance->mtc_clearance ? 'checked' : '' }}>
                                    <label class="form-check-label">Municipal Trial Court Clearance</label>
                                </div>

                                <input type="hidden" name="rtc_clearance" value="0">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                        name="rtc_clearance" value="1"
                                        {{ $clearance->rtc_clearance ? 'checked' : '' }}>
                                    <label class="form-check-label">Regional Trial Court Clearance</label>
                                </div>

                                <input type="hidden" name="nbi_clearance" value="0">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                        name="nbi_clearance" value="1"
                                        {{ $clearance->nbi_clearance ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        National Bureau of Investigation (NBI) Clearance
                                    </label>
                                </div>

                                <input type="hidden" name="barangay_clearance" value="0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="barangay_clearance" value="1"
                                        {{ $clearance->barangay_clearance ? 'checked' : '' }}>
                                    <label class="form-check-label">Barangay Clearance</label>
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- ================================= -->
                    <!-- TAB 3 : MAYOR'S REFERRAL -->
                    <!-- ================================= -->
                    <div class="tab-pane fade" id="referral" role="tabpanel">

                        <div class="card shadow-sm border-0">
                            <div class="card-body">

                                <h5 class="mb-4">Mayor’s Referral Requirements</h5>

                                @php $referral = optional($applicant->referral); @endphp

                                <input type="hidden" name="resume" value="0">
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox"
                                        name="resume" value="1"
                                        {{ $referral->resume ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold">
                                        Resume or Bio-Data
                                    </label>
                                </div>

                                <hr>

                                <p class="fw-semibold mb-3">Any of the following:</p>

                                <input type="hidden" name="ref_barangay_clearance" value="0">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox"
                                        name="ref_barangay_clearance" value="1"
                                        {{ $referral->barangay_clearance ? 'checked' : '' }}>
                                    <label class="form-check-label">Barangay Clearance</label>
                                </div>

                                <input type="hidden" name="ref_police_clearance" value="0">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox"
                                        name="ref_police_clearance" value="1"
                                        {{ $referral->police_clearance ? 'checked' : '' }}>
                                    <label class="form-check-label">Police Clearance</label>
                                </div>

                                <input type="hidden" name="ref_nbi_clearance" value="0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="ref_nbi_clearance" value="1"
                                        {{ $referral->nbi_clearance ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        National Bureau of Investigation (NBI) Clearance
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                    <div class="tab-pane fade show active" id="permit">

                        <h5 class="mb-3">Personal Information</h5>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label>First Name *</label>
                                <input type="text" name="first_name" value="{{ $applicant->first_name }}"
                                    class="form-control" required>
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

                                <input type="text" name="contact_no" value="{{ $applicant->contact_no }}"
                                    class="form-control" required>

                            </div>

                        </div>



                        <hr>

                        <h5 class="mb-3">Address</h5>


                        <div class="mb-3">

                            <label>Input Address *</label>

                            <input type="text" name="address_line" value="{{ $applicant->address_line }}"
                                class="form-control" required>

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