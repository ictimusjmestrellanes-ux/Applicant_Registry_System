<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display Activity Logs
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $logs = ActivityLog::with('user')
                    ->latest()
                    ->paginate(15);

        return view('activity.index', compact('logs'));
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Single Log
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Activity log deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Clear All Logs (Optional)
    |--------------------------------------------------------------------------
    */
    public function clear()
    {
        ActivityLog::truncate();

        return redirect()->back()->with('success', 'All activity logs cleared.');
    }
}