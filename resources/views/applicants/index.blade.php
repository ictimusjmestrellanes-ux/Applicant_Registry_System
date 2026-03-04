@extends('layouts.app')

@section('content')

    <div class="container-fluid">


        <!-- PAGE HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="page-title">Applicants</h3>
                <small class="text-muted">
                    Manage registered applicants
                </small>
            </div>


            <a href="{{ route('applicants.create') }}" class="btn btn-save">

                <i class="bi bi-person-plus"></i>
                Add Applicant

            </a>

        </div>
        <!-- SEARCH BAR -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <form method="GET" action="{{ route('applicants.index') }}">
                    <div class="row g-3 align-items-end">

                        {{-- Search --}}
                        <div class="col-lg-5 col-md-6">
                            <label class="form-label fw-semibold text-muted small">
                                Search Applicant
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-0 bg-light"
                                    placeholder="Enter name or contact number..." value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label fw-semibold text-muted small">
                                Status
                            </label>
                            <select name="status" class="form-select bg-light border-0">
                                <option value="">All Applicants</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>
                                    Archived
                                </option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-lg-4 col-md-12">
                            <div class="d-flex gap-2 justify-content-lg-end">

                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-funnel me-1"></i>
                                    Apply Filter
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



        <!-- SUCCESS MESSAGE -->

        @if(session('success'))

            <div class="alert alert-success shadow-sm">

                {{ session('success') }}

            </div>

        @endif



        <!-- TABLE CARD -->
        <div class="card table-card">

            <div class="card-body p-0">


                <table class="table table-responsive table-hover mb-0">

                    <thead class="table-light">
                        <tr class="align-middle text-nowrap">
                            <th class="text-muted small">ID</th>

                            <th>Applicant Name</th>

                            <th class="text-nowrap">Contact</th>

                            <th class="text-truncate" style="max-width: 200px;">
                                Address
                            </th>

                            <th class="text-center text-nowrap">
                                Mayor's Permit
                            </th>

                            <th class="text-center text-nowrap">
                                Mayor's Clearance
                            </th>

                            <th class="text-center text-nowrap">
                                Mayor's Referral
                            </th>

                            <th class="text-nowrap">
                                Date Created
                            </th>

                            <th class="text-center">
                                Actions
                            </th>
                        </tr>
                    </thead>



                    <tbody>

                        @forelse($applicants as $applicant)

                            <tr>

                                <td class="text-muted">
                                    #{{ $applicant->id }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $applicant->first_name }}
                                        {{ $applicant->middle_name }}
                                        {{ $applicant->last_name }}
                                        {{ $applicant->suffix }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $applicant->contact_no }}
                                </td>

                                <td class="text-muted">
                                    {{ $applicant->address_line }},
                                    {{ $applicant->barangay }},
                                    {{ $applicant->city }},
                                    {{ $applicant->province }}
                                </td>
                                <td>
                                    @php
                                        $permit = optional($applicant->permit);

                                        $requirements = [
                                            $permit->health_card,
                                            $permit->nbi_or_police_clearance,
                                            $permit->cedula,
                                            $permit->referral_letter,
                                        ];

                                        $uploaded = collect($requirements)->filter()->count();
                                        $total = count($requirements);
                                        $percentage = $total > 0 ? ($uploaded / $total) * 100 : 0;
                                    @endphp

                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar 
                                                                    {{ $percentage == 100 ? 'bg-success' : ($percentage > 0 ? 'bg-warning' : 'bg-danger') }}"
                                            role="progressbar" style="width: {{ $percentage }}%;"
                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ $uploaded }} / {{ $total }} Submitted
                                    </small>
                                </td>
                                <td>
                                    @php
                                        $clearance = optional($applicant->clearance);

                                        $requirements = [
                                            $clearance->prosecutor_clearance,
                                            $clearance->mtc_clearance,
                                            $clearance->rtc_clearance,
                                            $clearance->nbi_clearance,
                                            $clearance->barangay_clearance,
                                        ];

                                        $uploaded = collect($requirements)->filter()->count();
                                        $total = count($requirements);
                                        $percentage = $total > 0 ? ($uploaded / $total) * 100 : 0;
                                    @endphp

                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar 
                                                                    {{ $percentage == 100 ? 'bg-success' : ($percentage > 0 ? 'bg-warning' : 'bg-danger') }}"
                                            role="progressbar" style="width: {{ $percentage }}%;"
                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ $uploaded }} / {{ $total }} Submitted
                                    </small>
                                </td>
                                <td>
                                    @php
                                        $referral = optional($applicant->referral);

                                        $hasResume = !empty($referral->resume);

                                        $clearanceGroup = [
                                            $referral->barangay_clearance,
                                            $referral->police_clearance,
                                            $referral->nbi_clearance,
                                        ];

                                        $hasAtLeastOneClearance = collect($clearanceGroup)->filter()->count() > 0;

                                        // 2 main requirements:
                                        $requirements = [
                                            $hasResume,
                                            $hasAtLeastOneClearance,
                                        ];

                                        $uploaded = collect($requirements)->filter()->count();
                                        $total = count($requirements);
                                        $percentage = ($uploaded / $total) * 100;
                                    @endphp

                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar 
                                                                    {{ $percentage == 100 ? 'bg-success' : ($percentage > 0 ? 'bg-warning' : 'bg-danger') }}"
                                            role="progressbar" style="width: {{ $percentage }}%;"
                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>

                                    <small class="text-muted">
                                        {{ $uploaded }} / {{ $total }} Submitted
                                    </small>
                                </td>
                                <td>
                                    {{ $applicant->created_at }}
                                </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            {{-- Edit --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('applicants.edit', $applicant->id) }}">
                                                    <i class="bi bi-pencil me-2"></i> Edit
                                                </a>
                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            {{-- Archive --}}
                                            <li>
                                                <form action="{{ route('applicants.destroy', $applicant->id) }}" method="POST"
                                                    onsubmit="return confirm('Archive applicant?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-archive me-2"></i> Archive
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center p-4 text-muted">
                                    No applicants found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>


            </div>

        </div>



        <!-- PAGINATION -->

        <div class="mt-3 d-flex justify-content-end">

            {{ $applicants->links() }}

        </div>


    </div>



@endsection