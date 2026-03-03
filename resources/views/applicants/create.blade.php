@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="page-title">Add Applicant</h3>
                <small class="text-muted">
                    Register a new applicant into the system
                </small>
            </div>

        </div>



        <form action="{{ route('applicants.store') }}" method="POST">
            @csrf


            <div class="card form-card">

                <!-- PERSONAL INFORMATION -->
                <div class="section-header">
                    <i class="bi bi-person"></i>
                    Personal Information
                </div>


                <div class="section-body">

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control form-input" required>
                        </div>


                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Middle Name
                            </label>

                            <input type="text" name="middle_name" class="form-control form-input">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label class="form-label">Last Name *</label>

                            <input type="text" name="last_name" class="form-control form-input" required>
                        </div>

                    </div>



                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Suffix
                            </label>

                            <select name="suffix" class="form-control form-input">

                                <option value="">None</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>

                            </select>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Contact Number *
                            </label>

                            <input type="text" name="contact_no" class="form-control form-input" required>

                        </div>

                    </div>

                </div>



                <!-- ADDRESS -->
                <div class="section-header">

                    <i class="bi bi-geo-alt"></i>
                    Address Information

                </div>


                <div class="section-body">


                    <div class="mb-3">

                        <label class="form-label">
                            Complete Address *
                        </label>

                        <input type="text" name="address_line" class="form-control form-input"
                            placeholder="House No. / Street" required>

                    </div>



                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Province *
                            </label>

                            <select name="province" id="province" class="form-control form-input" required>

                                <option>Select Province</option>

                            </select>

                        </div>



                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                City / Municipality *
                            </label>

                            <select name="city" id="city" class="form-control form-input" required>

                                <option>Select City</option>

                            </select>

                        </div>



                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Barangay *
                            </label>

                            <select name="barangay" id="barangay" class="form-control form-input" required>

                                <option>Select Barangay</option>

                            </select>

                        </div>

                    </div>


                </div>



                <!-- BUTTONS -->
                <div class="form-footer">

                    <button class="btn btn-save">

                        <i class="bi bi-check-circle"></i>
                        Save Applicant

                    </button>


                    <a href="{{ route('applicants.index') }}" class="btn btn-cancel">

                        Cancel

                    </a>

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

                        provinceSelect.appendChild(option);

                    });

                });

        }



        // ---------- LOAD CITIES ----------
        function loadCities(provinceCode) {

            citySelect.innerHTML = '<option>Loading cities...</option>';
            barangaySelect.innerHTML = '<option>Select Barangay</option>';

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