<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ApplicantEducationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));

        $configuredOptions = collect(Config::get('educational_attainments', []))
            ->filter(fn (string $option) => $search === '' || Str::contains(Str::lower($option), Str::lower($search)))
            ->values();

        $savedOptions = Applicant::query()
            ->whereNotNull('educational_attainment')
            ->where('educational_attainment', '!=', '')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('educational_attainment', 'like', '%'.$search.'%');
            })
            ->distinct()
            ->orderBy('educational_attainment')
            ->limit(20)
            ->pluck('educational_attainment');

        $results = $configuredOptions
            ->merge($savedOptions)
            ->unique()
            ->sort()
            ->values()
            ->take(20)
            ->map(fn (string $option) => [
                'id' => $option,
                'text' => $option,
            ]);

        return response()->json([
            'results' => $results,
        ]);
    }
}
