@extends('layouts.app')

@section('title', 'Archived Applicants')

@section('content')
    <style>
        :root {
            --archive-ink: #10243d;
            --archive-slate: #5f7088;
            --archive-line: #d9e4ef;
            --archive-panel: rgba(255, 255, 255, 0.95);
            --archive-primary: #1d4ed8;
            --archive-success: #059669;
            --archive-success-soft: #d1fae5;
            --archive-warm: #b45309;
            --archive-warm-soft: #fef3c7;
            --archive-danger: #b91c1c;
            --archive-danger-soft: #fee2e2;
        }

        body {
            background-color: #eef4f9;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .archive-page {
            max-width: 1720px;
        }

        .archive-shell {
            display: grid;
            gap: 1rem;
        }

        .archive-hero {
            position: relative;
            overflow: hidden;
            padding: 28px 30px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            background-color: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .archive-hero::after {
            content: "";
            position: absolute;
            right: -70px;
            top: -70px;
            width: 240px;
            height: 240px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(180, 83, 9, 0.14), rgba(180, 83, 9, 0));
        }

        .archive-hero>* {
            position: relative;
            z-index: 1;
        }

        .hero-top,
        .panel-header,
        .table-header,
        .pagination-wrap {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .page-kicker,
        .metric-label,
        .table-label {
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
            background-color: var(--archive-warm-soft);
            color: var(--archive-warm);
        }

        .archive-hero h2 {
            margin-bottom: 6px;
            color: var(--archive-ink);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .page-subtitle,
        .metric-copy,
        .panel-copy,
        .table-copy,
        .empty-copy,
        .pagination-copy {
            color: var(--archive-slate);
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 22px;
        }

        .metric-card,
        .archive-panel,
        .archive-table-shell {
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            background-color: var(--archive-panel);
            backdrop-filter: blur(12px);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.06);
        }

        .metric-card {
            padding: 18px 18px 16px;
        }

        .metric-label {
            color: var(--archive-slate);
            margin-bottom: 8px;
        }

        .metric-value {
            color: var(--archive-ink);
            font-size: 1.08rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .metric-copy {
            display: block;
            margin-top: 6px;
            font-size: 0.82rem;
        }

        .archive-panel,
        .archive-table-shell {
            padding: 22px;
        }

        .panel-header,
        .table-header {
            margin-bottom: 18px;
        }

        .panel-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background-color: var(--archive-success-soft);
            color: var(--archive-success);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .search-card {
            padding: 1rem;
            border-radius: 18px;
            border: 1px solid #e2ebf4;
            background: linear-gradient(180deg, #fbfdff 0%, #f8fbff 100%);
        }

        .form-label {
            margin-bottom: 7px;
            color: #44526f;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .archive-search-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 50px;
            padding: 0 14px;
            border-radius: 14px;
            border: 1px solid var(--archive-line);
            background: #ffffff;
            transition: all 0.25s ease;
        }

        .archive-search-wrap:focus-within {
            border-color: #7aa2ff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .archive-search-icon {
            color: var(--archive-slate);
            font-size: 0.95rem;
        }

        .archive-search-input {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--archive-ink);
            font-size: 0.95rem;
        }

        .archive-search-input::placeholder {
            color: #91a0b5;
        }

        .archive-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-ghost,
        .btn-primary-soft,
        .btn-restore {
            border-radius: 14px;
            padding: 10px 18px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .btn-ghost {
            background: #f4f6fb;
            color: #5b6b8b;
            border: 1px solid #dce3ef;
        }

        .btn-ghost:hover {
            background: #e9efff;
            color: #2c3e50;
        }

        .btn-primary-soft {
            background-color: var(--archive-primary);
            border: none;
            color: #fff;
            box-shadow: 0 10px 22px rgba(29, 78, 216, 0.24);
        }

        .btn-primary-soft:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(29, 78, 216, 0.28);
            color: #fff;
        }

        .table-label {
            margin-bottom: 0.4rem;
            color: var(--archive-slate);
        }

        .archive-table-wrap {
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid #e2ebf4;
            background: #ffffff;
        }

        .archive-table {
            margin-bottom: 0;
        }

        .archive-table thead th {
            padding: 1rem 1.1rem;
            border-bottom: 1px solid #e8eef6;
            background: #f8fbff;
            color: #6a7b92;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            white-space: nowrap;
        }

        .archive-table tbody td {
            padding: 1rem 1.1rem;
            vertical-align: middle;
            border-color: #eef3f8;
        }

        .archive-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .archive-table tbody tr:hover {
            background: #fbfdff;
        }

        .applicant-cell {
            display: flex;
            align-items: center;
            gap: 0.9rem;
        }

        .applicant-avatar {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #f1f5f9;
            color: #64748b;
            font-weight: 800;
            font-size: 0.92rem;
        }

        .applicant-name,
        .archive-date,
        .contact-main {
            color: var(--archive-ink);
            font-weight: 700;
        }

        .applicant-meta,
        .contact-meta,
        .archive-time {
            color: var(--archive-slate);
            font-size: 0.83rem;
        }

        .address-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.42rem 0.72rem;
            border-radius: 999px;
            background: #f5f8fc;
            color: #4d5f78;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .btn-restore {
            background-color: var(--archive-success);
            border: none;
            color: #fff;
            box-shadow: 0 10px 20px rgba(5, 150, 105, 0.22);
        }

        .btn-restore:hover {
            transform: translateY(-1px);
            background-color: #047857;
            color: #fff;
        }

        .empty-state {
            padding: 3.5rem 1.5rem;
            text-align: center;
        }

        .empty-icon {
            width: 78px;
            height: 78px;
            margin: 0 auto 1rem;
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--archive-danger-soft);
            color: var(--archive-danger);
            font-size: 2rem;
        }

        .empty-title {
            color: var(--archive-ink);
            font-size: 1rem;
            font-weight: 800;
        }

        .pagination-wrap {
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .alert-success {
            border-radius: 18px;
            background: #ecfdf5;
            color: #065f46;
        }

        @media (max-width: 1200px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {

            .archive-hero,
            .archive-panel,
            .archive-table-shell {
                padding: 18px;
            }

            .archive-hero h2 {
                font-size: 1.55rem;
            }

            .hero-top,
            .panel-header,
            .table-header,
            .pagination-wrap {
                flex-direction: column;
                align-items: flex-start;
            }

            .archive-actions {
                width: 100%;
                justify-content: stretch;
            }

            .archive-actions .btn {
                width: 100%;
            }
        }
    </style>

    <div class="container-fluid archive-page py-4 px-md-4 px-xl-5">
        <div class="archive-shell">
            <section class="archive-hero">
                <span class="page-kicker">
                    <i class="bi bi-archive"></i>
                    Archive workspace
                </span>

                <div class="hero-top">
                    <div>
                        <h2>Archived Applicants</h2>
                        <p class="page-subtitle mb-0">Review archived records, search historical entries, and restore
                            applicants back to the active registry when needed.</p>
                    </div>

                    <a href="{{ route('applicants.index') }}" class="btn btn-ghost">
                        <i class="bi bi-arrow-left me-2"></i>Back to Active List
                    </a>
                </div>
            </section>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            <section class="archive-panel">
                <div class="panel-header">
                    <div>
                        <h5 class="fw-bold mb-1">Search Archive</h5>
                        <p class="panel-copy mb-0">Find archived applicants by name, contact number, barangay, or city.</p>
                    </div>
                    <span class="panel-chip">
                        <i class="bi bi-funnel me-2"></i>Filter records
                    </span>
                </div>

                <form method="GET" action="{{ route('applicants.archive') }}">
                    <div class="search-card">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-8">
                                <label class="form-label">Archived Applicant Search</label>
                                <div class="archive-search-wrap">
                                    <i class="bi bi-search archive-search-icon"></i>
                                    <input type="text" name="search" class="archive-search-input"
                                        placeholder="Search by name, contact number, barangay, or city..."
                                        value="{{ $search ?? '' }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="archive-actions">
                                    <button type="submit" class="btn btn-primary-soft">
                                        <i class="bi bi-search me-2"></i>Search
                                    </button>
                                    <a href="{{ route('applicants.archive') }}" class="btn btn-ghost">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            <section class="archive-table-shell">
                <div class="table-header">
                    <div>
                        <div class="table-label">Archived Records</div>
                        <h5 class="fw-bold mb-1">Applicant archive table</h5>
                        <p class="table-copy mb-0">Review archived applicant details and restore records without leaving the
                            page.</p>
                    </div>
                </div>

                <div class="archive-table-wrap">
                    <div class="table-responsive">
                        <table class="table archive-table align-middle">
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Archived On</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applicants as $applicant)
                                    <tr>
                                        <td>
                                            <div class="applicant-cell">
                                                <div class="applicant-avatar">
                                                    {{ strtoupper(substr($applicant->first_name, 0, 1)) }}{{ strtoupper(substr($applicant->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="applicant-name">
                                                        {{ trim($applicant->first_name . ' ' . ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') . $applicant->last_name . ' ' . ($applicant->suffix ?? '')) }}
                                                    </div>
                                                    <div class="applicant-meta">ID:
                                                        #{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="contact-main">{{ $applicant->contact_no ?: 'N/A' }}</div>
                                            <div class="contact-meta">{{ $applicant->gender ?: 'N/A' }} /
                                                {{ $applicant->civil_status ?: 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <span class="address-pill">
                                                {{ trim(collect([$applicant->address_line, $applicant->barangay, $applicant->city])->filter()->implode(', ')) ?: 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="archive-date">{{ $applicant->deleted_at?->format('M d, Y') ?? 'N/A' }}
                                            </div>
                                            <div class="archive-time">{{ $applicant->deleted_at?->format('h:i A') ?? '' }}</div>
                                        </td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('applicants.restore', $applicant->id) }}"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-restore"
                                                    onclick="return confirm('Restore this applicant to the active list?')">
                                                    <i class="bi bi-arrow-counterclockwise me-2"></i>Restore
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <div class="empty-icon">
                                                    <i class="bi bi-archive"></i>
                                                </div>
                                                <div class="empty-title">No archived applicants found</div>
                                                <p class="empty-copy mb-0">Try clearing the search or archive a record from the
                                                    active applicants list.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($applicants->hasPages())
                    <div class="pagination-wrap">
                        <div class="pagination-copy">
                            Showing {{ $applicants->firstItem() }} to {{ $applicants->lastItem() }} of
                            {{ $applicants->total() }} archived applicants
                        </div>
                        <div>
                            {{ $applicants->links() }}
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection