<?php

namespace Tests\Feature;

use Tests\TestCase;

class PermitIssuedAtCityGovernmentApiTest extends TestCase
{
    public function test_it_returns_only_cavite_and_ncr_city_and_municipality_places(): void
    {
        $response = $this->getJson('/api/permit-issued-at/city-governments');

        $response->assertOk();

        $actual = collect($response->json('results'))
            ->pluck('id')
            ->sort()
            ->values()
            ->all();

        $expected = [
            'ALFONSO',
            'AMADEO',
            'CITY OF BACOOR',
            'CITY OF CALOOCAN',
            'CITY OF CARMONA',
            'CITY OF CAVITE',
            'CITY OF DASMARINAS',
            'GENERAL EMILIO AGUINALDO',
            'GENERAL MARIANO ALVAREZ',
            'CITY OF GENERAL TRIAS',
            'INDANG',
            'CITY OF IMUS',
            'KAWIT',
            'CITY OF LAS PINAS',
            'MAGALLANES',
            'CITY OF MAKATI',
            'CITY OF MALABON',
            'CITY OF MANDALUYONG',
            'CITY OF MANILA',
            'MARAGONDON',
            'CITY OF MARIKINA',
            'MENDEZ',
            'CITY OF MUNTINLUPA',
            'NAIC',
            'CITY OF NAVOTAS',
            'NOVELETA',
            'PATEROS',
            'CITY OF PARANAQUE',
            'CITY OF PASAY',
            'CITY OF PASIG',
            'CITY OF QUEZON CITY',
            'ROSARIO',
            'CITY OF SAN JUAN',
            'SILANG',
            'CITY OF TAGAYTAY',
            'CITY OF TAGUIG',
            'TANZA',
            'TERNATE',
            'CITY OF TRECE MARTIRES',
            'CITY OF VALENZUELA',
        ];

        sort($expected);

        $this->assertSame($expected, $actual);
    }

    public function test_it_filters_results_for_cavite_and_ncr_places(): void
    {
        $caviteResponse = $this->getJson('/api/permit-issued-at/city-governments?q=kawit');
        $caviteResponse->assertOk();

        $this->assertSame(
            ['KAWIT'],
            collect($caviteResponse->json('results'))->pluck('id')->all()
        );

        $ncrResponse = $this->getJson('/api/permit-issued-at/city-governments?q=pateros');
        $ncrResponse->assertOk();

        $this->assertSame(
            ['PATEROS'],
            collect($ncrResponse->json('results'))->pluck('id')->all()
        );
    }
}
