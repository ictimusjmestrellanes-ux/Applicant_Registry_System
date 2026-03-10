@extends('layouts.app')

@section('content')
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
            color: #1e293b;
            padding-bottom: 50px;
        }

        .page-title {
            font-weight: 800;
            color: var(--accent-blue);
            letter-spacing: -0.025em;
        }

        /* Card & Form Styling */
        .form-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            background: var(--section-bg);
            overflow: hidden;
            max-width: 1500px;
            margin: 0 auto;
        }

        .section-header {
            background-color: #f8fafc;
            padding: 1rem 1.5rem;
            font-weight: 700;
            color: var(--accent-blue);
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .section-header i {
            margin-right: 10px;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .section-body {
            padding: 2rem 1.5rem;
        }

        /* Input Styling */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .form-input {
            padding: 0.625rem 0.875rem;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
        }

        /* Buttons */
        .form-footer {
            background-color: #f8fafc;
            padding: 1.5rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 12px;
        }

        .btn-save {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            transition: all 0.2s;
        }

        .btn-save:hover {
            background-color: var(--primary-hover);
            color: white;
            transform: translateY(-1px);
        }

        .btn-cancel {
            background-color: #ffffff;
            color: #64748b;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background-color: #f1f5f9;
            color: var(--accent-blue);
        }

        /* Progress Step Visual (Optional) */
        .required-mark {
            color: #ef4444; /* Standard Red for required marks */
            margin-left: 2px;
        }
    </style>

    <div class="container-fluid py-5">

        <div class="mx-auto mb-4 text-center text-md-start">
            <h3 class="page-title">Add Applicant</h3>
            <p class="text-muted mb-0">
                Register a new applicant into the system. Ensure all required fields (<span
                    class="text-danger">*</span>) are filled.
            </p>
        </div>

        <form action="{{ route('applicants.store') }}" method="POST">
            @csrf

            <div class="card form-card">

                <div class="section-header">
                    <i class="bi bi-person-circle"></i>
                    Personal Information
                </div>

                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="required-mark">*</span></label>
                            <input type="text" name="first_name" class="form-control form-input" placeholder="e.g. John" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control form-input" placeholder="e.g. Quinto">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="required-mark">*</span></label>
                            <input type="text" name="last_name" class="form-control form-input" placeholder="e.g. Doe" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Suffix</label>
                            <select name="suffix" class="form-select form-input">
                                <option value="">None</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sex/Gender <span class="required-mark">*</span></label>
                            <select name="gender" class="form-select form-input" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact Number <span class="required-mark">*</span></label>
                            <input type="text" name="contact_no" class="form-control form-input" placeholder="09123456789" required>
                        </div>
                        
                    </div>
                </div>

                <div class="section-header">
                    <i class="bi bi-geo-alt-fill"></i>
                    Address Information
                </div>

                <div class="section-body border-top">
                    <div class="mb-4">
                        <label class="form-label">Complete Address <span class="required-mark">*</span></label>
                        <input type="text" name="address_line" class="form-control form-input" placeholder="House No. / Street / Phase / Block" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Province <span class="required-mark">*</span></label>
                            <select name="province" id="province" class="form-select form-input" required>
                                <option value="">Select Province</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">City / Municipality <span class="required-mark">*</span></label>
                            <select name="city" id="city" class="form-select form-input" required>
                                <option value="">Select City</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Barangay <span class="required-mark">*</span></label>
                            <select name="barangay" id="barangay" class="form-select form-input" required>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-footer justify-content-end">
                    <a href="{{ route('applicants.index') }}" class="btn btn-cancel">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-save shadow-sm">
                        <i class="bi bi-check-circle-fill me-2"></i>Save Applicant
                    </button>
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