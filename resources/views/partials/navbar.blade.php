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
    :root {
        --nav-ink: #10243d;
        --nav-muted: #334155;
        --nav-line: #dbe5ef;
        --nav-accent: #10b981;
        --nav-accent-strong: #059669;
        --nav-primary: #10243d;
    }

    .dashboard-navbar {
        position: sticky;
        top: 0;
        z-index: 1030;
        padding: 16px 0;
        background:
            linear-gradient(180deg, rgba(241, 247, 253, 0.96) 0%, rgba(255, 255, 255, 0.94) 100%);
        backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(219, 229, 239, 0.8);
        box-shadow: 0 8px 30px rgba(15, 34, 58, 0.06);
    }

    .nav-toggle-btn {
        width: 42px;
        height: 42px;
        border: 1px solid var(--nav-line);
        border-radius: 14px;
        background: #ffffff;
        color: var(--nav-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 8px 18px rgba(15, 34, 58, 0.06);
    }

    .nav-heading-wrap {
        min-width: 0;
    }

    .nav-kicker {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--nav-accent-strong);
    }

    .nav-page-title {
        font-family: Arial, Helvetica, sans-serif;
        color: var(--nav-ink);
        font-size: 1.2rem;
        font-weight: 800;
        line-height: 1.2;
    }

    .nav-utility {
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 16px;
        border: 1px solid var(--nav-line);
        background: rgba(255, 255, 255, 0.9);
    }

    .utility-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(18, 60, 115, 0.12), rgba(16, 185, 129, 0.14));
        color: var(--nav-primary);
        font-size: 1rem;
    }

    .utility-label {
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--nav-muted);
    }

    .utility-value {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--nav-ink);
    }

    .nav-action-btn {
        width: 42px;
        height: 42px;
        position: relative;
        border-radius: 14px;
        border: 1px solid var(--nav-line);
        background: rgba(255, 255, 255, 0.92);
        color: #23364d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 8px 18px rgba(15, 34, 58, 0.05);
    }

    .nav-action-btn:hover {
        color: var(--nav-accent-strong);
        border-color: #bfe8d9;
        background: #f3fdf8;
        transform: translateY(-1px);
    }

    .notification-dot {
        position: absolute;
        top: 9px;
        right: 9px;
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #ef4444;
        box-shadow: 0 0 0 3px #ffffff;
    }

    .user-menu-link {
        text-decoration: none;
        padding: 6px 8px 6px 6px;
        border-radius: 18px;
        border: 1px solid var(--nav-line);
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 10px 20px rgba(15, 34, 58, 0.05);
        transition: all 0.2s ease;
    }

    .user-menu-link:hover {
        background: #ffffff;
        border-color: #c8d8e8;
    }

    .avatar-circle {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--nav-accent), var(--nav-accent-strong));
        color: white;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 800;
        box-shadow: 0 10px 18px rgba(16, 185, 129, 0.22);
    }

    .avatar-image {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        object-fit: cover;
        box-shadow: 0 10px 18px rgba(15, 34, 58, 0.12);
    }

    .user-name {
        color: var(--nav-ink);
        font-size: 0.84rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .user-role {
        color: var(--nav-muted);
        font-size: 0.71rem;
        font-weight: 600;
    }

    .dropdown-arrow {
        color: #334155;
        font-size: 0.78rem;
    }

    .navbar-dropdown {
        min-width: 270px;
        padding: 0;
        overflow: hidden;
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 26px 40px rgba(15, 34, 58, 0.14);
    }

    .dropdown-header-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 18px 16px;
        background:
            radial-gradient(circle at top right, rgba(16, 185, 129, 0.18), transparent 28%),
            linear-gradient(135deg, #f7fbff 0%, #eef7ff 100%);
    }

    .dropdown-avatar {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #123c73, #1f5fa0);
        color: #fff;
        font-weight: 800;
        box-shadow: 0 10px 18px rgba(18, 60, 115, 0.2);
    }

    .dropdown-kicker {
        color: var(--nav-accent-strong);
        font-size: 0.64rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .dropdown-name {
        color: var(--nav-ink);
        font-weight: 800;
        line-height: 1.2;
    }

    .dropdown-email {
        color: var(--nav-muted);
        font-size: 0.75rem;
        word-break: break-word;
    }

    .navbar-item {
        padding-left: 1.2rem;
        padding-right: 1.2rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #1f3147;
        transition: all 0.18s ease;
    }

    .navbar-item i {
        color: var(--nav-accent-strong);
    }

    .navbar-item:hover {
        background: #f3f8fd;
        color: var(--nav-primary);
        padding-left: 1.35rem;
    }

    .logout-item {
        color: #dc2626;
    }

    .logout-item i {
        color: #dc2626;
    }

    .logout-item:hover {
        background: #fef2f2;
        color: #b91c1c;
    }

    @media (max-width: 767.98px) {
        .dashboard-navbar {
            padding: 12px 0;
        }

        .nav-page-title {
            font-size: 1rem;
        }

        .user-menu-link {
            padding-right: 6px;
        }
    }
</style>
