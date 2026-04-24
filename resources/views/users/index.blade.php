@extends('layouts.app')

@section('title', 'Users')

@section('content')
    @php
        $adminCount = $users->getCollection()->filter(fn ($user) => $user->isAdmin())->count();
        $staffCount = $users->getCollection()->count() - $adminCount;
        $azureCount = $users->getCollection()->filter(fn ($user) => ($user->auth_provider ?? 'local') === 'azure')->count();
    @endphp

    <style>
        :root {
            --users-ink: #10243d;
            --users-slate: #5f7088;
            --users-line: #d9e4ef;
            --users-panel: rgba(255, 255, 255, 0.96);
            --users-primary: #1d4ed8;
            --users-primary-soft: #dbeafe;
            --users-success: #059669;
            --users-success-soft: #d1fae5;
            --users-warm: #b45309;
            --users-warm-soft: #fef3c7;
        }

        .users-page {
            max-width: 1720px;
        }

        .users-shell {
            display: grid;
            gap: 1rem;
        }

        .users-page,
        .users-shell,
        .users-hero,
        .users-panel,
        .users-table-shell,
        .users-table-wrap,
        .mobile-user-list,
        .mobile-user-card {
            min-width: 0;
        }

        .users-hero,
        .users-panel,
        .users-table-shell {
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.78);
            background: var(--users-panel);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .users-hero {
            position: relative;
            overflow: hidden;
            padding: clamp(1.25rem, 2vw, 1.875rem);
        }

        .users-hero::after {
            content: "";
            position: absolute;
            right: -70px;
            top: -70px;
            width: 240px;
            height: 240px;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.08);
        }

        .users-hero > * {
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
            background: var(--users-primary-soft);
            color: var(--users-primary);
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

        .users-hero h2 {
            margin-bottom: 6px;
            color: var(--users-ink);
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
            color: var(--users-slate);
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
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
            color: var(--users-slate);
            margin-bottom: 8px;
        }

        .metric-value {
            color: var(--users-ink);
            font-size: 1.08rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .metric-copy {
            display: block;
            margin-top: 6px;
            font-size: 0.82rem;
        }

        .users-panel,
        .users-table-shell {
            padding: clamp(1rem, 1.8vw, 1.375rem);
        }

        .section-kicker {
            margin-bottom: 0.45rem;
            color: var(--users-slate);
        }

        .panel-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: var(--users-success-soft);
            color: var(--users-success);
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

        .user-search-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 52px;
            padding: 0 14px;
            border-radius: 15px;
            border: 1px solid var(--users-line);
            background: #ffffff;
            transition: all 0.25s ease;
        }

        .user-search-wrap:focus-within {
            border-color: #7aa2ff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .user-search-icon {
            color: var(--users-slate);
            font-size: 0.95rem;
        }

        .user-search-input {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--users-ink);
            font-size: 0.95rem;
        }

        .user-search-input::placeholder {
            color: #91a0b5;
        }

        .users-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-ghost,
        .btn-primary-soft,
        .btn-edit-user {
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
            background: var(--users-primary);
            border: none;
            color: #ffffff;
            box-shadow: 0 10px 22px rgba(29, 78, 216, 0.24);
        }

        .btn-primary-soft:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(29, 78, 216, 0.28);
            color: #ffffff;
        }

        .users-table-wrap {
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid #e2ebf4;
            background: #ffffff;
        }

        .users-table {
            margin-bottom: 0;
        }

        .users-table thead th {
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

        .users-table tbody td {
            padding: 1rem 1.1rem;
            vertical-align: middle;
            border-color: #eef3f8;
        }

        .users-table tbody tr:hover {
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
        .provider-main {
            color: var(--users-ink);
            font-weight: 700;
            word-break: break-word;
        }

        .user-meta,
        .provider-meta {
            color: var(--users-slate);
            font-size: 0.83rem;
            word-break: break-word;
        }

        .role-pill,
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
            background: var(--users-primary-soft);
            color: var(--users-primary);
        }

        .role-pill-user {
            background: #eef2f7;
            color: #475569;
        }

        .permission-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
        }

        .permission-pill {
            background: #f8fbff;
            color: #42566f;
            border: 1px solid #dbe6f2;
            font-size: 0.72rem;
            text-transform: uppercase;
        }

        .permission-pill-success {
            background: var(--users-success-soft);
            color: var(--users-success);
        }

        .provider-stack {
            display: grid;
            gap: 0.2rem;
        }

        .btn-edit-user {
            background: #f8fbff;
            border: 1px solid #dbe6f2;
            color: var(--users-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
        }

        .btn-edit-user:hover {
            transform: translateY(-1px);
            background: #eaf2ff;
            color: #1743ba;
        }

        .mobile-user-list {
            display: none;
            gap: 1rem;
        }

        .mobile-user-card {
            padding: 1rem;
            border-radius: 20px;
            border: 1px solid #e2ebf4;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            box-shadow: 0 12px 28px rgba(15, 34, 58, 0.05);
        }

        .mobile-user-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .mobile-user-grid {
            display: grid;
            gap: 0.9rem;
        }

        .mobile-user-row {
            display: grid;
            gap: 0.3rem;
        }

        .mobile-user-label {
            color: var(--users-slate);
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
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
            color: var(--users-ink);
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
            color: var(--users-primary);
        }

        .pagination-wrap .page-item.active .page-link {
            background: var(--users-primary);
            border-color: var(--users-primary);
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
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 991.98px) {
            .container-fluid.users-page {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .users-hero::after {
                width: 180px;
                height: 180px;
                right: -50px;
                top: -50px;
            }

            .users-table-wrap {
                display: none;
            }

            .mobile-user-list {
                display: grid;
            }
        }

        @media (max-width: 768px) {
            .users-hero,
            .users-panel,
            .users-table-shell {
                padding: 18px;
            }

            .users-hero h2 {
                font-size: 1.55rem;
            }

            .hero-top,
            .panel-header,
            .table-header,
            .pagination-wrap {
                flex-direction: column;
                align-items: flex-start;
            }

            .users-actions {
                width: 100%;
                justify-content: stretch;
            }

            .users-actions .btn {
                width: 100%;
            }

            .pagination-wrap .pagination {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 575.98px) {
            .container-fluid.users-page {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            .users-hero h2 {
                font-size: 1.35rem;
            }

            .mobile-user-card {
                padding: 0.9rem;
                border-radius: 18px;
            }

            .mobile-user-head {
                flex-direction: column;
                align-items: stretch;
            }

            .mobile-user-head .btn-edit-user {
                width: 100%;
            }

            .user-cell {
                align-items: flex-start;
            }

            .user-avatar {
                width: 42px;
                height: 42px;
                border-radius: 14px;
            }

            .permission-pills {
                gap: 0.35rem;
            }

            .permission-pill,
            .role-pill {
                width: 100%;
                justify-content: flex-start;
            }

            .pagination-wrap .page-link {
                min-width: 36px;
                height: 36px;
            }
        }
    </style>

    <div class="container-fluid users-page py-1 px-md-4 px-xl-1">
        <div class="users-shell">
            <section class="users-hero">
                <span class="page-kicker">
                    <i class="bi bi-people"></i>
                    User workspace
                </span>

                <div class="hero-top">
                    <div>
                        <h2>Users</h2>
                        <p class="page-subtitle mb-0">Manage synced accounts, review access levels, and update document permissions from one workspace.</p>
                    </div>
                </div>
            </section>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            <section class="users-table-shell">
                <div class="table-header">
                    <div>
                        <div class="section-kicker">User Directory</div>
                        <h5 class="fw-bold mb-1">Manage accounts and permissions</h5>
                        <p class="table-copy mb-0">Open a user record to adjust role assignments and document action permissions.</p>
                    </div>
                </div>

                <div class="users-table-wrap">
                    <div class="table-responsive">
                        <table class="table users-table align-middle">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>Permissions</th>
                                    <th>Provider</th>
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
                                            <span class="role-pill {{ $user->isAdmin() ? 'role-pill-admin' : 'role-pill-user' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->isAdmin())
                                                <div class="permission-pills">
                                                    <span class="permission-pill permission-pill-success">All Permissions</span>
                                                </div>
                                            @else
                                                <div class="permission-pills">
                                                    @forelse($user->permissions ?? [] as $permission)
                                                        <span class="permission-pill">{{ $permissionOptions[$permission] ?? $permission }}</span>
                                                    @empty
                                                        <span class="user-meta">No document permissions</span>
                                                    @endforelse
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="provider-stack">
                                                <div class="provider-main">{{ ucfirst($user->auth_provider ?? 'local') }}</div>
                                                <div class="provider-meta">{{ $user->isAdmin() ? 'Administrator access' : 'Managed permission set' }}</div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-edit-user" title="Edit user">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <div class="empty-icon">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                                <div class="empty-title">No users found</div>
                                                <p class="empty-copy mb-0">Try changing the search term or clear the filter to see the full directory.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mobile-user-list">
                    @forelse($users as $user)
                        <article class="mobile-user-card">
                            <div class="mobile-user-head">
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-meta">{{ $user->email }}</div>
                                    </div>
                                </div>

                                <a href="{{ route('users.edit', $user) }}" class="btn btn-edit-user" title="Edit user">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </div>

                            <div class="mobile-user-grid">
                                <div class="mobile-user-row">
                                    <div class="mobile-user-label">Role</div>
                                    <div>
                                        <span class="role-pill {{ $user->isAdmin() ? 'role-pill-admin' : 'role-pill-user' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mobile-user-row">
                                    <div class="mobile-user-label">Permissions</div>
                                    @if($user->isAdmin())
                                        <div class="permission-pills">
                                            <span class="permission-pill permission-pill-success">All Permissions</span>
                                        </div>
                                    @else
                                        <div class="permission-pills">
                                            @forelse($user->permissions ?? [] as $permission)
                                                <span class="permission-pill">{{ $permissionOptions[$permission] ?? $permission }}</span>
                                            @empty
                                                <span class="user-meta">No document permissions</span>
                                            @endforelse
                                        </div>
                                    @endif
                                </div>

                                <div class="mobile-user-row">
                                    <div class="mobile-user-label">Provider</div>
                                    <div class="provider-main">{{ ucfirst($user->auth_provider ?? 'local') }}</div>
                                    <div class="provider-meta">{{ $user->isAdmin() ? 'Administrator access' : 'Managed permission set' }}</div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="empty-title">No users found</div>
                            <p class="empty-copy mb-0">Try changing the search term or clear the filter to see the full directory.</p>
                        </div>
                    @endforelse
                </div>

                @if($users->hasPages())
                    <div class="pagination-wrap">
                        <div class="pagination-copy">
                            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                        </div>
                        <div>
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
