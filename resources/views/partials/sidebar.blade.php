<div class="sidebar d-flex flex-column flex-shrink-0 p-3">
    <button class="btn-close-sidebar d-lg-none" onclick="toggleSidebar()">
        <i class="bi bi-x-lg"></i>
    </button>

    <div class="d-flex align-items-center mb-4 px-2">
        <div class="brand-icon me-2">
            <i class="bi bi-shield-check text-white fs-4"></i>
        </div>
        <span class="fs-5 fw-bold text-white tracking-tight">
            Applicant Registry
            <span class="fw-light opacity-50">v1.0</span>
        </span>
    </div>

    <hr class="border-white opacity-10 mb-4">

    <ul class="nav nav-pills flex-column mb-auto">
        @foreach ($menuItems as $item)
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

            {{-- WITH CHILDREN --}}
            @if (!empty($item['children']))
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center justify-content-between sidebar-link {{ $isActive ? 'active' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#menu-{{ \Illuminate\Support\Str::slug($item['label']) }}" role="button"
                        aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                        <span>
                            <i class="{{ $item['icon'] }} me-3 opacity-75"></i>
                            {{ $item['label'] }}
                        </span>
                        <i class="bi bi-chevron-right chevron-icon small"></i>
                    </a>

                    <div class="collapse {{ $isActive ? 'show' : '' }}"
                        id="menu-{{ \Illuminate\Support\Str::slug($item['label']) }}">
                        <ul class="nav flex-column ms-4 mt-1 border-start border-white border-opacity-10">
                            @foreach ($item['children'] as $child)
                                <li class="ms-3">
                                    <a href="{{ route($child['route']) }}" class="nav-link sidebar-child-link 
                                                   {{ request()->routeIs($child['route'] . '*') ? 'active-child' : '' }}">
                                        {{ $child['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>

                {{-- WITHOUT CHILDREN --}}
            @else
                <li class="nav-item mb-1">
                    <a href="{{ $routeName !== '#' ? route($routeName) : '#' }}" class="nav-link text-white d-flex align-items-center sidebar-link 
                               {{ $isActive ? 'active shadow-sm' : '' }}">
                        <i class="{{ $item['icon'] }} me-3 opacity-75"></i>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>

    <div class="mt-auto px-2">
        <div class="sidebar-footer pb-2 px-1 text-center">
            <div class="d-flex flex-column align-items-center">
                <span class="text-white-50" style="font-size: 0.7rem; letter-spacing: 0.02em;">
                    &copy; {{ date('Y') }} Applicant Registry System
                </span>
                <span class="text-white-50 opacity-50" style="font-size: 0.65rem;">
                    City Government of Imus. All Rights Reserved.
                </span>
                <span class="text-white-50 opacity-50" style="font-size: 0.65rem;">
                    Developed by the City Government IT Team.
                </span>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        /* Theme: Deep Navy & Emerald */
        --sidebar-bg: #1e59a7;
        /* Deep Navy */
        --sidebar-accent: #10b981;
        /* Emerald Green */
        --sidebar-hover: rgba(255, 255, 255, 0.08);
        --text-dim: rgba(255, 255, 255, 0.6);
    }

    .sidebar {
        background-color: var(--sidebar-bg);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
        width: 280px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }
    }

    .brand-icon {
        background: var(--sidebar-accent);
        padding: 5px 10px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
    }

    .sidebar-link {
        font-weight: 500;
        padding: 0.85rem 1.2rem;
        border-radius: 12px;
        transition: all 0.2s ease;
        color: var(--text-dim) !important;
        margin: 0 4px;
    }

    .sidebar-link:hover {
        background: var(--sidebar-hover);
        color: #fff !important;
    }

    .sidebar-link.active {
        background-color: var(--sidebar-accent) !important;
        color: #fff !important;
        box-shadow: 0 8px 15px -3px rgba(16, 185, 129, 0.3);
    }

    .sidebar-link.active i {
        opacity: 1;
    }

    .sidebar-child-link {
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
        transition: all 0.2s;
        color: var(--text-dim);
        text-decoration: none;
        display: block;
        position: relative;
    }

    .sidebar-child-link:hover {
        color: #fff !important;
        padding-left: 1.25rem;
    }

    .active-child {
        color: var(--sidebar-accent) !important;
        font-weight: 700;
    }

    .status-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .status-dot {
        height: 8px;
        width: 8px;
        background-color: var(--sidebar-accent);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--sidebar-accent);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }

        100% {
            opacity: 1;
        }
    }

    .chevron-icon {
        transition: transform 0.3s ease;
        opacity: 0.5;
    }

    .sidebar-link[aria-expanded="true"] .chevron-icon {
        transform: rotate(90deg);
        opacity: 1;
    }

    .btn-close-sidebar {
        background: none;
        border: none;
        color: white;
        padding: 10px;
        position: absolute;
        right: 10px;
        top: 10px;
    }
</style>