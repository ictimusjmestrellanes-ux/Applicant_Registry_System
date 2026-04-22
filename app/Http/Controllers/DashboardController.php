<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Applicant;
use App\Models\User;
use Illuminate\Support\Facades\Config;

class DashboardController extends Controller
{
    public function index()
    {
        $menuItems = Config::get('menu');
        $applicants = Applicant::with(['permit', 'clearance', 'referral'])
            ->latest()
            ->get();
        $chartYear = 2026;
        $monthlyApplicants = collect(range(1, 12))
            ->map(function (int $month) use ($chartYear) {
                return [
                    'label' => now()->setMonth($month)->format('M'),
                    'count' => Applicant::query()
                        ->whereYear('created_at', $chartYear)
                        ->whereMonth('created_at', $month)
                        ->count(),
                ];
            });

        $normalizeValue = static function ($value, string $fallback = 'UNSPECIFIED') {
            $value = is_string($value) ? trim($value) : '';

            if ($value === '') {
                return $fallback;
            }

            return strtoupper(preg_replace('/\s+/', ' ', $value));
        };

        $mapBreakdown = static function ($items, array $labels, callable $resolver) {
            return collect($labels)->map(function (string $label) use ($items, $resolver) {
                return [
                    'label' => $label,
                    'count' => $items->filter(fn (Applicant $applicant) => $resolver($applicant) === $label)->count(),
                ];
            });
        };

        $genderBreakdown = $mapBreakdown(
            $applicants,
            ['MALE', 'FEMALE'],
            function (Applicant $applicant) use ($normalizeValue) {
                return $normalizeValue($applicant->gender, 'UNSPECIFIED');
            }
        );
        $pwdBreakdown = $mapBreakdown(
            $applicants,
            ['YES', 'NO'],
            function (Applicant $applicant) use ($normalizeValue) {
                return $normalizeValue($applicant->pwd, 'NO');
            }
        );
        $fourPsBreakdown = $mapBreakdown(
            $applicants,
            ['YES', 'NO'],
            function (Applicant $applicant) use ($normalizeValue) {
                return $normalizeValue($applicant->four_ps, 'NO');
            }
        );

        $cityBreakdown = $applicants
            ->groupBy(fn (Applicant $applicant) => $normalizeValue($applicant->city))
            ->map(fn ($group, $label) => [
                'label' => $label,
                'count' => $group->count(),
            ])
            ->sortByDesc('count')
            ->take(10)
            ->values();

        $provinceBreakdown = $applicants
            ->groupBy(fn (Applicant $applicant) => $normalizeValue($applicant->province))
            ->map(fn ($group, $label) => [
                'label' => $label,
                'count' => $group->count(),
            ])
            ->sortByDesc('count')
            ->take(10)
            ->values();

        $totalApplicants = $applicants->count();
        $newThisMonth = Applicant::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $totalClearances = Applicant::query()
            ->whereHas('clearance', function ($query) {
                $query->whereNotNull('clearance_peso_control_no');
            })
            ->count();
        $totalReferrals = $applicants->sum(function (Applicant $applicant) {
            $referral = $applicant->referral;

            if (! $referral) {
                return 0;
            }

            $count = ! empty($referral->ref_imus_ocrl) ? 1 : 0;
            $details = is_array($referral->referral_details ?? null) ? array_slice($referral->referral_details, 1) : [];

            foreach ($details as $detail) {
                $detail = is_array($detail) ? $detail : [];

                if (! empty(trim((string) ($detail['ref_imus_ocrl'] ?? '')))) {
                    $count += 1;
                }
            }

            return $count;
        });

        $completePermitCount = $applicants->filter(fn (Applicant $applicant) => $applicant->isPermitComplete())->count();
        $completeClearanceCount = $applicants->filter(fn (Applicant $applicant) => $applicant->isClearanceComplete())->count();
        $completeReferralCount = $applicants->filter(fn (Applicant $applicant) => $applicant->isReferralComplete())->count();
        $fullyReadyCount = $applicants->filter(
            fn (Applicant $applicant) => $applicant->isPermitComplete()
                && $applicant->isClearanceComplete()
                && $applicant->isReferralComplete()
        )->count();

        $completion = [
            'permit' => [
                'count' => $completePermitCount,
                'percent' => $totalApplicants > 0 ? (int) round(($completePermitCount / $totalApplicants) * 100) : 0,
            ],
            'clearance' => [
                'count' => $completeClearanceCount,
                'percent' => $totalApplicants > 0 ? (int) round(($completeClearanceCount / $totalApplicants) * 100) : 0,
            ],
            'referral' => [
                'count' => $completeReferralCount,
                'percent' => $totalApplicants > 0 ? (int) round(($completeReferralCount / $totalApplicants) * 100) : 0,
            ],
        ];

        $summary = [
            'totalApplicants' => $totalApplicants,
            'newThisMonth' => $newThisMonth,
            'totalClearances' => $totalClearances,
            'totalReferrals' => $totalReferrals,
            'fullyReadyCount' => $fullyReadyCount,
            'totalUsers' => User::count(),
        ];

        $recentApplicants = $applicants->take(5);
        $recentActivity = ActivityLog::with(['applicant', 'causer'])
            ->latest()
            ->take(6)
            ->get();
        $maxMonthlyApplicants = max($monthlyApplicants->max('count') ?? 0, 1);
        $maxGenderApplicants = max($genderBreakdown->max('count') ?? 0, 1);
        $maxCityApplicants = max($cityBreakdown->max('count') ?? 0, 1);
        $maxProvinceApplicants = max($provinceBreakdown->max('count') ?? 0, 1);
        $maxPwdApplicants = max($pwdBreakdown->max('count') ?? 0, 1);
        $maxFourPsApplicants = max($fourPsBreakdown->max('count') ?? 0, 1);

        return view('dashboard', compact(
            'menuItems',
            'summary',
            'completion',
            'chartYear',
            'monthlyApplicants',
            'maxMonthlyApplicants',
            'genderBreakdown',
            'maxGenderApplicants',
            'cityBreakdown',
            'maxCityApplicants',
            'provinceBreakdown',
            'maxProvinceApplicants',
            'pwdBreakdown',
            'maxPwdApplicants',
            'fourPsBreakdown',
            'maxFourPsApplicants',
            'recentApplicants',
            'recentActivity'
        ));
    }
}
