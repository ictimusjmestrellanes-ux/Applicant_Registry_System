<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\MayorsClearance;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClearanceController extends Controller
{
    public function update(Request $request, $id)
    {
        $isApplicantUser = $request->user()?->role === User::ROLE_USER;
        $linkedApplicant = $isApplicantUser ? $request->user()?->linkedApplicant() : null;
        $resolvedApplicantId = $isApplicantUser
            ? (int) ($linkedApplicant?->id ?? 0)
            : (int) $id;

        abort_if($isApplicantUser && $resolvedApplicantId <= 0, 403, 'Your account is not linked to an applicant record.');

        if ($isApplicantUser) {
            $id = $resolvedApplicantId;
        }

        $applicant = Applicant::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | CREATE OR UPDATE CLEARANCE
        |--------------------------------------------------------------------------
        */

        $clearance = MayorsClearance::firstOrNew([
            'applicant_id' => $applicant->id,
        ]);
        $wasRecentlyCreated = ! $clearance->exists;
        $before = $clearance->exists ? $clearance->only($clearance->getFillable()) : [];
        $approvalStatus = $isApplicantUser
            ? MayorsClearance::APPROVAL_PENDING
            : MayorsClearance::APPROVAL_APPROVED;

        /*
        |--------------------------------------------------------------------------
        | PREPARE FILE NAME
        |--------------------------------------------------------------------------
        */

        $fullName = Str::lower(Str::slug($applicant->last_name, '_'));

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOADS
        |--------------------------------------------------------------------------
        */

        // PROSECUTOR CLEARANCE
        if ($request->hasFile('prosecutor_clearance')) {
            $file = $request->file('prosecutor_clearance');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'clearances/prosecutor/'.$fileName;

            if ($clearance->prosecutor_clearance) {
                Storage::disk('azure')->delete($clearance->prosecutor_clearance);
            }

            if (Storage::disk('azure')->exists($filePath)) {
                Storage::disk('azure')->delete($filePath);
            }

            $clearance->prosecutor_clearance = $file->storeAs(
                'clearances/prosecutor',
                $fileName,
                'azure'
            );
        }

        // MUNICIPAL TRIAL COURT
        if ($request->hasFile('mtc_clearance')) {
            $file = $request->file('mtc_clearance');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'clearances/mtc/'.$fileName;

            if ($clearance->mtc_clearance) {
                Storage::disk('azure')->delete($clearance->mtc_clearance);
            }

            if (Storage::disk('azure')->exists($filePath)) {
                Storage::disk('azure')->delete($filePath);
            }

            $clearance->mtc_clearance = $file->storeAs(
                'clearances/mtc',
                $fileName,
                'azure'
            );
        }

        // REGIONAL TRIAL COURT
        if ($request->hasFile('rtc_clearance')) {
            $file = $request->file('rtc_clearance');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'clearances/rtc/'.$fileName;

            if ($clearance->rtc_clearance) {
                Storage::disk('azure')->delete($clearance->rtc_clearance);
            }

            if (Storage::disk('azure')->exists($filePath)) {
                Storage::disk('azure')->delete($filePath);
            }

            $clearance->rtc_clearance = $file->storeAs(
                'clearances/rtc',
                $fileName,
                'azure'
            );
        }

        // NBI CLEARANCE
        if ($request->hasFile('nbi_clearance')) {
            $file = $request->file('nbi_clearance');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'clearances/nbi/'.$fileName;

            if ($clearance->nbi_clearance) {
                Storage::disk('azure')->delete($clearance->nbi_clearance);
            }

            if (Storage::disk('azure')->exists($filePath)) {
                Storage::disk('azure')->delete($filePath);
            }

            $clearance->nbi_clearance = $file->storeAs(
                'clearances/nbi',
                $fileName,
                'azure'
            );
        }

        // BARANGAY CLEARANCE
        if ($request->hasFile('barangay_clearance')) {
            $file = $request->file('barangay_clearance');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'clearances/barangay/'.$fileName;

            if ($clearance->barangay_clearance) {
                Storage::disk('azure')->delete($clearance->barangay_clearance);
            }

            if (Storage::disk('azure')->exists($filePath)) {
                Storage::disk('azure')->delete($filePath);
            }

            $clearance->barangay_clearance = $file->storeAs(
                'clearances/barangay',
                $fileName,
                'azure'
            );
        }

        if (! $isApplicantUser) {
            $clearance->clearance_or_no = $request->clearance_or_no;
            $clearance->clearance_issued_on = $request->clearance_issued_on;

            $clearance->clearance_peso_control_no = $request->filled('clearance_peso_control_no')
                ? $request->clearance_peso_control_no
                : $clearance->clearance_peso_control_no;
            $clearance->clearance_doc_stamp_control_no = $request->clearance_doc_stamp_control_no;
            $clearance->clearance_date_of_payment = $request->clearance_date_of_payment;

            $clearance->clearance_hired_company = $request->clearance_hired_company;
            $clearance->disapproval_reason = null;
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE CLEARANCE
        |--------------------------------------------------------------------------
        */

        $clearance->approval_status = $approvalStatus;
        $clearance->save();

        if (empty($clearance->clearance_peso_control_no) && $clearance->isApproved() && $clearance->isReadyForControlNumber()) {
            $clearance->clearance_peso_control_no = MayorsClearance::generateNextPesoControlNo();

            $clearance->save(); // SAVE AGAIN with generated ID
        }

        $after = $clearance->fresh()->only($clearance->getFillable());
        $changes = ActivityLogger::diff($before, $after);

        if (! empty($changes)) {
            ActivityLogger::log(
                'clearance',
                $wasRecentlyCreated ? 'created' : 'updated',
                $wasRecentlyCreated ? 'Added mayor\'s clearance details for the applicant.' : 'Updated mayor\'s clearance details.',
                $applicant,
                $changes,
                $request->user()
            );
        }

        return redirect()
            ->to(route('applicants.edit', $applicant->id).'#clearance')
            ->with('success', 'Clearance updated successfully.');
    }

    public function approve(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        $clearance = MayorsClearance::where('applicant_id', $applicant->id)->firstOrFail();
        $before = $clearance->only($clearance->getFillable());

        $clearance->approval_status = MayorsClearance::APPROVAL_APPROVED;
        $clearance->disapproval_reason = null;
        $clearance->save();

        if (empty($clearance->clearance_peso_control_no) && $clearance->isApproved() && $clearance->isReadyForControlNumber()) {
            $clearance->clearance_peso_control_no = MayorsClearance::generateNextPesoControlNo();
            $clearance->save();
        }

        $after = $clearance->fresh()->only($clearance->getFillable());
        $changes = ActivityLogger::diff($before, $after);

        ActivityLogger::log(
            'clearance',
            'approved',
            'Approved the mayor\'s clearance requirements.',
            $applicant,
            $changes,
            $request->user()
        );

        return redirect()
            ->to(route('applicants.edit', $applicant->id).'#clearance')
            ->with('success', 'Clearance approved successfully.');
    }

    public function disapprove(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'disapproval_reason' => ['required', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('disapprove_requirement', 'clearance')
                ->with('disapprove_requirement_id', (int) $id);
        }

        $validated = $validator->validated();

        $applicant = Applicant::findOrFail($id);
        $clearance = MayorsClearance::where('applicant_id', $applicant->id)->firstOrFail();
        $before = $clearance->only($clearance->getFillable());

        $clearance->approval_status = MayorsClearance::APPROVAL_DISAPPROVED;
        $clearance->disapproval_reason = $validated['disapproval_reason'];
        $clearance->save();

        $after = $clearance->fresh()->only($clearance->getFillable());
        $changes = ActivityLogger::diff($before, $after);

        ActivityLogger::log(
            'clearance',
            'disapproved',
            'Disapproved the mayor\'s clearance requirements.',
            $applicant,
            $changes,
            $request->user()
        );

        return redirect()
            ->to(route('applicants.edit', $applicant->id).'#clearance')
            ->with('success', 'Clearance disapproved successfully.');
    }

    public function printLetter($id)
    {
        $applicant = Applicant::with('clearance')->findOrFail($id);

        if (! $applicant->clearance || ! $applicant->clearance->isComplete()) {
            return back()->with('error', 'Clearance is not complete.');
        }

        ActivityLogger::log(
            'clearance',
            'generated',
            'Generated the mayor\'s clearance letter.',
            $applicant,
            null,
            request()->user()
        );

        return view('clearance.clearance', compact('applicant'));
    }
}
