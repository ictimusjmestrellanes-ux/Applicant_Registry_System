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
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_no' => 'required',
            'address_line' => 'required',
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
        ]);

        Applicant::create($request->all());

        return redirect()->route('applicants.index')
            ->with('success', 'Applicant Added Successfully');
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $applicants = Applicant::where('first_name', 'like', "%$search%")
            ->orWhere('last_name', 'like', "%$search%")
            ->orWhere('contact_no', 'like', "%$search%")
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

    // Update applicant basic info (if any)
    $applicant->update($request->only([
        'first_name',
        'middle_name',
        'last_name',
        'contact_no',
        'address_line'
    ]));

    // ==========================
    // UPDATE OR CREATE PERMIT
    // ==========================

    $applicant->permit()->updateOrCreate(
        ['applicant_id' => $applicant->id],
        [
            'health_card' => $request->health_card,
            'nbi_or_police_clearance' => $request->nbi_or_police_clearance,
            'cedula' => $request->cedula,
            'referral_letter' => $request->referral_letter,
        ]
    );

    return redirect()
        ->route('applicants.index')
        ->with('success', 'Applicant updated successfully.');
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
}
