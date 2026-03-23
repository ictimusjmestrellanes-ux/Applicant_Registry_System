@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <div class="container-fluid py-4 px-md-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Activity Logs</h3>
                <p class="text-muted mb-0">Track user logins, logouts, applicant changes, and generated documents.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
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
                                <td>{{ $log->created_at ? $log->created_at->timezone(config('app.timezone'))->format('M d, Y h:i A') : 'N/A' }}</td>
                                <td>{{ $log->causer?->name ?? 'System' }}</td>
                                <td>
                                    @if($log->applicant)
                                        {{ trim($log->applicant->first_name . ' ' . $log->applicant->last_name) }}
                                    @else
                                        System Event
                                    @endif
                                </td>
                                <td><span class="badge bg-light text-dark text-uppercase">{{ $log->module }}</span></td>
                                <td><span class="badge bg-primary-subtle text-primary text-uppercase">{{ $log->action }}</span></td>
                                <td>{{ $log->description }}</td>
                                <td class="text-center">
                                    <a href="{{ route('activity-logs.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No activity logs available yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
