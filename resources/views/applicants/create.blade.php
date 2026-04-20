@extends('layouts.app')

@section('title', 'Add Applicant')

@section('content')
    <style>
        :root {
            --create-ink: #10243d;
            --create-slate: #5f7088;
            --create-line: #d9e4ef;
            --create-soft: #f5f8fc;
            --create-panel: rgba(255, 255, 255, 0.84);
            --create-primary: #1d4ed8;
            --create-primary-soft: #dbeafe;
            --create-success: #059669;
            --create-success-soft: #d1fae5;
            --create-warm: #f59e0b;
            --create-warm-soft: #fef3c7;
        }

        body {
            background-color: #eef4f9;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .applicant-create-page {
            max-width: 1720px;
        }

        .create-hero {
            position: relative;
            overflow: hidden;
            padding: 28px 30px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            background-color: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .create-hero::after {
            content: "";
            position: absolute;
            right: -60px;
            top: -60px;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.07);
        }

        .page-title-wrap,
        .quick-guide,
        .form-shell {
            position: relative;
            z-index: 1;
        }

        .page-kicker,
        .stat-label,
        .field-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .page-kicker {
            margin-bottom: 10px;
            padding: 7px 12px;
            border-radius: 999px;
            background-color: var(--create-primary-soft);
            color: var(--create-primary);
        }
            display: block;
            margin-top: 6px;
            font-size: 0.82rem;
        }

        .quick-guide {
            padding: 1.4rem;
            height: 100%;
            background-color: #ffffff;
        }

        .guide-title {
            font-size: 0.96rem;
            font-weight: 800;
            color: var(--create-ink);
            margin-bottom: 0.8rem;
        }

        .guide-list {
            display: grid;
            gap: 0.75rem;
        }

        .guide-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.85rem 0.95rem;
            border-radius: 16px;
            background-color: #ffffff;
            border: 1px solid #e2ebf4;
        }

        .guide-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
        }

        .icon-blue {
            background-color: rgba(59, 130, 246, 0.12);
            color: #2563eb;
        }

        .icon-emerald {
            background-color: rgba(16, 185, 129, 0.12);
            color: #059669;
        }

        .icon-amber {
            background-color: rgba(245, 158, 11, 0.16);
            color: #b45309;
        }

        .form-shell {
            padding: 22px;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 18px;
            padding: 0 2px;
        }

        .form-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background-color: var(--create-success-soft);
            color: var(--create-success);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .form-stack {
            display: grid;
            gap: 1rem;
        }

        .form-section {
            padding: 1.35rem;
        }

        .section-title {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            color: var(--create-ink);
            font-size: 0.96rem;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .section-title::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background-color: #10b981;
            box-shadow: 0 0 0 6px rgba(59, 130, 246, 0.08);
        }

        .form-label {
            margin-bottom: 7px;
            color: #44526f;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .form-control,
        .form-select {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid var(--create-line);
            padding: 10px 14px;
            font-size: 14px;
            background: #f8fbff;
            transition: all .25s ease;
        }

        .form-control:hover,
        .form-select:hover {
            border-color: #bfd0e6;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #7aa2ff;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .required-mark {
            color: #ef4444;
            margin-left: 3px;
            font-weight: 800;
        }

        .aside-panel {
            display: grid;
            gap: 1rem;
            align-content: start;
        }

        .aside-card {
            padding: 1.2rem;
            border-radius: 20px;
            border: 1px solid #dfe9f3;
            background-color: #fbfdff;
        }

        .aside-title {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--create-ink);
            margin-bottom: 0.75rem;
        }

        .aside-list {
            display: grid;
            gap: 0.65rem;
        }

        .aside-item {
            display: flex;
            gap: 0.6rem;
            align-items: flex-start;
            color: var(--create-slate);
            font-size: 0.88rem;
        }

        .aside-dot {
            width: 8px;
            height: 8px;
            margin-top: 0.35rem;
            border-radius: 999px;
            background: #3b82f6;
            flex-shrink: 0;
        }

        .sticky-card {
            position: sticky;
            top: 24px;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #e4edf7;
            margin-top: 20px;
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
            background-color: #1d4ed8;
            border: none;
            color: #fff;
            box-shadow: 0 10px 22px rgba(29, 78, 216, 0.24);
        }

        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(29, 78, 216, 0.28);
            color: #5b6b8b;
        }

        @media (max-width: 1400px) {
            .form-layout {
                grid-template-columns: 1fr;
            }

            .sticky-card {
                position: static;
            }
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .create-hero {
                padding: 22px 20px;
            }

            .create-hero h2 {
                font-size: 1.55rem;
            }

            .form-shell,
            .form-section {
                padding: 18px;
            }

            .form-header,
            .form-footer {
                flex-direction: column;
                align-items: flex-start;
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

        <form action="{{ route('applicants.store') }}" method="POST">
            @csrf

            <div class="form-shell">
                <div class="form-header">
                    <div>
                        <h5 class="fw-bold mb-1">Applicant Information Form</h5>
                        <p class="form-note mb-0">Fill out the profile below. After saving, you’ll continue in the applicant
                            workspace to manage requirements.</p>
                    </div>
                </div>

                <div class="form-layout">
                    <div class="form-stack">
                        <section class="form-section">
                            <h6 class="section-title">Personal Information</h6>
                            <div class="row g-4">
                                <div class="col-md-2">
                                    <label class="form-label">First Time Jobseeker <span
                                            class="required-mark">*</span></label>
                                    <select name="first_time_job_seeker" class="form-select" required>
                                        <option value="NO" {{ old('first_time_job_seeker', 'NO') === 'NO' ? 'selected' : '' }}>NO</option>
                                        <option value="YES" {{ old('first_time_job_seeker', 'YES') === 'YES' ? 'selected' : '' }}>YES
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">First Name <span class="required-mark">*</span></label>
                                    <input type="text" name="first_name" class="form-control" oninput="this.value = this.value.toUpperCase()"
                                        value="{{ old('first_name') }}" placeholder="e.g. John" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" oninput="this.value = this.value.toUpperCase()"
                                        value="{{ old('middle_name') }}" placeholder="Optional">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Last Name <span class="required-mark">*</span></label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" oninput="this.value = this.value.toUpperCase()"
                                        placeholder="e.g. Doe" required>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Suffix</label>
                                    <select name="suffix" class="form-select">
                                        <option value="">Optional</option>
                                        <option value="JR." {{ old('suffix') === 'JR.' ? 'selected' : '' }}>JR.</option>
                                        <option value="SR." {{ old('suffix') === 'SR.' ? 'selected' : '' }}>SR.</option>
                                        <option value="II" {{ old('suffix') === 'II' ? 'selected' : '' }}>II</option>
                                        <option value="III" {{ old('suffix') === 'III' ? 'selected' : '' }}>III</option>
                                        <option value="IV" {{ old('suffix') === 'IV' ? 'selected' : '' }}>IV</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Age <span class="required-mark">*</span></label>
                                    <input type="number" name="age" class="form-control" value="{{ old('age') }}"
                                        placeholder="e.g. 25" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Sex/Gender <span class="required-mark">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select Gender</option>
                                        <option value="MALE" {{ old('gender') === 'MALE' ? 'selected' : '' }}>MALE</option>
                                        <option value="FEMALE" {{ old('gender') === 'FEMALE' ? 'selected' : '' }}>FEMALE
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Civil Status <span class="required-mark">*</span></label>
                                    <select name="civil_status" class="form-select" required>
                                        <option value="">Select Status</option>
                                        <option value="Single" {{ old('civil_status') === 'Single' ? 'selected' : '' }}>Single
                                        </option>
                                        <option value="Married" {{ old('civil_status') === 'Married' ? 'selected' : '' }}>
                                            Married</option>
                                        <option value="Widowed" {{ old('civil_status') === 'Widowed' ? 'selected' : '' }}>
                                            Widowed</option>
                                    </select>
                                </div>
                                <div class="col-md-1" style="text-align: center">
                                    <label class="form-label">PWD?<span class="required-mark">*</span></label>
                                    <select name="pwd" class="form-select" required>
                                        <option value="NO" {{ old('pwd', 'NO') === 'NO' ? 'selected' : '' }}>NO</option>
                                        <option value="YES" {{ old('pwd') === 'YES' ? 'selected' : '' }}>YES</option>
                                    </select>
                                </div>
                                <div class="col-md-1" style="text-align: center">
                                    <label class="form-label">4Ps?<span class="required-mark">*</span></label>
                                    <select name="four_ps" class="form-select" required>
                                        <option value="NO" {{ old('four_ps', 'NO') === 'NO' ? 'selected' : '' }}>NO</option>
                                        <option value="YES" {{ old('four_ps') === 'YES' ? 'selected' : '' }}>YES</option>
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section class="form-section">
                            <h6 class="section-title">Contact & Location</h6>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label">Contact Number <span class="required-mark">*</span></label>
                                    <input type="tel" name="contact_no" class="form-control" value="{{ old('contact_no') }}"
                                        placeholder="09123456789" pattern="[0-9]{11}" maxlength="11" inputmode="numeric"
                                        required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Complete Address <span class="required-mark">*</span></label>
                                    <input type="text" name="address_line" class="form-control" oninput="this.value = this.value.toUpperCase()"
                                        value="{{ old('address_line') }}" placeholder="House No. / Street / Phase / Block"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Province <span class="required-mark">*</span></label>
                                    <select name="province" id="province" class="form-select" required> 
                                        <option value="">Select Province</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">City / Municipality <span
                                            class="required-mark">*</span></label>
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
                            <h6 class="section-title">Education & Hiring</h6>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label">Educational Attainment <span
                                            class="required-mark">*</span></label>
                                    <select name="educational_attainment" id="educationalAttainmentSelect"
                                        class="form-select" required>
                                        <option value="">Select educational attainment</option>
                                        @foreach(config('educational_attainments', []) as $attainment)
                                            <option value="{{ $attainment }}" {{ old('educational_attainment') === $attainment ? 'selected' : '' }}>
                                                {{ $attainment }}
                                            </option>
                                        @endforeach
                                        @if(old('educational_attainment') && !in_array(old('educational_attainment'), config('educational_attainments', []), true))
                                            <option value="{{ old('educational_attainment') }}" selected>
                                                {{ old('educational_attainment') }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Hiring Company <span class="required-mark">*</span></label>
                                    <input type="text" name="hiring_company" class="form-control"
                                        oninput="this.value = this.value.toUpperCase()" value="{{ old('hiring_company') }}"
                                        placeholder="e.g. TECH CORP" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Position Hired <span class="required-mark">*</span></label>
                                    <input type="text" name="position_hired" class="form-control" oninput="this.value = this.value.toUpperCase()"
                                        value="{{ old('position_hired') }}" placeholder="e.g. Software Engineer" required>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('applicants.index') }}" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-check-circle-fill me-2"></i>Save Applicant
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const educationalAttainmentSelect = document.getElementById('educationalAttainmentSelect');

        if (!educationalAttainmentSelect || !window.jQuery || typeof window.jQuery.fn.select2 !== 'function') {
            return;
        }

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
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');
        const savedProvince = @json(old('province'));
        const savedCity = @json(old('city'));
        const savedBarangay = @json(old('barangay'));

        if (!provinceSelect || !citySelect || !barangaySelect) return;

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
                        if (savedProvince && name === String(savedProvince).toUpperCase()) option.selected = true;
                        provinceSelect.appendChild(option);
                        if (name && code) window._provinceCodeMap[name] = code;
                    });

                    const selected = provinceSelect.options[provinceSelect.selectedIndex];
                    if (selected) {
                        const code = selected.dataset.code || window._provinceCodeMap[selected.value];
                        if (code) loadCities(code);
                    }
                })
                .catch(() => provinceSelect.innerHTML = '<option value="">Unable to load provinces</option>');
        }

        async function loadCities(provinceIdentifier) {
            citySelect.innerHTML = '<option>Loading cities...</option>';
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

            let provinceCode = provinceIdentifier;
            if (isNaN(Number(provinceCode))) provinceCode = window._provinceCodeMap && window._provinceCodeMap[provinceCode] ? window._provinceCodeMap[provinceCode] : provinceCode;

            try {
                const res = await fetch(`https://psgc.gitlab.io/api/provinces/${encodeURIComponent(provinceCode)}/cities-municipalities/`);
                if (!res.ok) throw new Error('no-cities');
                const data = await res.json();
                citySelect.innerHTML = '<option value="">Select City</option>';
                data.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
                data.forEach(city => {
                    const rawName = city.name || city.description || '';
                    const cleaned = String(rawName).replace(/^\s*(city of|municipality of)\s+/i, '');
                    let name = String(cleaned).toUpperCase().trim();
                    const isCity = /city/i.test(rawName);
                    if (isCity && !/\bCITY$/.test(name)) name = (name + ' CITY').trim();
                    const option = document.createElement('option');
                    option.value = name;
                    option.textContent = name;
                    option.dataset.code = city.code || '';
                    if (savedCity && name === String(savedCity).toUpperCase()) option.selected = true;
                    citySelect.appendChild(option);
                });

                // If provinceIdentifier corresponds to Batangas, append extra institutions/entries
                (function appendBatangasExtras() {
                    let provinceNameUpper = '';
                    if (isNaN(Number(provinceIdentifier))) provinceNameUpper = String(provinceIdentifier).toUpperCase();
                    else if (window._provinceCodeMap) {
                        for (const k in window._provinceCodeMap) {
                            if (window._provinceCodeMap[k] === provinceCode) { provinceNameUpper = k; break; }
                        }
                    }

                    if (provinceNameUpper && provinceNameUpper.includes('BATANGAS')) {
                        const extras = ['BATANGAS PROVINCE','BATANGAS STATE UNIVERSITY','UNIVERSITY OF BATANGAS-MAIN','RIZAL COLLEGE OF TAAL'];
                        extras.forEach(raw => {
                            const base = String(raw).replace(/^\s*(city of|municipality of)\s+/i, '').toUpperCase().trim();
                            const isCityExtra = /city/i.test(raw);
                            const name = (isCityExtra && !/\bCITY$/.test(base)) ? (base + ' CITY') : base;
                            if (!Array.from(citySelect.options).some(o => o.value === name)) {
                                const option = document.createElement('option');
                                option.value = name;
                                option.textContent = name;
                                option.dataset.code = '';
                                if (savedCity && name === String(savedCity).toUpperCase()) option.selected = true;
                                citySelect.appendChild(option);
                            }
                        });
                    }

                    if (provinceNameUpper && provinceNameUpper.includes('CAVITE')) {
                        const caviteExtras = [
                            'ALFONSO','AMADEO','BACOOR CITY','CARMONA','CAVITE CITY','DASMARIÑAS CITY','GENERAL EMILIO AGUINALDO','GENERAL MARIANO ALVAREZ','CITY OF GENERAL TRIAS','IMUS CITY','INDANG','KAWIT','MAGALLANES','MARAGONDON','MENDEZ','NAIC','NOVELETA','ROSARIO','SILANG','TAGAYTAY CITY','TANZA','TERNATE','TRECE MARTIRES CITY','CAVITE PROVINCE'
                        ];
                        caviteExtras.forEach(raw => {
                            const base = String(raw).replace(/^\s*(city of|municipality of)\s+/i, '').toUpperCase().trim();
                            const isCityExtra = /city/i.test(raw);
                            const name = (isCityExtra && !/\bCITY$/.test(base)) ? (base + ' CITY') : base;
                            if (!Array.from(citySelect.options).some(o => o.value === name)) {
                                const option = document.createElement('option');
                                option.value = name;
                                option.textContent = name;
                                option.dataset.code = '';
                                if (savedCity && name === String(savedCity).toUpperCase()) option.selected = true;
                                citySelect.appendChild(option);
                            }
                        });
                    }
                })();

                const selectedCity = citySelect.options[citySelect.selectedIndex];
                if (selectedCity && selectedCity.dataset.code) loadBarangays(selectedCity.dataset.code);
            } catch (e) {
                citySelect.innerHTML = '<option value="">Unable to load cities</option>';
            }
        }

        function loadBarangays(cityCode) {
            barangaySelect.innerHTML = '<option>Loading barangays...</option>';
            if (!cityCode) return barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${encodeURIComponent(cityCode)}/barangays/`)
                .then(res => res.json())
                .then(data => {
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    data.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
                    data.forEach(b => {
                        const rawName = b.name || '';
                        const name = String(rawName).toUpperCase();
                        const option = document.createElement('option');
                        option.value = name;
                        option.textContent = name;
                        if (savedBarangay && name === String(savedBarangay).toUpperCase()) option.selected = true;
                        barangaySelect.appendChild(option);
                    });
                })
                .catch(() => barangaySelect.innerHTML = '<option value="">Unable to load barangays</option>');
        }

        provinceSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const code = selected?.dataset.code;
            if (code) loadCities(code); else loadCities(selected?.value);
        });

        citySelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const code = selected?.dataset.code;
            if (code) loadBarangays(code); else loadBarangays(selected?.value);
        });

        loadProvinces();
    });
</script>