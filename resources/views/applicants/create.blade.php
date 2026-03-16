@extends('layouts.app')

@section('content')
    <style>
        /* ===============================
    GLOBAL PAGE STYLE
    ================================ */

        body {
            background: linear-gradient(135deg, #eef2f7, #e6edf5);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }


        /* ===============================
    PAGE TITLE AREA
    ================================ */

        .page-title {
            font-weight: 700;
            font-size: 26px;
            color: #2c3e50;
        }

        .container-fluid p {
            font-size: 14px;
            max-width: 1600px;
        }


        /* ===============================
    MAIN FORM CARD
    ================================ */

        .form-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.7);
            transition: all .35s ease;
        }

        .form-card:hover {
            transform: translateY(-3px);
            box-shadow:
                0 25px 60px rgba(0, 0, 0, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }


        /* ===============================
    SECTION HEADER
    ================================ */

        .section-header {
            font-weight: 700;
            font-size: 16px;
            padding: 18px 25px;
            border-bottom: 1px solid #edf1f7;
            color: #34495e;
            background: linear-gradient(135deg, #f8faff, #eef3fb);
            border-radius: 16px 16px 0 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header i {
            font-size: 20px;
            color: #4a7dff;
        }


        /* ===============================
    SECTION BODY
    ================================ */

        .section-body {
            padding: 25px;
        }


        /* ===============================
    FORM LABEL
    ================================ */

        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #44526f;
        }


        /* ===============================
    INPUT FIELDS
    ================================ */

        .form-input,
        .form-control,
        .form-select {

            border-radius: 10px;
            border: 1px solid #dce3ef;
            padding: 10px 12px;
            font-size: 14px;
            background: #f9fbff;
            transition: all .25s ease;
        }

        .form-input:focus,
        .form-control:focus,
        .form-select:focus {

            border-color: #5fa8ff;
            background: white;

            box-shadow:
                0 0 0 3px rgba(95, 168, 255, 0.15);
        }


        /* ===============================
    REQUIRED MARK
    ================================ */

        .required-mark {
            color: #e74c3c;
            font-weight: bold;
        }


        /* ===============================
    BUTTON AREA
    ================================ */

        .form-footer {
            display: flex;
            gap: 12px;
            padding: 20px 25px;
            border-top: 1px solid #edf1f7;
        }


        /* ===============================
    SAVE BUTTON
    ================================ */

        .btn-save {

            background: linear-gradient(135deg, #4a7dff, #6aa8ff);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px;

            box-shadow: 0 8px 18px rgba(74, 125, 255, 0.35);

            transition: all .3s ease;
        }

        .btn-save:hover {

            transform: translateY(-2px);

            box-shadow: 0 15px 35px rgba(74, 125, 255, 0.45);
        }


        /* ===============================
    CANCEL BUTTON
    ================================ */

        .btn-cancel {

            background: #f4f6fb;
            color: #5b6b8b;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;

            border: 1px solid #dce3ef;

            transition: all .25s ease;
        }

        .btn-cancel:hover {

            background: #e9efff;
            color: #2c3e50;
        }


        /* ===============================
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
            .col-md-6 {

                flex: 0 0 100%;
                max-width: 100%;
            }

            .section-body {
                padding: 20px;
            }

            .form-footer {
                flex-direction: column;
            }

        }


        /* ===============================
    SMOOTH INPUT HOVER EFFECT
    ================================ */

        .form-control:hover,
        .form-select:hover {

            border-color: #b8c8ea;
        }


        /* ===============================
    SUBTLE ANIMATION
    ================================ */

        .form-card {

            animation: fadeUp .4s ease;
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
    </style>
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
            color: #ef4444;
            /* Standard Red for required marks */
            margin-left: 2px;
        }
    </style> --}}

    <div class="container-fluid py-5">

        <div class="mx-auto mb-4 text-center text-md-start">
            <h3 class="page-title">Add Applicant</h3>
            <p class="text-muted mb-0">
                Register a new applicant into the system. Ensure all required fields (<span class="text-danger">*</span>)
                are filled.
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
                        <div class="col-md-2">
                            <label class="form-label">First Time Jobseeker <span class="required-mark">*</span></label>
                            <select name="first_time_job_seeker" class="form-select form-input" required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">First Name <span class="required-mark">*</span></label>
                            <input type="text" name="first_name" class="form-control form-input" placeholder="e.g. John"
                                required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Middle Name (Optional)</label>
                            <input type="text" name="middle_name" class="form-control form-input" placeholder="e.g. Quinto">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Last Name <span class="required-mark">*</span></label>
                            <input type="text" name="last_name" class="form-control form-input" placeholder="e.g. Doe"
                                required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Suffix (Optional)</label>
                            <select name="suffix" class="form-select form-input">
                                <option value="">None</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Age <span class="required-mark">*</span></label>
                            <input type="number" name="age" class="form-control form-input" placeholder="e.g. 25" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sex/Gender <span class="required-mark">*</span></label>
                            <select name="gender" class="form-select form-input" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <!-- Civil Status -->
                        <div class="col-md-2">
                            <label class="form-label">Civil Status <span class="required-mark">*</span></label>
                            <select name="civil_status" class="form-select form-input" required>
                                <option value="">Select Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>
                        <!-- PWD -->
                        <div class="col-md-2">
                            <label class="form-label">PWD <span class="required-mark">*</span></label>
                            <select name="pwd" class="form-select form-input" required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <!-- 4Ps -->
                        <div class="col-md-2">
                            <label class="form-label">4Ps Beneficiary <span class="required-mark">*</span></label>
                            <select name="four_ps" class="form-select form-input" required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Contact Number <span class="required-mark">*</span></label>
                            <input type="tel" name="contact_no" class="form-control form-input" placeholder="09123456789"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Complete Address <span class="required-mark">*</span></label>
                            <input type="text" name="address_line" class="form-control form-input"
                                placeholder="House No. / Street / Phase / Block" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Province <span class="required-mark">*</span></label>
                            <select name="province" id="province" class="form-select form-input" required>
                                <option value="">Select Province</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">City / Municipality <span class="required-mark">*</span></label>
                            <select name="city" id="city" class="form-select form-input" required>
                                <option value="">Select City</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Barangay <span class="required-mark">*</span></label>
                            <select name="barangay" id="barangay" class="form-select form-input" required>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Educational Attainment <span class="required-mark">*</span></label>
                            <input type="text" name="educational_attainment" class="form-control form-input"
                                placeholder="e.g. Bachelor's Degree" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hiring Company <span class="required-mark">*</span></label>
                            <input type="text" name="hiring_company" class="form-control form-input"
                                placeholder="e.g. Tech Corp" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Position Hired <span class="required-mark">*</span></label>
                            <input type="text" name="position_hired" class="form-control form-input"
                                placeholder="e.g. Software Engineer" required>
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