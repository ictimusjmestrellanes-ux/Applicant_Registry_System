<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm dashboard-navbar py-3">
    <div class="container-fluid px-md-4">

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="ms-auto" id="navbarNav">
            <ul class="navbar-nav align-items-center">

                <li class="nav-item me-3">
                    <a class="nav-link text-muted position-relative" href="#">
                        <i class="bi bi-bell fs-5"></i>
                        <span
                            class="position-absolute top-25 start-75 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-menu d-flex align-items-center fw-600" href="#"
                        id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <div class="avatar-circle me-2">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <span class="d-none d-md-inline text-dark">
                            {{ auth()->user()->name ?? 'User' }}
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 animate slideIn"
                        aria-labelledby="userDropdown">
                        <li class="px-3 py-2">
                            <div class="d-flex flex-column">
                                <small class="text-muted text-uppercase fw-bold"
                                    style="font-size: 0.65rem;">Account</small>
                                <span class="fw-bold text-dark">{{ auth()->user()->name ?? 'User' }}</span>
                                <small class="text-muted truncate"
                                    >{{ auth()->user()->email ?? '' }}</small>
                            </div>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item py-2" href="#">
                                <i class="bi bi-person me-2 text-primary"></i>
                                My Profile
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item py-2" href="#">
                                <i class="bi bi-gear me-2 text-primary"></i>
                                Settings
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2">
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
    .dashboard-navbar {
        z-index: 1030;
        border-bottom: 1px solid #edf2f7;
    }

    .fw-600 {
        font-weight: 600;
    }

    /* Avatar Circle */
    .avatar-circle {
        width: 32px;
        height: 32px;
        background-color: #4f46e5;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: bold;
    }

    /* Custom Dropdown Styling */
    .dropdown-menu {
        border-radius: 12px;
        min-width: 200px;
    }

    .dropdown-item {
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background-color: #f8fafc;
        padding-left: 1.25rem;
    }

    /* Animation */
    @media (min-width: 768px) {
        .animate {
            animation-duration: 0.2s;
            -webkit-animation-duration: 0.2s;
            animation-fill-mode: both;
            -webkit-animation-fill-mode: both;
        }
    }

    @keyframes slideIn {
        0% {
            transform: translateY(1rem);
            opacity: 0;
        }

        100% {
            transform: translateY(0rem);
            opacity: 1;
        }
    }

    .slideIn {
        animation-name: slideIn;
    }
</style>