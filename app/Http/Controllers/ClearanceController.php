<?php

namespace App\Http\Controllers;

use App\Models\Applicant;

class ClearanceController extends Controller
{
    public function generate($id)
    {
        $applicant = Applicant::with('clearance')->findOrFail($id);

        if (! $applicant->isClearanceComplete()) {
            return back()->with('error', 'Requirements not completed.');
        }

        return view('clearance.clearance', compact('applicant'));
    }
}
