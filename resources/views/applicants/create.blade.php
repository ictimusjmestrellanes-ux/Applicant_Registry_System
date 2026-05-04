@extends('layouts.app')

@section('title', 'Add Applicant')

@section('content')
    @php
        $educationalAttainments = config('educational_attainments', []);
        $selectedEducationalAttainment = old('educational_attainment');
    @endphp

    <style>
        :root {
            --create-ink: #10243d;
            --create-slate: #5f7088;
            --create-line: #d9e4ef;
            --create-soft: #f5f8fc;
            --create-panel: rgba(255, 255, 255, 0.88);
            --create-primary: #1d4ed8;
            --create-primary-soft: #dbeafe;
            --create-success: #059669;
            --create-success-soft: #d1fae5;
            --create-warm: #f59e0b;
            --create-warm-soft: #fef3c7;
        }

        .applicant-create-page {
            max-width: 1680px;
        }

        .create-hero {
            position: relative;
            overflow: hidden;
            padding: 28px 30px;
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .create-hero::after {
            content: "";
            position: absolute;
            right: -70px;
            top: -70px;
            width: 240px;
            height: 240px;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.06);
        }

        .page-kicker,
        .section-title,
        .field-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .page-kicker {
            margin-bottom: 10px;
            padding: 7px 12px;
            border-radius: 999px;
            background: var(--create-primary-soft);
            color: var(--create-primary);
            font-size: 0.75rem;
        }

        .page-title {
            margin: 0 0 10px;
            color: var(--create-ink);
            font-size: clamp(1.8rem, 2.6vw, 2.6rem);
            font-weight: 900;
            letter-spacing: -0.03em;
        }

        .page-copy {
            max-width: 820px;
            margin: 0;
            color: var(--create-slate);
        }

        .hero-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
            justify-content: flex-end;
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 0.85rem;
            border-radius: 999px;
            border: 1px solid #dce6f0;
            background: rgba(255, 255, 255, 0.95);
            color: #4b5f7a;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .form-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1.25rem;
            margin-top: 1.25rem;
        }

        .form-shell {
            min-width: 0;
        }

        .form-shell {
            padding: 22px;
            border-radius: 28px;
            border: 1px solid #e5edf5;
            background: var(--create-panel);
            box-shadow: 0 22px 48px rgba(15, 23, 42, 0.08);
        }

        .form-stack {
            display: grid;
            gap: 1rem;
        }

        .form-section {
            padding: 1.35rem;
            border-radius: 22px;
            border: 1px solid #e4edf7;
            background: #ffffff;
        }

        .section-title {
            position: relative;
            margin-bottom: 16px;
            color: var(--create-ink);
            font-size: 0.92rem;
        }

        .section-title::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, #10b981, #3b82f6);
            box-shadow: 0 0 0 6px rgba(59, 130, 246, 0.08);
        }

        .form-label {
            margin-bottom: 7px;
            color: #44526f;
            font-size: 0.82rem;
        }

        .form-control,
        .form-select {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid var(--create-line);
            padding: 10px 14px;
            font-size: 14px;
            background: #f8fbff;
            transition: all 0.25s ease;
        }

        .form-control:hover,
        .form-select:hover {
            border-color: #bfd0e6;
            background: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #7aa2ff;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .required-mark {
            color: #ef4444;
            margin-left: 3px;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #e4edf7;
        }

        .btn-cancel,
        .btn-save {
            border-radius: 14px;
            padding: 10px 20px;
            font-weight: 700;
        }

        .btn-cancel {
            background: #f4f6fb;
            color: #5b6b8b;
            border: 1px solid #dce3ef;
        }

        .btn-cancel:hover {
            background: #e9efff;
            color: #2c3e50;
        }

        .btn-save {
            background: #1d4ed8;
            border: none;
            color: #ffffff;
            box-shadow: 0 10px 22px rgba(29, 78, 216, 0.24);
        }

        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(29, 78, 216, 0.28);
            color: #ffffff;
        }

        .select2-container .select2-selection--single {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid var(--create-line);
            background: #f8fbff;
            display: flex;
            align-items: center;
            padding: 0 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #212529;
            line-height: 46px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            right: 10px;
        }

        @media (max-width: 1400px) {
            .form-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .create-hero,
            .form-shell,
            .form-section {
                padding: 18px;
            }

            .form-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-cancel,
            .btn-save {
                width: 100%;
            }
        }
    </style>

    <div class="container-fluid applicant-create-page py-4 px-md-4 px-xl-5">
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="create-hero">
            <div class="row g-3 align-items-start">
                <div class="col-lg-8">
                    <span class="page-kicker">Applicant Intake</span>
                    <h1 class="page-title">Add a new applicant record</h1>
                    <p class="page-copy">
                        Use this form to register an applicant profile before moving on to permits, clearance, or referral processing.
                    </p>
                </div>
                
            </div>
        </div>

        <div class="form-layout">
            <div class="form-shell">
                <form action="{{ route('applicants.store') }}" method="POST">
                    @csrf

                    <div class="form-stack">
                        <section class="form-section">
                            <h2 class="section-title">Personal Information</h2>
                            <div class="row g-4">
                                <div class="col-md-2">
                                    <label class="form-label">First Time Jobseeker <span class="required-mark">*</span></label>
                                    <select name="first_time_job_seeker" class="form-select" required>
                                        <option value="NO" {{ old('first_time_job_seeker', 'NO') === 'NO' ? 'selected' : '' }}>NO</option>
                                        <option value="YES" {{ old('first_time_job_seeker') === 'YES' ? 'selected' : '' }}>YES</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">First Name <span class="required-mark">*</span></label>
                                    <input type="text" name="first_name" class="form-control"
                                        value="{{ old('first_name') }}" oninput="this.value = this.value.toUpperCase()"
                                        placeholder="e.g. JOHN" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control"
                                        value="{{ old('middle_name') }}" oninput="this.value = this.value.toUpperCase()"
                                        placeholder="Optional">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Last Name <span class="required-mark">*</span></label>
                                    <input type="text" name="last_name" class="form-control"
                                        value="{{ old('last_name') }}" oninput="this.value = this.value.toUpperCase()"
                                        placeholder="e.g. DOE" required>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Suffix</label>
                                    <select name="suffix" class="form-select">
                                        <option value="">Optional</option>
                                        @foreach (['JR.', 'SR.', 'II', 'III', 'IV'] as $suffix)
                                            <option value="{{ $suffix }}" {{ old('suffix') === $suffix ? 'selected' : '' }}>
                                                {{ $suffix }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Age <span class="required-mark">*</span></label>
                                    <input type="number" name="age" class="form-control" value="{{ old('age') }}"
                                        placeholder="e.g. 25" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Sex <span class="required-mark">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select Sex</option>
                                        <option value="MALE" {{ old('gender') === 'MALE' ? 'selected' : '' }}>MALE</option>
                                        <option value="FEMALE" {{ old('gender') === 'FEMALE' ? 'selected' : '' }}>FEMALE</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Civil Status <span class="required-mark">*</span></label>
                                    <select name="civil_status" class="form-select" required>
                                        <option value="">Select Status</option>
                                        <option value="SINGLE" {{ old('civil_status') === 'SINGLE' ? 'selected' : '' }}>SINGLE</option>
                                        <option value="MARRIED" {{ old('civil_status') === 'MARRIED' ? 'selected' : '' }}>MARRIED</option>
                                        <option value="WIDOWED" {{ old('civil_status') === 'WIDOWED' ? 'selected' : '' }}>WIDOWED</option>
                                    </select>
                                </div>
                                <div class="col-md-1 text-center">
                                    <label class="form-label">PWD<span class="required-mark">*</span></label>
                                    <select name="pwd" class="form-select" required>
                                        <option value="NO" {{ old('pwd', 'NO') === 'NO' ? 'selected' : '' }}>NO</option>
                                        <option value="YES" {{ old('pwd') === 'YES' ? 'selected' : '' }}>YES</option>
                                    </select>
                                </div>
                                <div class="col-md-1 text-center">
                                    <label class="form-label">4Ps<span class="required-mark">*</span></label>
                                    <select name="four_ps" class="form-select" required>
                                        <option value="NO" {{ old('four_ps', 'NO') === 'NO' ? 'selected' : '' }}>NO</option>
                                        <option value="YES" {{ old('four_ps') === 'YES' ? 'selected' : '' }}>YES</option>
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section class="form-section">
                            <h2 class="section-title">Contact & Location</h2>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label">Contact Number <span class="required-mark">*</span></label>
                                    <input type="tel" name="contact_no" class="form-control"
                                        value="{{ old('contact_no') }}" placeholder="09123456789"
                                        pattern="[0-9]{11}" maxlength="11" inputmode="numeric" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Complete Address <span class="required-mark">*</span></label>
                                    <input type="text" name="address_line" class="form-control"
                                        value="{{ old('address_line') }}" oninput="this.value = this.value.toUpperCase()"
                                        placeholder="House No. / Street / Phase / Block" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Province <span class="required-mark">*</span></label>
                                    <select name="province" id="province" class="form-select" required>
                                        <option value="">Select Province</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">City / Municipality <span class="required-mark">*</span></label>
                                    <select name="city" id="city" class="form-select" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Barangay <span class="required-mark">*</span></label>
                                    <select name="barangay" id="barangay" class="form-select" required>
                                        <option value="">Select Barangay</option>
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section class="form-section">
                            <h2 class="section-title">Education & Hiring</h2>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label">Educational Attainment <span class="required-mark">*</span></label>
                                    <select name="educational_attainment" id="educationalAttainmentSelect" class="form-select" required>
                                        <option value="">Select educational attainment</option>
                                        @foreach ($educationalAttainments as $attainment)
                                            <option value="{{ $attainment }}" {{ $selectedEducationalAttainment === $attainment ? 'selected' : '' }}>
                                                {{ $attainment }}
                                            </option>
                                        @endforeach
                                        @if ($selectedEducationalAttainment && !in_array($selectedEducationalAttainment, $educationalAttainments, true))
                                            <option value="{{ $selectedEducationalAttainment }}" selected>{{ $selectedEducationalAttainment }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Hiring Company <span class="required-mark">*</span></label>
                                    <input type="text" name="hiring_company" class="form-control"
                                        value="{{ old('hiring_company') }}" oninput="this.value = this.value.toUpperCase()"
                                        placeholder="e.g. TECH CORP" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Position Hired <span class="required-mark">*</span></label>
                                    <input type="text" name="position_hired" class="form-control"
                                        value="{{ old('position_hired') }}" oninput="this.value = this.value.toUpperCase()"
                                        placeholder="e.g. SOFTWARE ENGINEER" required>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="form-footer">
                        <a href="{{ route('applicants.index') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-check-circle-fill me-2"></i>Save Applicant
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const educationalAttainmentSelect = document.getElementById('educationalAttainmentSelect');

            if (educationalAttainmentSelect && window.jQuery && typeof window.jQuery.fn.select2 === 'function') {
                window.jQuery(educationalAttainmentSelect).select2({
                    placeholder: 'Select educational attainment',
                    allowClear: true,
                    width: '100%',
                    dropdownAutoWidth: true,
                    minimumResultsForSearch: 0,
                    ajax: {
                        url: '{{ url('/api/educational-attainments') }}',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term || ''
                        }),
                        processResults: data => ({
                            results: data.results || []
                        })
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');
            const barangaySelect = document.getElementById('barangay');
            const savedProvince = @json(old('province'));
            const savedCity = @json(old('city'));
            const savedBarangay = @json(old('barangay'));

            if (!provinceSelect || !citySelect || !barangaySelect) return;

            function normalizeName(value) {
                return String(value || '').replace(/^\s*(city of|municipality of)\s+/i, '').trim().toUpperCase();
            }

            function loadProvinces() {
                provinceSelect.innerHTML = '<option value="">Loading provinces...</option>';

                fetch('https://psgc.gitlab.io/api/provinces/')
                    .then(res => res.json())
                    .then(data => {
                        const provinces = Array.isArray(data) ? data : [];
                        provinceSelect.innerHTML = '<option value="">Select Province</option>';
                        window._provinceCodeMap = window._provinceCodeMap || {};

                        provinces.sort((a, b) => {
                            const an = (a.name || a.province || a.description || '').toString();
                            const bn = (b.name || b.province || b.description || '').toString();
                            return an.localeCompare(bn, undefined, { sensitivity: 'base' });
                        });

                        provinces.forEach(p => {
                            const rawName = p.name || p.province || p.description || '';
                            const name = String(rawName).toUpperCase();
                            const code = p.code || '';

                            const option = document.createElement('option');
                            option.value = name;
                            option.textContent = name;
                            option.dataset.code = code;

                            if (savedProvince && name === String(savedProvince).toUpperCase()) {
                                option.selected = true;
                            }

                            provinceSelect.appendChild(option);

                            if (name && code) {
                                window._provinceCodeMap[name] = code;
                            }
                        });

                        const selected = provinceSelect.options[provinceSelect.selectedIndex];
                        if (selected) {
                            const code = selected.dataset.code || window._provinceCodeMap[selected.value];
                            if (code) {
                                loadCities(code);
                            }
                        }
                    })
                    .catch(() => {
                        provinceSelect.innerHTML = '<option value="">Unable to load provinces</option>';
                    });
            }

            function loadCities(provinceIdentifier) {
                citySelect.innerHTML = '<option value="">Loading cities...</option>';
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                let provinceCode = provinceIdentifier;
                if (isNaN(Number(provinceCode))) {
                    provinceCode = window._provinceCodeMap && window._provinceCodeMap[provinceCode]
                        ? window._provinceCodeMap[provinceCode]
                        : provinceCode;
                }

                fetch(`https://psgc.gitlab.io/api/provinces/${encodeURIComponent(provinceCode)}/cities-municipalities/`)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('no-cities');
                        }

                        return res.json();
                    })
                    .then(data => {
                        citySelect.innerHTML = '<option value="">Select City</option>';
                        const cities = Array.isArray(data) ? data : [];

                        cities.sort((a, b) => (a.name || '').localeCompare(b.name || '', undefined, { sensitivity: 'base' }));

                        cities.forEach(city => {
                            const rawName = city.name || city.description || '';
                            const cleaned = normalizeName(rawName);
                            const isCity = /city/i.test(rawName);
                            const name = isCity && !/\bCITY$/.test(cleaned) ? `${cleaned} CITY` : cleaned;

                            const option = document.createElement('option');
                            option.value = name;
                            option.textContent = name;
                            option.dataset.code = city.code || '';

                            if (savedCity && name === String(savedCity).toUpperCase()) {
                                option.selected = true;
                            }

                            citySelect.appendChild(option);
                        });

                        const selectedCity = citySelect.options[citySelect.selectedIndex];
                        if (selectedCity && selectedCity.dataset.code) {
                            loadBarangays(selectedCity.dataset.code);
                        }
                    })
                    .catch(() => {
                        citySelect.innerHTML = '<option value="">Unable to load cities</option>';
                    });
            }

            function loadBarangays(cityCode) {
                barangaySelect.innerHTML = '<option value="">Loading barangays...</option>';

                if (!cityCode) {
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    return;
                }

                fetch(`https://psgc.gitlab.io/api/cities-municipalities/${encodeURIComponent(cityCode)}/barangays/`)
                    .then(res => res.json())
                    .then(data => {
                        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                        const barangays = Array.isArray(data) ? data : [];

                        barangays.sort((a, b) => (a.name || '').localeCompare(b.name || '', undefined, { sensitivity: 'base' }));

                        barangays.forEach(barangay => {
                            const rawName = barangay.name || '';
                            const cleaned = String(rawName).replace(/\(\s*POB\.?\s*\)/ig, '').trim();
                            const name = String(cleaned).toUpperCase();

                            const option = document.createElement('option');
                            option.value = name;
                            option.textContent = name;

                            if (savedBarangay && name === String(savedBarangay).toUpperCase()) {
                                option.selected = true;
                            }

                            barangaySelect.appendChild(option);
                        });
                    })
                    .catch(() => {
                        barangaySelect.innerHTML = '<option value="">Unable to load barangays</option>';
                    });
            }

            provinceSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const code = selected?.dataset.code;

                if (code) {
                    loadCities(code);
                } else if (selected?.value) {
                    loadCities(selected.value);
                }
            });

            citySelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const code = selected?.dataset.code;

                if (code) {
                    loadBarangays(code);
                } else {
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                }
            });

            loadProvinces();
        });
    </script>
@endsection
