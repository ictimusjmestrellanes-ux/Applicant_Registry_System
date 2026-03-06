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
        }

        .page-title {
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0;
        }

        /* Custom Button Save */
        .btn-save {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s;
            border: none;
        }

        .btn-save:hover {
            background-color: var(--primary-hover);
            color: white;
            transform: translateY(-1px);
        }

        /* Table Card Styling */
        .table-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8fafc;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        /* Progress Bar Customization */
        .progress {
            background-color: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        /* Status Colors */
        .bg-success {
            background-color: #10b981 !important;
        }

        .bg-warning {
            background-color: #f59e0b !important;
        }

        .bg-danger {
            background-color: #ef4444 !important;
        }

        /* Search Bar Styling */
        .input-group-text {
            border-right: none;
        }

        .form-control:focus {
            box-shadow: none;
            background-color: #fff !important;
            border: 1px solid var(--primary-color);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>
    </head>

    <body>

        <div class="container-fluid py-4 px-md-5">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="page-title">Applicants</h3>
                    <p class="text-muted small mb-0">Manage and monitor registered applicants and their requirements.</p>
                </div>

                <a href="{{ route('applicants.create') }}" class="btn btn-save shadow-sm">
                    <i class="bi bi-person-plus-fill me-2"></i>Add Applicant
                </a>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('applicants.index') }}">
                        <div class="row g-3 align-items-end">

                            {{-- Search --}}
                            <div class="col-lg-5 col-md-6">
                                <label class="form-label fw-bold text-secondary small">SEARCH APPLICANT</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-0 bg-light py-2"
                                        placeholder="Enter name or contact number..." value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- Status Filter --}}
                            <div class="col-lg-3 col-md-3">
                                <label class="form-label fw-bold text-secondary small">STATUS</label>
                                <select name="status" class="form-select bg-light border-0 py-2">
                                    <option value="">All Applicants</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived
                                    </option>
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="col-lg-4 col-md-3">
                                <div class="d-flex gap-2 justify-content-lg-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-funnel me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('applicants.index') }}" class="btn btn-outline-secondary px-4">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <div class="card table-card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="align-middle">
                                <th>ID</th>
                                <th>Applicant Name</th>
                                <th>Contact</th>
                                <th style="min-width: 200px;">Address</th>
                                <th class="text-center">Mayor's Permit</th>
                                <th class="text-center">Mayor's Clearance</th>
                                <th class="text-center">Mayor's Referral</th>
                                <th>Date Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applicants as $applicant)
                                <tr>
                                    <h5>
                                        <td class="text-muted fw-medium">#{{ $applicant->id }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">
                                                {{ trim($applicant->first_name . ' ' . ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') . $applicant->last_name . ' ' . ($applicant->suffix ?? '')) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border"><i
                                                    class="bi bi-telephone me-1"></i>{{ $applicant->contact_no }}</span>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $applicant->address_line }}, {{ $applicant->barangay }}, {{ $applicant->city }}
                                        </td>

                                        {{-- Mayor's Permit Column --}}
                                        <td>
                                            @php
                                                $permit = optional($applicant->permit);

                                                // Detect if resident of City of Imus
                                                $isImusResident = stripos($applicant->city, 'City of Imus') !== false;

                                                if ($isImusResident) {
                                                    // 3 requirements for Imus residents
                                                    $pReqs = [
                                                        $permit->health_card,
                                                        $permit->nbi_or_police_clearance,
                                                        $permit->cedula,
                                                    ];
                                                } else {
                                                    // 4 requirements for non-Imus residents
                                                    $pReqs = [
                                                        $permit->health_card,
                                                        $permit->nbi_or_police_clearance,
                                                        $permit->cedula,
                                                        $permit->referral_letter,
                                                    ];
                                                }

                                                $pTotal = count($pReqs);
                                                $pUploaded = collect($pReqs)->filter()->count();
                                                $pPerc = $pTotal > 0 ? ($pUploaded / $pTotal) * 100 : 0;
                                            @endphp

                                            <div class="progress mb-1" style="height: 6px;">
                                                <div class="progress-bar 
                                                    {{ $pPerc == 100 ? 'bg-success' : ($pPerc > 0 ? 'bg-warning' : 'bg-danger') }}"
                                                    style="width: {{ $pPerc }}%;">
                                                </div>
                                            </div>

                                            <div class="text-center" style="font-size: 0.7rem;">
                                                <small class="text-muted">
                                                    {{ $pUploaded }} / {{ $pTotal }} Submitted
                                                </small>
                                            </div>
                                        </td>

                                        {{-- Mayor's Clearance Column --}}
                                        <td>
                                            @php
                                                $clearance = optional($applicant->clearance);
                                                $cReqs = [$clearance->prosecutor_clearance, $clearance->mtc_clearance, $clearance->rtc_clearance, $clearance->nbi_clearance, $clearance->barangay_clearance];
                                                $cUploaded = collect($cReqs)->filter()->count();
                                                $cTotal = count($cReqs);
                                                $cPerc = $cTotal > 0 ? ($cUploaded / $cTotal) * 100 : 0;
                                            @endphp
                                            <div class="progress mb-1" style="height: 6px;">
                                                <div class="progress-bar {{ $cPerc == 100 ? 'bg-success' : ($cPerc > 0 ? 'bg-warning' : 'bg-danger') }}"
                                                    style="width: {{ $cPerc }}%;"></div>
                                            </div>
                                            <div class="text-center" style="font-size: 0.7rem;"><small class="text-muted">
                                                    {{ $cUploaded }} / {{ $cTotal }} Submitted
                                                </small></div>
                                        </td>

                                        {{-- Mayor's Referral Column --}}
                                        <td>
                                            @php
                                                $referral = optional($applicant->referral);
                                                $hasResume = !empty($referral->resume);
                                                $hasOneClearance = collect([$referral->ref_barangay_clearance, $referral->ref_police_clearance, $referral->ref_nbi_clearance])->filter()->count() > 0;
                                                $rUploaded = ($hasResume ? 1 : 0) + ($hasOneClearance ? 1 : 0);
                                                $rPerc = ($rUploaded / 2) * 100;
                                            @endphp
                                            <div class="progress mb-1" style="height: 6px;">
                                                <div class="progress-bar {{ $rPerc == 100 ? 'bg-success' : ($rPerc > 0 ? 'bg-warning' : 'bg-danger') }}"
                                                    style="width: {{ $rPerc }}%;"></div>
                                            </div>
                                            <div class="text-center" style="font-size: 0.7rem;"><small class="text-muted">
                                                    {{ $rUploaded }} / 2 Submitted
                                                </small></div>
                                        </td>

                                        <td class="text-muted small">
                                            {{ $applicant->created_at->format('M d, Y') }}
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('applicants.edit', $applicant->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>

                                                <form action="{{ route('applicants.destroy', $applicant->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to archive this applicant?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Archive">
                                                        <i class="bi bi-archive"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </h5>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center p-5">
                                        <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3">No applicants found matching your criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $applicants->firstItem() }} to {{ $applicants->lastItem() }} of {{ $applicants->total() }}
                    applicants
                </div>
                <div>
                    {{ $applicants->links() }}
                </div>
            </div>

        </div>
@endsection