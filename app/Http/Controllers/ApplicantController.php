<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\DuplicateDismissal;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicantController extends Controller
{
    public function create()
    {
        return view('applicants.create');
    }

    public function store(Request $request)
    {
        $request->validate([

            // Applicant Personal Information
            'first_name' => 'required',
            'last_name' => 'required',
            'birthdate' => 'required|date',
            'email' => 'nullable|email|max:255',
            'contact_no' => 'required',
            'gender' => 'required',
            'civil_status' => 'required',
            'pwd' => 'required',
            'four_ps' => 'required',
            'address_line' => 'required',
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
            'educational_attainment' => 'required',
            'position_hired' => 'required',
            'first_time_job_seeker' => 'required',
        ]);

        $data = $request->all();
        $data['barangay'] = $this->normalizeBarangay($request->city, $request->barangay);
        $data['age'] = $this->calculateAgeFromBirthdate($data['birthdate'] ?? null);

        $applicant = Applicant::create($data);
        $applicant->forceFill([
            'profile_completed' => true,
        ])->saveQuietly();

        ActivityLogger::log(
            'applicant',
            'created',
            'Created a new applicant record.',
            $applicant,
            ActivityLogger::diff([], $applicant->only($applicant->getFillable())),
            $request->user()
        );

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('created_success', true)
            ->with('applicant_id', $applicant->id);
    }

    public function checkDuplicates(Request $request)
    {
        $firstName = trim($request->query('first_name', ''));
        $lastName = trim($request->query('last_name', ''));
        $middleName = trim($request->query('middle_name', ''));
        $birthdate = trim($request->query('birthdate', ''));

        if ($firstName === '' || $lastName === '') {
            return response()->json(['duplicates' => []]);
        }

        $query = Applicant::query()
            ->whereRaw('LOWER(TRIM(first_name)) = ?', [mb_strtolower($firstName)])
            ->whereRaw('LOWER(TRIM(last_name)) = ?', [mb_strtolower($lastName)]);

        if ($middleName !== '') {
            $query->whereRaw('LOWER(TRIM(COALESCE(middle_name, \'\'))) = ?', [mb_strtolower($middleName)]);
        }

        if ($birthdate !== '') {
            $query->whereDate('birthdate', $birthdate);
        }

        $duplicates = $query
            ->select('id', 'first_name', 'middle_name', 'last_name', 'suffix', 'birthdate', 'gender', 'civil_status', 'contact_no', 'city', 'barangay')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return response()->json(['duplicates' => $duplicates]);
    }

    public function duplicate(Request $request, $id)
    {
        $source = Applicant::findOrFail($id);

        $data = $source->only([
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'birthdate',
            'age',
            'email',
            'contact_no',
            'gender',
            'civil_status',
            'pwd',
            'four_ps',
            'address_line',
            'province',
            'city',
            'barangay',
            'educational_attainment',
            'hiring_company',
            'position_hired',
            'first_time_job_seeker',
        ]);

        $data['age'] = $this->calculateAgeFromBirthdate($data['birthdate'] ?? null);

        $applicant = Applicant::create($data);
        $applicant->forceFill([
            'profile_completed' => true,
        ])->saveQuietly();

        ActivityLogger::log(
            'applicant',
            'created',
            'Duplicated from applicant #' . $source->id . '.',
            $applicant,
            ActivityLogger::diff([], $applicant->only($applicant->getFillable())),
            $request->user()
        );

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('created_success', true)
            ->with('applicant_id', $applicant->id);
    }

    public function index(Request $request)
    {
        $filters = $this->getApplicantFilters($request);
        $perPageInput = strtolower((string) $request->query('per_page', '10'));
        $allowedPerPage = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100];

        $sortBy = $request->query('sort_by', 'id');
        $sortOrder = strtolower((string) $request->query('sort_order', 'desc'));
        $allowedSortFields = ['id', 'first_name', 'last_name', 'created_at', 'contact_no', 'city'];

        if (! in_array($sortBy, $allowedSortFields, true)) {
            $sortBy = 'id';
        }

        if (! in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'desc';
        }

        $query = $this->buildApplicantSearchQuery($filters)
            ->with(['permit', 'clearance', 'referral']);

        // If the authenticated user is an applicant account (role = 'user'),
        // show only the applicant record linked to that user.
        if (auth()->check() && auth()->user()->role === 'user') {
            $applicantId = auth()->user()->linkedApplicant()?->id;
            if ($applicantId) {
                $query->where('id', $applicantId);
            } else {
                // If no linked applicant, return empty result set.
                $query->whereRaw('0 = 1');
            }
        }

        if ($perPageInput === 'all') {
            $total = (clone $query)->count();
            $perPage = max($total, 1);
        } else {
            $perPage = (int) $perPageInput;

            if (! in_array($perPage, $allowedPerPage, true)) {
                $perPage = 10;
            }
        }

        $applicants = $query
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage)
            ->withQueryString();

        $genderOptions = $this->getDistinctApplicantFieldOptions('gender');
        $civilStatusOptions = $this->getDistinctApplicantFieldOptions('civil_status');
        $cityOptions = $this->getDistinctApplicantFieldOptions('city');
        $barangayOptions = $this->getDistinctApplicantFieldOptions('barangay');

        return view('applicants.index', compact(
            'applicants',
            'filters',
            'genderOptions',
            'civilStatusOptions',
            'cityOptions',
            'barangayOptions',
            'sortBy',
            'sortOrder'
        ));
    }

    public function export(Request $request)
    {
        $filters = $this->getApplicantFilters($request);

        $applicants = $this->buildApplicantSearchQuery($filters)
            ->orderBy('id')
            ->get();

        $rows = [];

        foreach ($applicants as $applicant) {
            $rows[] = [
                'Date Visited' => optional($applicant->created_at)->format('m/d/Y'),
                'Last Name' => (string) $applicant->last_name,
                'First Name' => (string) $applicant->first_name,
                'Middle Name' => (string) ($applicant->middle_name ?? ''),
                'Suffix' => (string) ($applicant->suffix ?? ''),
                'Age' => $applicant->age !== null ? (string) $applicant->age : '',
                'Email' => (string) ($applicant->email ?? ''),
                'Sex' => (string) ($applicant->gender ?? ''),
                'PWD' => (string) ($applicant->pwd ?? ''),
                '4Ps' => (string) ($applicant->four_ps ?? ''),
                'Educational Attainment' => (string) ($applicant->educational_attainment ?? ''),
                'Province' => (string) ($applicant->province ?? ''),
                'Street Address' => (string) ($applicant->address_line ?? ''),
                'Barangay' => (string) ($applicant->barangay ?? ''),
                'City' => (string) ($applicant->city ?? ''),
                'Contact No' => (string) ($applicant->contact_no ?? ''),
                'Hiring Company' => (string) ($applicant->hiring_company ?? ''),
                'Date Referred' => optional($applicant->created_at)->format('d F Y'),
                'Position Hired' => (string) ($applicant->position_hired ?? ''),
                'First Time Job Seeker' => (string) ($applicant->first_time_job_seeker ?? ''),
            ];
        }

        $filePath = $this->createApplicantsExportXlsx($rows);
        $fileName = 'applicants-export-'.now()->format('Y-m-d-His').'.xlsx';

        $count = $applicants->count();
        ActivityLogger::log(
            'applicant',
            'exported',
            "Exported {$count} applicant record(s) to {$fileName}.",
            null,
            null,
            $request->user()
        );

        return response()->download(
            $filePath,
            $fileName,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    public function edit($id)
    {
        if (auth()->check() && auth()->user()->role === 'user') {
            $linkedApplicantId = auth()->user()?->linkedApplicant()?->id;

            abort_if((int) $id !== (int) $linkedApplicantId, 403, 'You can only view your own applicant record.');
        }

        $applicant = Applicant::with(['permit', 'permits', 'clearance', 'referral'])->findOrFail($id);
        $activityLogs = $applicant->activityLogs()
            ->with('causer')
            ->paginate(10, ['*'], 'activity_page')
            ->withQueryString();

        return view('applicants.edit', compact('applicant', 'activityLogs'));
    }

    private function normalizeBarangay(?string $city, ?string $barangay): string
    {
        $normalizedCity = trim((string) $city);
        $normalizedCity = preg_replace('/^\s*(city of|municipality of)\s+/i', '', $normalizedCity);
        $normalizedCity = strtoupper(trim((string) $normalizedCity));

        $normalizedBarangay = strtoupper(trim((string) $barangay));

        if (str_contains($normalizedCity, 'BACOOR') && str_starts_with($normalizedBarangay, 'P.F. ESPIRITU')) {
            return preg_replace('/^P\.F\. ESPIRITU\b/i', 'PANAPAAN', $normalizedBarangay) ?: $normalizedBarangay;
        }

        return $normalizedBarangay;
    }

    public function update(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        $before = $applicant->only($applicant->getFillable());

        /*
        |--------------------------------------------------------------------------
        | 1. UPDATE PERSONAL INFORMATION
        |--------------------------------------------------------------------------
        */

        $birthdate = $request->filled('birthdate') ? $request->birthdate : $applicant->birthdate?->format('Y-m-d');

        $applicant->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'birthdate' => $birthdate,
            'age' => $this->calculateAgeFromBirthdate($birthdate),
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'gender' => $request->gender,
            'civil_status' => $request->civil_status,
            'pwd' => $request->pwd,
            'four_ps' => $request->four_ps,
            'address_line' => $request->address_line,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $this->normalizeBarangay($request->city, $request->barangay),
            'educational_attainment' => $request->educational_attainment,
            'hiring_company' => $request->hiring_company,
            'position_hired' => $request->position_hired,
            'first_time_job_seeker' => $request->first_time_job_seeker,
        ]);
        $applicant->forceFill([
            'profile_completed' => true,
        ])->saveQuietly();

        $changes = ActivityLogger::diff($before, $applicant->fresh()->only($applicant->getFillable()));

        if (! empty($changes)) {
            ActivityLogger::log(
                'applicant',
                'updated',
                'Updated applicant information.',
                $applicant,
                $changes,
                $request->user()
            );
        }

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('success', 'Applicant updated successfully.');
    }

    public function destroy($id)
    {
        abort_if(auth()->user()?->role === 'user', 403, 'Only administrators can archive applicants.');

        $applicant = Applicant::findOrFail($id);
        $applicantName = trim($applicant->first_name.' '.$applicant->last_name);

        $applicant->delete(); // Moves to Archive

        ActivityLogger::log(
            'applicant',
            'archived',
            'Archived applicant record.',
            $applicant,
            [
                'status' => [
                    'before' => 'Active',
                    'after' => 'Archived',
                ],
                'applicant_name' => [
                    'before' => $applicantName,
                    'after' => $applicantName,
                ],
            ],
            request()->user()
        );

        return redirect()->route('applicants.archive')
            ->with('success', 'Applicant Archived');
    }

    public function archive(Request $request)
    {
        abort_if(! auth()->user()?->hasPermission('view_archive_applicants') && ! auth()->user()?->isAdmin(), 403, 'You do not have permission to view archived applicants.');

        $search = trim((string) $request->search);

        $applicants = Applicant::onlyTrashed()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?", ["%{$search}%"])
                        ->orWhere('contact_no', 'like', "%{$search}%")
                        ->orWhere('barangay', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        return view('applicants.archive', compact('applicants', 'search'));
    }

    public function restore($id)
    {
        abort_if(! auth()->user()?->hasPermission('restore_archive_applicants') && ! auth()->user()?->isAdmin(), 403, 'You do not have permission to restore archived applicants.');

        $applicant = Applicant::withTrashed()->findOrFail($id);
        $applicantName = trim($applicant->first_name.' '.$applicant->last_name);
        $applicant->restore();

        ActivityLogger::log(
            'applicant',
            'restored',
            'Restored applicant record from archive.',
            $applicant,
            [
                'status' => [
                    'before' => 'Archived',
                    'after' => 'Active',
                ],
                'applicant_name' => [
                    'before' => $applicantName,
                    'after' => $applicantName,
                ],
            ],
            request()->user()
        );

        return redirect()->route('applicants.index')
            ->with('success', 'Applicant restored successfully.');
    }

    public function duplicates(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $cacheKey = 'duplicates:groups:'.($search !== '' ? 'search-'.md5($search) : 'all');

        [$exactGroups, $likelyGroups, $possibleGroups] = Cache::remember($cacheKey, 900, function () use ($search) {
            $applicants = Applicant::whereNull('deleted_at')
                ->when($search !== '', function ($q) use ($search) {
                    $q->where(function ($inner) use ($search) {
                        $inner->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('contact_no', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%")
                            ->orWhere('barangay', 'like', "%{$search}%");
                    });
                })
                ->get([
                    'id', 'first_name', 'middle_name', 'last_name', 'suffix',
                    'birthdate', 'contact_no', 'gender', 'civil_status',
                    'address_line', 'city', 'barangay', 'created_at',
                ]);

            $rows = $applicants->map(function ($applicant) {
                $baseName = trim(
                    $this->normalizeName($applicant->first_name)
                    .' '.$this->normalizeName($applicant->last_name)
                );
                $fullName = trim($baseName.' '.$this->normalizeName($applicant->middle_name)
                    .' '.$this->normalizeName($applicant->suffix));

                return [
                    'applicant' => $applicant,
                    'base' => $baseName,
                    'full' => $fullName,
                    'birthdate' => $applicant->birthdate?->format('Y-m-d'),
                ];
            });

            $exactGroups = collect();
            $likelyGroups = collect();

            foreach ($rows->groupBy('base') as $baseKey => $baseRows) {
                if ($baseKey === '' || $baseRows->count() < 2) {
                    continue;
                }

                $assignedIds = [];

                foreach ($baseRows->groupBy('full') as $fullKey => $fullRows) {
                    if ($fullKey === '' || $fullRows->count() < 2) {
                        continue;
                    }

                    foreach ($fullRows->groupBy('birthdate') as $birthdate => $birthRows) {
                        if ($birthdate === null || $birthdate === '' || $birthRows->count() < 2) {
                            continue;
                        }

                        $exactGroups->push($this->makeDuplicateGroup($birthRows->values(), 'exact'));
                        $assignedIds = array_merge(
                            $assignedIds,
                            $birthRows->pluck('applicant.id')->all()
                        );
                    }
                }

                $leftoverRows = $baseRows
                    ->reject(fn ($row) => in_array($row['applicant']->id, $assignedIds, true))
                    ->values();

                if ($leftoverRows->count() >= 2) {
                    $likelyGroups->push($this->makeDuplicateGroup($leftoverRows, 'likely'));
                }
            }

            $possibleGroups = collect($this->buildPossibleGroups($rows));

            $exactGroups = $exactGroups->sortByDesc('count')->sortBy('first_name')->values();
            $likelyGroups = $likelyGroups->sortByDesc('count')->sortBy('first_name')->values();
            $possibleGroups = $possibleGroups->sortByDesc('count')->sortBy('first_name')->values();

            return [$exactGroups, $likelyGroups, $possibleGroups];
        });

        $dismissals = DuplicateDismissal::orderByDesc('id')->get()->keyBy('group_hash');

        $dismissedGroups = collect();
        $isDismissed = function ($group) use ($dismissals) {
            return $dismissals->has($this->duplicateGroupHash($group['applicants']));
        };

        foreach ($exactGroups->concat($likelyGroups)->concat($possibleGroups) as $group) {
            if (! $isDismissed($group)) {
                continue;
            }

            $dismissal = $dismissals->get($this->duplicateGroupHash($group['applicants']));
            $group['hash'] = $dismissal->group_hash;
            $group['dismissed_at'] = $dismissal->created_at;
            $dismissedGroups->push($group);
        }

        $exactGroups = $exactGroups->reject($isDismissed)->values();
        $likelyGroups = $likelyGroups->reject($isDismissed)->values();
        $possibleGroups = $possibleGroups->reject($isDismissed)->values();

        $dismissedGroups = $dismissedGroups->sortByDesc('dismissed_at')->values();

        $totalGroups = $exactGroups->count() + $likelyGroups->count() + $possibleGroups->count();
        $totalDuplicates = $exactGroups->sum('count')
            + $likelyGroups->sum('count')
            + $possibleGroups->sum('count');

        $exactCount = $exactGroups->sum('count');
        $likelyCount = $likelyGroups->sum('count');
        $possibleCount = $possibleGroups->sum('count');

        $isApplicantUser = auth()->check() && auth()->user()->role === 'user';

        $tiers = [
            [
                'key' => 'exact',
                'label' => 'Exact Match',
                'icon' => 'bi-exclamation-octagon',
                'criteria' => 'Same full name and exact-same birthdate. Almost certainly the same person.',
                'action' => 'Review and merge duplicates',
                'groups' => $exactGroups,
            ],
            [
                'key' => 'likely',
                'label' => 'Likely Match',
                'icon' => 'bi-exclamation-triangle',
                'criteria' => 'Likely-same name. Birthdate is missing on a record or matches by year only.',
                'action' => 'Verify before acting',
                'groups' => $likelyGroups,
            ],
            [
                'key' => 'possible',
                'label' => 'Possible Match',
                'icon' => 'bi-question-circle',
                'criteria' => 'Possible-similar name spelling (e.g. Juan vs Juana, Dela Cruz vs Delacruz).',
                'action' => 'Verify before acting',
                'groups' => $possibleGroups,
            ],
        ];

        return view('applicants.duplicates', compact(
            'tiers',
            'totalDuplicates',
            'totalGroups',
            'exactCount',
            'likelyCount',
            'possibleCount',
            'dismissedGroups',
            'search',
            'isApplicantUser'
        ));
    }

    public function dismissDuplicateGroup(Request $request)
    {
        $request->validate([
            'applicant_ids' => 'required|array|min:2',
            'applicant_ids.*' => 'integer',
        ]);

        $ids = collect($request->input('applicant_ids'))
            ->map(fn ($id) => (int) $id)
            ->sort()
            ->values();

        DuplicateDismissal::updateOrCreate(
            ['group_hash' => md5($ids->implode('-'))],
            [
                'applicant_ids' => $ids->all(),
                'user_id' => $request->user()?->id,
            ]
        );

        return back()->with('success', 'Group marked as not a duplicate.');
    }

    public function restoreDuplicateGroup(Request $request)
    {
        $request->validate([
            'group_hash' => 'required|string',
        ]);

        DuplicateDismissal::where('group_hash', $request->input('group_hash'))->delete();

        return back()->with('success', 'Duplicate group restored.');
    }

    private function duplicateGroupHash($applicants): string
    {
        return md5(
            collect($applicants)->pluck('id')->sort()->values()->implode('-')
        );
    }

    private function normalizeName(?string $value): string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? '' : mb_strtolower((string) preg_replace('/\s+/', ' ', $value));
    }

    private function makeDuplicateGroup($rows, string $tier): array
    {
        $applicants = collect($rows)
            ->map(fn ($row) => $row['applicant'])
            ->sortBy('id')
            ->values();

        return [
            'tier' => $tier,
            'first_name' => $applicants->first()->first_name,
            'last_name' => $applicants->first()->last_name,
            'count' => $applicants->count(),
            'applicants' => $applicants,
            'reason' => $this->duplicateGroupReason($applicants, $tier),
        ];
    }

    private function duplicateGroupReason($applicants, string $tier): string
    {
        if ($tier === 'exact') {
            return 'Same full name and exact-same birthdate across all records';
        }

        $birthdates = $applicants->filter(fn ($a) => $a->birthdate !== null);
        $missing = $birthdates->count() < $applicants->count();
        $years = $birthdates
            ->map(fn ($a) => $a->birthdate->format('Y'))
            ->unique()
            ->values();

        if ($birthdates->isEmpty()) {
            return 'Same name — birthdate not recorded on any record';
        }

        if ($years->count() === 1) {
            return $missing
                ? 'Same name — birthdate missing or matches by year only'
                : 'Same name — birthdates differ but match by year only';
        }

        return 'Same name — birthdates differ or are missing, verify identity';
    }

    private function buildPossibleGroups($rows): array
    {
        $rows = collect($rows)->values();
        $n = $rows->count();

        if ($n < 2) {
            return [];
        }

        $bases = $rows->pluck('base')->all();
        $firstEdges = $rows->map(fn ($row) => $this->nameEdgeChars($row['base'])[0])->all();
        $lastEdges = $rows->map(fn ($row) => $this->nameEdgeChars($row['base'])[1])->all();

        $byFirstEdge = [];
        $byLastEdge = [];

        for ($i = 0; $i < $n; $i++) {
            if ($bases[$i] === '') {
                continue;
            }

            $byFirstEdge[$firstEdges[$i]][] = $i;
            $byLastEdge[$lastEdges[$i]][] = $i;
        }

        $parent = range(0, $n - 1);

        $find = function (int $x) use (&$parent) {
            while ($parent[$x] !== $x) {
                $parent[$x] = $parent[$parent[$x]];
                $x = $parent[$x];
            }

            return $x;
        };

        $union = function (int $x, int $y) use (&$parent, $find) {
            $rootX = $find($x);
            $rootY = $find($y);

            if ($rootX !== $rootY) {
                $parent[$rootY] = $rootX;
            }
        };

        foreach ($byFirstEdge as $indices) {
            $count = count($indices);

            for ($i = 0; $i < $count; $i++) {
                $a = $bases[$indices[$i]];

                for ($j = $i + 1; $j < $count; $j++) {
                    if ($a === $bases[$indices[$j]] || !$this->namesSimilar($a, $bases[$indices[$j]])) {
                        continue;
                    }

                    $union($indices[$i], $indices[$j]);
                }
            }
        }

        foreach ($byLastEdge as $indices) {
            $count = count($indices);

            for ($i = 0; $i < $count; $i++) {
                $x = $indices[$i];
                $a = $bases[$x];

                for ($j = $i + 1; $j < $count; $j++) {
                    $y = $indices[$j];

                    if ($firstEdges[$x] === $firstEdges[$y]) {
                        continue;
                    }

                    if ($a === $bases[$y] || !$this->namesSimilar($a, $bases[$y])) {
                        continue;
                    }

                    $union($x, $y);
                }
            }
        }

        $membersByRoot = [];

        for ($i = 0; $i < $n; $i++) {
            $membersByRoot[$find($i)][] = $rows[$i]['applicant'];
        }

        $groups = [];

        foreach ($membersByRoot as $members) {
            if (count($members) < 2) {
                continue;
            }

            $applicants = collect($members)->sortBy('id')->values();
            $firstName = $applicants->first()->first_name;
            $lastName = $applicants->first()->last_name;
            $differentSpellings = $applicants
                ->map(fn ($a) => trim($a->first_name.' '.$a->last_name))
                ->unique()
                ->count();

            $groups[] = [
                'tier' => 'possible',
                'first_name' => $firstName,
                'last_name' => $lastName,
                'count' => $applicants->count(),
                'applicants' => $applicants,
                'reason' => $differentSpellings > 1
                    ? 'Similar name spelling across records — verify before acting'
                    : 'Similar name spelling — verify before acting',
            ];
        }

        return $groups;
    }

    private function nameEdgeChars(string $base): array
    {
        $tokens = preg_split('/\s+/', trim($base));
        $first = $tokens[0] ?? '';
        $last = count($tokens) > 1 ? end($tokens) : '';

        return [mb_substr($first, 0, 1), mb_substr($last, 0, 1)];
    }

    private function namesSimilar(string $a, string $b): bool
    {
        $a = trim($a);
        $b = trim($b);

        if ($a === '' || $b === '' || $a === $b) {
            return false;
        }

        if (str_contains($a, $b) || str_contains($b, $a)) {
            return true;
        }

        if (abs(strlen($a) - strlen($b)) > 2) {
            return false;
        }

        $distance = levenshtein($a, $b);

        if ($distance <= 1) {
            return true;
        }

        return max(strlen($a), strlen($b)) >= 4 && $distance <= 2;
    }

    private function buildApplicantSearchQuery(array $filters)
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $gender = trim((string) ($filters['gender'] ?? ''));
        $civilStatus = trim((string) ($filters['civil_status'] ?? ''));
        $city = trim((string) ($filters['city'] ?? ''));
        $barangay = trim((string) ($filters['barangay'] ?? ''));
        $transactionType = trim((string) ($filters['transaction_type'] ?? ''));
        $dateFrom = trim((string) ($filters['date_from'] ?? ''));
        $dateTo = trim((string) ($filters['date_to'] ?? ''));
        $firstTimeJobSeeker = trim((string) ($filters['first_time_job_seeker'] ?? ''));

        return Applicant::query()
            ->where('profile_completed', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?", ["%{$search}%"]);
                });
            })
            ->when($gender !== '', function ($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->when($civilStatus !== '', function ($query) use ($civilStatus) {
                $query->where('civil_status', $civilStatus);
            })
            ->when($city !== '', function ($query) use ($city) {
                $query->where('city', $city);
            })
            ->when($barangay !== '', function ($query) use ($barangay) {
                $query->where('barangay', $barangay);
            })
            ->when($dateFrom !== '', function ($query) use ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo !== '', function ($query) use ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->when($transactionType !== '', function ($query) use ($transactionType) {
                return match ($transactionType) {
                    'permit' => $query->whereHas('permit'),
                    'clearance' => $query->whereHas('clearance'),
                    'referral' => $query->whereHas('referral'),
                    'all' => $query->where(function ($innerQuery) {
                        $innerQuery->whereHas('permit')
                            ->orWhereHas('clearance')
                            ->orWhereHas('referral');
                    }),
                    default => $query,
                };
            })
            ->when($firstTimeJobSeeker !== '', function ($query) use ($firstTimeJobSeeker) {
                $query->whereRaw('UPPER(TRIM(first_time_job_seeker)) = ?', [strtoupper($firstTimeJobSeeker)]);
            });
    }

    private function getApplicantFilters(Request $request): array
    {
        $dateFrom = $this->normalizeDateFilter((string) $request->query('date_from', ''));
        $dateTo = $this->normalizeDateFilter((string) $request->query('date_to', ''));

        if ($dateFrom !== '' && $dateTo !== '' && $dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        return [
            'search' => trim((string) $request->query('search', '')),
            'gender' => trim((string) $request->query('gender', '')),
            'civil_status' => trim((string) $request->query('civil_status', '')),
            'city' => trim((string) $request->query('city', '')),
            'barangay' => trim((string) $request->query('barangay', '')),
            'transaction_type' => trim((string) $request->query('transaction_type', '')),
            'first_time_job_seeker' => trim((string) $request->query('first_time_job_seeker', '')),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];
    }

    private function normalizeDateFilter(string $value): string
    {
        $value = trim($value);

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) ? $value : '';
    }

    private function calculateAgeFromBirthdate(?string $birthdate): ?int
    {
        $birthdate = trim((string) $birthdate);

        if ($birthdate === '') {
            return null;
        }

        try {
            return Carbon::parse($birthdate)->age;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function getDistinctApplicantFieldOptions(string $field)
    {
        return Applicant::query()
            ->whereNotNull($field)
            ->where($field, '!=', '')
            ->distinct()
            ->orderBy($field)
            ->pluck($field);
    }

    private function createApplicantsExportXlsx(array $rows): string
    {
        $headers = [
            'Date Visited',
            'Last Name',
            'First Name',
            'Middle Name',
            'Suffix',
            'Age',
            'Sex',
            'PWD',
            '4Ps',
            'Educational Attainment',
            'Province',
            'Street Address',
            'Barangay',
            'City',
            'Contact No',
            'Position Hired',
            'Date Referred',
            'Hiring Company',
            'First Time Job Seeker',
        ];

        $sheetRows = [$headers];

        foreach ($rows as $row) {
            $sheetRows[] = array_map(
                fn ($header) => (string) ($row[$header] ?? ''),
                $headers
            );
        }

        return \App\Support\XlsxWriter::create('applicants_export_', [
            'Applicants' => [
                'rows' => $sheetRows,
                'widths' => [
                    [1, 1, 10],
                    [2, 5, 18],
                    [6, 6, 10],
                    [7, 11, 16],
                    [12, 15, 22],
                    [16, 20, 24],
                ],
            ],
        ]);
    }
}
