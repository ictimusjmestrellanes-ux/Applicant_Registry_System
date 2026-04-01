<nav class="navbar dashboard-navbar">
    @php
        $navProfileImageUrl = auth()->user()?->profileImageUrl();
    @endphp

    <div class="container-fluid px-md-4 px-3">
        <div class="d-flex align-items-center gap-3 flex-grow-1">
            <button id="sidebarToggle" class="nav-toggle-btn d-lg-none" type="button" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>

            <div class="nav-heading-wrap">
                <div class="nav-page-title">@yield('title', 'Dashboard')</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 gap-md-3">
            <div class="nav-utility d-none d-md-flex">
                <div class="utility-icon">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div>
                    <div class="utility-label">Today</div>
                    <div class="utility-value">{{ now()->format('M d, Y') }}</div>
                </div>
            </div>

            <a class="nav-action-btn" href="{{ route('activity-logs.index') }}" title="Activity Logs">
                <i class="bi bi-journal-text"></i>
            </a>

            <a class="nav-action-btn" href="#" title="Notifications">
                <i class="bi bi-bell"></i>
                <span class="notification-dot"></span>
            </a>

            <div class="dropdown">
                <a
                    class="user-menu-link d-flex align-items-center"
                    href="#"
                    id="userDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    @if($navProfileImageUrl)
                        <img src="{{ $navProfileImageUrl }}" alt="{{ auth()->user()->name }}" class="avatar-image me-2">
                    @else
                        <div class="avatar-circle me-2">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div class="d-none d-md-block">
                        <span class="d-block user-name">
                            {{ auth()->user()->name ?? 'User' }}
                        </span>
                        <small class="user-role">{{ ucfirst(auth()->user()->role ?? 'user') }}</small>
                    </div>
                    <i class="bi bi-chevron-down ms-2 dropdown-arrow d-none d-md-inline-flex"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end navbar-dropdown border-0 mt-3" aria-labelledby="userDropdown">

                    <li>
                        <a class="dropdown-item navbar-item py-2 mt-2" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-circle me-2"></i>
                            My Profile
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item navbar-item py-2" href="{{ route('activity-logs.index') }}">
                            <i class="bi bi-clock-history me-2"></i>
                            Activity Logs
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item navbar-item py-2" href="#">
                            <i class="bi bi-gear me-2"></i>
                            Settings
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item navbar-item logout-item py-2 mb-1">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout Account
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    /* NAVBAR + SIDEBAR UI updates: white background, black text/icons, subtle surfaces */
    :root {
        --bg-white: #ffffff;
        --muted: #6b7280;
        --line: #e5e7eb;
        --ink: #000000;
        --surface: #f3f4f6;
        --accent-dark: #111827;
        --danger: #dc2626;
    }

    /* NAVBAR */
    .dashboard-navbar {
        position: sticky;
        top: 0;
        z-index: 1030;
        padding: 12px 0;
        background: var(--bg-white);
        border-bottom: 1px solid var(--line);
        box-shadow: 0 4px 10px rgba(0,0,0,0.04);
    }

    .nav-toggle-btn {
        width: 42px;
        height: 42px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--bg-white);
        color: var(--ink);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    .nav-page-title {
        font-family: Inter, Arial, Helvetica, sans-serif;
        color: var(--ink);
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .nav-utility {
        align-items: center;
        gap: 10px;
        padding: 6px 10px;
        border-radius: 12px;
        border: 1px solid var(--line);
        background: var(--bg-white);
    }

    .utility-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.04);
        color: var(--ink);
        font-size: 0.95rem;
    }

    .utility-label { font-size: 0.68rem; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; }
    .utility-value { font-size: 0.85rem; font-weight: 700; color: var(--ink); }

    .nav-action-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid var(--line);
        background: var(--bg-white);
        color: var(--ink);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.98rem;
        transition: all 0.12s ease;
    }
    .nav-action-btn:hover {
        color: var(--accent-dark);
        background: rgba(0,0,0,0.03);
        transform: translateY(-1px);
    }

    .notification-dot {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #ef4444;
        box-shadow: 0 0 0 3px var(--bg-white);
    }

    .user-menu-link {
        text-decoration: none;
        padding: 6px 8px 6px 6px;
        border-radius: 14px;
        border: 1px solid var(--line);
        background: var(--bg-white);
        transition: all 0.12s ease;
    }
    .user-menu-link:hover { background: rgba(0,0,0,0.03); }

    .avatar-circle {
        width: 42px;
        height: 42px;
        background: var(--surface);
        color: var(--ink);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 800;
    }
    .avatar-image { width: 42px; height: 42px; border-radius: 12px; object-fit: cover; }

    .user-name { color: var(--ink); font-size: 0.84rem; font-weight: 700; }
    .user-role { color: var(--muted); font-size: 0.71rem; font-weight: 600; }
    .dropdown-arrow { color: var(--muted); font-size: 0.78rem; }

    .navbar-dropdown {
        min-width: 270px;
        padding: 0;
        overflow: hidden;
        border-radius: 12px;
        background: var(--bg-white);
        box-shadow: 0 12px 24px rgba(0,0,0,0.06);
    }

    .dropdown-header-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 14px 12px;
        background: linear-gradient(180deg, #ffffff, #fafafa);
    }
    .dropdown-avatar { width: 46px; height: 46px; border-radius: 10px; background: var(--surface); color: var(--ink); font-weight: 800; display:flex; align-items:center; justify-content:center; }
    .dropdown-kicker { color: var(--accent-dark); font-size: 0.64rem; font-weight: 800; text-transform: uppercase; }

    .dropdown-name { color: var(--ink); font-weight: 700; }
    .dropdown-email { color: var(--muted); font-size: 0.75rem; word-break: break-word; }

    .navbar-item {
        padding-left: 1.1rem;
        padding-right: 1.1rem;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--ink);
        transition: all 0.12s ease;
    }
    .navbar-item i { color: var(--ink); }
    .navbar-item:hover { background: rgba(0,0,0,0.03); color: var(--accent-dark); padding-left: 1.25rem; }

    .logout-item { color: var(--danger); }
    .logout-item i { color: var(--danger); }
    .logout-item:hover { background: #fff5f5; color: #b91c1c; }

    @media (max-width: 767.98px) {
        .dashboard-navbar { padding: 10px 0; }
        .nav-page-title { font-size: 1rem; }
        .user-menu-link { padding-right: 6px; }
    }

    /* SIDEBAR (keeps white bg / black text theme) */
    .sidebar {
        width: 280px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        background: var(--bg-white);
        color: var(--ink);
        box-shadow: 8px 0 30px rgba(7, 16, 31, 0.06);
    }
    .sidebar-top { background: var(--surface); padding-top: 1rem; }
    .brand-card { padding: 1rem; border-radius: 16px; background: var(--surface); border: 1px solid var(--line); }
    .brand-label { color: var(--ink); font-weight: 600; }
    .brand-subtitle, .brand-badge { color: var(--muted); }

    .sidebar-divider { height: 1px; background: var(--line); margin: .5rem 0; }
    .sidebar-scroll { overflow-y: auto; padding-bottom: 1rem; }

    .sidebar-section-label { color: var(--muted); font-size: .72rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; }

    .sidebar-link {
        padding: 0.9rem 1rem;
        border-radius: 12px;
        color: var(--ink) !important;
        font-weight: 600;
        background: transparent;
        border: 1px solid transparent;
        transition: background .15s ease, transform .12s ease;
    }
    .sidebar-link:hover { background: rgba(0,0,0,0.03); transform: translateY(-1px); }

    .sidebar-icon {
        width: 34px; height: 34px; margin-right: 12px; border-radius: 10px;
        display:inline-flex; align-items:center; justify-content:center; background: rgba(0,0,0,0.04); color:var(--ink);
    }
    /* neutralize Bootstrap blue active styles */
    .nav-pills .nav-link.active,
    .nav-pills .nav-link.active:focus,
    .nav-pills .nav-link.active:hover {
        background: rgba(0,0,0,0.06) !important;
        color: var(--ink) !important;
        border-color: transparent !important;
        box-shadow: none !important;
    }
    .nav-pills .nav-link.active .sidebar-icon,
    .nav-pills .nav-link.active .sidebar-icon i {
        background: rgba(0,0,0,0.06) !important;
        color: var(--ink) !important;
    }

    .sidebar-child-link { color: var(--muted); }
    .sidebar-child-link:hover { color: var(--ink); background: rgba(0,0,0,0.03); }

    @media (max-width: 991.98px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.active { transform: translateX(0); }
    }
</style>
