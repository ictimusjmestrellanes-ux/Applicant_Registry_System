<div class="sidebar d-flex flex-column">
    <button class="btn-close-sidebar d-lg-none" type="button" onclick="toggleSidebar()">
        <i class="bi bi-x-lg"></i>
    </button>

    <div class="sidebar-top px-3 pt-4 pb-3">
        <div class="brand-shell">
            <div class="brand-icon">
                <img src="{{ asset('images/logo_peso.png') }}" alt="PESO Logo" class="brand-img">
            </div>

            <div class="brand-copy">
                <div class="brand-label">Applicant Registry</div>
                <div class="brand-subtitle">Public Employment Service Office</div>
            </div>
        </div>
    </div>

    <div class="sidebar-divider mx-3"></div>

    <div class="sidebar-scroll px-3 py-3">
        <div class="sidebar-section-label">Navigation</div>

        <ul class="nav nav-pills flex-column gap-2">
            @foreach ($menuItems as $item)
                @continue(($item['admin_only'] ?? false) && !auth()->user()?->isAdmin())

                @php
                    $routeName = $item['route'] ?? null;
                    $isActive = false;

                    if ($routeName && $routeName !== '#') {
                        $isActive = request()->routeIs($routeName . '*');
                    }

                    if (!empty($item['children'])) {
                        foreach ($item['children'] as $child) {
                            if (request()->routeIs($child['route'] . '*')) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                @endphp

                @if (!empty($item['children']))
                    <li class="nav-item">
                        <a class="nav-link sidebar-link d-flex align-items-center justify-content-between {{ $isActive ? 'active' : 'collapsed' }}"
                            data-bs-toggle="collapse" href="#menu-{{ \Illuminate\Support\Str::slug($item['label']) }}"
                            role="button" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                            <span class="d-flex align-items-center">
                                <span class="sidebar-icon">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                                <span>{{ $item['label'] }}</span>
                            </span>
                            <i class="bi bi-chevron-right chevron-icon"></i>
                        </a>

                        <div class="collapse {{ $isActive ? 'show' : '' }}"
                            id="menu-{{ \Illuminate\Support\Str::slug($item['label']) }}">
                            <ul class="nav flex-column sidebar-submenu">
                                @foreach ($item['children'] as $child)
                                    <li>
                                        <a href="{{ route($child['route']) }}"
                                            class="sidebar-child-link {{ request()->routeIs($child['route'] . '*') ? 'active-child' : '' }}">
                                            {{ $child['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ $routeName !== '#' ? route($routeName) : '#' }}"
                            class="nav-link sidebar-link d-flex align-items-center {{ $isActive ? 'active' : '' }}">
                            <span class="sidebar-icon">
                                <i class="{{ $item['icon'] }}"></i>
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>

<style>
    :root {
        --sidebar-bg: #ffffff;           /* white background */
        --sidebar-surface: #f3f4f6;      /* light surface */
        --sidebar-border: #e6e6e6;       /* light border */
        --sidebar-text: #000000;         /* black text & icons */
        --sidebar-muted: #6b6b6b;        /* muted gray */
        --sidebar-accent: #000000;       /* accent dark */
        --sidebar-accent-strong: #000000;/* strong accent (black) */
    }

    .sidebar {
        width: 280px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        background: var(--sidebar-bg);
        color: var(--sidebar-text);
        box-shadow: 8px 0 30px rgba(7, 16, 31, 0.06);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .sidebar-top {
        position: relative;
        background: var(--sidebar-surface);
    }

    .brand-card {
        position: relative;
        overflow: hidden;
        padding: 1rem;
        border-radius: 22px;
        background: var(--sidebar-surface);
        border: 1px solid var(--sidebar-border);
        box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.04), 0 16px 30px rgba(7, 16, 31, 0.06);
        backdrop-filter: blur(10px);
    }

    .brand-card::after {
        content: "";
        position: absolute;
        right: -34px;
        top: -34px;
        width: 120px;
        height: 120px;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.03);
    }

    .brand-shell {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .brand-img {
        width: 60px;
        height: 60px;
        object-fit: contain;
    }

    .brand-copy {
        min-width: 0;
    }

    .brand-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.45rem;
        padding: 0.3rem 0.65rem;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.03);
        color: var(--sidebar-muted);
    }

    .brand-badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: var(--sidebar-accent);
        box-shadow: 0 0 0 4px rgba(43, 43, 43, 0.06);
    }

    .brand-label {
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.01em;
        line-height: 1.15;
        color: var(--sidebar-text);
    }

    .brand-subtitle {
        margin-top: 0.2rem;
        font-size: 0.69rem;
        color: var(--sidebar-muted);
    }

    .brand-meta {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin-top: 1rem;
    }

    .brand-meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.7rem;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.02);
        color: var(--sidebar-text);
    }

    .sidebar-account {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border: 1px solid var(--sidebar-border);
        border-radius: 16px;
        background: var(--sidebar-surface);
        backdrop-filter: blur(10px);
    }

    .account-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        background: rgba(0, 0, 0, 0.06);
        color: var(--sidebar-text);
    }

    .account-name {
        font-size: 0.92rem;
        font-weight: 700;
        line-height: 1.2;
        color: var(--sidebar-text);
    }

    .account-role {
        font-size: 0.75rem;
        color: var(--sidebar-muted);
    }

    .sidebar-divider {
        height: 1px;
        background: var(--sidebar-border);
    }

    .sidebar-scroll {
        overflow-y: auto;
    }

    .sidebar-section-label {
        margin-bottom: 12px;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        font-weight: 700;
        color: var(--sidebar-muted);
    }

    .sidebar-link {
        padding: 0.9rem 1rem;
        border-radius: 16px;
        color: var(--sidebar-text) !important;
        font-weight: 600;
        transition: all 0.2s ease;
        background: transparent;
        border: 1px solid transparent;
    }

    .sidebar-link:hover {
        background: rgba(0, 0, 0, 0.03);
        border-color: rgba(0, 0, 0, 0.02);
    }

    .sidebar-link.active {
        background: rgba(0, 0, 0, 0.06);
        border-color: rgba(0, 0, 0, 0.08);
        box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.02);
    }

    .sidebar-icon {
        width: 34px;
        height: 34px;
        margin-right: 12px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.03);
        color: var(--sidebar-text);
    }

    .sidebar-link.active .sidebar-icon {
        background: rgba(0, 0, 0, 0.308);
        color: var(--sidebar-text);
    }

    .sidebar-submenu {
        margin: 8px 0 0 18px;
        padding: 8px 0 2px 16px;
        border-left: 1px solid rgba(0, 0, 0, 0.04);
        gap: 4px;
    }

    .sidebar-child-link {
        display: block;
        padding: 0.55rem 0.85rem;
        border-radius: 12px;
        text-decoration: none;
        color: var(--sidebar-muted);
        font-size: 0.86rem;
        transition: all 0.2s ease;
    }

    .sidebar-child-link:hover {
        color: var(--sidebar-text);
        background: rgba(0, 0, 0, 0.03);
    }

    .sidebar-child-link.active-child {
        color: var(--sidebar-text);
        background: rgba(0, 0, 0, 0.05);
    }

    .chevron-icon {
        font-size: 0.8rem;
        color: var(--sidebar-muted);
        transition: transform 0.25s ease;
    }

    .sidebar-link[aria-expanded="true"] .chevron-icon {
        transform: rotate(90deg);
    }

    .system-status {
        padding: 14px 15px;
        border-radius: 16px;
        background: rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.02);
    }

    .status-line {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
        font-size: 0.88rem;
        font-weight: 600;
    }

    .system-status small {
        color: var(--sidebar-muted);
    }

    // ...existing code...
    .status-dot {
        width: 9px;
        height: 9px;
        border-radius: 999px;
        background: var(--sidebar-accent-strong);
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.03);
    }

    .btn-close-sidebar {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 2;
        border: 0;
        background: transparent;
        color: var(--sidebar-text);
        padding: 8px;
    }

    /* OVERRIDE: remove Bootstrap/blue "active" color */
    .nav-pills .nav-link.active,
    .nav-pills .nav-link.active:focus,
    .nav-pills .nav-link.active:hover {
        background: transparent !important;
        color: var(--sidebar-text) !important;
        border-color: transparent !important;
        box-shadow: none !important;
    }

    /* keep icon area matching sidebar style (no blue) */
    .nav-pills .nav-link.active .sidebar-icon,
    .nav-pills .nav-link.active .sidebar-icon i {
        background: rgba(0, 0, 0, 0.03) !important;
        color: var(--sidebar-text) !important;
    }

    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }
    }
</style>