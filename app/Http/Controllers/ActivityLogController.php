<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $search = trim((string) $request->search);
        $parsedDate = $this->parseSearchDate($search);

        $activityLogs = ActivityLog::with(['applicant', 'causer'])
            ->when(! $user->isAdmin(), function ($query) use ($user) {
                $query->where('causer_id', $user->id);
            })
            ->when($search !== '', function ($query) use ($search, $parsedDate) {
                $query->where(function ($innerQuery) use ($search, $parsedDate) {
                    $innerQuery->where('module', 'like', "%{$search}%")
                        ->orWhere('action', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('causer', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('applicant', function ($applicantQuery) use ($search) {
                            $applicantQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('middle_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });

                    if ($parsedDate !== null) {
                        $innerQuery->orWhereDate('created_at', $parsedDate->toDateString());
                    }
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('activity-logs.index', compact('activityLogs', 'search'));
    }

    private function parseSearchDate(string $search): ?Carbon
    {
        if ($search === '') {
            return null;
        }

        $formats = [
            'Y-m-d',
            'm/d/Y',
            'n/j/Y',
            'm-d-Y',
            'M j Y',
            'M j, Y',
            'F j Y',
            'F j, Y',
        ];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $search);
            } catch (\Throwable $e) {
                continue;
            }
        }

        try {
            return Carbon::parse($search);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function show(Request $request, ActivityLog $activityLog)
    {
        $user = $request->user();

        abort_if(! $user->isAdmin() && $activityLog->causer_id !== $user->id, 403);

        $activityLog->load(['applicant', 'causer']);

        return view('activity-logs.show', compact('activityLog'));
    }
}
