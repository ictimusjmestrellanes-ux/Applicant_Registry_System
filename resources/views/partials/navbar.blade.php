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
        --surface: #9b9b9b;
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

    

    @media (max-width: 767.98px) {
        .dashboard-navbar { padding: 10px 0; }
        .nav-page-title { font-size: 1rem; }
    }

</style>
