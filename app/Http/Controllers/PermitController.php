<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\MayorsPermit;
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
            'applicant_id' => $applicant->id
        ]);

        /*
        |--------------------------------------------------------------------------
        | PREPARE FILE NAME
        |--------------------------------------------------------------------------
        */

        $fullName = $applicant->last_name.'_'.$applicant->first_name;
        $fullName = Str::upper(Str::slug($fullName,'_'));

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOADS
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('health_card')) {

            $file = $request->file('health_card');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'HEALTH_CARD_'.$fullName.'.'.$extension;

            $permit->health_card = $file->storeAs(
                'permits/health_cards',
                $fileName,
                'public'
            );
        }


        if ($request->hasFile('nbi_or_police_clearance')) {

            $file = $request->file('nbi_or_police_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'NBI_CLEARANCE_'.$fullName.'.'.$extension;

            $permit->nbi_or_police_clearance = $file->storeAs(
                'permits/nbi_clearance',
                $fileName,
                'public'
            );
        }


        if ($request->hasFile('cedula')) {

            $file = $request->file('cedula');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'CEDULA_'.$fullName.'.'.$extension;

            $permit->cedula = $file->storeAs(
                'permits/cedula',
                $fileName,
                'public'
            );
        }


        if ($request->hasFile('referral_letter')) {

            $file = $request->file('referral_letter');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'REFERRAL_LETTER_'.$fullName.'.'.$extension;

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
        $permit->permit_or_no = $request->permit_or_no;
        $permit->peso_id_no = $request->peso_id_no;
        $permit->community_tax_no = $request->community_tax_no;

        $permit->permit_issued_on = $request->permit_issued_on;
        $permit->permit_issued_in = $request->permit_issued_in;

        $permit->permit_date = $request->permit_date;
        $permit->expires_on = $request->expires_on;

        $permit->permit_doc_stamp_control_no = $request->permit_doc_stamp_control_no;
        $permit->permit_date_of_payment = $request->permit_date_of_payment;

        $permit->save();


        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('success', 'Permit updated successfully.');
    }
}
