@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Activity Logs
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Description</th>
                        <th>Device</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                            <td>{{ $log->user->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td>{{ $log->module }}</td>
                            <td>{{ $log->description }}</td>
                            <td class="text-truncate" style="max-width:150px;">
                                {{ $log->user_agent }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $logs->links() }}

        </div>
    </div>
</div>

@endsection