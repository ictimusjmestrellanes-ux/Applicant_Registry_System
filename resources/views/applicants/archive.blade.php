@extends('layouts.app')

@section('title', 'Archived Applicants')

@section('content')
    <div class="container-fluid py-4 px-md-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-archive me-2 text-secondary"></i>Archived Applicants
                </h3>
                <p class="text-muted small mb-0">Review archived records and restore applicants when needed.</p>
            </div>
            <a href="{{ route('applicants.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Active List
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('applicants.archive') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-8">
                            <label class="form-label fw-semibold text-secondary small">SEARCH ARCHIVED APPLICANT</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control border-0 bg-light"
                                    placeholder="Search by name, contact number, barangay, or city..."
                                    value="{{ $search ?? '' }}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="d-flex gap-2 justify-content-lg-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-funnel me-1"></i> Search
                                </button>
                                <a href="{{ route('applicants.archive') }}" class="btn btn-outline-secondary px-4">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase fs-xs fw-bold text-secondary">Applicant</th>
                                <th class="py-3 text-uppercase fs-xs fw-bold text-secondary">Contact</th>
                                <th class="py-3 text-uppercase fs-xs fw-bold text-secondary">Address</th>
                                <th class="py-3 text-uppercase fs-xs fw-bold text-secondary">Archived On</th>
                                <th class="pe-4 py-3 text-uppercase fs-xs fw-bold text-secondary text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applicants as $applicant)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm-muted me-3">
                                                {{ strtoupper(substr($applicant->first_name, 0, 1)) }}{{ strtoupper(substr($applicant->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    {{ trim($applicant->first_name . ' ' . ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') . $applicant->last_name . ' ' . ($applicant->suffix ?? '')) }}
                                                </div>
                                                <small class="text-muted">ID: #{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $applicant->contact_no ?: 'N/A' }}</div>
                                        <small class="text-muted">{{ $applicant->gender ?: 'N/A' }} / {{ $applicant->civil_status ?: 'N/A' }}</small>
                                    </td>
                                    <td class="text-muted small">
                                        {{ trim(collect([$applicant->address_line, $applicant->barangay, $applicant->city])->filter()->implode(', ')) ?: 'N/A' }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $applicant->deleted_at?->format('M d, Y') ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $applicant->deleted_at?->format('h:i A') ?? '' }}</small>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <form method="POST" action="{{ route('applicants.restore', $applicant->id) }}" class="d-inline">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="btn btn-restore btn-sm px-3 shadow-sm"
                                                onclick="return confirm('Restore this applicant to the active list?')"
                                            >
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-archive-fill display-1 text-light"></i>
                                            <p class="text-muted mt-3 mb-1">No archived applicants found.</p>
                                            <small class="text-muted">Try clearing the search or archive a record from the active applicants list.</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($applicants->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-muted small">
                    Showing {{ $applicants->firstItem() }} to {{ $applicants->lastItem() }} of {{ $applicants->total() }} archived applicants
                </div>
                <div>
                    {{ $applicants->links() }}
                </div>
            </div>
        @endif
    </div>

    <style>
        .fs-xs {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .avatar-sm-muted {
            width: 42px;
            height: 42px;
            background-color: #f1f5f9;
            color: #64748b;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .input-group {
            border-radius: 10px;
            overflow: hidden;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .btn-restore {
            background-color: #10b981;
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-restore:hover {
            background-color: #059669;
            color: white;
            transform: translateY(-1px);
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }
    </style>
@endsection
