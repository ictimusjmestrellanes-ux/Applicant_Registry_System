@extends('layouts.app')

@section('title', 'Archived Applicants')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-archive me-2 text-secondary"></i>Archived Applicants
                </h3>
                <p class="text-muted small mb-0">Manage and restore previously removed applicant records.</p>
            </div>
            <a href="{{ route('applicants.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Active List
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase fs-xs fw-bold text-secondary">Applicant Name</th>
                                <th class="py-3 text-uppercase fs-xs fw-bold text-secondary">Contact Information</th>
                                <th class="py-3 text-uppercase fs-xs fw-bold text-secondary">Date Archived</th>
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
                                                <div class="fw-bold text-dark">{{ $applicant->first_name }}
                                                    {{ $applicant->last_name }}</div>
                                                <small class="text-muted">ID:
                                                    #{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-telephone me-2 text-muted"></i>
                                            {{ $applicant->contact_no }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            {{ $applicant->deleted_at ? $applicant->deleted_at->format('M d, Y') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <form method="POST" action="{{ route('applicants.restore', $applicant->id) }}"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-restore btn-sm px-3 shadow-sm">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-archive-fill display-1 text-light"></i>
                                            <p class="text-muted mt-3">No archived applicants found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Table Styling */
        .fs-xs {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .avatar-sm-muted {
            width: 38px;
            height: 38px;
            background-color: #f1f5f9;
            color: #64748b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .table thead {
            border-bottom: 2px solid #f1f5f9;
        }

        .table tbody tr {
            transition: background-color 0.2s;
        }

        /* Restore Button Custom Style */
        .btn-restore {
            background-color: #10b981;
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.2s;
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