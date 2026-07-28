@extends('layouts.app')

@section('title', 'ARS | Permissions')

@section('content')
    <style>
        :root {
            --perm-ink: #10243d;
            --perm-slate: #5f7088;
            --perm-line: #d9e4ef;
            --perm-panel: rgba(255, 255, 255, 0.96);
            --perm-primary: #1d4ed8;
            --perm-primary-soft: #dbeafe;
            --perm-success: #059669;
            --perm-success-soft: #d1fae5;
            --perm-warm: #b45309;
            --perm-warm-soft: #fef3c7;
            --perm-danger: #dc2626;
            --perm-danger-soft: #fee2e2;
        }

        .perm-page {
            max-width: 2000px;
        }

        .perm-shell {
            display: grid;
            gap: 1rem;
        }

        .perm-hero,
        .perm-panel,
        .perm-table-shell {
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.78);
            background: var(--perm-panel);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .perm-hero {
            position: relative;
            overflow: hidden;
            padding: clamp(1.25rem, 2vw, 1.875rem);
        }

        .perm-hero::after {
            content: "";
            position: absolute;
            right: -70px;
            top: -70px;
            width: 240px;
            height: 240px;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.08);
        }

        .perm-hero > * {
            position: relative;
            z-index: 1;
        }

        .perm-hero .hero-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .perm-hero h2 {
            font-weight: 900;
            letter-spacing: -0.02em;
            color: var(--perm-ink);
        }

        .page-subtitle {
            color: var(--perm-slate);
            font-size: 0.92rem;
            line-height: 1.5;
            max-width: 520px;
        }

        .perm-panel,
        .perm-table-shell {
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
            color: var(--perm-slate);
            margin-bottom: 0.45rem;
        }

        .panel-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: var(--perm-success-soft);
            color: var(--perm-success);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .add-perm-form {
            display: grid;
            grid-template-columns: 1fr 2fr auto;
            gap: 0.75rem;
            align-items: end;
        }

        .add-perm-form .form-label {
            margin-bottom: 6px;
            color: #44526f;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .add-perm-form .form-control,
        .add-perm-form .form-text {
            border-radius: 12px;
        }

        .btn-add-perm {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 700;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border: none;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .btn-add-perm:hover {
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.25);
        }

        .perm-table-wrap {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
        }

        .perm-table {
            margin: 0;
        }

        .perm-table thead th {
            background: #f8fafc;
            border-bottom: 2px solid var(--perm-line);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--perm-slate);
            padding: 0.85rem 1.1rem;
        }

        .perm-table tbody td {
            padding: 0.9rem 1.1rem;
            vertical-align: middle;
            border-color: #eef3f8;
        }

        .perm-table tbody tr:hover {
            background: #fbfdff;
        }

        .perm-key-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.38rem 0.7rem;
            border-radius: 8px;
            background: var(--perm-primary-soft);
            color: var(--perm-primary);
            font-size: 0.78rem;
            font-weight: 700;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }

        .perm-label-text {
            font-weight: 700;
            color: var(--perm-ink);
        }

        .btn-delete-perm {
            border-radius: 10px;
            padding: 7px 12px;
            font-weight: 700;
            background: var(--perm-danger-soft);
            color: var(--perm-danger);
            border: 1px solid rgba(220, 38, 38, 0.15);
            transition: all 0.2s ease;
        }

        .btn-delete-perm:hover {
            background: var(--perm-danger);
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
            color: var(--perm-ink);
        }

        .empty-copy {
            color: var(--perm-slate);
            font-size: 0.88rem;
            max-width: 380px;
        }

        .mobile-perm-list {
            display: none;
        }

        .mobile-perm-card {
            padding: 1rem;
            border-radius: 18px;
            border: 1px solid var(--perm-line);
            background: #fff;
        }

        .mobile-perm-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.6rem;
        }

        .mobile-perm-key {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.35rem 0.65rem;
            border-radius: 8px;
            background: var(--perm-primary-soft);
            color: var(--perm-primary);
            font-size: 0.75rem;
            font-weight: 700;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }

        .mobile-perm-label {
            font-weight: 700;
            color: var(--perm-ink);
            font-size: 0.95rem;
        }

        @media (max-width: 991.98px) {
            .perm-table-wrap {
                display: none;
            }

            .mobile-perm-list {
                display: grid;
                gap: 0.65rem;
            }
        }

        @media (max-width: 767.98px) {
            .add-perm-form {
                grid-template-columns: 1fr;
            }
        }

        html[data-theme="night"] body {
            background: #050816;
        }

        html[data-theme="night"] .perm-hero,
        html[data-theme="night"] .perm-panel,
        html[data-theme="night"] .perm-table-shell,
        html[data-theme="night"] .perm-table-wrap,
        html[data-theme="night"] .mobile-perm-card,
        html[data-theme="night"] .empty-state,
        html[data-theme="night"] .perm-key-badge {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.16) !important;
            color: #e2e8f0;
        }

        html[data-theme="night"] .perm-hero::after {
            background: rgba(59, 130, 246, 0.08);
        }

        html[data-theme="night"] .perm-hero h2,
        html[data-theme="night"] .empty-title,
        html[data-theme="night"] .perm-label-text,
        html[data-theme="night"] .mobile-perm-label {
            color: #f8fafc;
        }

        html[data-theme="night"] .page-subtitle,
        html[data-theme="night"] .section-kicker,
        html[data-theme="night"] .empty-copy {
            color: #94a3b8;
        }

        html[data-theme="night"] .perm-table thead th {
            background: #1e293b;
            border-bottom-color: rgba(148, 163, 184, 0.14);
            color: #94a3b8;
        }

        html[data-theme="night"] .perm-table tbody td {
            border-color: rgba(148, 163, 184, 0.1);
        }

        html[data-theme="night"] .perm-table tbody tr:hover {
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

        html[data-theme="night"] .btn-delete-perm {
            background: rgba(248, 113, 113, 0.14);
            color: #fecaca;
            border-color: rgba(248, 113, 113, 0.22);
        }

        html[data-theme="night"] .btn-delete-perm:hover {
            background: rgba(248, 113, 113, 0.22);
            color: #fff1f2;
        }

        html[data-theme="night"] .mobile-perm-card {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.16) !important;
        }

        html[data-theme="night"] .empty-icon {
            background: #1e293b;
            color: #64748b;
        }
    </style>

    <div class="container-fluid perm-page py-0 px-md-2 px-xl-0">
        <div class="perm-shell">
            <section class="perm-hero">
                <div class="hero-top">
                    <div>
                        <h2>Permissions</h2>
                        <p class="page-subtitle mb-0">Manage the permission keys that can be assigned to staff and user accounts.</p>
                    </div>
                    <span class="panel-chip">{{ $permissions->count() }} {{ Str::plural('Permission', $permissions->count()) }}</span>
                </div>
            </section>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            <section class="perm-panel">
                <div class="section-kicker"><i class="bi bi-plus-circle"></i> Add Permission</div>
                <form action="{{ route('permissions.store') }}" method="POST" class="add-perm-form">
                    @csrf
                    <div>
                        <label class="form-label">Key (slug)</label>
                        <input type="text" name="key" class="form-control" placeholder="e.g. manage_reports" required pattern="[a-z][a-z0-9_]*" maxlength="255" value="{{ old('key') }}">
                        @error('key')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Label (display name)</label>
                        <input type="text" name="label" class="form-control" placeholder="e.g. Manage Reports" required maxlength="255" value="{{ old('label') }}">
                        @error('label')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-add-perm">
                        <i class="bi bi-plus-lg me-1"></i>Add
                    </button>
                </form>
            </section>

            <section class="perm-table-shell">
                <div class="perm-table-wrap">
                    <div class="table-responsive">
                        <table class="table perm-table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Key</th>
                                    <th>Label</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $index => $perm)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="perm-key-badge">
                                                <i class="bi bi-key"></i>{{ $perm->key }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="perm-label-text">{{ $perm->label }}</span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('permissions.destroy', $perm) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Delete this permission? Users who have it assigned will lose access to the corresponding features.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete-perm" title="Delete permission">
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
                                                    <i class="bi bi-key"></i>
                                                </div>
                                                <div class="empty-title">No permissions defined</div>
                                                <p class="empty-copy mb-0">Add your first permission using the form above to start building your access control list.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mobile-perm-list">
                    @forelse($permissions as $perm)
                        <article class="mobile-perm-card">
                            <div class="mobile-perm-head">
                                <div>
                                    <div class="mobile-perm-key">
                                        <i class="bi bi-key"></i>{{ $perm->key }}
                                    </div>
                                    <div class="mobile-perm-label mt-2">{{ $perm->label }}</div>
                                </div>
                                <form action="{{ route('permissions.destroy', $perm) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this permission?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete-perm" title="Delete permission">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-key"></i>
                            </div>
                            <div class="empty-title">No permissions defined</div>
                            <p class="empty-copy mb-0">Add your first permission using the form above.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection
