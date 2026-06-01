@extends('layouts.app')

@section('title', 'Activity Log Details')

@section('content')
    <div class="activity-log-detail-page container-fluid py-0 px-md-4 px-xl-0">
        <section class="activity-log-hero mb-4">
            <div>
                <span class="page-kicker">
                    <i class="bi bi-journal-text"></i>
                    Audit Trail
                </span>
                <h3 class="fw-bold mb-1">Activity Log Details</h3>
                <p class="text-muted mb-0">{{ $activityLog->description }}</p>
            </div>

            <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary activity-log-back">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </section>

        <section class="activity-log-summary card border-0 shadow-sm mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="row g-4">
                    <div class="col-sm-6 col-lg-4">
                        <div class="activity-meta">
                            <small class="text-muted d-block">Date</small>
                            <div class="fw-semibold">{{ $activityLog->created_at->format('F d, Y h:i A') }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="activity-meta">
                            <small class="text-muted d-block">User</small>
                            <div class="fw-semibold">{{ $activityLog->causer?->name ?? 'System' }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="activity-meta">
                            <small class="text-muted d-block">Applicant</small>
                            <div class="fw-semibold">
                                {{ $activityLog->applicant ? trim($activityLog->applicant->first_name . ' ' . $activityLog->applicant->last_name) : 'System Event' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="activity-meta">
                            <small class="text-muted d-block">Module</small>
                            <div class="activity-pill activity-pill-module">{{ $activityLog->module }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="activity-meta">
                            <small class="text-muted d-block">Action</small>
                            <div class="activity-pill activity-pill-action">{{ $activityLog->action }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="activity-log-changes card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Changed Fields</h5>
                        <p class="text-muted mb-0">Review field-level updates captured in this event.</p>
                    </div>
                    @php
                        $changeCount = is_array($activityLog->changes) ? count($activityLog->changes) : 0;
                        $changeLabel = $changeCount === 1 ? '1 change' : $changeCount . ' changes';
                    @endphp
                    <span class="activity-count {{ $changeCount === 0 ? 'activity-count-empty' : '' }}">
                        {{ $changeCount === 0 ? 'No changes' : $changeLabel }}
                    </span>
                </div>

                @if(empty($activityLog->changes))
                    <div class="activity-empty">
                        <div class="activity-empty-icon">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <div class="fw-semibold mb-1">No field-level changes</div>
                        <p class="text-muted mb-0">This activity only recorded the event summary, not individual field edits.</p>
                    </div>
                @else
                    <div class="table-responsive activity-table-wrap">
                        <table class="table align-middle mb-0 activity-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Field</th>
                                    <th>Before</th>
                                    <th>After</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activityLog->changes as $field => $change)
                                    <tr>
                                        <td class="fw-semibold">{{ str_replace('_', ' ', ucfirst($field)) }}</td>
                                        <td>{{ $change['before'] ?? 'N/A' }}</td>
                                        <td>{{ $change['after'] ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <style>
        .activity-log-detail-page {
            max-width: 1800px;
        }

        .activity-log-hero {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            padding: 28px 30px;
            border-radius: 26px;
            border: 1px solid #dbe4ee;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .page-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            padding: 7px 12px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .activity-log-hero h3 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #10243d;
        }

        .activity-log-hero .text-muted {
            max-width: 68ch;
        }

        .activity-log-back {
            flex-shrink: 0;
            min-width: 114px;
            white-space: nowrap;
        }

        .activity-log-summary,
        .activity-log-changes {
            border-radius: 22px;
            border: 1px solid #dbe4ee;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.06);
        }

        .activity-meta {
            min-height: 100%;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid #e6edf5;
            background: #f8fbff;
        }

        .activity-meta small {
            margin-bottom: 6px;
            color: #6b7d94 !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
        }

        .activity-meta .fw-semibold {
            color: #10243d;
            font-size: 1.05rem;
        }

        .activity-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0.6rem 1rem;
            border-radius: 14px;
            border: 1px solid transparent;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .activity-pill-module {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .activity-pill-action {
            background: #dcfce7;
            color: #059669;
        }

        .activity-count {
            flex-shrink: 0;
            padding: 0.5rem 0.8rem;
            border-radius: 999px;
            background: #dbeafe;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .activity-count-empty {
            background: #e2e8f0;
            border-color: #cbd5e1;
            color: #334155;
        }

        .activity-empty {
            display: grid;
            place-items: center;
            gap: 0.6rem;
            padding: 2.25rem 1rem;
            text-align: center;
            border-radius: 18px;
            border: 1px dashed #d2dcea;
            background: linear-gradient(180deg, #fbfdff 0%, #f8fbff 100%);
        }

        .activity-empty-icon {
            display: grid;
            place-items: center;
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 1.6rem;
        }

        .activity-table-wrap {
            overflow: hidden;
            border-radius: 18px;
            border: 1px solid #e2ebf4;
        }

        .activity-table {
            margin-bottom: 0;
        }

        .activity-table thead th {
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

        .activity-table tbody td {
            padding: 1rem 1.1rem;
            border-color: #eef3f8;
            color: #10243d;
        }

        .activity-table tbody tr:hover {
            background: #fbfdff;
        }

        .activity-table tbody td:first-child {
            width: 24%;
        }

        html[data-theme="night"] body {
            background: #050816 !important;
        }

        html[data-theme="night"] .activity-log-detail-page {
            color: #e2e8f0;
        }

        html[data-theme="night"] .activity-log-hero,
        html[data-theme="night"] .activity-log-summary,
        html[data-theme="night"] .activity-log-changes {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.16) !important;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28) !important;
        }

        html[data-theme="night"] .activity-log-hero h3,
        html[data-theme="night"] .activity-log-detail-page h5,
        html[data-theme="night"] .activity-log-detail-page .fw-semibold {
            color: #f8fafc !important;
        }

        html[data-theme="night"] .activity-log-detail-page .text-muted,
        html[data-theme="night"] .activity-log-detail-page .activity-count {
            color: #94a3b8 !important;
        }

        html[data-theme="night"] .activity-count {
            background: rgba(59, 130, 246, 0.16);
            border-color: rgba(96, 165, 250, 0.22);
            color: #93c5fd !important;
        }

        html[data-theme="night"] .activity-log-detail-page .activity-count-empty {
            background: rgba(148, 163, 184, 0.14);
            border-color: rgba(148, 163, 184, 0.2);
            color: #cbd5e1 !important;
        }

        html[data-theme="night"] .activity-log-back {
            background: #111827;
            color: #cbd5e1;
            border-color: rgba(148, 163, 184, 0.18);
        }

        html[data-theme="night"] .activity-log-back:hover {
            background: #1f2937;
            color: #f8fafc;
        }

        html[data-theme="night"] .activity-meta {
            background: #0b1324;
            border-color: rgba(148, 163, 184, 0.16);
        }

        html[data-theme="night"] .activity-meta small {
            color: #94a3b8 !important;
        }

        html[data-theme="night"] .activity-pill-module {
            background: rgba(59, 130, 246, 0.18);
            color: #93c5fd;
        }

        html[data-theme="night"] .activity-pill-action {
            background: rgba(16, 185, 129, 0.16);
            color: #6ee7b7;
        }

        html[data-theme="night"] .activity-empty {
            background: #0b1324;
            border-color: rgba(148, 163, 184, 0.18);
        }

        html[data-theme="night"] .activity-empty-icon {
            background: rgba(59, 130, 246, 0.16);
            color: #93c5fd;
        }

        html[data-theme="night"] .activity-table {
            color: #e2e8f0 !important;
        }

        html[data-theme="night"] .activity-table thead th {
            background: #111827 !important;
            color: #cbd5e1 !important;
            border-bottom-color: rgba(148, 163, 184, 0.16) !important;
        }

        html[data-theme="night"] .activity-table tbody td {
            background: #0f172a !important;
            color: #e2e8f0 !important;
            border-color: rgba(148, 163, 184, 0.12) !important;
        }

        html[data-theme="night"] .activity-table tbody tr:hover {
            background: #101a30 !important;
        }

        html[data-theme="night"] .activity-table-wrap {
            border-color: rgba(148, 163, 184, 0.16);
        }

        @media (max-width: 767.98px) {
            .activity-log-hero {
                flex-direction: column;
            }

            .activity-log-back {
                width: 100%;
            }
        }
    </style>
@endsection
