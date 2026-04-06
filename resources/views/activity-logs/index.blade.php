@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <style>
        :root {
            --logs-ink: #10243d;
            --logs-slate: #5f7088;
            --logs-line: #d9e4ef;
            --logs-panel: rgba(255, 255, 255, 0.95);
            --logs-primary: #1d4ed8;
            --logs-primary-soft: #dbeafe;
            --logs-success: #059669;
            --logs-success-soft: #d1fae5;
            --logs-warm: #2c2a28;
            --logs-warm-soft: #9c9c9b;
            --logs-violet: #2c2a28;
            --logs-violet-soft: #9c9c9b;
        }

        body {
            background-color: #eef4f9;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .logs-page {
            max-width: 1720px;
        }

        .logs-shell {
            display: grid;
            gap: 1rem;
        }

        .logs-hero {
            position: relative;
            overflow: hidden;
            padding: 28px 30px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            background-color: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .logs-hero::after {
            content: "";
            position: absolute;
            right: -60px;
            top: -60px;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.07);
        }

        .logs-hero > * {
            position: relative;
            z-index: 1;
        }

        .page-kicker,
        .metric-label,
        .table-kicker {
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
            background-color: var(--logs-primary-soft);
            color: var(--logs-primary);
        }

        .logs-hero h2 {
            margin-bottom: 6px;
            color: var(--logs-ink);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .page-subtitle,
        .metric-copy,
        .table-copy,
        .empty-copy,
        .pagination-copy {
            color: var(--logs-slate);
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 22px;
        }

        .metric-card,
        .logs-table-shell {
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            background-color: var(--logs-panel);
            backdrop-filter: blur(12px);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.06);
        }

        .metric-card {
            padding: 18px 18px 16px;
        }

        .metric-label {
            color: var(--logs-slate);
            margin-bottom: 8px;
        }

        .metric-value {
            color: var(--logs-ink);
            font-size: 1.08rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .metric-copy {
            display: block;
            margin-top: 6px;
            font-size: 0.82rem;
        }

        .logs-table-shell {
            padding: 22px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 18px;
        }

        .table-kicker {
            margin-bottom: 0.45rem;
            color: var(--logs-slate);
        }

        

        .logs-table-wrap {
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid #e2ebf4;
            background: #ffffff;
        }

        .logs-table {
            margin-bottom: 0;
        }

        .logs-table thead th {
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

        .logs-table tbody td {
            padding: 1rem 1.1rem;
            vertical-align: middle;
            border-color: #eef3f8;
        }

        .logs-table tbody tr:hover {
            background: #fbfdff;
        }

        .log-date {
            color: var(--logs-ink);
            font-weight: 700;
        }

        .log-time,
        .log-muted {
            color: var(--logs-slate);
            font-size: 0.83rem;
        }

        .entity-stack {
            display: grid;
            gap: 0.2rem;
        }

        .entity-name {
            color: var(--logs-ink);
            font-weight: 700;
        }

        .badge-soft {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.42rem 0.72rem;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .badge-module {
            background: var(--logs-warm-soft);
            color: var(--logs-warm);
        }

        .badge-action {
            background: var(--logs-violet-soft);
            color: var(--logs-violet);
        }

        .description-copy {
            color: #44526f;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .btn-view-log {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid #dbe6f2;
            background: #f8fbff;
            color: var(--logs-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-view-log:hover {
            transform: translateY(-1px);
            background: #eaf2ff;
            color: #1743ba;
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
            color: var(--logs-ink);
            font-size: 1rem;
            font-weight: 800;
        }

        .pagination-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
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
            color: var(--logs-primary);
        }

        .pagination-wrap .page-item.active .page-link {
            background: var(--logs-primary);
            border-color: var(--logs-primary);
            color: #ffffff;
        }

        .pagination-wrap .page-item.disabled .page-link {
            background: #f8fafc;
            color: #94a3b8;
            border-color: #e2e8f0;
        }

        @media (max-width: 1200px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .logs-hero,
            .logs-table-shell {
                padding: 18px;
            }

            .logs-hero h2 {
                font-size: 1.55rem;
            }

            .table-header,
            .pagination-wrap {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="container-fluid logs-page py-4 px-md-4 px-xl-5">
        <div class="logs-shell">
            <section class="logs-hero">
                <span class="page-kicker">
                    <i class="bi bi-clock-history"></i>
                    Audit trail
                </span>

                <div>
                    <h2>Activity Logs</h2>
                    <p class="page-subtitle mb-0">Track user logins, logouts, applicant changes, and generated documents across the system in one timeline.</p>
                </div>
            </section>

            <section class="logs-table-shell">
                <div class="table-header">
                    <div>
                        <div class="table-kicker">System Timeline</div>
                        <h5 class="fw-bold mb-1">Recent activity records</h5>
                        <p class="table-copy mb-0">Review the latest actions performed in the system and open a full detail view for any specific entry.</p>
                    </div>
                </div>

                <div class="logs-table-wrap">
                    <div class="table-responsive">
                        <table class="table logs-table align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Applicant</th>
                                    <th>Module</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th class="text-center">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityLogs as $log)
                                    <tr>
                                        <td>
                                            <div class="log-date">{{ $log->created_at ? $log->created_at->timezone(config('app.timezone'))->format('M d, Y') : 'N/A' }}</div>
                                            <div class="log-time">{{ $log->created_at ? $log->created_at->timezone(config('app.timezone'))->format('h:i A') : '' }}</div>
                                        </td>
                                        <td>
                                            <div class="entity-stack">
                                                <div class="entity-name">{{ $log->causer?->name ?? 'System' }}</div>
                                                <div class="log-muted">{{ $log->causer?->role ? ucfirst($log->causer->role) : 'Automated event' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="entity-stack">
                                                <div class="entity-name">
                                                    @if($log->applicant)
                                                        {{ trim($log->applicant->first_name . ' ' . $log->applicant->last_name) }}
                                                    @else
                                                        System Event
                                                    @endif
                                                </div>
                                                <div class="log-muted">
                                                    @if($log->applicant)
                                                        Applicant record
                                                    @else
                                                        No linked applicant
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-soft badge-module">{{ $log->module }}</span>
                                        </td>
                                        <td>
                                            <span class="badge-soft badge-action">{{ $log->action }}</span>
                                        </td>
                                        <td>
                                            <div class="description-copy">{{ $log->description }}</div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('activity-logs.show', $log) }}" class="btn-view-log" title="View log details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <div class="empty-icon">
                                                    <i class="bi bi-journal-x"></i>
                                                </div>
                                                <div class="empty-title">No activity logs available yet</div>
                                                <p class="empty-copy mb-0">Once actions are recorded in the system, they will appear here for review.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($activityLogs->hasPages())
                    <div class="pagination-wrap">
                        <div class="pagination-copy">
                            Showing {{ $activityLogs->firstItem() ?? 0 }} to {{ $activityLogs->lastItem() ?? 0 }} of
                            {{ $activityLogs->total() }} activity logs
                        </div>
                        <div>
                            {{ $activityLogs->appends(request()->query())->links('vendor.pagination.activity-logs') }}
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
