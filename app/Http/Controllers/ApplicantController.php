<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

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
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_no' => 'required',
            'gender' => 'required',
            'civil_status' => 'required',
            'pwd' => 'required',
            'four_ps' => 'required',
            'address_line' => 'required',
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
            'educational_attainment' => 'required',
            'position_hired' => 'required',
            'first_time_job_seeker' => 'required',
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
        $applicant = Applicant::with(['activityLogs.causer'])->findOrFail($id);

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
        ]);

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
