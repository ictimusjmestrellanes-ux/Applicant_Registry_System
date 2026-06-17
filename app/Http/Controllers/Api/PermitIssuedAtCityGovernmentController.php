<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class PermitIssuedAtCityGovernmentController extends Controller
{
    /**
     * Return the Cavite + NCR city/municipality list for the Permit Issued At dropdown.
     */
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));

        $cityGovernments = collect(Config::get('permit_issued_at.city_governments', []))
            ->map(fn (string $cityGovernment) => strtoupper(trim($cityGovernment)))
            ->filter()
            ->values();

        $results = $cityGovernments
            ->filter(function (string $cityGovernment) use ($search) {
                if ($search === '') {
                    return true;
                }

                return Str::contains(Str::lower($cityGovernment), Str::lower($search));
            })
            ->values()
            ->map(function (string $cityGovernment) {
                return [
                    'id' => $cityGovernment,
                    'text' => $cityGovernment,
                ];
            });

        return response()->json([
            'results' => $results,
        ]);
    }
}
