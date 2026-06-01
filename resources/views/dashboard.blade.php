@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $totalApplicants = data_get($summary, 'totalApplicants', 0);
        $totalArchivedApplicants = data_get($summary, 'totalArchivedApplicants', 0);
        $newThisMonth = data_get($summary, 'newThisMonth', 0);
        $totalClearances = data_get($summary, 'totalClearances', 0);
        $totalReferrals = data_get($summary, 'totalReferrals', 0);
        $maleCount = data_get($genderBreakdown->firstWhere('label', 'MALE'), 'count', 0);
        $femaleCount = data_get($genderBreakdown->firstWhere('label', 'FEMALE'), 'count', 0);
        $pwdYesCount = data_get($pwdBreakdown->firstWhere('label', 'YES'), 'count', 0);
        $pwdNoCount = data_get($pwdBreakdown->firstWhere('label', 'NO'), 'count', 0);
        $fourPsYesCount = data_get($fourPsBreakdown->firstWhere('label', 'YES'), 'count', 0);
        $fourPsNoCount = data_get($fourPsBreakdown->firstWhere('label', 'NO'), 'count', 0);
        $monthlyRegistrationLabels = $trendMonths ?? [];
        $yearlyApplicantTrendLabels = $trendMonths ?? [];
        $yearlyApplicantTrendDatasets = $yearlyApplicantTrendDatasets ?? [];
        $timeGreeting = (now()->hour >= 0 && now()->hour <= 11) ? 'Good Morning' : ((now()->hour >= 12 && now()->hour <= 17) ? 'Good Afternoon' : 'Good Evening');
        $isAdminOrStaff = auth()->check() && auth()->user()?->role !== \App\Models\User::ROLE_USER;
    @endphp

    <div class="dashboard-page container-fluid py-0 px-md-4 px-xl-0">
        <section class="hero-panel mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <span class="eyebrow">Applicant Registry Overview</span>
                    <h2 class="hero-title mb-2">{{ $timeGreeting }}, {{ Auth::user()->name }}</h2>
                    <p class="hero-copy mb-0">
                        Track applicant progress, monitor document completion, and jump straight into the tasks that need
                        attention today.
                    </p>
                </div>
                @if($isAdminOrStaff)
                    <div class="col-lg-4 d-flex justify-content-lg-end align-items-start">
                        <button type="button" id="exportChartsButton" class="btn btn-light fw-bold px-4">
                            <i class="bi bi-download me-2"></i>Export Charts
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <section class="row row-cols-1 row-cols-md-2 row-cols-xl-5 g-3 mb-4">
            <div class="col">
                <div class="metric-card h-100">
                    <div class="metric-icon icon-blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="metric-label">Total Applicants</div>
                        <div class="metric-value">{{ number_format($totalApplicants) }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="metric-card h-100">
                    <div class="metric-icon icon-slate">
                        <i class="bi bi-archive-fill"></i>
                    </div>
                    <div>
                        <div class="metric-label">Total Archive</div>
                        <div class="metric-value">{{ number_format($totalArchivedApplicants) }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="metric-card h-100">
                    <div class="metric-icon icon-emerald">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div>
                        <div class="metric-label">Total Permit</div>
                        <div class="metric-value">{{ data_get($completion, 'permit.count', 0) }}</div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="metric-card h-100">
                    <div class="metric-icon icon-slate">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <div class="metric-label">Total Clearance</div>
                        <div class="metric-value">{{ number_format($totalClearances) }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="metric-card h-100">
                    <div class="metric-icon icon-amber">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div>
                        <div class="metric-label">Total Referral</div>
                        <div class="metric-value">{{ number_format($totalReferrals) }}</div>
                    </div>
                </div>
            </div>
        </section>

        @unless(auth()->user()?->role === 'user')
            <section class="row g-4 mb-4">
                <div class="col-12">
                    <div class="dashboard-card monthly-registration-card">
                        <div class="section-header">
                            <div>
                                <h5 class="section-title mb-1">Monthly Registration</h5>
                            </div>
                            <div class="year-filter-wrap">
                                <label for="trendYearFilter" class="visually-hidden">Filter by year</label>
                                <select id="trendYearFilter" class="form-select form-select-sm year-filter">
                                    <option value="all">All years</option>
                                    @foreach($trendYears as $trendYear)
                                        <option value="{{ $trendYear }}">{{ $trendYear }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-canvas-wrap chart-canvas-wrap--line">
                                <canvas id="monthlyRegistrationChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="row g-4 mb-4">
                <div class="col-xl-4">
                    <div class="dashboard-card h-100">
                        <div class="section-header">
                            <div>
                                <h5 class="section-title mb-1">Male and Female Summary</h5>
                                <p class="section-copy mb-0">Applicant gender breakdown.</p>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-canvas-wrap chart-canvas-wrap--pie">
                                <canvas id="sexPolarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="dashboard-card h-100">
                        <div class="section-header">
                            <div>
                                <h5 class="section-title mb-1">PWD Summary</h5>
                                <p class="section-copy mb-0">Applicant mark as PWD.</p>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-canvas-wrap chart-canvas-wrap--pie">
                                <canvas id="pwdPolarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="dashboard-card h-100">
                        <div class="section-header">
                            <div>
                                <h5 class="section-title mb-1">4Ps Summary</h5>
                                <p class="section-copy mb-0">Applicants marked as 4Ps.</p>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-canvas-wrap chart-canvas-wrap--pie">
                                <canvas id="fourPsPolarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="row g-4 mb-4">
                <div class="col-xl-6">
                    <div class="dashboard-card h-100">
                        <div class="section-header">
                            <div>
                                <h5 class="section-title mb-1">City Summary</h5>
                                <p class="section-copy mb-0">Top 10 cities or municipalities based on applicant count.</p>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-canvas-wrap">
                                <canvas id="cityColumnChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="dashboard-card h-100">
                        <div class="section-header">
                            <div>
                                <h5 class="section-title mb-1">Province Summary</h5>
                                <p class="section-copy mb-0">Top 10 provinces based on applicant count.</p>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-canvas-wrap">
                                <canvas id="provinceColumnChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endunless
    </div>

    <style>
        .dashboard-page {
            max-width: 1800px;
        }

        .hero-panel {
            background: white;
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 0.85rem;
            padding: 0.4rem 0.8rem;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: clamp(1.9rem, 3vw, 2.75rem);
            font-weight: 800;
            color: #0f172a;
        }

        .hero-copy,
        .section-copy,
        .list-copy,
        .metric-note {
            color: #64748b;
        }

        .hero-highlight {
            background: #0f172a;
            color: #e2e8f0;
            border-radius: 20px;
            padding: 1.5rem;
            min-height: 100%;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
        }

        .hero-highlight-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
        }

        .hero-highlight-value {
            font-size: 3rem;
            line-height: 1;
            font-weight: 800;
            margin: 0.75rem 0 0.5rem;
        }

        .hero-highlight-note {
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        .metric-card,
        .dashboard-card {
            background: #fff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 20px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
        }

        .metric-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.4rem;
        }

        .metric-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .icon-blue {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .icon-emerald {
            background: rgba(22, 163, 74, 0.12);
            color: #15803d;
        }

        .icon-amber {
            background: rgba(245, 158, 11, 0.15);
            color: #b45309;
        }

        .icon-slate {
            background: rgba(71, 85, 105, 0.12);
            color: #334155;
        }

        .metric-label {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            font-weight: 700;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            margin: 0.35rem 0;
        }

        .dashboard-card {
            padding: 1.5rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-weight: 800;
            color: #0f172a;
        }

        .year-filter-wrap {
            min-width: 150px;
        }

        .year-filter {
            min-width: 150px;
            border-radius: 12px;
            border-color: #dbe6f2;
            color: #334155;
            box-shadow: none;
        }

        .progress-list {
            display: flex;
            flex-direction: column;
        }

        .progress-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .progress-title,
        .list-title,
        .action-title {
            font-weight: 700;
            color: #0f172a;
        }

        .progress-subtitle {
            font-size: 0.9rem;
            color: #64748b;
        }

        .progress-value {
            font-weight: 800;
            color: #0f172a;
        }

        .dashboard-progress {
            height: 12px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .progress-bar-referral {
            background: #7c3aed;
        }

        .action-grid {
            display: grid;
            gap: 0.9rem;
        }

        .chart-card {
            padding-top: 0.5rem;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 1rem;
            align-items: end;
            min-height: 320px;
        }

        .chart-grid--compact {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            min-height: 260px;
        }

        .chart-grid--top {
            grid-template-columns: repeat(10, minmax(0, 1fr));
        }

        .chart-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.65rem;
            min-width: 0;
        }

        .chart-value {
            font-weight: 800;
            color: #0f172a;
        }

        .chart-bar-wrap {
            width: 100%;
            height: 220px;
            border-radius: 18px;
            background: linear-gradient(to top, rgba(148, 163, 184, 0.08), rgba(148, 163, 184, 0.18));
            padding: 0.65rem;
            display: flex;
            align-items: end;
        }

        .chart-bar {
            width: 100%;
            border-radius: 14px 14px 10px 10px;
            background: linear-gradient(180deg, #7997da, #2960e0);
            box-shadow: 0 14px 24px rgba(88, 100, 124, 0.22);
            transition: height 0.3s ease;
        }

        .chart-bar--emerald {
            background: linear-gradient(180deg, #6ee7b7, #059669);
        }

        .chart-bar--amber {
            background: linear-gradient(180deg, #fcd34d, #d97706);
        }

        .chart-bar--violet {
            background: linear-gradient(180deg, #c4b5fd, #7c3aed);
        }

        .chart-bar--slate {
            background: linear-gradient(180deg, #cbd5e1, #475569);
        }

        .chart-bar--rose {
            background: linear-gradient(180deg, #fda4af, #e11d48);
        }

        .chart-label {
            font-size: 0.86rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
            word-break: break-word;
        }

        .chart-label--stacked {
            min-height: 2.8rem;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .chart-bar-wrap--short {
            height: 180px;
        }

        .chart-canvas-wrap {
            position: relative;
            height: 360px;
            width: 100%;
        }

        .chart-canvas-wrap--line {
            height: 380px;
        }

        .chart-canvas-wrap--pie {
            height: 300px;
        }

        .monthly-registration-card .section-header {
            margin-bottom: 0.5rem;
        }

        .monthly-registration-card .chart-card {
            padding-top: 0;
        }

        .monthly-registration-card .chart-canvas-wrap--line {
            height: 420px;
        }

        .chart-canvas-wrap canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .action-tile {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.1rem;
            border-radius: 16px;
            text-decoration: none;
            color: inherit;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .action-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
            border-color: rgba(13, 110, 253, 0.2);
        }

        .action-tile i {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #ffffff;
            color: #0d6efd;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .action-primary {
            background: linear-gradient(135deg, #0d6efd, #2563eb);
            color: #fff;
            border-color: transparent;
        }

        .action-primary i,
        .action-primary .action-copy,
        .action-primary .action-title {
            color: inherit;
        }

        .list-stack {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
        }

        .list-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.7rem;
            background: #e2e8f0;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #334155;
        }

        .activity-dot {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            flex-shrink: 0;
        }

        .activity-module {
            margin-left: 0.45rem;
            color: #0d6efd;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            min-height: 220px;
            text-align: center;
            color: #64748b;
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            background: #f8fafc;
        }

        .empty-state i {
            font-size: 2rem;
            color: #94a3b8;
        }

        @media (max-width: 991.98px) {

            .hero-panel,
            .dashboard-card {
                padding: 1.25rem;
            }

            .section-header,
            .progress-row,
            .list-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .list-item .text-end {
                text-align: left !important;
            }

            .chart-grid {
                grid-template-columns: repeat(6, minmax(0, 1fr));
            }

            .chart-grid--compact {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .chart-grid--top {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .chart-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .chart-grid--compact {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .chart-grid--top {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .chart-bar-wrap {
                height: 180px;
            }
        }

        html[data-theme="night"] .hero-panel,
        html[data-theme="night"] .metric-card,
        html[data-theme="night"] .dashboard-card,
        html[data-theme="night"] .list-item,
        html[data-theme="night"] .action-card,
        html[data-theme="night"] .summary-card,
        html[data-theme="night"] .progress-card {
            background: #0f172a;
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
        }

        html[data-theme="night"] .hero-title,
        html[data-theme="night"] .section-title,
        html[data-theme="night"] .metric-value,
        html[data-theme="night"] .progress-title,
        html[data-theme="night"] .list-title,
        html[data-theme="night"] .action-title,
        html[data-theme="night"] .chart-value {
            color: #f8fafc;
        }

        html[data-theme="night"] .hero-copy,
        html[data-theme="night"] .section-copy,
        html[data-theme="night"] .list-copy,
        html[data-theme="night"] .metric-note,
        html[data-theme="night"] .progress-subtitle,
        html[data-theme="night"] .empty-state {
            color: #94a3b8;
        }

        html[data-theme="night"] .eyebrow {
            background: rgba(59, 130, 246, 0.16);
            color: #bfdbfe;
        }

        html[data-theme="night"] .hero-highlight {
            background: linear-gradient(180deg, #111827, #0f172a);
            color: #e2e8f0;
        }

        html[data-theme="night"] .metric-label,
        html[data-theme="night"] .hero-highlight-label,
        html[data-theme="night"] .empty-state i {
            color: #94a3b8;
        }

        html[data-theme="night"] .year-filter {
            background: #111827;
            border-color: rgba(148, 163, 184, 0.2);
            color: #e2e8f0;
        }

        html[data-theme="night"] .dashboard-progress {
            background: #1e293b;
        }

        html[data-theme="night"] .chart-bar-wrap {
            background: linear-gradient(to top, rgba(148, 163, 184, 0.08), rgba(148, 163, 184, 0.14));
        }

        html[data-theme="night"] .empty-state {
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(148, 163, 184, 0.18);
        }

        html[data-theme="night"] #exportChartsButton {
            background: #1e293b;
            color: #e2e8f0;
            border-color: rgba(148, 163, 184, 0.2);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exportChartsButton = document.getElementById('exportChartsButton');
            const monthlyRegistrationLabels = @json($monthlyRegistrationLabels);
            const yearlyTrendDatasets = @json($yearlyApplicantTrendDatasets);
            const trendYearFilter = document.getElementById('trendYearFilter');

            const chartMaxForDatasets = (datasets) => Math.max(...datasets.flatMap((dataset) => dataset.data), 0) + 2;
            const getDatasetsForYear = (year) => {
                if (year === 'all') {
                    return yearlyTrendDatasets;
                }

                return yearlyTrendDatasets.filter((dataset) => dataset.label === year);
            };
            const initialTrendDatasets = getDatasetsForYear(trendYearFilter?.value ?? 'all');
            const yearlyTrendMax = chartMaxForDatasets(initialTrendDatasets);

            const sexLabels = ['Male', 'Female'];
            const sexData = [
                @json((int) $maleCount),
                @json((int) $femaleCount),
            ];

            const pwdData = [
                @json((int) $pwdYesCount),
                @json((int) $pwdNoCount),
            ];

            const fourPsData = [
                @json((int) $fourPsYesCount),
                @json((int) $fourPsNoCount),
            ];

            const cityLabels = @json($cityBreakdown->pluck('label')->values());
            const cityData = @json($cityBreakdown->pluck('count')->values());

            const provinceLabels = @json($provinceBreakdown->pluck('label')->values());
            const provinceData = @json($provinceBreakdown->pluck('count')->values());

            const chartExportItems = [
                { id: 'monthlyRegistrationChart', title: 'Monthly Registration' },
                { id: 'sexPolarChart', title: 'Male and Female Summary' },
                { id: 'pwdPolarChart', title: 'PWD Summary' },
                { id: 'fourPsPolarChart', title: '4Ps Summary' },
                { id: 'cityColumnChart', title: 'City Summary' },
                { id: 'provinceColumnChart', title: 'Province Summary' },
            ];

            const getCanvasDataUrl = (canvas) => {
                if (!canvas) {
                    return null;
                }

                return canvas.toDataURL('image/png', 1.0);
            };

            const exportChartsToPdf = async () => {
                if (!window.jspdf || !window.jspdf.jsPDF) {
                    alert('PDF export is not available right now.');
                    return;
                }

                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('l', 'mm', 'a4');
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
                const margin = 8;
                const gap = 4;
                const columns = 2;
                const rows = 3;
                const cellWidth = (pageWidth - (margin * 2) - gap) / columns;
                const cellHeight = (pageHeight - (margin * 2) - (gap * (rows - 1))) / rows;
                const titleHeight = 8;

                chartExportItems.forEach((item, index) => {
                    const canvas = document.getElementById(item.id);

                    if (!canvas) {
                        return;
                    }

                    const image = getCanvasDataUrl(canvas);

                    if (!image) {
                        return;
                    }

                    const imgProps = pdf.getImageProperties(image);
                    const col = index % columns;
                    const row = Math.floor(index / columns);
                    const x = margin + (col * (cellWidth + gap));
                    const y = margin + (row * (cellHeight + gap));
                    const imageAreaWidth = cellWidth;
                    const imageAreaHeight = cellHeight - titleHeight;
                    const scale = Math.min(
                        imageAreaWidth / imgProps.width,
                        imageAreaHeight / imgProps.height
                    );
                    const drawWidth = imgProps.width * scale;
                    const drawHeight = imgProps.height * scale;
                    const drawX = x + ((imageAreaWidth - drawWidth) / 2);
                    const drawY = y + titleHeight + ((imageAreaHeight - drawHeight) / 2);

                    pdf.setFont('helvetica', 'bold');
                    pdf.setFontSize(11);
                    pdf.text(item.title, x, y + 4);
                    pdf.addImage(image, 'PNG', drawX, drawY, drawWidth, drawHeight);
                });

                pdf.save(`dashboard-charts-${new Date().toISOString().slice(0, 10)}.pdf`);
            };

            if (exportChartsButton) {
                exportChartsButton.addEventListener('click', exportChartsToPdf);
            }

            const lineOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 16,
                        },
                    },
                    tooltip: {
                        padding: 12,
                        displayColors: true,
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: true,
                            color: 'rgba(148, 163, 184, 0.18)',
                        },
                        ticks: {
                            color: '#475569',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#475569',
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.18)',
                        },
                    },
                },
                elements: {
                    line: {
                        tension: 0.35,
                        borderWidth: 3,
                    },
                    point: {
                        radius: 3,
                        hoverRadius: 6,
                    },
                },
            };

            const createChart = (id, config) => {
                const canvas = document.getElementById(id);

                if (!canvas) {
                    return;
                }

                new Chart(canvas, config);
            };

            const monthlyCanvas = document.getElementById('monthlyRegistrationChart');

            if (monthlyCanvas) {
                const monthlyRegistrationChart = new Chart(monthlyCanvas, {
                    type: 'line',
                    data: {
                        labels: monthlyRegistrationLabels,
                        datasets: initialTrendDatasets,
                    },
                    options: {
                        ...lineOptions,
                        plugins: {
                            ...lineOptions.plugins,
                            title: {
                                display: false,
                            },
                        },
                        scales: {
                            ...lineOptions.scales,
                            y: {
                                ...lineOptions.scales.y,
                                suggestedMax: yearlyTrendMax,
                            },
                        },
                    },
                });

                if (trendYearFilter) {
                    trendYearFilter.addEventListener('change', function () {
                        const selectedYear = this.value;
                        const datasets = getDatasetsForYear(selectedYear);

                        monthlyRegistrationChart.data.datasets = datasets;
                        monthlyRegistrationChart.options.scales.y.suggestedMax = chartMaxForDatasets(datasets);
                        monthlyRegistrationChart.update();
                    });
                }
            }

            createChart('sexPolarChart', {
                type: 'polarArea',
                data: {
                    labels: sexLabels,
                    datasets: [{
                        data: sexData,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.72)',
                            'rgba(244, 114, 182, 0.72)',
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 18,
                            },
                        },
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                backdropColor: 'transparent',
                                color: '#64748b',
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.18)',
                            },
                            angleLines: {
                                color: 'rgba(148, 163, 184, 0.18)',
                            },
                            pointLabels: {
                                color: '#334155',
                                font: {
                                    size: 13,
                                    weight: '600',
                                },
                            },
                        },
                    },
                },
            });

            createChart('pwdPolarChart', {
                type: 'polarArea',
                data: {
                    labels: ['PWD YES', 'PWD NO'],
                    datasets: [{
                        data: pwdData,
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.82)',
                            'rgba(245, 158, 11, 0.34)',
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 18,
                            },
                        },
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                backdropColor: 'transparent',
                                color: '#64748b',
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.18)',
                            },
                            angleLines: {
                                color: 'rgba(148, 163, 184, 0.18)',
                            },
                            pointLabels: {
                                color: '#334155',
                                font: {
                                    size: 13,
                                    weight: '600',
                                },
                            },
                        },
                    },
                },
            });

            createChart('fourPsPolarChart', {
                type: 'polarArea',
                data: {
                    labels: ['4Ps YES', '4Ps NO'],
                    datasets: [{
                        data: fourPsData,
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.82)',
                            'rgba(16, 185, 129, 0.34)',
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 18,
                            },
                        },
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                backdropColor: 'transparent',
                                color: '#64748b',
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.18)',
                            },
                            angleLines: {
                                color: 'rgba(148, 163, 184, 0.18)',
                            },
                            pointLabels: {
                                color: '#334155',
                                font: {
                                    size: 13,
                                    weight: '600',
                                },
                            },
                        },
                    },
                },
            });

            createChart('cityColumnChart', {
                type: 'line',
                data: {
                    labels: cityLabels,
                    datasets: [{
                        data: cityData,
                        label: 'City Count',
                        borderColor: '#e7ed3a',
                        backgroundColor: 'rgba(58, 237, 118, 0.14)',
                        pointBackgroundColor: '#e7ed3a',
                        pointBorderColor: '#ffffff',
                        fill: false,
                    }],
                },
                options: {
                    ...lineOptions,
                    plugins: {
                        ...lineOptions.plugins,
                        legend: {
                            display: false,
                        },
                    },
                },
            });

            createChart('provinceColumnChart', {
                type: 'line',
                data: {
                    labels: provinceLabels,
                    datasets: [{
                        data: provinceData,
                        label: 'Province Count',
                        borderColor: '#248a3d',
                        backgroundColor: 'rgba(71, 85, 105, 0.14)',
                        pointBackgroundColor: '#248a3d',
                        pointBorderColor: '#ffffff',
                        fill: false,
                    }],
                },
                options: {
                    ...lineOptions,
                    plugins: {
                        ...lineOptions.plugins,
                        legend: {
                            display: false,
                        },
                    },
                },
            });
        });
    </script>
@endsection
