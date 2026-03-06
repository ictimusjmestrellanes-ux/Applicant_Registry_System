<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;

class PermitController extends Controller
{
    public function generate($id)
    {
        $applicant = Applicant::with('permit')->findOrFail($id);

        if (! $applicant->isPermitComplete()) {
            return back()->with('error', 'Requirements not complete.');
        }

        return view('permit.id', compact('applicant'));
    }

}
