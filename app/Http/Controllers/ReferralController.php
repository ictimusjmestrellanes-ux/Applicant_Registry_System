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

        $nextImusOcrl = null;
        $allocateImusOcrl = function ($value = null) use (&$nextImusOcrl) {
            $value = trim((string) $value);

            if ($value !== '') {
                return $value;
            }

            if ($nextImusOcrl === null) {
                $nextImusOcrl = MayorsReferral::generateNextImusOcrl();
            }

            $assigned = $nextImusOcrl;
            $nextImusOcrl = $this->incrementImusOcrl($nextImusOcrl);

            return $assigned;
        };

        $referralData = [
            'referral_type' => $referralType,
        ];

        if ($referralType === MayorsReferral::TYPE_PESO_OFFICE) {
            $existingDetails = array_values(array_slice($referral->referral_details ?? [], 1));
            $submittedDetails = array_values($request->input('referral_details', []));
            $submittedFiles = array_values($request->file('referral_details', []));
            $primaryDetail = [
                'ref_employer_name' => $request->ref_employer_name,
                'ref_position' => $request->ref_position,
                'ref_place' => $request->ref_place,
                'ref_hired_company' => $request->ref_hired_company,
            ];

            $primaryHasRequirements = MayorsReferral::hasPesoDetailRequirements($primaryDetail);
            $primaryOcrl = trim((string) ($request->ref_imus_ocrl ?? $referral->ref_imus_ocrl ?? ''));

            if ($primaryHasRequirements) {
                $primaryOcrl = $allocateImusOcrl($primaryOcrl);
            }

            $primaryDetails = [
                'ref_imus_ocrl' => $primaryOcrl,
                'ref_employer_name' => $request->ref_employer_name,
                'ref_position' => $request->ref_position,
                'ref_or_no' => $request->ref_or_no,
                'ref_place' => $request->ref_place,
                'ref_hired_company' => $request->ref_hired_company,
            ];

            $supplementaryDetails = collect($submittedDetails)
                ->map(function ($detail, $index) use ($existingDetails, $allocateImusOcrl, $submittedFiles) {
                    $detail = is_array($detail) ? $detail : [];
                    $existingDetail = is_array($existingDetails[$index] ?? null) ? $existingDetails[$index] : [];
                    $uploadedAttachment = data_get($submittedFiles, $index.'.ref_attachment');

                    $attachmentPath = $existingDetail['ref_attachment'] ?? null;

                    if ($uploadedAttachment && method_exists($uploadedAttachment, 'getClientOriginalName')) {
                        if (! empty($attachmentPath)) {
                            Storage::disk('public')->delete($attachmentPath);
                        }

                        $originalName = pathinfo($uploadedAttachment->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension = $uploadedAttachment->getClientOriginalExtension();
                        $fileName = Str::slug($originalName, '_').'_'.time().'_'.$index.'.'.$extension;
                        $attachmentPath = $uploadedAttachment->storeAs('referrals/extra-details', $fileName, 'public');
                    }

                    $detailHasRequirements = MayorsReferral::hasPesoDetailRequirements($detail);
                    $detailOcrl = trim((string) ($detail['ref_imus_ocrl'] ?? $existingDetail['ref_imus_ocrl'] ?? ''));

                    if ($detailHasRequirements) {
                        $detailOcrl = $allocateImusOcrl($detailOcrl);
                    }

                    return [
                        'ref_imus_ocrl' => $detailOcrl,
                        'ref_employer_name' => trim((string) ($detail['ref_employer_name'] ?? '')),
                        'ref_position' => trim((string) ($detail['ref_position'] ?? '')),
                        'ref_place' => trim((string) ($detail['ref_place'] ?? '')),
                        'ref_hired_company' => trim((string) ($detail['ref_hired_company'] ?? '')),
                        'ref_attachment' => $attachmentPath,
                    ];
                })
                ->filter(fn (array $detail) => collect($detail)->contains(fn ($value) => trim((string) $value) !== ''))
                ->values()
                ->map(fn (array $detail) => [
                    'ref_imus_ocrl' => trim((string) ($detail['ref_imus_ocrl'] ?? '')),
                    'ref_employer_name' => $detail['ref_employer_name'] ?? '',
                    'ref_position' => $detail['ref_position'] ?? '',
                    'ref_place' => $detail['ref_place'] ?? '',
                    'ref_hired_company' => $detail['ref_hired_company'] ?? '',
                    'ref_attachment' => $detail['ref_attachment'] ?? null,
                ])
                ->all();

            $referralData = array_merge($referralData, [
                ...$primaryDetails,
                'referral_details' => array_values(array_merge([$primaryDetails], $supplementaryDetails)),
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
            ->to(route('applicants.edit', $applicant->id).'#referral')
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

    public function printLetter(Request $request, $id)
    {
        $applicant = Applicant::with('referral')->findOrFail($id);

        if (! $applicant->referral || ! $applicant->referral->canPrint()) {
            return back()->with('error', 'Referral is not complete.');
        }

        $printDetail = null;
        $detailIndex = $request->query('detail');

        if (
            $applicant->referral->referral_type === MayorsReferral::TYPE_PESO_OFFICE &&
            $detailIndex !== null &&
            $detailIndex !== ''
        ) {
            $supplementaryDetails = array_values(array_slice($applicant->referral->referral_details ?? [], 1));
            $resolvedIndex = (int) $detailIndex;
            $printDetail = $supplementaryDetails[$resolvedIndex] ?? null;

            if (! is_array($printDetail)) {
                return back()->with('error', 'Referral detail not found.');
            }

            if (! MayorsReferral::hasPrintablePesoDetail($printDetail)) {
                return back()->with('error', 'Referral detail is incomplete.');
            }
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

        return view($view, compact('applicant', 'printDetail'));
    }

    private function incrementImusOcrl(string $value): string
    {
        if (preg_match('/^(\d{4})-(\d{5})$/', $value, $matches)) {
            return sprintf('%d-%05d', (int) $matches[1], ((int) $matches[2]) + 1);
        }

        return MayorsReferral::generateNextImusOcrl();
    }
}
