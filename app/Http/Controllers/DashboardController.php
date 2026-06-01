<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Applicant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user?->role === User::ROLE_USER) {
            $applicant = $user->linkedApplicant();

            abort_if(! $applicant, 403, 'Your account is not linked to an applicant record.');

            $applicant->loadMissing(['permit', 'clearance', 'referral']);

            return view('applicants.edit', compact('applicant'));
        }

        $menuItems = Config::get('menu');
        $applicants = Applicant::with(['permit', 'clearance', 'referral'])
            ->withoutTrashed()
            ->latest()
            ->get();
        $yearlyApplicantTrends = Applicant::query()
            ->withoutTrashed()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        $trendMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $trendYears = $yearlyApplicantTrends
            ->pluck('year')
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->values();

        $monthlyRegistrationYears = $trendYears->slice(max($trendYears->count() - 2, 0))->values();

        $trendPalette = [
            ['border' => '#ff5c7a', 'background' => 'rgba(255, 92, 122, 0.14)'],
            ['border' => '#3b82f6', 'background' => 'rgba(59, 130, 246, 0.14)'],
            ['border' => '#16a34a', 'background' => 'rgba(22, 163, 74, 0.14)'],
            ['border' => '#7c3aed', 'background' => 'rgba(124, 58, 237, 0.14)'],
            ['border' => '#ea580c', 'background' => 'rgba(234, 88, 12, 0.14)'],
            ['border' => '#0f766e', 'background' => 'rgba(15, 118, 110, 0.14)'],
        ];

        $yearlyApplicantTrendDatasets = $trendYears->map(function (int $year, int $index) use ($yearlyApplicantTrends, $trendPalette) {
            $palette = $trendPalette[$index % count($trendPalette)];
            $yearRows = $yearlyApplicantTrends
                ->where('year', $year)
                ->keyBy('month');

            $data = collect(range(1, 12))
                ->map(function (int $month) use ($yearRows) {
                    return (int) ($yearRows->get($month)?->total ?? 0);
                })
                ->values();

            return [
                'label' => (string) $year,
                'data' => $data,
                'borderColor' => $palette['border'],
                'backgroundColor' => $palette['background'],
                'pointBackgroundColor' => $palette['border'],
                'pointBorderColor' => '#ffffff',
                'fill' => false,
            ];
        })->values();

        $monthlyRegistrationDatasets = $monthlyRegistrationYears->map(function (int $year, int $index) use ($yearlyApplicantTrends, $trendPalette) {
            $palette = $trendPalette[$index % count($trendPalette)];
            $yearRows = $yearlyApplicantTrends
                ->where('year', $year)
                ->keyBy('month');

            $data = collect(range(1, 12))
                ->map(function (int $month) use ($yearRows) {
                    return (int) ($yearRows->get($month)?->total ?? 0);
                })
                ->values();

            return [
                'label' => (string) $year,
                'data' => $data,
                'borderColor' => $palette['border'],
                'backgroundColor' => $palette['background'],
                'pointBackgroundColor' => $palette['border'],
                'pointBorderColor' => '#ffffff',
                'fill' => false,
                'tension' => 0.35,
            ];
        })->values();

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

        $activeApplicantsCount = $applicants->count();
        $totalApplicants = Applicant::withTrashed()->count();
        $newThisMonth = Applicant::query()
            ->withoutTrashed()
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
                'percent' => $activeApplicantsCount > 0 ? (int) round(($completePermitCount / $activeApplicantsCount) * 100) : 0,
            ],
            'clearance' => [
                'count' => $completeClearanceCount,
                'percent' => $activeApplicantsCount > 0 ? (int) round(($completeClearanceCount / $activeApplicantsCount) * 100) : 0,
            ],
            'referral' => [
                'count' => $completeReferralCount,
                'percent' => $activeApplicantsCount > 0 ? (int) round(($completeReferralCount / $activeApplicantsCount) * 100) : 0,
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
        $maxMonthlyApplicants = max(
            $yearlyApplicantTrendDatasets
                ->pluck('data')
                ->flatten()
                ->max() ?? 0,
            1
        );
        $monthlyRegistrationValues = $monthlyRegistrationDatasets
            ->pluck('data')
            ->flatten()
            ->filter(fn ($value) => $value > 0);
        $monthlyRegistrationMin = max(($monthlyRegistrationValues->min() ?? 0) - 2, 0);
        $monthlyRegistrationMax = max(($monthlyRegistrationDatasets->pluck('data')->flatten()->max() ?? 0) + 2, 1);
        $maxGenderApplicants = max($genderBreakdown->max('count') ?? 0, 1);
        $maxCityApplicants = max($cityBreakdown->max('count') ?? 0, 1);
        $maxProvinceApplicants = max($provinceBreakdown->max('count') ?? 0, 1);
        $maxPwdApplicants = max($pwdBreakdown->max('count') ?? 0, 1);
        $maxFourPsApplicants = max($fourPsBreakdown->max('count') ?? 0, 1);

        return view('dashboard', compact(
            'menuItems',
            'summary',
            'completion',
            'trendMonths',
            'trendYears',
            'yearlyApplicantTrendDatasets',
            'monthlyRegistrationYears',
            'monthlyRegistrationDatasets',
            'monthlyRegistrationMin',
            'monthlyRegistrationMax',
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
