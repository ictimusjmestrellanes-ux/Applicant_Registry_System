@extends('layouts.app')

@section('title', 'ARS | Roles')

@section('content')
    <style>
        :root {
            --role-ink: #10243d;
            --role-slate: #5f7088;
            --role-line: #d9e4ef;
            --role-panel: rgba(255, 255, 255, 0.96);
            --role-primary: #1d4ed8;
            --role-primary-soft: #dbeafe;
            --role-success: #059669;
            --role-success-soft: #d1fae5;
            --role-danger: #dc2626;
            --role-danger-soft: #fee2e2;
        }

        .role-page {
            max-width: 2000px;
        }

        .role-shell {
            display: grid;
            gap: 1rem;
        }

        .role-hero,
        .role-panel,
        .role-table-shell {
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.78);
            background: var(--role-panel);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .role-hero {
            position: relative;
            overflow: hidden;
            padding: clamp(1.25rem, 2vw, 1.875rem);
        }

        .role-hero::after {
            content: "";
            position: absolute;
            right: -70px;
            top: -70px;
            width: 240px;
            height: 240px;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.08);
        }

        .role-hero > * {
            position: relative;
            z-index: 1;
        }

        .role-hero .hero-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .role-hero h2 {
            font-weight: 900;
            letter-spacing: -0.02em;
            color: var(--role-ink);
        }

        .page-subtitle {
            color: var(--role-slate);
            font-size: 0.92rem;
            line-height: 1.5;
            max-width: 520px;
        }

        .role-panel,
        .role-table-shell {
            padding: clamp(1rem, 1.8vw, 1.375rem);
        }

        .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--role-slate);
            margin-bottom: 0.45rem;
        }

        .panel-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: var(--role-success-soft);
            color: var(--role-success);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .add-role-form {
            display: grid;
            grid-template-columns: 1fr 2fr auto;
            gap: 0.75rem;
            align-items: end;
        }

        .add-role-form .form-label {
            margin-bottom: 6px;
            color: #44526f;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .add-role-form .form-control,
        .add-role-form .form-text {
            border-radius: 12px;
        }

        .btn-add-role {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 700;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border: none;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .btn-add-role:hover {
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.25);
        }

        .role-table-wrap {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
        }

        .role-table {
            margin: 0;
        }

        .role-table thead th {
            background: #f8fafc;
            border-bottom: 2px solid var(--role-line);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--role-slate);
            padding: 0.85rem 1.1rem;
        }

        .role-table tbody td {
            padding: 0.9rem 1.1rem;
            vertical-align: middle;
            border-color: #eef3f8;
        }

        .role-table tbody tr:hover {
            background: #fbfdff;
        }

        .role-slug-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.38rem 0.7rem;
            border-radius: 8px;
            background: var(--role-primary-soft);
            color: var(--role-primary);
            font-size: 0.78rem;
            font-weight: 700;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }

        .role-label-text {
            font-weight: 700;
            color: var(--role-ink);
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.42rem 0.72rem;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.04em;
        }

        .role-badge-admin {
            background: var(--role-primary-soft);
            color: var(--role-primary);
        }

        .role-badge-staff {
            background: var(--role-success-soft);
            color: var(--role-success);
        }

        .role-badge-user {
            background: #eef2f7;
            color: #475569;
        }

        .role-badge-default {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-delete-role {
            border-radius: 10px;
            padding: 7px 12px;
            font-weight: 700;
            background: var(--role-danger-soft);
            color: var(--role-danger);
            border: 1px solid rgba(220, 38, 38, 0.15);
            transition: all 0.2s ease;
        }

        .btn-delete-role:hover {
            background: var(--role-danger);
            color: #fff;
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.2);
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 2.5rem 1rem;
            text-align: center;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 1.5rem;
        }

        .empty-title {
            font-weight: 800;
            color: var(--role-ink);
        }

        .empty-copy {
            color: var(--role-slate);
            font-size: 0.88rem;
            max-width: 380px;
        }

        .mobile-role-list {
            display: none;
        }

        .mobile-role-card {
            padding: 1rem;
            border-radius: 18px;
            border: 1px solid var(--role-line);
            background: #fff;
        }

        .mobile-role-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.6rem;
        }

        .mobile-role-slug {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.35rem 0.65rem;
            border-radius: 8px;
            background: var(--role-primary-soft);
            color: var(--role-primary);
            font-size: 0.75rem;
            font-weight: 700;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }

        .mobile-role-label {
            font-weight: 700;
            color: var(--role-ink);
            font-size: 0.95rem;
        }

        @media (max-width: 991.98px) {
            .role-table-wrap {
                display: none;
            }

            .mobile-role-list {
                display: grid;
                gap: 0.65rem;
            }
        }

        @media (max-width: 767.98px) {
            .add-role-form {
                grid-template-columns: 1fr;
            }
        }

        html[data-theme="night"] body {
            background: #050816;
        }

        html[data-theme="night"] .role-hero,
        html[data-theme="night"] .role-panel,
        html[data-theme="night"] .role-table-shell,
        html[data-theme="night"] .role-table-wrap,
        html[data-theme="night"] .mobile-role-card,
        html[data-theme="night"] .empty-state,
        html[data-theme="night"] .role-slug-badge {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.16) !important;
            color: #e2e8f0;
        }

        html[data-theme="night"] .role-hero::after {
            background: rgba(59, 130, 246, 0.08);
        }

        html[data-theme="night"] .role-hero h2,
        html[data-theme="night"] .empty-title,
        html[data-theme="night"] .role-label-text,
        html[data-theme="night"] .mobile-role-label {
            color: #f8fafc;
        }

        html[data-theme="night"] .page-subtitle,
        html[data-theme="night"] .section-kicker,
        html[data-theme="night"] .empty-copy {
            color: #94a3b8;
        }

        html[data-theme="night"] .role-table thead th {
            background: #1e293b;
            border-bottom-color: rgba(148, 163, 184, 0.14);
            color: #94a3b8;
        }

        html[data-theme="night"] .role-table tbody td {
            border-color: rgba(148, 163, 184, 0.1);
        }

        html[data-theme="night"] .role-table tbody tr:hover {
            background: #1e293b;
        }

        html[data-theme="night"] .form-control,
        html[data-theme="night"] .form-text {
            background: #111827;
            border-color: rgba(148, 163, 184, 0.2);
            color: #e2e8f0;
        }

        html[data-theme="night"] .form-text {
            color: #94a3b8 !important;
        }

        html[data-theme="night"] .btn-delete-role {
            background: rgba(248, 113, 113, 0.14);
            color: #fecaca;
            border-color: rgba(248, 113, 113, 0.22);
        }

        html[data-theme="night"] .btn-delete-role:hover {
            background: rgba(248, 113, 113, 0.22);
            color: #fff1f2;
        }

        html[data-theme="night"] .mobile-role-card {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.16) !important;
        }

        html[data-theme="night"] .empty-icon {
            background: #1e293b;
            color: #64748b;
        }
    </style>

    <div class="container-fluid role-page py-0 px-md-2 px-xl-0">
        <div class="role-shell">
            <section class="role-hero">
                <div class="hero-top">
                    <div>
                        <h2>Roles</h2>
                        <p class="page-subtitle mb-0">Manage the roles that can be assigned to user accounts for access control.</p>
                    </div>
                    <span class="panel-chip">{{ $roles->count() }} {{ Str::plural('Role', $roles->count()) }}</span>
                </div>
            </section>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            <section class="role-panel">
                <div class="section-kicker"><i class="bi bi-plus-circle"></i> Add Role</div>
                <form action="{{ route('roles.store') }}" method="POST" class="add-role-form">
                    @csrf
                    <div>
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" placeholder="e.g. supervisor" required pattern="[a-z][a-z0-9_]*" maxlength="255" value="{{ old('slug') }}">
                        @error('slug')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Label (display name)</label>
                        <input type="text" name="label" class="form-control" placeholder="e.g. Supervisor" required maxlength="255" value="{{ old('label') }}">
                        @error('label')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-add-role">
                        <i class="bi bi-plus-lg me-1"></i>Add
                    </button>
                </form>
            </section>

            <section class="role-table-shell">
                <div class="role-table-wrap">
                    <div class="table-responsive">
                        <table class="table role-table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Slug</th>
                                    <th>Label</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $index => $role)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="role-slug-badge">
                                                <i class="bi bi-shield-lock"></i>{{ $role->slug }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="role-label-text">{{ $role->label }}</span>
                                                @if(in_array($role->slug, ['admin', 'staff', 'user']))
                                                    <span class="role-badge role-badge-{{ $role->slug }}">{{ ucfirst($role->slug) }}</span>
                                                @else
                                                    <span class="role-badge role-badge-default">Custom</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Delete this role? Users with this role will lose their role assignment.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete-role" title="Delete role">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">
                                                <div class="empty-icon">
                                                    <i class="bi bi-shield-lock"></i>
                                                </div>
                                                <div class="empty-title">No roles defined</div>
                                                <p class="empty-copy mb-0">Add your first role using the form above to start building your role hierarchy.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mobile-role-list">
                    @forelse($roles as $role)
                        <article class="mobile-role-card">
                            <div class="mobile-role-head">
                                <div>
                                    <div class="mobile-role-slug">
                                        <i class="bi bi-shield-lock"></i>{{ $role->slug }}
                                    </div>
                                    <div class="mobile-role-label mt-2">{{ $role->label }}</div>
                                </div>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete-role" title="Delete role">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <div class="empty-title">No roles defined</div>
                            <p class="empty-copy mb-0">Add your first role using the form above.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection
