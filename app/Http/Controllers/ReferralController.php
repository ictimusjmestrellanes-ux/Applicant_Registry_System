<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\MayorsReferral;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function update(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        $referralType = $request->input('referral_type');

        $referral = MayorsReferral::firstOrNew([
            'applicant_id' => $applicant->id,
        ]);
        $wasRecentlyCreated = ! $referral->exists;
        $before = $referral->exists ? $referral->only($referral->getFillable()) : [];
        $fullName = Str::lower(Str::slug($applicant->last_name, '_'));

        $fileFields = [
            'resume' => 'referrals/resume',
            'ref_barangay_clearance' => 'referrals/barangay',
            'ref_police_clearance' => 'referrals/police',
            'ref_nbi_clearance' => 'referrals/nbi',
        ];

        foreach ($fileFields as $field => $directory) {
            if (! $request->hasFile($field)) {
                continue;
            }

            if ($referral->{$field}) {
                Storage::disk('public')->delete($referral->{$field});
            }

            $file = $request->file($field);
            $extension = $file->getClientOriginalExtension();
            $fileName = $field.'_'.$fullName.'.'.$extension;

            $referral->{$field} = $file->storeAs($directory, $fileName, 'public');
        }

        $referralData = [
            'referral_type' => $referralType,
            'ref_or_no' => null,
            'ref_mayor_recipient_firstname' => null,
            'ref_mayor_recipient_middlename' => null,
            'ref_mayor_recipient_lastname' => null,
            'ref_city_gov' => null,
            'ref_company_address' => null,
            'ref_hired_company' => null,
            'ref_peso_or_no' => null,
            'ref_recipient' => null,
            'ref_place' => null,
        ];

        if ($referralType === MayorsReferral::TYPE_PESO_OFFICE) {
            $referralData = array_merge($referralData, [
                'ref_or_no' => $request->ref_or_no,
                'ref_mayor_recipient_firstname' => $request->ref_mayor_recipient_firstname ?? '',
                'ref_mayor_recipient_middlename' => $request->ref_mayor_recipient_middlename,
                'ref_mayor_recipient_lastname' => $request->ref_mayor_recipient_lastname ?? '',
                'ref_city_gov' => $request->ref_city_gov,
                'ref_place' => $request->ref_place,
                'ref_hired_company' => $request->ref_hired_company,
            ]);
        } elseif ($referralType === MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT) {
            $referralData = array_merge($referralData, [
                'ref_peso_or_no' => $request->ref_peso_or_no,
                'ref_recipient' => $request->ref_recipient,
                'ref_company_address' => $request->ref_company_address,
            ]);
        }

        $referral->fill($referralData);

        $referral->save();

        $after = $referral->fresh()->only($referral->getFillable());
        $changes = ActivityLogger::diff($before, $after);

        if (! empty($changes)) {
            ActivityLogger::log(
                'referral',
                $wasRecentlyCreated ? 'created' : 'updated',
                $wasRecentlyCreated ? 'Added mayor\'s referral details for the applicant.' : 'Updated mayor\'s referral details.',
                $applicant,
                $changes,
                $request->user()
            );
        }

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('success', 'Referral updated successfully.');
    }

    public function printLetter($id)
    {
        $applicant = Applicant::with('referral')->findOrFail($id);

        if (! $applicant->referral || ! $applicant->referral->isComplete()) {
            return back()->with('error', 'Referral is not complete.');
        }

        ActivityLogger::log(
            'referral',
            'generated',
            'Generated the mayor\'s referral letter.',
            $applicant,
            null,
            request()->user()
        );

        return view('referral.letter', compact('applicant'));
    }
}
