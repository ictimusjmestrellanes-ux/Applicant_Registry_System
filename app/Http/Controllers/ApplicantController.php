<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicantController extends Controller
{
    public function create()
    {
        return view('applicants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_no' => 'required',
            'address_line' => 'required',
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
        ]);

        $applicant = Applicant::create($request->all());

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('created_success', true);
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $applicants = Applicant::with(['permit', 'clearance', 'referral'])
            ->when($search, function ($query) use ($search) {
                $query->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('contact_no', 'like', "%$search%");
            })
            ->paginate(10);

        return view('applicants.index', compact('applicants', 'search'));
    }

    public function edit($id)
    {
        $applicant = Applicant::findOrFail($id);

        return view('applicants.edit', compact('applicant'));
    }

    public function update(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:20',
            'address_line' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',

            // file validation
            '*.pdf',
            '*.jpg',
            '*.jpeg',
            '*.png',
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE PERSONAL INFO
        |--------------------------------------------------------------------------
        */
        $applicant->update($request->only([
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'contact_no',
            'address_line',
            'province',
            'city',
            'barangay',
        ]));

        /*
        |--------------------------------------------------------------------------
        | PERMIT FILES
        |--------------------------------------------------------------------------
        */
        $permit = $applicant->permit()->firstOrCreate([]);

        $permitFields = [
            'health_card',
            'nbi_or_police_clearance',
            'cedula',
            'referral_letter',
        ];

        foreach ($permitFields as $field) {
            if ($request->hasFile($field)) {
                $permit->$field = $request->file($field)->store('permits', 'public');
            }
        }

        $permit->save();

        /*
        |--------------------------------------------------------------------------
        | CLEARANCE FILES
        |--------------------------------------------------------------------------
        */
        $clearance = $applicant->clearance()->firstOrCreate([]);

        $clearanceFields = [
            'prosecutor_clearance',
            'mtc_clearance',
            'rtc_clearance',
            'nbi_clearance',
            'barangay_clearance',
        ];

        foreach ($clearanceFields as $field) {
            if ($request->hasFile($field)) {
                $clearance->$field = $request->file($field)->store('clearances', 'public');
            }
        }

        $clearance->save();

        /*
        |--------------------------------------------------------------------------
        | REFERRAL FILES
        |--------------------------------------------------------------------------
        */
        $referral = $applicant->referral()->firstOrCreate([]);

        $referralFields = [
            'resume',
            'barangay_clearance',
            'police_clearance',
            'nbi_clearance',
        ];

        foreach ($referralFields as $field) {
            if ($request->hasFile($field)) {
                $referral->$field = $request->file($field)->store('referrals', 'public');
            }
        }

        $referral->save();

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('success', 'Applicant updated successfully!');
    }

    public function destroy($id)
    {
        $applicant = Applicant::findOrFail($id);

        $applicant->delete(); // Moves to Archive

        return redirect()->route('applicants.index')
            ->with('success', 'Applicant Archived');
    }

    public function archive()
    {
        $applicants = Applicant::onlyTrashed()->paginate(10);

        return view('applicants.archive', compact('applicants'));
    }

    public function viewFile(Applicant $applicant, $field)
    {
        $applicant->load(['permit', 'clearance', 'referral']);

        $filePath = null;

        // Permit fields
        $permitFields = [
            'health_card',
            'nbi_or_police_clearance',
            'cedula',
            'referral_letter',
        ];

        // Clearance fields
        $clearanceFields = [
            'prosecutor_clearance',
            'mtc_clearance',
            'rtc_clearance',
            'nbi_clearance',
            'barangay_clearance',
        ];

        // Referral fields
        $referralFields = [
            'resume',
            'ref_barangay_clearance',
            'ref_police_clearance',
            'ref_nbi_clearance',
        ];

        if (in_array($field, $permitFields)) {
            $filePath = optional($applicant->permit)->$field;
        }

        if (in_array($field, $clearanceFields)) {
            $filePath = optional($applicant->clearance)->$field;
        }

        if (in_array($field, $referralFields)) {
            $filePath = optional($applicant->referral)->$field;
        }

        if (! $filePath || ! Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        $fullPath = storage_path('app/public/'.$filePath);

        return response()->file($fullPath, [
            'Content-Disposition' => 'inline',
        ]);
    }

    public function deleteFile(Applicant $applicant, $field)
    {
        $applicant->load(['permit', 'clearance', 'referral']);

        $filePath = null;
        $model = null;

        $permitFields = [
            'health_card',
            'nbi_or_police_clearance',
            'cedula',
            'referral_letter',
        ];

        $clearanceFields = [
            'prosecutor_clearance',
            'mtc_clearance',
            'rtc_clearance',
            'nbi_clearance',
            'barangay_clearance',
        ];

        $referralFields = [
            'resume',
            'ref_barangay_clearance',
            'ref_police_clearance',
            'ref_nbi_clearance',
        ];

        if (in_array($field, $permitFields)) {
            $model = $applicant->permit;
        }

        if (in_array($field, $clearanceFields)) {
            $model = $applicant->clearance;
        }

        if (in_array($field, $referralFields)) {
            $model = $applicant->referral;
        }

        if (! $model || ! $model->$field) {
            return back()->with('error', 'File not found.');
        }

        $filePath = $model->$field;

        // Delete file from storage
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // Remove file path from database
        $model->$field = null;
        $model->save();

        return back()->with('success', 'File deleted successfully.');
    }
}
