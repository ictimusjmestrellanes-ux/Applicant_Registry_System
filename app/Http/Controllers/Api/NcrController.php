<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class NcrController extends Controller
{
    /**
     * Return list of NCR cities/municipalities.
     */
    public function cities(Request $request): JsonResponse
    {
        $cities = [
            'CITY OF MANILA',
            'CITY OF QUEZON',
            'CITY OF CALOOCAN',
            'CITY OF LAS PINAS',
            'CITY OF MAKATI',
            'CITY OF MALABON',
            'CITY OF MANDALUYONG',
            'CITY OF MARIKINA',
            'CITY OF MUNTINLUPA',
            'CITY OF NAVOTAS',
            'CITY OF PARANAQUE',
            'CITY OF PASAY',
            'CITY OF PASIG',
            'CITY OF SAN JUAN',
            'CITY OF TAGUIG',
            'CITY OF VALENZUELA',
            'PATEROS'
        ];

        return response()->json(array_values($cities));
    }

    /**
     * Return barangays for a given NCR city/municipality.
     * Query param: `city` (e.g. "MANILA CITY" or "QUEZON CITY")
     */
    public function barangays(Request $request): JsonResponse
    {
        $cityParam = (string) $request->query('city', '');
        $cityParam = trim(strtoupper($cityParam));

        if ($cityParam === '') {
            return response()->json([]);
        }

        // Local override for Caloocan barangays to ensure exact expected names
        $localBarangays = $this->getLocalBarangays($cityParam);
        if (!empty($localBarangays)) {
            return response()->json($localBarangays);
        }

        // PSGC province code for NCR
        $provinceCode = '130000000';

        try {
            $res = Http::timeout(5)->get("https://psgc.gitlab.io/api/provinces/{$provinceCode}/cities-municipalities/");

            if (!$res->successful()) {
                return response()->json([]);
            }

            $cities = $res->json() ?: [];
            $matchedCode = null;

            // Normalize helper for comparison
            $normalize = function ($v) {
                $v = (string) ($v ?? '');
                $v = preg_replace('/^\s*(city of|municipality of)\s+/i', '', $v);
                $v = preg_replace('/\(.*?\)/', '', $v); // remove parens
                $v = trim($v);
                $v = strtoupper($v);
                return $v;
            };

            $paramNorm = $normalize($cityParam);
            $paramVariants = [$paramNorm];
            if (!str_ends_with($paramNorm, ' CITY')) {
                $paramVariants[] = $paramNorm . ' CITY';
            }
            if (str_ends_with($paramNorm, ' CITY')) {
                $paramVariants[] = preg_replace('/\s+CITY$/', '', $paramNorm);
            }

            foreach ($cities as $c) {
                $rawName = $c['name'] ?? $c['description'] ?? '';
                $clean = $normalize($rawName);
                $isCity = preg_match('/city/i', $rawName) === 1;
                $nameVariant = $clean;
                if ($isCity && !preg_match('/\bCITY$/', $clean)) {
                    $nameVariant = $clean . ' CITY';
                }

                $candidates = [$clean, $nameVariant];
                if (str_ends_with($clean, ' CITY')) {
                    $candidates[] = preg_replace('/\s+CITY$/', '', $clean);
                }

                // compare normalized variants
                foreach ($paramVariants as $pv) {
                    foreach ($candidates as $cand) {
                        if ($pv === $cand) {
                            $matchedCode = $c['code'] ?? null;
                            break 3;
                        }
                    }
                }
            }

            if (!$matchedCode) {
                return response()->json([]);
            }

            $bRes = Http::timeout(5)->get("https://psgc.gitlab.io/api/cities-municipalities/" . urlencode($matchedCode) . "/barangays/");
            if (!$bRes->successful()) {
                return response()->json([]);
            }

            $barangays = $bRes->json() ?: [];
            $names = array_map(function ($b) use ($cityParam) {
                $raw = $b['name'] ?? '';
                $name = preg_replace('/\(\s*POB\.?\s*\)/i', '', $raw);
                $name = trim(strtoupper($name));

                // keep compatibility with client remapping rules (no Bacoor special-case needed for NCR)
                return $name;
            }, $barangays);

            sort($names, SORT_STRING | SORT_FLAG_CASE);

            return response()->json(array_values($names));
        } catch (\Throwable $e) {
            return response()->json([]);
        }
    }

    private function getLocalBarangays(string $cityParam): array
    {
        $caloocanNames = [
            'CALOOCAN',
            'CALOOCAN CITY',
            'CITY OF CALOOCAN',
        ];

        if (in_array($cityParam, $caloocanNames, true)) {
            return $this->generateCaloocanBarangays();
        }

        $lasPinasNames = [
            'LAS PINAS',
            'LAS PINAS CITY',
            'LAS PINAS, CITY',
            'CITY OF LAS PINAS',
        ];

        if (in_array($cityParam, $lasPinasNames, true)) {
            return $this->generateLasPinasBarangays();
        }

        $makatiNames = [
            'MAKATI',
            'MAKATI CITY',
            'CITY OF MAKATI',
        ];

        if (in_array($cityParam, $makatiNames, true)) {
            return $this->generateMakatiBarangays();
        }

        $malabonNames = [
            'MALABON',
            'MALABON CITY',
            'CITY OF MALABON',
        ];

        if (in_array($cityParam, $malabonNames, true)) {
            return $this->generateMalabonBarangays();
        }

        $manilaNames = [
            'MANILA',
            'MANILA CITY',
            'CITY OF MANILA',
        ];

        if (in_array($cityParam, $manilaNames, true)) {
            return $this->generateManilaBarangays();
        }

        $mandaluyongNames = [
            'MANDALUYONG',
            'MANDALUYONG CITY',
            'CITY OF MANDALUYONG',
        ];

        if (in_array($cityParam, $mandaluyongNames, true)) {
            return $this->generateMandaluyongBarangays();
        }

        $makatiNames2 = [
            'MARIKINA',
            'MARIKINA CITY',
            'CITY OF MARIKINA',
        ];

        if (in_array($cityParam, $makatiNames2, true)) {
            return $this->generateMarikinajBarangays();
        }

        $muntinlupaNames = [
            'MUNTINLUPA',
            'MUNTINLUPA CITY',
            'CITY OF MUNTINLUPA',
        ];

        if (in_array($cityParam, $muntinlupaNames, true)) {
            return $this->generateMuntinlupaBarangays();
        }

        $navotasNames = [
            'NAVOTAS',
            'NAVOTAS CITY',
            'CITY OF NAVOTAS',
        ];

        if (in_array($cityParam, $navotasNames, true)) {
            return $this->generateNavotasBarangays();
        }

        $parañaqueNames = [
            'PARANAQUE',
            'PARANAQUE CITY',
            'PARANAQUE, CITY',
            'CITY OF PARANAQUE',
        ];

        if (in_array($cityParam, $parañaqueNames, true)) {
            return $this->generateParañaqueBarangays();
        }

        $pasayNames = [
            'PASAY',
            'PASAY CITY',
            'CITY OF PASAY',
        ];
        
        if (in_array($cityParam, $pasayNames, true)) {
            return $this->generatePasayBarangays();
        }

        $pasigNames = [
            'PASIG',
            'PASIG CITY',
            'CITY OF PASIG',
        ];

        if (in_array($cityParam, $pasigNames, true)) {
            return $this->generatePasigBarangays();
        }

        $sanJuanNames = [
            'SAN JUAN',
            'SAN JUAN CITY',
            'CITY OF SAN JUAN',
        ];

        if (in_array($cityParam, $sanJuanNames, true)) {
            return $this->generateSanJuanBarangays();
        }

        $taguigNames = [
            'TAGUIG',
            'TAGUIG CITY',
            'CITY OF TAGUIG',
        ];

        if (in_array($cityParam, $taguigNames, true)) {
            return $this->generateTaguigBarangays();
        }

        $quezonCityNames = [
            'QUEZON',
            'QUEZON CITY',
            'QUEZON, CITY',
            'CITY OF QUEZON',
        ];

        if (in_array($cityParam, $quezonCityNames, true)) {
            return $this->generateQuezonCityBarangays();
        }

        $valenzuelaNames = [
            'VALENZUELA',
            'VALENZUELA CITY',
            'CITY OF VALENZUELA',
        ];

        if (in_array($cityParam, $valenzuelaNames, true)) {
            return $this->generateValenzuelaBarangays();
        }

        $paterosNames = [
            'PATEROS',
            'PATEROS MUNICIPALITY',
        ];

        if (in_array($cityParam, $paterosNames, true)) {
            return $this->generatePaterosBarangays();
        }

        return [];
    }

    private function generateCaloocanBarangays(): array
    {
        $barangays = [];

        for ($i = 1; $i <= 188; $i++) {
            $barangays[] = 'BARANGAY ' . $i;
        }

        return $barangays;
    }

    private function generateLasPinasBarangays(): array
    {
        return [
                'ALMANZA DOS',
                'ALMANZA UNO',
                'B. F. INTERNATIONAL VILLAGE',
                'DANIEL FAJARDO',
                'ELIAS ALDANA',
                'ILAYA',
                'MANUYO DOS',
                'MANUYO UNO',
                'PAMPLONA DOS',
                'PAMPLONA TRES',
                'PAMPLONA UNO',
                'PILAR',
                'PULANG LUPA DOS',
                'PULANG LUPA UNO',
                'TALON DOS',
                'TALON KUATRO',
                'TALON SINGKO',
                'TALON TRES',
                'TALON UNO',
                'ZAPOTE',
        ];
    }

    private function generateMakatiBarangays(): array
    {
        return [
            'BANGKAL',
            'BEL-AIR',
            'CARMONA',
            'CEMBO',
            'COMEMBO',
            'DASMARIÑAS',
            'EAST REMBO',
            'FORBES PARK',
            'GUADALUPE NUEVO',
            'GUADALUPE VIEJO',
            'KASILAWAN',
            'LA PAZ',
            'MAGALLANES',
            'OLYMPIA',
            'PALANAN',
            'PEMBO',
            'PINAGKAISAHAN',
            'PIO DEL PILAR',
            'PITOGO',
            'POBLACION',
            'POST PROPER NORTHSIDE',
            'POST PROPER SOUTHSIDE',
            'RIZAL',
            'SAN ANTONIO',
            'SAN ISIDRO',
            'SAN LORENZO',
            'SANTA CRUZ',
            'SINGKAMAS',
            'SOUTH CEMBO',
            'TEJEROS',
            'URDANETA',
            'VALENZUELA',
            'WEST REMBO',
        ];
    }

    private function generateMalabonBarangays(): array
    {
        return [
            'ACACIA',
            'BARITAN',
            'BAYAN-BAYANAN',
            'CATMON',
            'CONCEPCION',
            'DAMPALIT',
            'FLORES',
            'HULONG DUHAT',
            'IBABA',
            'LONGOS',
            'MAYSILO',
            'MUZON',
            'NIUGAN',
            'PANGHULO',
            'POTRERO',
            'SAN AGUSTIN',
            'SANTOLAN',
            'TAÑONG',
            'TINAJEROS',
            'TONSUYA',
            'TUGATOG',
        ];
    }

    private function generateManilaBarangays(): array
    {
        $barangays = [];

        for ($i = 1; $i <= 905; $i++) {
            $barangays[] = 'BARANGAY ' . $i;
        }

        return $barangays;
    }

    private function generateMandaluyongBarangays(): array
    {
        return [
            'ADDITION HILLS',
            'BAGONG SILANG',
            'BARANGKA DRIVE',
            'BARANGKA IBABA',
            'BARANGKA ILAYA',
            'BARANGKA ITAAS',
            'BUAYANG BATO',
            'BUROL',
            'DAANG BAKAL',
            'HAGDANG BATO ITAAS',
            'HAGDANG BATO LIBIS',
            'HARAPIN ANG BUKAS',
            'HIGHWAY HILLS',
            'HULO',
            'MABINI-J. RIZAL',
            'MALAMIG',
            'MAUWAY',
            'NAMAYAN',
            'NEW ZAÑIGA',
            'OLD ZAÑIGA',
            'PAG-ASA',
            'PLAINVIEW',
            'PLEASANT HILLS',
            'POBLACION',
            'SAN JOSE',
            'VERGARA',
            'WACK-WACK GREENHILLS',
        ];
    }

    private function generateMarikinajBarangays(): array
    {
        return [
            'BARANGKA',
            'CALUMPANG',
            'CONCEPCION DOS',
            'CONCEPCION UNO',
            'FORTUNE',
            'INDUSTRIAL VALLEY',
            'JESUS DE LA PEÑA',
            'MALANDAY',
            'MARIKINA HEIGHTS',
            'NANGKA',
            'PARANG',
            'SAN ROQUE',
            'SANTA ELENA',
            'SANTO NIÑO',
            'TAÑONG',
            'TUMANA',
        ];
    }

    private function generateMuntinlupaBarangays(): array
    {
        return [
            'ALABANG',
            'BAYANAN',
            'BULI',
            'CUPANG',
            'NEW ALABANG VILLAGE',
            'POBLACION',
            'PUTATAN',
            'SUCAT',
            'TUNASAN',
        ];
    }

    private function generateNavotasBarangays(): array
    {
        return [
            'BAGUMBAYAN NORTH',
            'BAGUMBAYAN SOUTH',
            'BANGCULASI',
            'DAANGHARI',
            'NBBS DAGAT-DAGATAN',
            'NBBS KAUNLARAN',
            'NBBS PROPER',
            'NAVOTAS EAST',
            'NAVOTAS WEST',
            'NORTH BAY BOULEVARD NORTH',
            'SAN JOSE',
            'SAN RAFAEL VILLAGE',
            'SAN ROQUE',
            'SIPAC-ALMACEN',
            'TANGOS NORTH',
            'TANGOS SOUTH',
            'TANZA 1',
            'TANZA 2',
        ];
    }

    private function generateSanJuanBarangays(): array
    {
        return [
            'ADDITION HILLS',
            'BALONG-BATO',
            'BATIS',
            'CORAZON DE JESUS',
            'ERMITAÑO',
            'GREENHILLS',
            'HALO-HALO',
            'ISABELITA',
            'KABAYANAN',
            'LITTLE BAGUIO',
            'MAYTUNAS',
            'ONSE',
            'PASADEÑA',
            'PEDRO CRUZ',
            'PROGRESO',
            'RIVERA',
            'SALAPAN',
            'SAN PERFECTO',
            'SANTA LUCIA',
            'TIBAGAN',
            'WEST CRAME',
        ];
    }

    private function generateTaguigBarangays(): array
    {
        return [
            'BAGUMBAYAN',
            'BAMBANG',
            'CALZADA',
            'CENTRAL BICUTAN',
            'CENTRAL SIGNAL VILLAGE',
            'FORT BONIFACIO',
            'HAGONOY',
            'IBAYO-TIPAS',
            'KATUPARAN',
            'LIGID-TIPAS',
            'LOWER BICUTAN',
            'MAHARLIKA VILLAGE',
            'NAPINDAN',
            'NEW LOWER BICUTAN',
            'NORTH DAANG HARI',
            'NORTH SIGNAL VILLAGE',
            'PALINGON',
            'PINAGSAMA',
            'SAN MIGUEL',
            'SANTA ANA',
            'SOUTH DAANG HARI',
            'SOUTH SIGNAL VILLAGE',
            'TANYAG',
            'TUKTUKAN',
            'UPPER BICUTAN',
            'USUSAN',
            'WAWA',
            'WESTERN BICUTAN',
        ];
    }

    private function generateValenzuelaBarangays(): array
    {
        return [
            'ARKONG BATO',
            'BAGBAGUIN',
            'BALANGKAS',
            'BIGNAY',
            'BISIG',
            'CANUMAY EAST',
            'CANUMAY WEST',
            'COLOONG',
            'DALANDANAN',
            'GEN. T. DE LEON',
            'ISLA',
            'KARUHATAN',
            'LAWANG BATO',
            'LINGUNAN',
            'MABOLO',
            'MALANDAY',
            'MALINTA',
            'MAPULANG LUPA',
            'MARULAS',
            'MAYSAN',
            'PALASAN',
            'PARADA',
            'PARIANCILLO VILLA',
            'PASO DE BLAS',
            'PASOLO',
            'POBLACION',
            'PULO',
            'PUNTURIN',
            'RINCON',
            'TAGALAG',
            'UGONG',
            'VIENTE REALES',
            'WAWANG PULO',
        ];
    }

    private function generateQuezonCityBarangays(): array
    {
        return [
            'ALICIA',
            'AMIHAN',
            'APOLONIO SAMSON',
            'AURORA',
            'BAESA',
            'BAGBAG',
            'BAGONG LIPUNAN NG CRAME',
            'BAGONG PAG-ASA',
            'BAGONG SILANGAN',
            'BAGUMBAYAN',
            'BAGUMBUHAY',
            'BAHAY TORO',
            'BALINGASA',
            'BALONG BATO',
            'BATASAN HILLS',
            'BAYANIHAN',
            'BLUE RIDGE A',
            'BLUE RIDGE B',
            'BOTOCAN',
            'BUNGAD',
            'CAMP AGUINALDO',
            'CAPRI',
            'CENTRAL',
            'CLARO',
            'COMMONWEALTH',
            'CULIAT',
            'DAMAR',
            'DAMAYAN',
            'DAMAYANG LAGI',
            'DEL MONTE',
            'DIOQUINO ZOBEL',
            'DON MANUEL',
            'DOÑA IMELDA',
            'DOÑA JOSEFA',
            'DUYAN-DUYAN',
            'E. RODRIGUEZ',
            'EAST KAMIAS',
            'ESCOPA I',
            'ESCOPA II',
            'ESCOPA III',
            'ESCOPA IV',
            'FAIRVIEW',
            'GREATER LAGRO',
            'GULOD',
            'HOLY SPIRIT',
            'HORSESHOE',
            'IMMACULATE CONCEPCION',
            'KALIGAYAHAN',
            'KALUSUGAN',
            'KAMUNING',
            'KATIPUNAN',
            'KAUNLARAN',
            'KRISTONG HARI',
            'KRUS NA LIGAS',
            'LAGING HANDA',
            'LIBIS',
            'LOURDES',
            'LOYOLA HEIGHTS',
            'MAHARLIKA',
            'MALAYA',
            'MANGGA',
            'MANRESA',
            'MARIANA',
            'MARIBLO',
            'MARILAG',
            'MASAGANA',
            'MASAMBONG',
            'MATANDANG BALARA',
            'MILAGROSA',
            'N. S. AMORANTO',
            'NAGKAISANG NAYON',
            'NAYONG KANLURAN',
            'NEW ERA',
            'NORTH FAIRVIEW',
            'NOVALICHES PROPER',
            'OBRERO',
            'OLD CAPITOL SITE',
            'PAANG BUNDOK',
            'PAG-IBIG SA NAYON',
            'PALIGSAHAN',
            'PALTOK',
            'PANSOL',
            'PARAISO',
            'PASONG PUTIK PROPER',
            'PASONG TAMO',
            'PAYATAS',
            'PHIL-AM',
            'PINAGKAISAHAN',
            'PINYAHAN',
            'PROJECT 6',
            'QUIRINO 2-A',
            'QUIRINO 2-B',
            'QUIRINO 2-C',
            'QUIRINO 3-A',
            'RAMON MAGSAYSAY',
            'ROXAS',
            'SACRED HEART',
            'SAINT IGNATIUS',
            'SAINT PETER',
            'SALVACION',
            'SAN AGUSTIN',
            'SAN ANTONIO',
            'SAN BARTOLOME',
            'SAN ISIDRO',
            'SAN ISIDRO LABRADOR',
            'SAN JOSE',
            'SAN MARTIN DE PORRES',
            'SAN ROQUE',
            'SAN VICENTE',
            'SANGANDAAN',
            'SANTA CRUZ',
            'SANTA LUCIA',
            'SANTA MONICA',
            'SANTA TERESITA',
            'SANTO CRISTO',
            'SANTO DOMINGO',
            'SANTO NIÑO',
            'SANTOL',
            'SAUYO',
            'SIENNA',
            'SIKATUNA VILLAGE',
            'SILANGAN',
            'SOCORRO',
            'SOUTH TRIANGLE',
            'TAGUMPAY',
            'TALAYAN',
            'TALIPAPA',
            'TANDANG SORA',
            'TATALON',
            'TEACHERS VILLAGE EAST',
            'TEACHERS VILLAGE WEST',
            'U.P. CAMPUS',
            'U.P. VILLAGE',
            'UGONG NORTE',
            'UNANG SIGAW',
            'VALENCIA',
            'VASRA',
            'VETERANS VILLAGE',
            'VILLA MARIA CLARA',
            'WEST KAMIAS',
            'WEST TRIANGLE',
            'WHITE PLAINS',
        ];
    }

    public function generateParañaqueBarangays(): array
    {
        return [
            'B. F. HOMES',
            'BACLARAN',
            'DON BOSCO',
            'DON GALO',
            'LA HUERTA',
            'MARCELO GREEN VILLAGE',
            'MERVILLE',
            'MOONWALK',
            'SAN ANTONIO',
            'SAN DIONISIO',
            'SAN ISIDRO',
            'SAN MARTIN DE PORRES',
            'SANTO NIÑO',
            'SUN VALLEY',
            'TAMBO',
            'VITALEZ'
        ];
    }

    public function generatePasayBarangays(): array
    {
        $barangays = [];

        for ($i = 1; $i <= 201; $i++) {
            $barangays[] = 'BARANGAY ' . $i;
        }

        return $barangays;
    }

    public function generatePasigBarangays(): array
    {
        return [
            'BAGONG ILOG',
            'BAGONG KATIPUNAN',
            'BAMBANG',
            'BUTING',
            'CANIOGAN',
            'DELA PAZ',
            'KALAWAAN',
            'KAPASIGAN',
            'KAPITOLYO',
            'MALINAO',
            'MANGGAHAN',
            'MAYBUNGA',
            'ORANBO',
            'PALATIW',
            'PINAGBUHATAN',
            'PINEDA',
            'ROSARIO',
            'SAGAD',
            'SAN ANTONIO',
            'SAN JOAQUIN',
            'SAN JOSE',
            'SAN MIGUEL',
            'SAN NICOLAS',
            'SANTA CRUZ',
            'SANTA LUCIA',
            'SANTA ROSA',
            'SANTO TOMAS',
            'SANTOLAN',
            'SUMILANG',
            'UGONG'
        ];
    }

    public function generatePaterosBarangays(): array
    {
        return [
            'AGUHO',
            'MAGTANGGOL',
            'MARTIRES DEL 96',
            'POBLACION',
            'SAN PEDRO',
            'SAN ROQUE',
            'SANTA ANA',
            'SANTO ROSARIO-KANLURAN',
            'SANTO ROSARIO-SILANGAN',
            'TABACALERA'
        ];
    }
}
