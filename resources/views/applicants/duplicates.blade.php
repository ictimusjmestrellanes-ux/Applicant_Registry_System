@extends('layouts.app')

@section('title', 'ARS | Duplicate Applicants')

@section('content')
    <style>
        :root {
            --dup-ink: #10243d;
            --dup-slate: #5f7088;
            --dup-line: #d9e4ef;
            --dup-panel: rgb(255, 255, 255);
            --dup-primary: #1d4ed8;
            --dup-success: #059669;
            --dup-warm: #b45309;
        }

        body {
            background-color: #eef4f9;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .dup-page {
            max-width: 2000px;
        }

        .dup-shell {
            display: grid;
            gap: 1rem;
        }

        .dup-hero {
            position: relative;
            overflow: hidden;
            padding: 28px 30px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            background-color: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .dup-hero::after {
            content: "";
            position: absolute;
            right: -70px;
            top: -70px;
            width: 240px;
            height: 240px;
            border-radius: 999px;
        }

        .dup-hero>* {
            position: relative;
            z-index: 1;
        }

        .hero-top,
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .page-kicker,
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
            background-color: var(--dup-warm-soft, #fef3c7);
            color: var(--dup-warm);
        }

        .dup-hero h2 {
            margin-bottom: 6px;
            color: var(--dup-ink);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .page-subtitle,
        .table-copy,
        .empty-copy,
        .pagination-copy {
            color: var(--dup-slate);
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 22px;
        }

        .metric-card,
        .dup-table-shell {
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            background-color: var(--dup-panel);
            backdrop-filter: blur(12px);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.06);
        }

        .metric-card {
            padding: 18px 18px 16px;
        }

        .metric-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--dup-slate);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .metric-value {
            color: var(--dup-ink);
            font-size: 1.08rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .metric-copy {
            display: block;
            margin-top: 6px;
            font-size: 0.82rem;
            color: var(--dup-slate);
        }

        .dup-table-shell {
            padding: 22px;
        }

        .table-header {
            margin-bottom: 18px;
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

        .dup-search-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 50px;
            padding: 0 14px;
            border-radius: 14px;
            border: 1px solid var(--dup-line);
            background: #ffffff;
            transition: all 0.25s ease;
        }

        .dup-search-wrap:focus-within {
            border-color: #7aa2ff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .dup-search-icon {
            color: var(--dup-slate);
            font-size: 0.95rem;
        }

        .dup-search-input {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--dup-ink);
            font-size: 0.95rem;
        }

        .dup-search-input::placeholder {
            color: #91a0b5;
        }

        .dup-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-ghost,
        .btn-primary-soft {
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
            background-color: var(--dup-primary);
            border: none;
            color: #fff;
            box-shadow: 0 10px 22px rgba(29, 78, 216, 0.24);
        }

        .btn-primary-soft:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(29, 78, 216, 0.28);
            color: #000000;
        }

        .group-card {
            border-radius: 22px;
            border: 1px solid #e2ebf4;
            background: #ffffff;
            box-shadow: 0 12px 28px rgba(15, 34, 58, 0.05);
            overflow: hidden;
        }

        .group-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 18px 22px;
            background: #f8fbff;
            border-bottom: 1px solid #e8eef6;
        }

        .group-card-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .group-avatar {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            font-size: 0.85rem;
            font-weight: 800;
        }

        .group-name {
            color: var(--dup-ink);
            font-size: 1.05rem;
            font-weight: 800;
        }

        .group-subtitle {
            color: var(--dup-slate);
            font-size: 0.82rem;
            margin-top: 2px;
        }

        .group-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .dup-table-wrap {
            overflow-x: auto;
        }

        .dup-table-wrap table {
            min-width: 600px;
        }

        .dup-table {
            margin-bottom: 0;
            font-size: 0.85rem;
        }

        .dup-table thead th {
            padding: 0.85rem 1.1rem;
            border-bottom: 1px solid #e8eef6;
            background: #ffffff;
            color: #6a7b92;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            white-space: nowrap;
        }

        .dup-table tbody td {
            padding: 0.9rem 1.1rem;
            vertical-align: middle;
            border-color: #eef3f8;
        }

        .dup-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .dup-table tbody tr:hover {
            background: #fbfdff;
        }

        .applicant-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .applicant-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eef2ff;
            color: #4f46e5;
            font-size: 0.78rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .applicant-name {
            color: var(--dup-ink);
            font-weight: 700;
        }

        .applicant-meta {
            color: var(--dup-slate);
            font-size: 0.78rem;
        }

        .contact-main {
            color: var(--dup-ink);
            font-weight: 600;
        }

        .contact-meta {
            color: var(--dup-slate);
            font-size: 0.8rem;
        }

        .address-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.82rem;
            font-weight: 600;
            word-break: break-word;
            max-width: 100%;
        }

        .btn-edit-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 10px;
            background: rgba(37, 99, 235, 0.1);
            color: #1d4ed8;
            font-size: 0.8rem;
            font-weight: 700;
            border: none;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-edit-sm:hover {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-dup-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 10px;
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            font-size: 0.8rem;
            font-weight: 700;
            border: none;
            transition: all 0.15s ease;
        }

        .btn-dup-sm:hover {
            background: #d1fae5;
            color: #047857;
        }

        .mobile-dup-list {
            display: none;
            gap: 1rem;
        }

        .mobile-dup-card {
            padding: 1rem;
            border-radius: 20px;
            border: 1px solid #e2ebf4;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            box-shadow: 0 12px 28px rgba(15, 34, 58, 0.05);
        }

        .mobile-dup-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 0.9rem;
        }

        .mobile-dup-grid {
            display: grid;
            gap: 0.75rem;
        }

        .mobile-dup-row {
            display: grid;
            gap: 2px;
        }

        .mobile-dup-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--dup-slate);
        }

        .mobile-dup-actions {
            display: flex;
            gap: 8px;
            margin-top: 1rem;
        }

        .mobile-dup-actions .btn {
            flex: 1;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
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
            background: rgba(16, 185, 129, 0.1);
            color: var(--dup-success);
            font-size: 2rem;
        }

        .empty-title {
            color: var(--dup-ink);
            font-size: 1rem;
            font-weight: 800;
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
            .container-fluid.dup-page {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .dup-hero {
                padding: 20px;
            }

            .dup-hero h2 {
                font-size: 1.6rem;
            }

            .hero-top,
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .dup-actions {
                width: 100%;
                justify-content: stretch;
            }

            .dup-actions .btn {
                width: 100%;
            }

            .dup-table-wrap {
                display: none;
            }

            .mobile-dup-list {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .group-card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .group-card-header-left {
                width: 100%;
            }

            .group-count-badge {
                align-self: flex-start;
            }

            .group-card {
                border-radius: 18px;
            }
        }

        @media (max-width: 767.98px) {
            .mobile-dup-list {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .dup-hero h2 {
                font-size: 1.35rem;
            }

            .dup-hero {
                padding: 16px;
                border-radius: 18px;
            }

            .metric-card {
                padding: 14px;
            }

            .mobile-dup-card {
                padding: 0.85rem;
                border-radius: 16px;
            }

            .mobile-dup-head {
                flex-direction: column;
                align-items: stretch;
            }

            .mobile-dup-actions {
                flex-direction: column;
            }

            .mobile-dup-actions .btn {
                width: 100%;
            }

            .group-card-header {
                padding: 14px 16px;
            }

            .dup-table-shell {
                padding: 14px;
            }
        }

        html[data-theme="night"] body {
            background: #050816;
        }

        html[data-theme="night"] .page-subtitle,
        html[data-theme="night"] .metric-copy,
        html[data-theme="night"] .table-copy,
        html[data-theme="night"] .empty-copy,
        html[data-theme="night"] .pagination-copy,
        html[data-theme="night"] .contact-meta,
        html[data-theme="night"] .mobile-dup-label,
        html[data-theme="night"] .form-label,
        html[data-theme="night"] .dup-search-icon,
        html[data-theme="night"] .group-subtitle {
            color: #94a3b8;
        }

        html[data-theme="night"] .dup-hero,
        html[data-theme="night"] .metric-card,
        html[data-theme="night"] .dup-table-shell,
        html[data-theme="night"] .search-card,
        html[data-theme="night"] .dup-table-wrap,
        html[data-theme="night"] .group-card,
        html[data-theme="night"] .mobile-dup-card,
        html[data-theme="night"] .empty-state,
        html[data-theme="night"] .dup-search-wrap,
        html[data-theme="night"] .address-pill,
        html[data-theme="night"] .pagination-wrap .page-link {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.16) !important;
            color: #e2e8f0;
        }

        html[data-theme="night"] .dup-hero::after {
            background: rgba(59, 130, 246, 0.08);
        }

        html[data-theme="night"] .dup-hero h2,
        html[data-theme="night"] .metric-value,
        html[data-theme="night"] .empty-title,
        html[data-theme="night"] .applicant-name,
        html[data-theme="night"] .group-name {
            color: #f8fafc;
        }

        html[data-theme="night"] .page-kicker {
            background: rgba(245, 158, 11, 0.16);
            color: #fcd34d;
        }

        html[data-theme="night"] .group-card-header {
            background: #111827 !important;
            border-bottom-color: rgba(148, 163, 184, 0.16) !important;
        }

        html[data-theme="night"] .dup-table thead th {
            background: #111827 !important;
            color: #cbd5e1;
            border-bottom-color: rgba(148, 163, 184, 0.16) !important;
        }

        html[data-theme="night"] .dup-table tbody td {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.12) !important;
        }

        html[data-theme="night"] .dup-table tbody tr:hover {
            background: rgba(59, 130, 246, 0.06) !important;
        }

        html[data-theme="night"] .group-avatar {
            background: rgba(248, 113, 113, 0.16);
            color: #fca5a5;
        }

        html[data-theme="night"] .group-count-badge {
            background: rgba(248, 113, 113, 0.16);
            color: #fca5a5;
        }

        html[data-theme="night"] .applicant-avatar {
            background: #111827;
            color: #cbd5e1;
        }

        html[data-theme="night"] .btn-ghost {
            background: #111827;
            color: #cbd5e1;
            border-color: rgba(148, 163, 184, 0.18);
        }

        html[data-theme="night"] .btn-ghost:hover {
            background: #1f2937;
            color: #f8fafc;
        }

        html[data-theme="night"] .btn-primary-soft {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
        }

        html[data-theme="night"] .btn-edit-sm {
            background: rgba(96, 165, 250, 0.14);
            color: #93c5fd;
        }

        html[data-theme="night"] .btn-edit-sm:hover {
            background: rgba(96, 165, 250, 0.22);
            color: #bfdbfe;
        }

        html[data-theme="night"] .btn-dup-sm {
            background: rgba(52, 211, 153, 0.14);
            color: #6ee7b7;
        }

        html[data-theme="night"] .btn-dup-sm:hover {
            background: rgba(52, 211, 153, 0.22);
            color: #d1fae5;
        }
    </style>

    <div class="container-fluid dup-page py-0 px-md-4 px-xl-0">
        <div class="dup-shell">

            <section class="dup-hero">
                <div class="hero-top">
                    <div>   
                        <h2>Duplicate Applicants</h2>
                        <p class="page-subtitle mb-0">Identify applicants with matching names across records. Review
                            groups to find potential double entries that need merging or cleanup.</p>
                    </div>
                    <a href="{{ route('applicants.index') }}" class="btn btn-ghost">
                        <i class="bi bi-arrow-left me-2"></i>Back to Active List
                    </a>
                </div>

                <form method="GET" action="{{ route('applicants.duplicates') }}">
                    <div class="row g-3 align-items-end mt-3">
                        <div class="col-lg-8">
                            <label class="form-label">Search Duplicate Groups</label>
                            <div class="dup-search-wrap">
                                <i class="bi bi-search dup-search-icon"></i>
                                <input type="text" name="search" class="dup-search-input"
                                    placeholder="Search by name, contact number, barangay, or city..."
                                    value="{{ $search ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="dup-actions">
                                <button type="submit" class="btn btn-primary-soft">
                                    <i class="bi bi-search me-2"></i>Search
                                </button>
                                <a href="{{ route('applicants.duplicates') }}" class="btn btn-ghost">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-label"><i class="bi bi-layers"></i> Duplicate Groups</div>
                    <div class="metric-value">{{ number_format($totalGroups) }}</div>
                    <span class="metric-copy">Unique name combinations with duplicates</span>
                </div>
                <div class="metric-card">
                    <div class="metric-label"><i class="bi bi-people"></i> Total Records</div>
                    <div class="metric-value">{{ number_format($totalDuplicates) }}</div>
                    <span class="metric-copy">Applicants involved in duplicate groups</span>
                </div>
                <div class="metric-card">
                    <div class="metric-label"><i class="bi bi-exclamation-triangle"></i> Action Needed</div>
                    <div class="metric-value">{{ number_format($totalDuplicates - $totalGroups) }}</div>
                    <span class="metric-copy">Extra records that may need review</span>
                </div>
            </div>

            <section class="dup-table-shell">
                <div class="table-header">
                    <div>
                        <div class="table-label">Duplicate Records</div>
                        <h5 class="fw-bold mb-1">Grouped by full name match</h5>
                        <p class="table-copy mb-0">Each group shows applicants sharing the same first and last name.
                            Open the applicant workspace to manage document compliance.</p>
                    </div>
                </div>

                @forelse($duplicateGroups as $group)
                    <div class="group-card mb-4">
                        <div class="group-card-header">
                            <div class="group-card-header-left">
                                <div class="group-avatar">
                                    {{ strtoupper(substr($group['first_name'], 0, 1)) }}{{ strtoupper(substr($group['last_name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="group-name">
                                        {{ trim($group['first_name'] . ' ' . $group['last_name']) }}
                                    </div>
                                    <div class="group-subtitle">
                                        {{ $group['count'] }} records found in the system
                                    </div>
                                </div>
                            </div>
                            <div class="group-count-badge">
                                <i class="bi bi-copy"></i>
                                {{ $group['count'] }} duplicates
                            </div>
                        </div>

                        <div class="dup-table-wrap">
                            <table class="table dup-table align-middle">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Contact</th>
                                        <th>Address</th>
                                        <th>Created</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group['applicants'] as $applicant)
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
                                                            #{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="contact-main">{{ $applicant->contact_no ?: 'N/A' }}</div>
                                                <div class="contact-meta">{{ $applicant->gender ?: 'N/A' }} /
                                                    {{ $applicant->civil_status ?: 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="address-pill">
                                                    {{ trim(collect([$applicant->address_line, $applicant->barangay, $applicant->city])->filter()->implode(', ')) ?: 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="contact-main">
                                                    {{ $applicant->created_at?->format('M d, Y') ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('applicants.edit', $applicant->id) }}"
                                                    class="btn-edit-sm" title="View Applicant">
                                                    <i class="bi bi-eye-fill"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mobile-dup-list">
                            @foreach($group['applicants'] as $applicant)
                                <article class="mobile-dup-card">
                                    <div class="mobile-dup-head">
                                        <div class="applicant-cell">
                                            <div class="applicant-avatar">
                                                {{ strtoupper(substr($applicant->first_name, 0, 1)) }}{{ strtoupper(substr($applicant->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="applicant-name">
                                                    {{ trim($applicant->first_name . ' ' . ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') . $applicant->last_name . ' ' . ($applicant->suffix ?? '')) }}
                                                </div>
                                                <div class="applicant-meta">ID:
                                                    #{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mobile-dup-grid">
                                        <div class="mobile-dup-row">
                                            <div class="mobile-dup-label">Contact</div>
                                            <div class="contact-main">{{ $applicant->contact_no ?: 'N/A' }}</div>
                                            <div class="contact-meta">{{ $applicant->gender ?: 'N/A' }} /
                                                {{ $applicant->civil_status ?: 'N/A' }}</div>
                                        </div>
                                        <div class="mobile-dup-row">
                                            <div class="mobile-dup-label">Address</div>
                                            <span class="address-pill">
                                                {{ trim(collect([$applicant->address_line, $applicant->barangay, $applicant->city])->filter()->implode(', ')) ?: 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mobile-dup-actions">
                                        <a href="{{ route('applicants.edit', $applicant->id) }}" class="btn btn-edit-sm">
                                            <i class="bi bi-eye-fill"></i> View
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="empty-title">No duplicate applicants found</div>
                        <p class="empty-copy mb-0">All applicant records are unique. No matching name combinations were
                            detected.</p>
                    </div>
                @endforelse
            </section>

        </div>
    </div>
@endsection
