<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\MayorsPermit;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PermitController extends Controller
{
    public function update(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);

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

            if ($permit->health_card) {
                Storage::disk('public')->delete($permit->health_card);
            }

            $file = $request->file('health_card');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'health_card_'.$fullName.'.'.$extension;

            $permit->health_card = $file->storeAs(
                'permits/health_cards',
                $fileName,
                'public'
            );
        }

        // =========================
        // NBI / POLICE (DROPDOWN LOGIC)
        // =========================
        if ($request->clearance_type === 'nbi') {

            // delete police if exists
            if ($permit->permit_police_clearance) {
                Storage::disk('public')->delete($permit->permit_police_clearance);
                $permit->permit_police_clearance = null;
            }

            if ($request->hasFile('permit_nbi_clearance')) {

                if ($permit->permit_nbi_clearance) {
                    Storage::disk('public')->delete($permit->permit_nbi_clearance);
                }

                $file = $request->file('permit_nbi_clearance');
                $extension = $file->getClientOriginalExtension();

                $fileName = 'nbi_clearance_'.$fullName.'.'.$extension;

                $permit->permit_nbi_clearance = $file->storeAs(
                    'permits/permit_nbi_clearance',
                    $fileName,
                    'public'
                );
            }
        }

        if ($request->clearance_type === 'police') {

            // delete nbi if exists
            if ($permit->permit_nbi_clearance) {
                Storage::disk('public')->delete($permit->permit_nbi_clearance);
                $permit->permit_nbi_clearance = null;
            }

            if ($request->hasFile('permit_police_clearance')) {

                if ($permit->permit_police_clearance) {
                    Storage::disk('public')->delete($permit->permit_police_clearance);
                }

                $file = $request->file('permit_police_clearance');
                $extension = $file->getClientOriginalExtension();

                $fileName = 'police_clearance_'.$fullName.'.'.$extension;

                $permit->permit_police_clearance = $file->storeAs(
                    'permits/permit_police_clearance',
                    $fileName,
                    'public'
                );
            }
        }

        // =========================
        // CEDULA
        // =========================
        if ($request->hasFile('cedula')) {

            if ($permit->cedula) {
                Storage::disk('public')->delete($permit->cedula);
            }

            $file = $request->file('cedula');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'cedula_'.$fullName.'.'.$extension;

            $permit->cedula = $file->storeAs(
                'permits/cedula',
                $fileName,
                'public'
            );
        }

        // =========================
        // REFERRAL LETTER
        // =========================
        if ($request->hasFile('referral_letter')) {

            if ($permit->referral_letter) {
                Storage::disk('public')->delete($permit->referral_letter);
            }

            $file = $request->file('referral_letter');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'referral_letter_'.$fullName.'.'.$extension;

            $permit->referral_letter = $file->storeAs(
                'permits/referral_letters',
                $fileName,
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE FORM DATA
        |--------------------------------------------------------------------------
        */
        $permit->fill([
            'permit_or_no' => $request->permit_or_no,
            'community_tax_no' => $request->community_tax_no,
            'permit_issued_on' => $request->permit_issued_on,
            'permit_date' => $request->permit_date,
            'expires_on' => $request->expires_on,
            'permit_doc_stamp_control_no' => $request->permit_doc_stamp_control_no,
            'permit_date_of_payment' => $request->permit_date_of_payment,
            'clearance_type' => $request->clearance_type,
        ]);

        $permit->save();

        /*
        |--------------------------------------------------------------------------
        | AUTO GENERATE PESO ID
        |--------------------------------------------------------------------------
        */
        if (empty($permit->peso_id_no)) {

            $year = date('Y');

            $latest = MayorsPermit::whereYear('created_at', $year)
                ->whereNotNull('peso_id_no')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $latest
                ? ((int) substr($latest->peso_id_no, -7)) + 1
                : 1;

            $permit->peso_id_no = $year.'-'.str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

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
            ->route('applicants.edit', $applicant->id)
            ->with('success', 'Permit updated successfully.');
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
