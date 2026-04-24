@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-page container-fluid px-md-4 px-xl-1">
        <section class="hero-panel mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <span class="eyebrow">Applicant Registry Overview</span>
                    <h2 class="hero-title mb-2">Welcome back, {{ Auth::user()->name }}</h2>
                    <p class="hero-copy mb-0">
                        Track applicant progress, monitor document completion, and jump straight into the tasks that need
                        attention today.
                    </p>
                </div>
            </div>
        </section>

        <section class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="metric-card h-100">
                    <div class="metric-icon icon-blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="metric-label">Total Applicants</div>
                        <div class="metric-value">{{ number_format($summary['totalApplicants']) }}</div>
                        <div class="metric-note">{{ $summary['newThisMonth'] }} added this month</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="metric-card h-100">
                    <div class="metric-icon icon-emerald">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div>
                        <div class="metric-label">Total Permit</div>
                        <div class="metric-value">{{ data_get($completion, 'permit.count', 0) }}</div>
                        <div class="metric-note">Applicants who have completed</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="metric-card h-100">
                    <div class="metric-icon icon-slate">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <div class="metric-label">Total Clearance</div>
                        <div class="metric-value">{{ number_format($summary['totalClearances']) }}</div>
                        <div class="metric-note">Clearance with clearance records</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="metric-card h-100">
                    <div class="metric-icon icon-amber">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div>
                        <div class="metric-label">Total Referral</div>
                        <div class="metric-value">{{ number_format($summary['totalReferrals']) }}</div>
                        <div class="metric-note">Including extra employer details</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-4 mb-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="section-header">
                        <div>
                            <h5 class="section-title mb-1">{{ now()->format('Y') }} Applicant Registrations Summary</h5>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-canvas-wrap">
                            <canvas id="monthlyApplicantsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-4 mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="dashboard-card h-100">
                    <div class="section-header">
                        <div>
                            <h5 class="section-title mb-1">Total of Male and Female Summary</h5>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-canvas-wrap chart-canvas-wrap--pie">
                            <canvas id="sexPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="dashboard-card h-100">
                    <div class="section-header">
                        <div>
                            <h5 class="section-title mb-1">PWD Summary</h5>
                            <p class="section-copy mb-0">Applicants marked as PWD:</p>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-canvas-wrap chart-canvas-wrap--pie">
                            <canvas id="pwdPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-12">
                <div class="dashboard-card h-100">
                    <div class="section-header">
                        <div>
                            <h5 class="section-title mb-1">4Ps Summary</h5>
                            <p class="section-copy mb-0">Applicants marked as 4Ps:</p>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-canvas-wrap chart-canvas-wrap--pie">
                            <canvas id="fourPsPieChart"></canvas>
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

        .chart-canvas-wrap--pie {
            height: 300px;
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
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthlyLabels = @json($monthlyApplicants->pluck('label')->values());
            const monthlyData = @json($monthlyApplicants->pluck('count')->values());

            const sexLabels = @json($genderBreakdown->pluck('label')->values());
            const sexData = @json($genderBreakdown->pluck('count')->values());

            const pwdLabels = @json($pwdBreakdown->pluck('label')->values());
            const pwdData = @json($pwdBreakdown->pluck('count')->values());

            const fourPsLabels = @json($fourPsBreakdown->pluck('label')->values());
            const fourPsData = @json($fourPsBreakdown->pluck('count')->values());

            const cityLabels = @json($cityBreakdown->pluck('label')->values());
            const cityData = @json($cityBreakdown->pluck('count')->values());

            const provinceLabels = @json($provinceBreakdown->pluck('label')->values());
            const provinceData = @json($provinceBreakdown->pluck('count')->values());

            const pieOptions = {
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
            };

            const columnOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 25,
                        },
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
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

            createChart('monthlyApplicantsChart', {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        data: monthlyData,
                        backgroundColor: '#2563eb',
                        borderRadius: 8,
                        maxBarThickness: 42,
                    }],
                },
                options: columnOptions,
            });

            createChart('sexPieChart', {
                type: 'pie',
                data: {
                    labels: sexLabels,
                    datasets: [{
                        data: sexData,
                        backgroundColor: ['#2563eb', '#ec4899'],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                    }],
                },
                options: pieOptions,
            });

            createChart('pwdPieChart', {
                type: 'pie',
                data: {
                    labels: pwdLabels,
                    datasets: [{
                        data: pwdData,
                        backgroundColor: ['#10b981', '#f59e0b'],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                    }],
                },
                options: pieOptions,
            });

            createChart('fourPsPieChart', {
                type: 'pie',
                data: {
                    labels: fourPsLabels,
                    datasets: [{
                        data: fourPsData,
                        backgroundColor: ['#8b5cf6', '#f43f5e'],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                    }],
                },
                options: pieOptions,
            });

            createChart('cityColumnChart', {
                type: 'bar',
                data: {
                    labels: cityLabels,
                    datasets: [{
                        data: cityData,
                        backgroundColor: '#7c3aed',
                        borderRadius: 8,
                        maxBarThickness: 42,
                    }],
                },
                options: columnOptions,
            });

            createChart('provinceColumnChart', {
                type: 'bar',
                data: {
                    labels: provinceLabels,
                    datasets: [{
                        data: provinceData,
                        backgroundColor: '#475569',
                        borderRadius: 8,
                        maxBarThickness: 42,
                    }],
                },
                options: columnOptions,
            });
        });
    </script>
@endsection
