<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Applicant;
use App\Models\MayorsPermit;
use App\Models\MayorsClearance;
use App\Models\MayorsReferral;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\XlsxWriter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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

            $applicant->loadMissing(['permit', 'permits', 'clearance', 'referral']);

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
            ['border' => '#7c3aed', 'background' => 'rgba(255, 92, 122, 0.14)'],
            ['border' => '#3b82f6', 'background' => 'rgba(59, 130, 246, 0.14)'],
            ['border' => '#16a34a', 'background' => 'rgba(22, 163, 74, 0.14)'],
            ['border' => '#535353', 'background' => 'rgba(124, 58, 237, 0.14)'],
            ['border' => '#feffff', 'background' => 'rgba(234, 88, 12, 0.14)'],
            ['border' => '#f0ff25', 'background' => 'rgba(15, 118, 110, 0.14)'],
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

        $genderBreakdown = $this->breakdownCounts(
            $applicants,
            ['MALE', 'FEMALE'],
            fn (Applicant $applicant) => $this->normalizeValue($applicant->gender, 'UNSPECIFIED')
        );
        $pwdBreakdown = $this->breakdownCounts(
            $applicants,
            ['YES', 'NO'],
            fn (Applicant $applicant) => $this->normalizeValue($applicant->pwd, 'NO')
        );
        $fourPsBreakdown = $this->breakdownCounts(
            $applicants,
            ['YES', 'NO'],
            fn (Applicant $applicant) => $this->normalizeValue($applicant->four_ps, 'NO')
        );

        $cityBreakdown = $applicants
            ->groupBy(fn (Applicant $applicant) => $this->normalizeValue($applicant->city))
            ->map(fn ($group, $label) => [
                'label' => $label,
                'count' => $group->count(),
            ])
            ->sortByDesc('count')
            ->take(10)
            ->values();

        $provinceBreakdown = $applicants
            ->groupBy(fn (Applicant $applicant) => $this->normalizeValue($applicant->province))
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

        $totalPermits = MayorsPermit::query()
            ->whereHas('applicant', function ($query) {
                $query->withoutTrashed();
            })
            ->count();
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
            'totalArchivedApplicants' => Applicant::onlyTrashed()->count(),
            'totalPermits' => $totalPermits,
            'newThisMonth' => $newThisMonth,
            'totalClearances' => $totalClearances,
            'totalReferrals' => $totalReferrals,
            'fullyReadyCount' => $fullyReadyCount,
            'totalUsers' => User::count(),
            'permitsToday' => MayorsPermit::whereDate('created_at', today())->count(),
            'clearancesToday' => \App\Models\MayorsClearance::whereDate('created_at', today())->count(),
            'referralsToday' => \App\Models\MayorsReferral::whereDate('created_at', today())->count(),
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

        $trendDataByPeriod = [
            'year' => $this->registrationTrend('year'),
            'month' => $this->registrationTrend('month'),
            'week' => $this->registrationTrend('week'),
            'day' => $this->registrationTrend('day', 90),
        ];

        $breakdownDataByPeriod = [
            'year' => $this->breakdownsForPeriod($applicants, now()->startOfYear()),
            'month' => $this->breakdownsForPeriod($applicants, now()->startOfMonth()),
            'week' => $this->breakdownsForPeriod($applicants, now()->startOfWeek()),
            'day' => $this->breakdownsForPeriod($applicants, now()->startOfDay()),
        ];

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
            'recentActivity',
            'trendDataByPeriod',
            'breakdownDataByPeriod'
        ));
    }

    public function exportCharts(Request $request)
    {
        $user = Auth::user();

        abort_if(! $user || $user->role === User::ROLE_USER, 403);

        $period = $request->query('period', 'month');

        abort_if(! in_array($period, ['year', 'month', 'week', 'day'], true), 422);

        $trend = $this->registrationTrend($period);
        $trendRows = [];

        if (count($trend['datasets']) > 1) {
            $trendRows[] = ['Year', 'Month', 'Registrations'];

            foreach ($trend['datasets'] as $dataset) {
                foreach ($trend['labels'] as $index => $label) {
                    $trendRows[] = [(string) $dataset['label'], $label, (int) ($dataset['data'][$index] ?? 0)];
                }
            }
        } else {
            $trendRows[] = ['Period', 'Registrations'];
            $data = $trend['datasets'][0]['data'] ?? [];

            foreach ($trend['labels'] as $index => $label) {
                $trendRows[] = [$label, (int) ($data[$index] ?? 0)];
            }
        }

        $sheets = [
            'Registration Trend' => ['rows' => $trendRows],
        ];

        $applicants = Applicant::with(['permit', 'clearance', 'referral'])
            ->withoutTrashed()
            ->latest()
            ->get();

        $summaryLabels = [
            'Total Applicants' => Applicant::withTrashed()->count(),
            'Total Archived Applicants' => Applicant::onlyTrashed()->count(),
            'Total Permits' => MayorsPermit::query()
                ->whereHas('applicant', fn ($query) => $query->withoutTrashed())
                ->count(),
            'New This Month' => Applicant::query()
                ->withoutTrashed()
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'Total Clearances' => Applicant::query()
                ->whereHas('clearance', fn ($query) => $query->whereNotNull('clearance_peso_control_no'))
                ->count(),
            'Total Referrals' => $applicants->sum(function (Applicant $applicant) {
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
            }),
            'Fully Ready Applicants' => $applicants->filter(
                fn (Applicant $applicant) => $applicant->isPermitComplete()
                    && $applicant->isClearanceComplete()
                    && $applicant->isReferralComplete()
            )->count(),
            'Total Users' => User::count(),
            'Permits Today' => MayorsPermit::whereDate('created_at', today())->count(),
            'Clearances Today' => MayorsClearance::whereDate('created_at', today())->count(),
            'Referrals Today' => MayorsReferral::whereDate('created_at', today())->count(),
        ];

        $summaryRows = [['Metric', 'Value']];

        foreach ($summaryLabels as $label => $value) {
            $summaryRows[] = [$label, (int) $value];
        }

        $sheets['Summary'] = ['rows' => $summaryRows];

        $start = match ($period) {
            'year' => now()->startOfYear(),
            'month' => now()->startOfMonth(),
            'week' => now()->startOfWeek(),
            'day' => now()->startOfDay(),
        };

        $breakdown = $this->breakdownsForPeriod($applicants, $start);

        foreach ([
            'Gender' => $breakdown['sex'],
            'PWD' => $breakdown['pwd'],
            '4Ps' => $breakdown['fourPs'],
            'City' => $breakdown['city'],
            'Province' => $breakdown['province'],
        ] as $sheetName => $series) {
            $rows = [['Label', 'Count']];

            foreach ($series['labels'] as $index => $label) {
                $rows[] = [(string) $label, (int) ($series['data'][$index] ?? 0)];
            }

            $sheets[$sheetName] = ['rows' => $rows];
        }

        $xlsxPath = XlsxWriter::create('dashboard_export_', $sheets);
        $fileName = 'dashboard-export-'.now()->format('Y-m-d-His').'.xlsx';

        ActivityLogger::log(
            'dashboard',
            'exported',
            "Exported dashboard data ({$period} registration trend) to {$fileName}.",
            null,
            null,
            $user
        );

        return response()->download($xlsxPath, $fileName)->deleteFileAfterSend(true);
    }

    protected function normalizeValue(mixed $value, string $fallback = 'UNSPECIFIED'): string
    {
        $value = is_string($value) ? trim($value) : '';

        if ($value === '') {
            return $fallback;
        }

        return strtoupper(preg_replace('/\s+/', ' ', $value));
    }

    protected function breakdownCounts(Collection $applicants, array $labels, callable $resolver): Collection
    {
        return collect($labels)->map(function (string $label) use ($applicants, $resolver) {
            return [
                'label' => $label,
                'count' => $applicants->filter(fn (Applicant $applicant) => $resolver($applicant) === $label)->count(),
            ];
        });
    }

    protected function breakdownsForPeriod(Collection $applicants, Carbon $start): array
    {
        $filtered = $applicants->filter(fn (Applicant $applicant) => $applicant->created_at->gte($start));

        return [
            'sex' => $this->breakdownSeries(
                $filtered,
                ['Male', 'Female'],
                fn (Applicant $applicant) => ucfirst(strtolower($this->normalizeValue($applicant->gender, 'UNSPECIFIED')))
            ),
            'pwd' => $this->breakdownSeries(
                $filtered,
                ['PWD YES', 'PWD NO'],
                fn (Applicant $applicant) => $this->normalizeValue($applicant->pwd, 'NO') === 'YES' ? 'PWD YES' : 'PWD NO'
            ),
            'fourPs' => $this->breakdownSeries(
                $filtered,
                ['4Ps YES', '4Ps NO'],
                fn (Applicant $applicant) => $this->normalizeValue($applicant->four_ps, 'NO') === 'YES' ? '4Ps YES' : '4Ps NO'
            ),
            'city' => $this->topBreakdown($filtered, fn (Applicant $applicant) => $this->normalizeValue($applicant->city)),
            'province' => $this->topBreakdown($filtered, fn (Applicant $applicant) => $this->normalizeValue($applicant->province)),
        ];
    }

    protected function breakdownSeries(Collection $applicants, array $labels, callable $resolver): array
    {
        $data = collect($labels)
            ->map(fn (string $label) => $applicants->filter(fn (Applicant $applicant) => $resolver($applicant) === $label)->count());

        return [
            'labels' => $labels,
            'data' => $data->all(),
        ];
    }

    protected function topBreakdown(Collection $applicants, callable $resolver): array
    {
        $grouped = $applicants
            ->groupBy($resolver)
            ->sortByDesc(fn ($group) => $group->count())
            ->take(10);

        return [
            'labels' => $grouped->keys()->all(),
            'data' => $grouped->map(fn ($group) => $group->count())->values()->all(),
        ];
    }

    protected function registrationTrend(string $period, ?int $dayLimit = null): array
    {
        $palette = [
            ['border' => '#7c3aed', 'background' => 'rgba(124, 58, 237, 0.18)'],
            ['border' => '#3b82f6', 'background' => 'rgba(59, 130, 246, 0.18)'],
            ['border' => '#16a34a', 'background' => 'rgba(22, 163, 74, 0.18)'],
            ['border' => '#f97316', 'background' => 'rgba(249, 115, 22, 0.18)'],
        ];

        $query = Applicant::query()->withoutTrashed();

        return match ($period) {
            'year' => $this->trendYearlyRegistrations($query, $palette),
            'month' => $this->trendMonthlyRegistrations($query, $palette),
            'week' => $this->trendWeeklyRegistrations($query, $palette),
            'day' => $this->trendDailyRegistrations($query, $palette, $dayLimit),
            default => abort(422),
        };
    }

    protected function trendYearlyRegistrations(Builder $query, array $palette): array
    {
        $rows = (clone $query)
            ->selectRaw('YEAR(created_at) as year, COUNT(*) as total')
            ->groupByRaw('YEAR(created_at)')
            ->orderByRaw('YEAR(created_at)')
            ->get();

        $color = $palette[0]['border'];

        return [
            'type' => 'bar',
            'labels' => $rows->map(fn ($row) => (string) (int) $row->year)->values()->all(),
            'datasets' => [[
                'label' => 'Registrations',
                'data' => $rows->map(fn ($row) => (int) $row->total)->values()->all(),
                'backgroundColor' => 'rgba(124, 58, 237, 0.55)',
                'borderColor' => $color,
                'borderWidth' => 1,
                'borderRadius' => 6,
            ]],
        ];
    }

    protected function trendMonthlyRegistrations(Builder $query, array $palette): array
    {
        $rows = (clone $query)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        $years = $rows->pluck('year')->map(fn ($year) => (int) $year)->unique()->values();
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $datasets = $years->map(function (int $year, int $index) use ($rows, $palette) {
            $yearRows = $rows->where('year', $year)->keyBy('month');
            $data = collect(range(1, 12))
                ->map(fn (int $month) => (int) ($yearRows->get($month)?->total ?? 0))
                ->values();
            $color = $palette[$index % count($palette)]['border'];

            return [
                'label' => (string) $year,
                'data' => $data,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'pointBackgroundColor' => $color,
                'pointBorderColor' => '#ffffff',
                'fill' => false,
                'tension' => 0.35,
            ];
        })->values();

        return [
            'type' => 'line',
            'labels' => $months,
            'datasets' => $datasets->all(),
        ];
    }

    protected function trendWeeklyRegistrations(Builder $query, array $palette): array
    {
        $rows = (clone $query)
            ->selectRaw('YEARWEEK(created_at, 3) as yw, COUNT(*) as total')
            ->groupBy('yw')
            ->orderBy('yw')
            ->get();

        $labels = $rows->map(function ($row) {
            $year = (int) substr((string) $row->yw, 0, 4);
            $week = (int) substr((string) $row->yw, 4);

            return (new \DateTimeImmutable)->setISODate($year, $week, 1)->format('M d, Y');
        })->values()->all();

        $color = $palette[2]['border'];

        return [
            'type' => 'line',
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Registrations',
                'data' => $rows->map(fn ($row) => (int) $row->total)->values()->all(),
                'borderColor' => $color,
                'backgroundColor' => $color,
                'pointBackgroundColor' => $color,
                'pointBorderColor' => '#ffffff',
                'fill' => false,
                'tension' => 0.35,
            ]],
        ];
    }

    protected function trendDailyRegistrations(Builder $query, array $palette, ?int $dayLimit = null): array
    {
        $rows = (clone $query)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        if ($dayLimit !== null && $rows->count() > $dayLimit) {
            $rows = $rows->slice(-$dayLimit)->values();
        }

        $color = $palette[1]['border'];

        return [
            'type' => 'line',
            'labels' => $rows->map(fn ($row) => Carbon::parse($row->date)->format('M d, Y'))->values()->all(),
            'datasets' => [[
                'label' => 'Registrations',
                'data' => $rows->map(fn ($row) => (int) $row->total)->values()->all(),
                'borderColor' => $color,
                'backgroundColor' => $color,
                'pointBackgroundColor' => $color,
                'pointBorderColor' => '#ffffff',
                'fill' => false,
                'tension' => 0.35,
            ]],
        ];
    }

    public function todayRecords(Request $request, string $type)
    {
        $user = Auth::user();
        if ($user?->role === User::ROLE_USER) {
            abort(403);
        }

        $today = today();

        return match ($type) {
            'archive' => $this->todayArchive($today),
            'permit' => $this->todayPermits($today),
            'clearance' => $this->todayClearances($today),
            'referral' => $this->todayReferrals($today),
            default => abort(404),
        };
    }

    public function allRecords(Request $request, string $type)
    {
        $user = Auth::user();
        if ($user?->role === User::ROLE_USER) {
            abort(403);
        }

        return match ($type) {
            'archive' => $this->allArchive(),
            'permit' => $this->allPermits(),
            'clearance' => $this->allClearances(),
            'referral' => $this->allReferrals(),
            'applicants' => $this->allApplicants(),
            default => abort(404),
        };
    }

    protected function todayArchive($today)
    {
        $records = Applicant::onlyTrashed()
            ->whereDate('deleted_at', $today)
            ->latest('deleted_at')
            ->get();

        $html = view('dashboard._today_archive', compact('records'))->render();

        return response()->json(['html' => $html]);
    }

    protected function todayPermits($today)
    {
        $records = MayorsPermit::with('applicant')
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $html = view('dashboard._today_permits', compact('records'))->render();

        return response()->json(['html' => $html]);
    }

    protected function todayClearances($today)
    {
        $records = MayorsClearance::with('applicant')
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $html = view('dashboard._today_clearances', compact('records'))->render();

        return response()->json(['html' => $html]);
    }

    protected function todayReferrals($today)
    {
        $records = MayorsReferral::with('applicant')
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $html = view('dashboard._today_referrals', compact('records'))->render();

        return response()->json(['html' => $html]);
    }

    protected function allArchive()
    {
        $records = Applicant::onlyTrashed()
            ->latest('deleted_at')
            ->get();

        $html = view('dashboard._all_archive', compact('records'))->render();

        return response()->json(['html' => $html]);
    }

    protected function allPermits()
    {
        $records = MayorsPermit::with('applicant')
            ->latest()
            ->get();

        $html = view('dashboard._all_permits', compact('records'))->render();

        return response()->json(['html' => $html]);
    }

    protected function allClearances()
    {
        $records = MayorsClearance::with('applicant')
            ->latest()
            ->get();

        $html = view('dashboard._all_clearances', compact('records'))->render();

        return response()->json(['html' => $html]);
    }

    protected function allReferrals()
    {
        $records = MayorsReferral::with('applicant')
            ->latest()
            ->get();

        $html = view('dashboard._all_referrals', compact('records'))->render();

        return response()->json(['html' => $html]);
    }

    protected function allApplicants()
    {
        $records = Applicant::withoutTrashed()
            ->latest()
            ->get();

        $html = view('dashboard._all_applicants', compact('records'))->render();

        return response()->json(['html' => $html]);
    }
}
