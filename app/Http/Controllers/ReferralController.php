<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\MayorsReferral;
use App\Support\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function searchRecipients(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));
        $cityGovernment = trim((string) $request->query('city_government', ''));

        $results = $this->searchPhilippineMayors($search, $cityGovernment)
            ->map(fn (array $mayor) => [
                'id' => $mayor['recipient'],
                'text' => $mayor['recipient'],
                'city_government' => $mayor['city_government'],
                'company_address' => $mayor['company_address'],
            ])
            ->values();

        return response()->json([
            'results' => $results,
        ]);
    }

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

            $file = $request->file($field);
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName, '_').'.'.$extension;
            $filePath = $directory.'/'.$fileName;

            if ($referral->{$field}) {
                Storage::disk('public')->delete($referral->{$field});
            }

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $referral->{$field} = $file->storeAs($directory, $fileName, 'public');
        }

        $referralData = [
            'referral_type' => $referralType,

            'ref_imus_ocrl' => null,
            'ref_employer_name' => null,
            'ref_position' => null,
            'ref_or_no' => null,
            'ref_company_address' => null,
            'ref_hired_company' => null,


            'ref_peso_or_no' => null,
            'ref_recipient' => null,
            'ref_place' => null,
            'ref_ocrl' => null,
            'ref_city_gov' => null,
        ];

        if ($referralType === MayorsReferral::TYPE_PESO_OFFICE) {
            $referralData = array_merge($referralData, [
                'ref_imus_ocrl' => $request->ref_imus_ocrl,
                'ref_employer_name' => $request->ref_employer_name,
                'ref_position' => $request->ref_position,
                'ref_or_no' => $request->ref_or_no,
                'ref_place' => $request->ref_place,
                'ref_hired_company' => $request->ref_hired_company,
            ]);
        } elseif ($referralType === MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT) {
            $referralData = array_merge($referralData, [
                'ref_peso_or_no' => $request->ref_peso_or_no,
                'ref_ocrl' => $request->ref_ocrl,
                'ref_recipient' => $request->ref_recipient,
                'ref_company_address' => $request->ref_company_address,
                'ref_city_gov' => $request->ref_city_gov,
            ]);
        }

        $referral->fill($referralData);
        $referral->save();

        if (
            $referral->referral_type === MayorsReferral::TYPE_PESO_OFFICE &&
            empty($referral->ref_imus_ocrl) &&
            $referral->canPrint()
        ) {
            $referral->ref_imus_ocrl = MayorsReferral::generateNextImusOcrl();
            $referral->save();
        }

        if (
            $referral->referral_type === MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT &&
            empty($referral->ref_ocrl) &&
            $referral->canPrint()
        ) {
            $referral->ref_ocrl = MayorsReferral::generateNextOcrl();
            $referral->save();
        }

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

    private function searchPhilippineMayors(string $search, string $cityGovernment = ''): Collection
    {
        $directoryResults = collect(Config::get('philippine_mayors', []))
            ->filter(function (array $mayor) use ($search, $cityGovernment) {
                $matchesSearch = $search === ''
                    || str_contains(Str::lower($mayor['recipient']), Str::lower($search));
                $matchesCity = $cityGovernment === ''
                    || str_contains(Str::lower($mayor['city_government']), Str::lower($cityGovernment));

                return $matchesSearch && $matchesCity;
            })
            ->map(fn (array $mayor) => [
                'recipient' => $mayor['recipient'],
                'city_government' => $mayor['city_government'],
                'company_address' => $mayor['company_address'],
            ]);

        $savedResults = MayorsReferral::query()
            ->where('referral_type', MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT)
            ->whereNotNull('ref_recipient')
            ->whereNotNull('ref_city_gov')
            ->whereNotNull('ref_company_address')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('ref_recipient', 'like', '%'.$search.'%');
            })
            ->when($cityGovernment !== '', function ($query) use ($cityGovernment) {
                $query->where('ref_city_gov', 'like', '%'.$cityGovernment.'%');
            })
            ->select('ref_recipient', 'ref_city_gov', 'ref_company_address')
            ->distinct()
            ->orderBy('ref_recipient')
            ->limit(20)
            ->get()
            ->map(fn (MayorsReferral $referral) => [
                'recipient' => $referral->ref_recipient,
                'city_government' => $referral->ref_city_gov,
                'company_address' => $referral->ref_company_address,
            ]);

        return $directoryResults
            ->merge($savedResults)
            ->unique(fn (array $mayor) => Str::lower($mayor['recipient']).'|'.Str::lower($mayor['city_government']))
            ->sortBy('recipient')
            ->values()
            ->take(20);
    }

    public function printLetter($id)
    {
        $applicant = Applicant::with('referral')->findOrFail($id);

        if (! $applicant->referral || ! $applicant->referral->canPrint()) {
            return back()->with('error', 'Referral is not complete.');
        }

        if (
            $applicant->referral->referral_type === MayorsReferral::TYPE_PESO_OFFICE &&
            empty($applicant->referral->ref_imus_ocrl)
        ) {
            $applicant->referral->ref_imus_ocrl = MayorsReferral::generateNextImusOcrl();
            $applicant->referral->save();
            $applicant->load('referral');
        }

        if (
            $applicant->referral->referral_type === MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT &&
            empty($applicant->referral->ref_ocrl)
        ) {
            $applicant->referral->ref_ocrl = MayorsReferral::generateNextOcrl();
            $applicant->referral->save();
            $applicant->load('referral');
        }

        ActivityLogger::log(
            'referral',
            'generated',
            'Generated the mayor\'s referral letter.',
            $applicant,
            null,
            request()->user()
        );

        $view = $applicant->referral->referral_type === MayorsReferral::TYPE_OTHER_CITY_GOVERNMENT
            ? 'referral.other-city-municipality'
            : 'referral.letter';

        return view($view, compact('applicant'));
    }
}
