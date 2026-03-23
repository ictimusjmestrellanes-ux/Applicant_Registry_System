@extends('layouts.app')

@section('title', 'Activity Log Details')

@section('content')
    <div class="container-fluid py-4 px-md-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Activity Log Details</h3>
                <p class="text-muted mb-0">{{ $activityLog->description }}</p>
            </div>
            <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Date</small>
                        <div class="fw-semibold">{{ $activityLog->created_at->format('F d, Y h:i A') }}</div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">User</small>
                        <div class="fw-semibold">{{ $activityLog->causer?->name ?? 'System' }}</div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Applicant</small>
                        <div class="fw-semibold">
                            {{ $activityLog->applicant ? trim($activityLog->applicant->first_name . ' ' . $activityLog->applicant->last_name) : 'System Event' }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Module</small>
                        <div class="fw-semibold text-uppercase">{{ $activityLog->module }}</div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Action</small>
                        <div class="fw-semibold text-uppercase">{{ $activityLog->action }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Changed Fields</h5>

                @if(empty($activityLog->changes))
                    <p class="text-muted mb-0">This activity does not have field-level changes.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
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
        </div>
    </div>
@endsection
