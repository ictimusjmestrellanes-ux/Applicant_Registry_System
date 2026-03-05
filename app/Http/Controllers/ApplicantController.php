<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\MayorsClearance;
use App\Models\MayorsPermit;
use App\Models\MayorsReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

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
        | 1. UPDATE PERSONAL INFORMATION
        |--------------------------------------------------------------------------
        */

        $applicant->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'contact_no' => $request->contact_no,
            'address_line' => $request->address_line,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $request->barangay,
        ]);

        /*
        |--------------------------------------------------------------------------
        | PREPARE FULL NAME FOR FILE RENAMING
        |--------------------------------------------------------------------------
        */

        $fullName = $applicant->first_name.'_'.
                    ($applicant->middle_name ? $applicant->middle_name.'_' : '').
                    $applicant->last_name;

        $fullName = Str::slug($fullName, '_');

        /*
        |--------------------------------------------------------------------------
        | 2. MAYOR'S PERMIT TO WORK FILES
        |--------------------------------------------------------------------------
        */

        $permit = MayorsPermit::firstOrCreate([
            'applicant_id' => $applicant->id,
        ]);

        $permitFields = [
            'health_card' => 'Health_Card',
            'nbi_or_police_clearance' => 'NBI_or_Police_Clearance',
            'cedula' => 'Cedula',
            'referral_letter' => 'Referral_Letter',
        ];

        foreach ($permitFields as $field => $label) {

            if ($request->hasFile($field)) {

                if ($permit->$field && Storage::disk('public')->exists($permit->$field)) {
                    Storage::disk('public')->delete($permit->$field);
                }

                $file = $request->file($field);
                $extension = $file->getClientOriginalExtension();

                $fileName = $label.'_'.$fullName.'.'.$extension;

                $path = $file->storeAs('permits', $fileName, 'public');

                $permit->$field = $path;
            }
        }

        $permit->save();

        /*
        |--------------------------------------------------------------------------
        | 3. MAYOR'S CLEARANCE FILES
        |--------------------------------------------------------------------------
        */

        $clearance = MayorsClearance::firstOrCreate([
            'applicant_id' => $applicant->id,
        ]);

        $clearanceFields = [
            'prosecutor_clearance' => 'Prosecutor_Clearance',
            'mtc_clearance' => 'MTC_Clearance',
            'rtc_clearance' => 'RTC_Clearance',
            'nbi_clearance' => 'NBI_Clearance',
            'barangay_clearance' => 'Barangay_Clearance',
        ];

        foreach ($clearanceFields as $field => $label) {

            if ($request->hasFile($field)) {

                if ($clearance->$field && Storage::disk('public')->exists($clearance->$field)) {
                    Storage::disk('public')->delete($clearance->$field);
                }

                $file = $request->file($field);
                $extension = $file->getClientOriginalExtension();

                $fileName = $label.'_'.$fullName.'.'.$extension;

                $path = $file->storeAs('clearances', $fileName, 'public');

                $clearance->$field = $path;
            }
        }

        $clearance->save();

        /*
        |--------------------------------------------------------------------------
        | 4. MAYOR'S REFERRAL FILES
        |--------------------------------------------------------------------------
        */

        $referral = MayorsReferral::firstOrCreate([
            'applicant_id' => $applicant->id,
        ]);

        $referralFields = [
            'resume' => 'Resume',
            'barangay_clearance' => 'Barangay_Clearance',
            'police_clearance' => 'Police_Clearance',
            'nbi_clearance' => 'NBI_Clearance',
        ];

        foreach ($referralFields as $field => $label) {

            if ($request->hasFile($field)) {

                if ($referral->$field && Storage::disk('public')->exists($referral->$field)) {
                    Storage::disk('public')->delete($referral->$field);
                }

                $file = $request->file($field);
                $extension = $file->getClientOriginalExtension();

                $fileName = $label.'_'.$fullName.'.'.$extension;

                $path = $file->storeAs('referrals', $fileName, 'public');

                $referral->$field = $path;
            }
        }

        $referral->save();

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('success', 'Applicant updated successfully.');
    }

    public function destroy($id)
    {
        $applicant = Applicant::findOrFail($id);

        $applicant->delete(); // Moves to Archive

        return redirect()->route('applicants.archive')
            ->with('success', 'Applicant Archived');
    }

    public function archive()
    {
        $applicants = Applicant::onlyTrashed()->paginate(10);

        return view('applicants.archive', compact('applicants'));
    }

    public function restore($id)
    {
        $applicant = Applicant::withTrashed()->findOrFail($id);
        $applicant->restore();

        return redirect()->route('applicants.index')
            ->with('success', 'Applicant restored successfully.');
    }
    
}
