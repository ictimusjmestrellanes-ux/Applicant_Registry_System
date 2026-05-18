<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\MayorsPermit;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PermitController extends Controller
{
    public function update(Request $request, $id)
    {
        $isApplicantUser = $request->user()?->role === User::ROLE_USER;
        $approvalStatus = $isApplicantUser
            ? MayorsPermit::APPROVAL_PENDING
            : MayorsPermit::APPROVAL_APPROVED;

        if ($request->user()?->role === User::ROLE_USER) {
            abort_if((int) $request->user()->applicant_id !== (int) $id, 403, 'You can only update your own requirements.');
        }

        $applicant = Applicant::findOrFail($id);
        $isFirstTimeJobSeeker = strtoupper(trim((string) ($applicant->first_time_job_seeker ?? ''))) === 'YES';
        $azure = Storage::disk('azure');

        /*
        |--------------------------------------------------------------------------
        | CREATE OR UPDATE PERMIT
        |--------------------------------------------------------------------------
        */
        $permit = MayorsPermit::firstOrNew([
            'applicant_id' => $applicant->id,
        ]);
        $wasRecentlyCreated = ! $permit->exists;
        $before = $permit->exists ? $permit->only($permit->getFillable()) : [];

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

        // =========================
        // HEALTH CARD
        // =========================
        if ($request->hasFile('health_card')) {
            $file = $request->file('health_card');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'permits/health_cards/'.$fileName;

            if ($permit->health_card) {
                $azure->delete($permit->health_card);
            }

            $azure->delete($filePath);

            $permit->health_card = $file->storeAs(
                'permits/health_cards',
                $fileName,
                'azure'
            );
        }

        // =========================
        // NBI / POLICE (DROPDOWN LOGIC)
        // =========================
        if ($request->clearance_type === 'nbi') {

            // delete police if exists
            if ($permit->permit_police_clearance) {
                $azure->delete($permit->permit_police_clearance);
                $permit->permit_police_clearance = null;
            }

            if ($request->hasFile('permit_nbi_clearance')) {
                $file = $request->file('permit_nbi_clearance');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::slug($originalName, '_').'.'.$extension;
                $filePath = 'permits/permit_nbi_clearance/'.$fileName;

                if ($permit->permit_nbi_clearance) {
                    $azure->delete($permit->permit_nbi_clearance);
                }

                $azure->delete($filePath);

                $permit->permit_nbi_clearance = $file->storeAs(
                    'permits/permit_nbi_clearance',
                    $fileName,
                    'azure'
                );
            }
        }

        if ($request->clearance_type === 'police') {

            // delete nbi if exists
            if ($permit->permit_nbi_clearance) {
                $azure->delete($permit->permit_nbi_clearance);
                $permit->permit_nbi_clearance = null;
            }

            if ($request->hasFile('permit_police_clearance')) {
                $file = $request->file('permit_police_clearance');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::slug($originalName, '_').'.'.$extension;
                $filePath = 'permits/permit_police_clearance/'.$fileName;

                if ($permit->permit_police_clearance) {
                    $azure->delete($permit->permit_police_clearance);
                }

                $azure->delete($filePath);

                $permit->permit_police_clearance = $file->storeAs(
                    'permits/permit_police_clearance',
                    $fileName,
                    'azure'
                );
            }
        }

        // =========================
        // CEDULA
        // =========================
        if ($request->hasFile('cedula')) {
            $file = $request->file('cedula');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'permits/cedulas/'.$fileName;

            if ($permit->cedula) {
                $azure->delete($permit->cedula);
            }

            $azure->delete($filePath);

            $permit->cedula = $file->storeAs(
                'permits/cedulas',
                $fileName,
                'azure'
            );
        }

        // =========================
        // REFERRAL LETTER
        // =========================
        if ($request->hasFile('referral_letter')) {
            $file = $request->file('referral_letter');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = 'permits/referral_letters/'.$fileName;

            if ($permit->referral_letter) {
                $azure->delete($permit->referral_letter);
            }

            $azure->delete($filePath);

            $permit->referral_letter = $file->storeAs(
                'permits/referral_letters',
                $fileName,
                'azure'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE FORM DATA
        |--------------------------------------------------------------------------
        */
        $permit->approval_status = $approvalStatus;
        $permit->clearance_type = $request->clearance_type;

        if (! $isApplicantUser) {
            $permit->fill([
                'permit_or_no' => $isFirstTimeJobSeeker ? 'RA11261' : $request->permit_or_no,
                'community_tax_no' => $request->community_tax_no,
                'permit_issued_on' => $request->permit_issued_on,
                'permit_issued_at' => $request->permit_issued_at,
                'permit_date' => $request->permit_date,
                'expires_on' => $request->expires_on,
                'permit_doc_stamp_control_no' => $isFirstTimeJobSeeker ? '-' : $request->permit_doc_stamp_control_no,
                'permit_date_of_payment' => $request->permit_date_of_payment,
            ]);
            $permit->disapproval_reason = null;
        }

        $permit->save();
        $permit->setRelation('applicant', $applicant);

        /*
        |--------------------------------------------------------------------------
        | AUTO GENERATE PESO ID ONLY WHEN PERMIT IS READY
        |--------------------------------------------------------------------------
        */
        if (empty($permit->peso_id_no) && $permit->isApproved() && $permit->isReadyForIdGeneration()) {
            $permit->peso_id_no = MayorsPermit::generateNextPesoIdNo();
            $permit->save();
        }

        $after = $permit->fresh()->only($permit->getFillable());
        $changes = ActivityLogger::diff($before, $after);

        if (! empty($changes)) {
            ActivityLogger::log(
                'permit',
                $wasRecentlyCreated ? 'created' : 'updated',
                $wasRecentlyCreated ? 'Added mayor\'s permit details for the applicant.' : 'Updated mayor\'s permit details.',
                $applicant,
                $changes,
                $request->user()
            );
        }

        return redirect()
            ->to(route('applicants.edit', $applicant->id).'#permit')
            ->with('success', 'Permit updated successfully.');
    }

    public function approve(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        $permit = MayorsPermit::where('applicant_id', $applicant->id)->firstOrFail();
        $before = $permit->only($permit->getFillable());

        $permit->approval_status = MayorsPermit::APPROVAL_APPROVED;
        $permit->disapproval_reason = null;
        $permit->save();

        $permit->loadMissing('applicant');

        if (empty($permit->peso_id_no) && $permit->isApproved() && $permit->isReadyForIdGeneration()) {
            $permit->peso_id_no = MayorsPermit::generateNextPesoIdNo();
            $permit->save();
        }

        $after = $permit->fresh()->only($permit->getFillable());
        $changes = ActivityLogger::diff($before, $after);

        ActivityLogger::log(
            'permit',
            'approved',
            'Approved the mayor\'s permit requirements.',
            $applicant,
            $changes,
            $request->user()
        );

        return redirect()
            ->to(route('applicants.edit', $applicant->id).'#permit')
            ->with('success', 'Permit approved successfully.');
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
                ->with('disapprove_requirement', 'permit')
                ->with('disapprove_requirement_id', (int) $id);
        }

        $validated = $validator->validated();

        $applicant = Applicant::findOrFail($id);
        $permit = MayorsPermit::where('applicant_id', $applicant->id)->firstOrFail();
        $before = $permit->only($permit->getFillable());

        $permit->approval_status = MayorsPermit::APPROVAL_DISAPPROVED;
        $permit->disapproval_reason = $validated['disapproval_reason'];
        $permit->save();

        $after = $permit->fresh()->only($permit->getFillable());
        $changes = ActivityLogger::diff($before, $after);

        ActivityLogger::log(
            'permit',
            'disapproved',
            'Disapproved the mayor\'s permit requirements.',
            $applicant,
            $changes,
            $request->user()
        );

        return redirect()
            ->to(route('applicants.edit', $applicant->id).'#permit')
            ->with('success', 'Permit disapproved successfully.');
    }

    public function printId($id)
    {
        $applicant = Applicant::with('permit')->findOrFail($id);

        if (! $applicant->permit || ! $applicant->permit->isComplete()) {
            return redirect()->back()->with('error', 'Permit is not complete.');
        }

        ActivityLogger::log(
            'permit',
            'generated',
            'Generated the mayor\'s permit to work document.',
            $applicant,
            null,
            request()->user()
        );

        return view('permit.id', compact('applicant'));
    }
}
