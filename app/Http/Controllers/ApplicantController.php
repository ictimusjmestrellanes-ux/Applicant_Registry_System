<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\MayorsClearance;
use App\Models\MayorsPermit;
use App\Models\MayorsReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicantController extends Controller
{
    public function create()
    {
        return view('applicants.create');
    }

    public function store(Request $request)
    {
        $request->validate([

        // Applicant Personal Information
            'or_no' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_no' => 'required',
            'gender' => 'required',
            'civil_status' => 'required',
            'pwd'=> 'required',
            'four_ps'=> 'required',
            'address_line' => 'required',
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
            'educational_attainment'=> 'required',
            'position_hired'=> 'required',
            'first_time_job_seeker'=> 'required',
            'is_paid'=> 'required',
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
                    ->orWhere('contact_no', 'like', "%$search%")
                    ->orWhere('gender', 'like', "%$search%");
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
            'or_no'=> $request->or_no,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'age' => $request->age,
            'contact_no' => $request->contact_no,
            'gender' => $request->gender,
            'civil_status' => $request->civil_status,
            'pwd' => $request->pwd,
            'four_ps' => $request->four_ps,
            'address_line' => $request->address_line,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $request->barangay,
            'educational_attainment' => $request->educational_attainment,
            'hiring_company' => $request->hiring_company,
            'position_hired' => $request->position_hired,
            'first_time_job_seeker' => $request->first_time_job_seeker,
            'is_paid' => $request->is_paid,
        ]);

        $permit = MayorsPermit::firstOrNew([
            'applicant_id' => $applicant->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | PREPARE FULL NAME FOR FILE RENAMING
        |--------------------------------------------------------------------------
        */

        $fullName = $applicant->last_name;

        $fullName = Str::slug($fullName, '_');

        /*
        |--------------------------------------------------------------------------
        | 2. MAYOR'S PERMIT TO WORK FILES
        |--------------------------------------------------------------------------
        */

        $permit = MayorsPermit::firstOrCreate([
            'applicant_id' => $applicant->id,
        ]);

        $permit->update([
            'peso_id_no' => $request->peso_id_no,
            'employers_name_or_address' => $request->employers_name_or_address,
            'community_tax_no' => $request->community_tax_no,
            'permit_issued_on' => $request->permit_issued_on,
            'permit_issued_in' => $request->permit_issued_in,
            'permit_date' => $request->permit_date,
            'expires_on' => $request->expires_on,
            'permit_doc_stamp_control_no' => $request->permit_doc_stamp_control_no,
            'permit_gor_serial_no' => $request->permit_gor_serial_no,
            'permit_date_of_payment' => $request->permit_date_of_payment,
        ]);


        // $permit->employers_name_or_address = $request->employers_name_or_address;
        // $permit->community_tax_no = $request->community_tax_no;
        // $permit->permit_issued_on = $request->permit_issued_on;
        // $permit->permit_issued_in = $request->permit_issued_in;
        // $permit->paid_under_official_receipt = $request->paid_under_official_receipt;
        // $permit->permit_date = $request->permit_date;
        // $permit->expires_on = $request->expires_on;
        // $permit->permit_doc_stamp_control_no = $request->permit_doc_stamp_control_no;
        // $permit->permit_gor_serial_no = $request->permit_gor_serial_no;
        // $permit->permit_date_of_payment = $request->permit_date_of_payment;

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

                $fileName = $label.'_'.strtoupper($fullName).'.'.$extension;

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

        $clearance->update([
            'clearance_or_no' => $request->clearance_or_no,
            'clearance_issued_on' => $request->clearance_issued_on,
            'clearance_issued_in' => $request->clearance_issued_in,
            'peso_control_no' => $request->peso_control_no,
            'clearance_doc_stamp_control_no' => $request->clearance_doc_stamp_control_no,
            'clearance_gor_control_no' => $request->clearance_gor_control_no,
            'date_of_payment' => $request->date_of_payment,
            'hired_company' => $request->hired_company,
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

                $fileName = $label.'_'.strtoupper($fullName).'.'.$extension;

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
            'ref_barangay_clearance' => 'Barangay_Clearance',
            'ref_police_clearance' => 'Police_Clearance',
            'ref_nbi_clearance' => 'NBI_Clearance',
        ];

        foreach ($referralFields as $field => $label) {

            if ($request->hasFile($field)) {

                if ($referral->$field && Storage::disk('public')->exists($referral->$field)) {
                    Storage::disk('public')->delete($referral->$field);
                }

                $file = $request->file($field);
                $extension = $file->getClientOriginalExtension();

                $fileName = $label.'_'.strtoupper($fullName).'.'.$extension;

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
