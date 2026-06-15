<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermitIssuedAtCityGovernmentController extends Controller
{
    /**
     * Return the complete CALABARZON city-government list for the Permit Issued At dropdown.
     */
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));

        $cityGovernments = collect([
            'CITY OF ANTIPOLO',
            'CITY OF BACOOR',
            'CITY OF BATANGAS',
            'CITY OF BIÑAN',
            'CITY OF CABUYAO',
            'CITY OF CALACA',
            'CITY OF CALAMBA',
            'CITY OF CAVITE',
            'CITY OF DASMARIÑAS',
            'CITY OF GENERAL TRIAS',
            'CITY OF IMUS',
            'CITY OF LIPA',
            'CITY OF LUCENA',
            'CITY OF SAN PABLO',
            'CITY OF SAN PEDRO',
            'CITY OF SANTA ROSA',
            'CITY OF SANTO TOMAS',
            'CITY OF TAGAYTAY',
            'CITY OF TANAUAN',
            'CITY OF TAYABAS',
            'CITY OF TRECE MARTIRES',
        ]);

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
