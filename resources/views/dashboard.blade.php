@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid py-4 px-md-5">

        <div class="mb-5">
            <h2 class="fw-800 text-dark">Welcome back, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-muted">Here’s what’s happening with your applicant management system today.</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary-light text-primary me-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold text-uppercase">Total Applicants</small>
                            <h4 class="mb-0 fw-bold">{{ \App\Models\Applicant::count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success-light text-success me-3">
                            <i class="bi bi-file-earmark-check-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold text-uppercase">Completed Docs</small>
                            <h4 class="mb-0 fw-bold">--</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning-light text-warning me-3">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold text-uppercase">Pending Referrals</small>
                            <h4 class="mb-0 fw-bold">--</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('applicants.create') }}"
                                class="btn btn-primary text-start p-3 border-0 shadow-none">
                                <i class="bi bi-person-plus-fill me-2"></i> Register New Applicant
                            </a>
                            <a href="{{ route('applicants.index') }}" class="btn btn-light text-start p-3 border-0">
                                <i class="bi bi-search me-2"></i> Search Applicants
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">System Status</h6>
                        <span class="badge bg-soft-primary text-primary">Live Updates</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5>System Secure & Operational</h5>
                        <p class="text-muted px-md-5">All services are running normally. You can start managing your
                            applicant requirements and generating referrals.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fw-800 {
            font-weight: 800;
        }

        /* Custom Icon Backgrounds */
        .stats-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.5rem;
        }

        .bg-primary-light {
            background-color: rgba(79, 70, 229, 0.1);
        }

        .bg-success-light {
            background-color: rgba(16, 185, 129, 0.1);
        }

        .bg-warning-light {
            background-color: rgba(245, 158, 11, 0.1);
        }

        .bg-soft-primary {
            background-color: #e0e7ff;
        }

        .btn-primary {
            background-color: #4f46e5;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .card {
            border-radius: 12px;
        }
    </style>
@endsection