<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $activityLogs = ActivityLog::with(['applicant', 'causer'])
            ->when(! $user->isAdmin(), function ($query) use ($user) {
                $query->where('causer_id', $user->id);
            })
            ->latest()
            ->paginate(20);

        return view('activity-logs.index', compact('activityLogs'));
    }

    public function show(Request $request, ActivityLog $activityLog)
    {
        $user = $request->user();

        abort_if(! $user->isAdmin() && $activityLog->causer_id !== $user->id, 403);

        $activityLog->load(['applicant', 'causer']);

        return view('activity-logs.show', compact('activityLog'));
    }
}
