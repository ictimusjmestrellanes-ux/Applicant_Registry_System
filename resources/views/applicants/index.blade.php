@extends('layouts.app')

@section('title', 'Applicants Records')

@section('content')
    @php
        $visibleApplicants = $applicants->getCollection();
        $searchTerm = trim((string) request('search'));
        $showingFrom = $applicants->firstItem() ?? 0;
        $showingTo = $applicants->lastItem() ?? 0;
        $totalApplicants = $applicants->total();
        $permitCompleteCount = $visibleApplicants->filter(fn($applicant) => $applicant->isPermitComplete())->count();
        $clearanceCompleteCount = $visibleApplicants->filter(fn($applicant) => $applicant->isClearanceComplete())->count();
        $referralCompleteCount = $visibleApplicants->filter(fn($applicant) => $applicant->isReferralComplete())->count();
        $fullyReadyCount = $visibleApplicants->filter(
            fn($applicant) => $applicant->isPermitComplete() && $applicant->isClearanceComplete() && $applicant->isReferralComplete()
        )->count();
    @endphp

    <div class="applicant-index-page container-fluid ">

        <section class="filter-card mb-4">
            <div class="section-head">
                <div>
                    <h5 class="section-title mb-1">Find Applicants</h5>
                    <p class="section-copy mb-0">Search by applicant name, contact number, or gender.</p>
                </div>
                <div class="results-chip">
                    Showing {{ $showingFrom }}-{{ $showingTo }} of {{ number_format($totalApplicants) }}
                </div>
            </div>

            <form method="GET" action="{{ route('applicants.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-8">
                        <label class="form-label field-label">Search</label>
                        <div class="search-shell">
                            <span class="search-icon"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control index-field-control"
                                placeholder="Type applicant name, phone number, or gender..." value="{{ $searchTerm }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary index-btn-primary">
                                <i class="bi bi-funnel-fill me-2"></i>Search Records
                            </button>
                            <a href="{{ route('applicants.index') }}" class="btn index-btn-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </section>

        @if(session('success'))
            <div class="alert alert-success success-banner border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <section class="records-card">
            <div class="section-head mb-3">
                <div>
                    <h5 class="section-title mb-1">Applicant Records</h5>
                    <p class="section-copy mb-0">Review profile details and document readiness before opening the full
                        applicant workspace.</p>
                </div>
                @if($searchTerm !== '')
                    <div class="search-chip">
                        <i class="bi bi-stars me-1"></i>Search: "{{ $searchTerm }}"
                    </div>
                @endif
            </div>

            <div class="table-responsive d-none d-lg-block">
                <table class="table applicants-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Applicant</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Permit</th>
                            <th>Clearance</th>
                            <th>Referral</th>
                            <th class="text-center">Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applicants as $applicant)
                            @php
                                $fullName = trim($applicant->first_name . ' ' . ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') . $applicant->last_name . ' ' . ($applicant->suffix ?? ''));
                                $permit = optional($applicant->permit);
                                $isImusResident = $applicant->city && stripos($applicant->city, 'City of Imus') !== false;
                                $hasPermitClearance =
                                    ($permit->clearance_type === 'nbi' && !empty($permit->permit_nbi_clearance)) ||
                                    ($permit->clearance_type === 'police' && !empty($permit->permit_police_clearance));
                                $permitRequirements = [!empty($permit->health_card), !empty($permit->cedula), $hasPermitClearance];
                                if (!$isImusResident) {
                                    $permitRequirements[] = !empty($permit->referral_letter);
                                }
                                $permitTotal = count($permitRequirements);
                                $permitUploaded = collect($permitRequirements)->filter()->count();
                                $permitPercent = $permitTotal > 0 ? ($permitUploaded / $permitTotal) * 100 : 0;

                                $clearance = optional($applicant->clearance);
                                $clearanceRequirements = [
                                    $clearance->prosecutor_clearance,
                                    $clearance->mtc_clearance,
                                    $clearance->rtc_clearance,
                                    $clearance->nbi_clearance,
                                    $clearance->barangay_clearance,
                                ];
                                $clearanceUploaded = collect($clearanceRequirements)->filter()->count();
                                $clearanceTotal = count($clearanceRequirements);
                                $clearancePercent = $clearanceTotal > 0 ? ($clearanceUploaded / $clearanceTotal) * 100 : 0;

                                $referral = optional($applicant->referral);
                                $hasResume = !empty($referral->resume);
                                $hasReferralClearance = collect([
                                    $referral->ref_barangay_clearance,
                                    $referral->ref_police_clearance,
                                    $referral->ref_nbi_clearance,
                                ])->filter()->count() > 0;
                                $referralUploaded = ($hasResume ? 1 : 0) + ($hasReferralClearance ? 1 : 0);
                                $referralPercent = ($referralUploaded / 2) * 100;
                                $completedStages = collect([$permitPercent == 100, $clearancePercent == 100, $referralPercent == 100])->filter()->count();
                            @endphp
                            <tr>
                                <td class="text-center table-id">#{{ $applicant->id }}</td>
                                <td>
                                    <div class="applicant-name">{{ $fullName }}</div>
                                </td>
                                <td>
                                    <div class="contact-line"></i>{{ $applicant->contact_no ?: 'No contact number' }}</div>
                                </td>
                                <td class="location-cell">
                                    <div>{{ $applicant->address_line ?: 'Address line not set' }}</div>
                                    <div class="applicant-meta mt-1">{{ $applicant->barangay ?: 'Barangay not set' }},
                                        {{ $applicant->city ?: 'City not set' }}</div>
                                </td>
                                <td>
                                    <div class="requirement-card">
                                        <div class="requirement-meta"><span>Mayor's
                                                Permit</span><span>{{ $permitUploaded }}/{{ $permitTotal }}</span></div>
                                        <div class="progress requirement-progress">
                                            <div class="progress-bar {{ $permitPercent == 100 ? 'bg-success' : ($permitPercent > 0 ? 'bg-warning' : 'bg-danger') }}"
                                                style="width: {{ $permitPercent }}%;"></div>
                                        </div>
                                        <div class="requirement-note">{{ round($permitPercent) }}% submitted</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="requirement-card">
                                        <div class="requirement-meta"><span>Mayor's
                                                Clearance</span><span>{{ $clearanceUploaded }}/{{ $clearanceTotal }}</span>
                                        </div>
                                        <div class="progress requirement-progress">
                                            <div class="progress-bar {{ $clearancePercent == 100 ? 'bg-success' : ($clearancePercent > 0 ? 'bg-warning' : 'bg-danger') }}"
                                                style="width: {{ $clearancePercent }}%;"></div>
                                        </div>
                                        <div class="requirement-note">{{ round($clearancePercent) }}% submitted</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="requirement-card">
                                        <div class="requirement-meta"><span>Mayor's
                                                Referral</span><span>{{ $referralUploaded }}/2</span></div>
                                        <div class="progress requirement-progress">
                                            <div class="progress-bar {{ $referralPercent == 100 ? 'bg-success' : ($referralPercent > 0 ? 'bg-warning' : 'bg-danger') }}"
                                                style="width: {{ $referralPercent }}%;"></div>
                                        </div>
                                        <div class="requirement-note">{{ round($referralPercent) }}% submitted</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="created-date">{{ $applicant->created_at->format('M d, Y') }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="action-stack">
                                        <a href="{{ route('applicants.edit', $applicant->id) }}" class="btn btn-sm btn-view"
                                            title="View Applicant"><i class="bi bi-eye-fill"></i></a>
                                        <form action="{{ route('applicants.destroy', $applicant->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to archive this applicant?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-archive" title="Archive Applicant"><i
                                                    class="bi bi-archive-fill"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center p-5">
                                    <div class="empty-state">
                                        <i class="bi bi-people"></i>
                                        <h6 class="mb-1">No applicants found</h6>
                                        <p class="mb-0">Try adjusting the search term to bring more records into view.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mobile-records d-lg-none">
                @forelse($applicants as $applicant)
                    @php
                        $fullName = trim($applicant->first_name . ' ' . ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') . $applicant->last_name . ' ' . ($applicant->suffix ?? ''));
                        $permit = optional($applicant->permit);
                        $isImusResident = $applicant->city && stripos($applicant->city, 'City of Imus') !== false;
                        $hasPermitClearance =
                            ($permit->clearance_type === 'nbi' && !empty($permit->permit_nbi_clearance)) ||
                            ($permit->clearance_type === 'police' && !empty($permit->permit_police_clearance));
                        $permitRequirements = [!empty($permit->health_card), !empty($permit->cedula), $hasPermitClearance];
                        if (!$isImusResident) {
                            $permitRequirements[] = !empty($permit->referral_letter);
                        }
                        $permitTotal = count($permitRequirements);
                        $permitUploaded = collect($permitRequirements)->filter()->count();
                        $permitPercent = $permitTotal > 0 ? ($permitUploaded / $permitTotal) * 100 : 0;

                        $clearance = optional($applicant->clearance);
                        $clearanceRequirements = [
                            $clearance->prosecutor_clearance,
                            $clearance->mtc_clearance,
                            $clearance->rtc_clearance,
                            $clearance->nbi_clearance,
                            $clearance->barangay_clearance,
                        ];
                        $clearanceUploaded = collect($clearanceRequirements)->filter()->count();
                        $clearanceTotal = count($clearanceRequirements);
                        $clearancePercent = $clearanceTotal > 0 ? ($clearanceUploaded / $clearanceTotal) * 100 : 0;

                        $referral = optional($applicant->referral);
                        $hasResume = !empty($referral->resume);
                        $hasReferralClearance = collect([
                            $referral->ref_barangay_clearance,
                            $referral->ref_police_clearance,
                            $referral->ref_nbi_clearance,
                        ])->filter()->count() > 0;
                        $referralUploaded = ($hasResume ? 1 : 0) + ($hasReferralClearance ? 1 : 0);
                        $referralPercent = ($referralUploaded / 2) * 100;
                    @endphp
                    <article class="mobile-record-card">
                        <div class="mobile-record-head">
                            <div>
                                <div class="applicant-name">{{ $fullName }}</div>
                                <div class="applicant-meta">#{{ $applicant->id }} •
                                    {{ $applicant->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="mobile-actions">
                                <a href="{{ route('applicants.edit', $applicant->id) }}" class="btn btn-sm btn-view"
                                    title="View Applicant"><i class="bi bi-eye-fill"></i></a>
                                <form action="{{ route('applicants.destroy', $applicant->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to archive this applicant?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-archive" title="Archive Applicant"><i
                                            class="bi bi-archive-fill"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="mini-pill-row mb-3">
                            <span class="mini-pill">{{ $applicant->gender ?: 'N/A' }}</span>
                            <span class="mini-pill">{{ $applicant->civil_status ?: 'Status not set' }}</span>
                            <span class="mini-pill">4Ps: {{ $applicant->four_ps ?: 'N/A' }}</span>
                        </div>
                        <div class="mobile-meta-line"><i
                                class="bi bi-telephone-fill me-2"></i>{{ $applicant->contact_no ?: 'No contact number' }}</div>
                        <div class="mobile-meta-line"><i
                                class="bi bi-geo-alt-fill me-2"></i>{{ $applicant->barangay ?: 'Barangay not set' }},
                            {{ $applicant->city ?: 'City not set' }}</div>
                        <div class="mobile-progress-list">
                            <div class="requirement-card">
                                <div class="requirement-meta"><span>Mayor's
                                        Permit</span><span>{{ $permitUploaded }}/{{ $permitTotal }}</span></div>
                                <div class="progress requirement-progress">
                                    <div class="progress-bar {{ $permitPercent == 100 ? 'bg-success' : ($permitPercent > 0 ? 'bg-warning' : 'bg-danger') }}"
                                        style="width: {{ $permitPercent }}%;"></div>
                                </div>
                            </div>
                            <div class="requirement-card">
                                <div class="requirement-meta"><span>Mayor's
                                        Clearance</span><span>{{ $clearanceUploaded }}/{{ $clearanceTotal }}</span></div>
                                <div class="progress requirement-progress">
                                    <div class="progress-bar {{ $clearancePercent == 100 ? 'bg-success' : ($clearancePercent > 0 ? 'bg-warning' : 'bg-danger') }}"
                                        style="width: {{ $clearancePercent }}%;"></div>
                                </div>
                            </div>
                            <div class="requirement-card">
                                <div class="requirement-meta"><span>Mayor's
                                        Referral</span><span>{{ $referralUploaded }}/2</span></div>
                                <div class="progress requirement-progress">
                                    <div class="progress-bar {{ $referralPercent == 100 ? 'bg-success' : ($referralPercent > 0 ? 'bg-warning' : 'bg-danger') }}"
                                        style="width: {{ $referralPercent }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <h6 class="mb-1">No applicants found</h6>
                        <p class="mb-0">Try adjusting the search term to bring more records into view.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <div class="pagination-wrap mt-4">
            <div class="pagination-summary">Showing {{ $showingFrom }} to {{ $showingTo }} of
                {{ number_format($totalApplicants) }} applicants</div>
            <div>{{ $applicants->appends(request()->query())->links() }}</div>
        </div>
    </div>

    <style>
        .applicant-index-page {
            max-width: 1800px;
        }

        .index-hero {
            overflow: hidden;
            background: radial-gradient(circle at top right, rgba(14, 165, 233, 0.16), transparent 28%), radial-gradient(circle at left bottom, rgba(16, 185, 129, 0.14), transparent 32%), linear-gradient(135deg, #ffffff, #f3f8ff 58%, #edf7f5);
            border: 1px solid rgba(203, 213, 225, 0.8);
            border-radius: 28px;
            padding: 1rem;
            box-shadow: 0 22px 46px rgba(15, 23, 42, 0.08);
        }

        .index-kicker,
        .overview-label,
        .field-label,
        .hero-side-label {
            display: inline-flex;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.76rem;
            font-weight: 800;
        }

        .index-kicker {
            margin-bottom: 0.9rem;
            padding: 0.48rem 0.85rem;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.1);
            color: #1d4ed8;
        }

        .index-title {
            font-size: clamp(2rem, 3vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #0f172a;
        }

        .index-copy,
        .section-copy,
        .overview-copy,
        .applicant-meta,
        .pagination-summary,
        .requirement-note,
        .hero-side-copy {
            color: #64748b;
        }

        .hero-side-card,
        .overview-card,
        .filter-card,
        .records-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 22px;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.06);
        }

        .hero-side-card {
            background: linear-gradient(180deg, #0f172a, #172554);
            color: #e2e8f0;
            padding: 1.5rem;
            min-height: 100%;
        }

        .hero-side-label {
            color: #93c5fd;
            margin-bottom: 0.7rem;
        }

        .hero-side-title {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 0.75rem;
        }

        .hero-side-actions,
        .filter-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .index-btn-primary,
        .index-btn-secondary {
            border-radius: 14px;
            padding: 0.82rem 1.15rem;
            font-weight: 700;
        }

        .index-btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.2);
        }

        .index-btn-secondary {
            background: #fff;
            border: 1px solid #dbeafe;
            color: #1e3a8a;
        }

        .view-switcher {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .view-pill {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            border-radius: 20px;
            text-decoration: none;
            color: inherit;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .view-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
            border-color: rgba(37, 99, 235, 0.25);
        }

        .view-pill.active {
            background: linear-gradient(135deg, #eff6ff, #f8fafc);
            border-color: rgba(37, 99, 235, 0.25);
        }

        .view-pill strong,
        .applicant-name,
        .section-title {
            color: #0f172a;
            font-weight: 800;
        }

        .view-pill small {
            display: block;
            color: #64748b;
            margin-top: 0.2rem;
        }

        .view-pill-icon {
            width: 50px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: rgba(37, 99, 235, 0.12);
            color: #1d4ed8;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .view-pill-icon.archive {
            background: rgba(148, 163, 184, 0.18);
            color: #334155;
        }

        .overview-card,
        .filter-card,
        .records-card {
            padding: 1.5rem;
        }

        .overview-card {
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .overview-card::after {
            content: "";
            position: absolute;
            right: -18px;
            bottom: -24px;
            width: 92px;
            height: 92px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.08);
        }

        .overview-icon {
            width: 54px;
            height: 54px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .icon-blue {
            background: rgba(37, 99, 235, 0.12);
            color: #2563eb;
        }

        .icon-emerald {
            background: rgba(5, 150, 105, 0.12);
            color: #059669;
        }

        .icon-amber {
            background: rgba(245, 158, 11, 0.14);
            color: #b45309;
        }

        .icon-slate {
            background: rgba(71, 85, 105, 0.12);
            color: #334155;
        }

        .overview-value {
            font-size: 2.1rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            margin: 0.35rem 0 0.55rem;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.4rem;
        }

        .search-shell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8fafc;
            border: 1px solid #dbeafe;
            border-radius: 18px;
            padding: 0 0.95rem;
        }

        .search-icon {
            color: #64748b;
        }

        .index-field-control {
            min-height: 54px;
            border: none;
            background: transparent;
        }

        .index-field-control:focus {
            box-shadow: none;
            background: transparent;
        }

        .results-chip,
        .search-chip,
        .mini-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .results-chip {
            padding: 0.55rem 0.9rem;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .search-chip {
            padding: 0.55rem 0.9rem;
            background: #f1f5f9;
            color: #334155;
        }

        .success-banner {
            border-radius: 18px;
            padding: 1rem 1.25rem;
        }

        .applicants-table thead th {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 1rem;
            white-space: nowrap;
        }

        .applicants-table tbody td {
            padding: 1rem;
            border-top: 1px solid #eef2f7;
            vertical-align: middle;
        }

        .applicants-table tbody tr:hover {
            background: #f8fbff;
        }

        .table-id,
        .created-date {
            color: #1f1f1f;
            font-weight: 400;
        }

        .mini-pill-row {
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .mini-pill {
            padding: 0.34rem 0.72rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
        }

        .contact-line,
        .mobile-meta-line {
            color: #334155;
            font-weight: 600;
        }

        .location-cell {
            min-width: 230px;
            color: #334155;
        }

        .requirement-card {
            min-width: 100px;
        }

        .requirement-meta {
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.45rem;
            color: #334155;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .requirement-progress {
            height: 8px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .requirement-note {
            margin-top: 0.45rem;
            font-size: 0.76rem;
        }

        .action-stack,
        .mobile-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-view,
        .btn-archive {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
        }

        .btn-view {
            background: rgba(37, 99, 235, 0.12);
            color: #1d4ed8;
        }

        .btn-view:hover {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-archive {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }

        .btn-archive:hover {
            background: #fee2e2;
            color: #991b1b;
        }

        .mobile-records {
            display: grid;
            gap: 1rem;
        }

        .mobile-record-card {
            padding: 1rem;
            border-radius: 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .mobile-record-head {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .mobile-progress-list {
            display: grid;
            gap: 0.85rem;
            margin-top: 1rem;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
            min-height: 260px;
            border: 1px dashed #cbd5e1;
            border-radius: 20px;
            background: #f8fafc;
            color: #64748b;
            text-align: center;
        }

        .empty-state i {
            font-size: 2.2rem;
            color: #94a3b8;
        }

        .pagination-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .pagination {
            gap: 0.4rem;
        }

        .page-link {
            border-radius: 10px;
            border: 1px solid #dbeafe;
            color: #334155;
        }

        .page-item.active .page-link {
            background: #2563eb;
            border-color: #2563eb;
        }

        @media (max-width: 1199.98px) {

            .hero-side-actions,
            .filter-actions {
                justify-content: flex-start;
            }
        }

        @media (max-width: 991.98px) {

            .index-hero,
            .overview-card,
            .filter-card,
            .records-card {
                padding: 1.25rem;
            }

            .view-switcher {
                grid-template-columns: 1fr;
            }

            .section-head,
            .mobile-record-head {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 767.98px) {

            .filter-actions,
            .hero-side-actions {
                flex-direction: column;
            }

            .index-btn-primary,
            .index-btn-secondary {
                width: 100%;
            }

            .pagination-wrap {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection