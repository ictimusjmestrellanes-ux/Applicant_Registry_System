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

        $totalApplicants = $applicants->count();
        $newThisMonth = Applicant::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $archivedApplicants = Applicant::onlyTrashed()->count();

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
            'archivedApplicants' => $archivedApplicants,
            'fullyReadyCount' => $fullyReadyCount,
            'totalUsers' => User::count(),
        ];

        $recentApplicants = $applicants->take(5);
        $recentActivity = ActivityLog::with(['applicant', 'causer'])
            ->latest()
            ->take(6)
            ->get();
        $maxMonthlyApplicants = max($monthlyApplicants->max('count') ?? 0, 1);

        return view('dashboard', compact(
            'menuItems',
            'summary',
            'completion',
            'chartYear',
            'monthlyApplicants',
            'maxMonthlyApplicants',
            'recentApplicants',
            'recentActivity'
        ));
    }
}
