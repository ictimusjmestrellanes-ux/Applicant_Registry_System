<nav class="navbar navbar-expand-lg navbar-light bg-white dashboard-navbar py-2">
    <div class="container-fluid px-md-4">
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="ms-auto" id="navbarNav">
            <ul class="navbar-nav align-items-center">

                <li class="nav-item me-2">
                    <a class="nav-link nav-icon-btn" href="#">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge"></span>
                    </a>
                </li>

                <div class="vr mx-3 d-none d-md-block opacity-10" style="height: 30px;"></div>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-menu-link d-flex align-items-center" href="#"
                        id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <div class="avatar-circle me-md-2">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="d-none d-md-block">
                            <span class="d-block fw-bold text-navy small line-height-1">
                                {{ auth()->user()->name ?? 'User' }}
                            </span>
                            <small class="text-muted fw-medium" style="font-size: 0.7rem;">Administrator</small>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-premium border-0 mt-3 animate slideIn"
                        aria-labelledby="userDropdown">
                        <li class="px-3 py-3 bg-light-green rounded-top">
                            <div class="d-flex flex-column">
                                <small class="text-emerald text-uppercase fw-800"
                                    style="font-size: 0.6rem; letter-spacing: 0.05em;">Authorized Account</small>
                                <span class="fw-bold text-navy">{{ auth()->user()->name ?? 'User' }}</span>
                                <small class="text-muted truncate">{{ auth()->user()->email ?? 'admin@system.com' }}</small>
                            </div>
                        </li>

                        <li><hr class="dropdown-divider my-0"></li>

                        <li>
                            <a class="dropdown-item py-2 mt-2" href="#">
                                <i class="bi bi-person me-2 text-emerald"></i>
                                My Profile
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item py-2" href="#">
                                <i class="bi bi-gear me-2 text-emerald"></i>
                                Settings
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2 mb-1">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout Account
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    :root {
        --nav-navy: #0f172a;
        --nav-emerald: #10b981;
    }

    .dashboard-navbar {
        z-index: 1030;
        background: #ffffff !important;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }

    .text-navy { color: var(--nav-navy); }
    .text-emerald { color: var(--nav-emerald); }
    .fw-800 { font-weight: 800; }
    .line-height-1 { line-height: 1.2; }

    /* Nav Icons */
    .nav-icon-btn {
        color: #64748b !important;
        padding: 8px !important;
        border-radius: 8px;
        transition: all 0.2s;
        position: relative;
    }

    .nav-icon-btn:hover {
        background-color: #f1f5f9;
        color: var(--nav-emerald) !important;
    }

    .notification-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background-color: #ef4444;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    /* Avatar Circle */
    .avatar-circle {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, var(--nav-emerald), #059669);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 700;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
    }

    .user-menu-link {
        text-decoration: none;
        padding: 4px 8px !important;
        border-radius: 12px;
        transition: background 0.2s;
    }

    .user-menu-link:hover {
        background-color: #f8fafc;
    }

    /* Premium Dropdown */
    .shadow-premium {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }

    .bg-light-green {
        background-color: #f0fdf4;
    }

    .dropdown-menu {
        border-radius: 12px;
        min-width: 240px;
        padding: 0;
        overflow: hidden;
    }

    .dropdown-item {
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        padding-left: 1.25rem;
        padding-right: 1.25rem;
    }

    .dropdown-item i {
        font-size: 1.1rem;
    }

    .dropdown-item:hover {
        background-color: #f1f5f9;
        color: var(--nav-emerald);
        padding-left: 1.5rem;
    }

    /* Animation */
    @keyframes slideIn {
        0% { transform: translateY(10px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

    .slideIn { animation: slideIn 0.25s ease-out; }
</style>