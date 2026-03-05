<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Applicant;

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

    public function deleteFile($applicantId, $field)
    {
        $applicant = Applicant::with('permit')->findOrFail($applicantId);

        if (! $applicant->permit) {
            return back()->with('error', 'Permit not found.');
        }

        $permit = $applicant->permit;

        $allowedFields = [
            'health_card',
            'nbi_or_police_clearance',
            'cedula',
            'referral_letter',
        ];

        if (! in_array($field, $allowedFields)) {
            abort(403);
        }

        if ($permit->$field) {

            // Important: make sure path is correct
            if (Storage::disk('public')->exists($permit->$field)) {
                Storage::disk('public')->delete($permit->$field);
            }

            $permit->$field = null;
            $permit->save();
        }

        return back()->with('success', 'File removed successfully.');
    }
}
