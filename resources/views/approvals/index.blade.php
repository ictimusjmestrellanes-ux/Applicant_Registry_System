@extends('layouts.app')

@section('title', 'Approvals')

@section('content')
    @php
        $disapproveModalUserId = session('disapprove_user_id');
    @endphp

    <style>
        :root {
            --approval-ink: #10243d;
            --approval-slate: #5f7088;
            --approval-line: #d9e4ef;
            --approval-panel: rgba(255, 255, 255, 0.96);
            --approval-primary: #1d4ed8;
            --approval-primary-soft: #dbeafe;
            --approval-success: #059669;
            --approval-success-soft: #d1fae5;
            --approval-warm: #b45309;
            --approval-warm-soft: #fef3c7;
            --approval-danger: #b91c1c;
            --approval-danger-soft: #fee2e2;
        }

        .approvals-page {
            max-width: 1720px;
        }

        .approvals-shell {
            display: grid;
            gap: 1rem;
        }

        .approval-hero,
        .approval-panel,
        .approval-table-shell {
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.78);
            background: var(--approval-panel);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .approval-hero {
            position: relative;
            overflow: hidden;
            padding: clamp(1.25rem, 2vw, 1.875rem);
        }

        .approval-hero::after {
            content: "";
            position: absolute;
            right: -70px;
            top: -70px;
            width: 240px;
            height: 240px;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.08);
        }

        .approval-hero > * {
            position: relative;
            z-index: 1;
        }

        .page-kicker,
        .metric-label,
        .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .page-kicker {
            margin-bottom: 10px;
            padding: 7px 12px;
            border-radius: 999px;
            background: var(--approval-primary-soft);
            color: var(--approval-primary);
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

        .approval-hero h2 {
            margin-bottom: 6px;
            color: var(--approval-ink);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .page-subtitle,
        .metric-copy,
        .panel-copy,
        .table-copy,
        .pagination-copy,
        .empty-copy {
            color: var(--approval-slate);
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-top: 22px;
        }

        .metric-card {
            padding: 18px;
            border-radius: 20px;
            border: 1px solid #e2ebf4;
            background: #ffffff;
            box-shadow: 0 12px 30px rgba(15, 34, 58, 0.05);
        }

        .metric-label {
            color: var(--approval-slate);
            margin-bottom: 8px;
        }

        .metric-value {
            color: var(--approval-ink);
            font-size: 1.08rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .approval-panel,
        .approval-table-shell {
            padding: clamp(1rem, 1.8vw, 1.375rem);
        }

        .section-kicker {
            margin-bottom: 0.45rem;
            color: var(--approval-slate);
        }

        .panel-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: var(--approval-success-soft);
            color: var(--approval-success);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.55rem 0.9rem;
            border-radius: 999px;
            border: 1px solid #dbe6f2;
            background: #fff;
            color: #334155;
            font-weight: 700;
            text-decoration: none;
        }

        .filter-pill.active {
            background: var(--approval-primary);
            border-color: var(--approval-primary);
            color: #fff;
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
        }

        .approval-search-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 52px;
            padding: 0 14px;
            border-radius: 15px;
            border: 1px solid var(--approval-line);
            background: #fff;
        }

        .approval-search-input {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--approval-ink);
            font-size: 0.95rem;
        }

        .approval-table-wrap {
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid #e2ebf4;
            background: #ffffff;
        }

        .approval-table {
            margin-bottom: 0;
        }

        .approval-table thead th {
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

        .approval-table tbody td {
            padding: 1rem 1.1rem;
            vertical-align: middle;
            border-color: #eef3f8;
        }

        .approval-table tbody tr:hover {
            background: #fbfdff;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            min-width: 0;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
            color: #1d4ed8;
            font-weight: 800;
            font-size: 0.92rem;
        }

        .user-name,
        .status-main {
            color: var(--approval-ink);
            font-weight: 700;
            word-break: break-word;
        }

        .user-meta,
        .status-meta {
            color: var(--approval-slate);
            font-size: 0.83rem;
            word-break: break-word;
        }

        .role-pill,
        .status-pill,
        .permission-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.42rem 0.72rem;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.04em;
        }

        .role-pill-admin {
            background: var(--approval-primary-soft);
            color: var(--approval-primary);
        }

        .role-pill-staff {
            background: var(--approval-success-soft);
            color: var(--approval-success);
        }

        .role-pill-user {
            background: #eef2f7;
            color: #475569;
        }

        .approval-pill-pending {
            background: var(--approval-warm-soft);
            color: var(--approval-warm);
        }

        .approval-pill-approved {
            background: var(--approval-success-soft);
            color: var(--approval-success);
        }

        .approval-pill-disapproved {
            background: var(--approval-danger-soft);
            color: var(--approval-danger);
        }

        .approval-pill-pending,
        .approval-pill-approved,
        .approval-pill-disapproved {
            transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease, color 0.18s ease;
        }

        .approval-pill-pending:hover,
        .approval-pill-approved:hover,
        .approval-pill-disapproved:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
        }

        .approval-pill-pending:hover {
            background: #fde68a;
            color: #92400e;
        }

        .approval-pill-approved:hover {
            background: #bbf7d0;
            color: #047857;
        }

        .approval-pill-disapproved:hover {
            background: #fecaca;
            color: #991b1b;
        }

        .btn-approve,
        .btn-disapprove {
            border-radius: 14px;
            padding: 10px 18px;
            font-weight: 700;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-approve {
            background: var(--approval-success);
            color: #fff;
        }

        .btn-disapprove {
            background: var(--approval-danger);
            color: #fff;
        }

        .btn-approve:hover,
        .btn-disapprove:hover {
            color: #fff;
            transform: translateY(-1px);
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
            background: #f5f8fc;
            color: #94a3b8;
            font-size: 2rem;
        }

        .empty-title {
            color: var(--approval-ink);
            font-size: 1rem;
            font-weight: 800;
        }

        .pagination-wrap {
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .pagination-wrap .pagination {
            margin-bottom: 0;
            gap: 0.35rem;
        }

        .pagination-wrap .page-link {
            min-width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid #dbe6f2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            background: #ffffff;
            box-shadow: none;
        }

        .pagination-wrap .page-link:hover {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: var(--approval-primary);
        }

        .pagination-wrap .page-item.active .page-link {
            background: var(--approval-primary);
            border-color: var(--approval-primary);
            color: #ffffff;
        }

        .pagination-wrap .page-item.disabled .page-link {
            background: #f8fafc;
            color: #94a3b8;
            border-color: #e2e8f0;
        }

        .alert-success {
            border-radius: 18px;
            background: #ecfdf5;
            color: #065f46;
        }

        @media (max-width: 1200px) {
            .metrics-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 991.98px) {
            .container-fluid.approvals-page {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .approval-hero::after {
                width: 180px;
                height: 180px;
                right: -50px;
                top: -50px;
            }
        }

        @media (max-width: 768px) {
            .approval-hero,
            .approval-panel,
            .approval-table-shell {
                padding: 18px;
            }

            .approval-hero h2 {
                font-size: 1.55rem;
            }

            .hero-top,
            .panel-header,
            .table-header,
            .pagination-wrap {
                flex-direction: column;
                align-items: flex-start;
            }

            .metrics-grid {
                grid-template-columns: 1fr;
            }

            .pagination-wrap .pagination {
                flex-wrap: wrap;
            }
        }
    </style>

    <div class="container-fluid approvals-page py-1 px-md-4 px-xl-1">
        <div class="approvals-shell">
            <section class="approval-hero">
                <span class="page-kicker">
                    <i class="bi bi-patch-check"></i>
                    Approval Queue
                </span>

                <div class="hero-top">
                    <div>
                        <h2>Applicant approvals</h2>
                        <p class="page-subtitle mb-0">Review pending applicant accounts, mark them approved or disapproved, and keep the queue organized.</p>
                    </div>
                </div>

                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-label">All</div>
                        <div class="metric-value">{{ number_format($statusCounts['all'] ?? 0) }}</div>
                        <span class="metric-copy">Applicant-linked accounts</span>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Pending</div>
                        <div class="metric-value">{{ number_format($statusCounts['pending'] ?? 0) }}</div>
                        <span class="metric-copy">Waiting for approval</span>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Approved</div>
                        <div class="metric-value">{{ number_format($statusCounts['approved'] ?? 0) }}</div>
                        <span class="metric-copy">Allowed to sign in</span>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Disapproved</div>
                        <div class="metric-value">{{ number_format($statusCounts['disapproved'] ?? 0) }}</div>
                        <span class="metric-copy">Blocked accounts</span>
                    </div>
                </div>
            </section>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            <section class="approval-panel">
                <div class="panel-header">
                    <div>
                        <div class="section-kicker">Review list</div>
                        <h5 class="fw-bold mb-1">Approve or disapprove applicants</h5>
                        <p class="panel-copy mb-0">Use the filters below to narrow the queue.</p>
                    </div>

                    <span class="panel-chip">
                        <i class="bi bi-shield-check me-2"></i>{{ ucfirst($status) }} view
                    </span>
                </div>

                <form method="GET" action="{{ route('approvals.index') }}" class="search-card mt-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-8">
                            <label class="form-label">Search</label>
                            <div class="approval-search-wrap">
                                <i class="bi bi-search text-muted"></i>
                                <input
                                    type="text"
                                    name="search"
                                    class="approval-search-input"
                                    value="{{ $search }}"
                                    placeholder="Search by name, username, or email"
                                >
                            </div>
                        </div>
                        <div class="col-lg-4 d-grid">
                            <button type="submit" class="btn btn-primary px-4">Search Queue</button>
                        </div>
                    </div>
                </form>

                <div class="filter-row">
                    <a href="{{ route('approvals.index', ['status' => 'all', 'search' => $search]) }}" class="filter-pill {{ $status === 'all' ? 'active' : '' }}">
                        All <span class="badge text-bg-light">{{ number_format($statusCounts['all'] ?? 0) }}</span>
                    </a>
                    <a href="{{ route('approvals.index', ['status' => 'pending', 'search' => $search]) }}" class="filter-pill {{ $status === 'pending' ? 'active' : '' }}">
                        Pending <span class="badge text-bg-light">{{ number_format($statusCounts['pending'] ?? 0) }}</span>
                    </a>
                    <a href="{{ route('approvals.index', ['status' => 'approved', 'search' => $search]) }}" class="filter-pill {{ $status === 'approved' ? 'active' : '' }}">
                        Approved <span class="badge text-bg-light">{{ number_format($statusCounts['approved'] ?? 0) }}</span>
                    </a>
                    <a href="{{ route('approvals.index', ['status' => 'disapproved', 'search' => $search]) }}" class="filter-pill {{ $status === 'disapproved' ? 'active' : '' }}">
                        Disapproved <span class="badge text-bg-light">{{ number_format($statusCounts['disapproved'] ?? 0) }}</span>
                    </a>
                </div>
            </section>

            <section class="approval-table-shell">
                <div class="table-header">
                    <div>
                        <div class="section-kicker">Applicant queue</div>
                        <h5 class="fw-bold mb-1">Status overview</h5>
                        <p class="table-copy mb-0">Review each account and take the approval action you need.</p>
                    </div>
                </div>

                <div class="approval-table-wrap mt-3">
                    <div class="table-responsive">
                        <table class="table approval-table align-middle">
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Username</th>
                                    <th>Status</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="user-cell">
                                                <div class="user-avatar">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="user-name">{{ $user->name }}</div>
                                                    <div class="user-meta">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="status-main">{{ $user->username ?: 'N/A' }}</div>
                                            <div class="status-meta">Applicant portal login</div>
                                        </td>
                                        <td>
                                            <div class="d-grid gap-1">
                                                <span class="role-pill {{ $user->approvalStatusBadgeClass() }}">
                                                    {{ $user->approvalStatusLabel() }}
                                                </span>
                                                @if($user->approval_status === \App\Models\User::APPROVAL_DISAPPROVED && trim((string) ($user->disapproval_reason ?? '')) !== '')
                                                    <small class="status-meta">
                                                        Reason: {{ $user->disapproval_reason }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="role-pill {{ $user->roleBadgeClass() }}">
                                                {{ $user->roleLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="status-main">{{ $user->created_at?->format('M d, Y') }}</div>
                                            <div class="status-meta">{{ $user->created_at?->format('h:i A') }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex gap-2 flex-wrap justify-content-center">
                                                @if($user->approval_status !== \App\Models\User::APPROVAL_APPROVED)
                                                    <form action="{{ route('users.approve', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-approve">
                                                            <i class="bi bi-check2-circle me-1"></i>Approve
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($user->approval_status !== \App\Models\User::APPROVAL_DISAPPROVED)
                                                    <button
                                                        type="button"
                                                        class="btn btn-disapprove"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#disapproveModal-{{ $user->id }}"
                                                    >
                                                        <i class="bi bi-x-circle me-1"></i>Disapprove
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        class="btn btn-disapprove"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#disapproveModal-{{ $user->id }}"
                                                    >
                                                        <i class="bi bi-pencil-square me-1"></i>Update Reason
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <div class="empty-icon">
                                                    <i class="bi bi-inbox"></i>
                                                </div>
                                                <div class="empty-title">No approvals found</div>
                                                <p class="empty-copy mb-0">Try a different filter or search term.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @foreach($users as $user)
                    <div class="modal fade" id="disapproveModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow-lg">
                                <form action="{{ route('users.disapprove', $user) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header">
                                        <div>
                                            <h5 class="modal-title mb-1">Disapprove Applicant</h5>
                                            <div class="text-muted small">{{ $user->name }} ({{ $user->username ?: 'No username' }})</div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <p class="text-muted mb-3">Please provide the reason for disapproval. This will be shown to the applicant when they try to log in.</p>

                                        @if($errors->any())
                                            <div class="alert alert-danger border-0">
                                                <ul class="mb-0 small">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Reason for disapproval <span class="text-danger">*</span></label>
                                            <textarea name="disapproval_reason" class="form-control" rows="3" required>{{ old('disapproval_reason', $user->disapproval_reason) }}</textarea>
                                        </div>

                                        <div class="mb-0">
                                            <label class="form-label fw-semibold">Additional notes</label>
                                            <textarea name="disapproval_notes" class="form-control" rows="4" placeholder="Optional notes for internal reference">{{ old('disapproval_notes', $user->disapproval_notes) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-disapprove">
                                            <i class="bi bi-x-circle me-1"></i>Confirm Disapprove
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($users->hasPages())
                    <div class="pagination-wrap">
                        <div class="pagination-copy">
                            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} accounts
                        </div>
                        <div>
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>

    @if($disapproveModalUserId)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('disapproveModal-{{ $disapproveModalUserId }}');
                if (modalEl && window.bootstrap) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            });
        </script>
    @endif
@endsection
