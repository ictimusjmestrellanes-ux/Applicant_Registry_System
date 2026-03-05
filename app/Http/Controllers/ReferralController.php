<?php

namespace App\Http\Controllers;

use App\Models\Applicant;

class ReferralController extends Controller
{
    public function generate($id)
    {
        $applicant = Applicant::with('referral')->findOrFail($id);

        if (! $applicant->isReferralComplete()) {
            return back()->with('error', 'Requirements not completed.');
        }

        return view('referral.letter', compact('applicant'));
    }
}
