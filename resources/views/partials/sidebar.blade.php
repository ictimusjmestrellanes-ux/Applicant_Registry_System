<div class="sidebar d-flex flex-column flex-shrink-0 p-3">

    <div class="d-flex align-items-center mb-4 px-2">
        <div class="brand-icon me-2">
            <i class="bi bi-shield-check text-white fs-4"></i>
        </div>
        <span class="fs-5 fw-bold text-white tracking-tight">
            Applicant Registry System 
            <span class="fw-light opacity-75">v1.0</span>
        </span>
    </div>

    <hr class="border-secondary opacity-25 mb-4">

    <ul class="nav nav-pills flex-column mb-auto">
        @foreach ($menuItems as $item)

            @php
                $routeName = $item['route'] ?? null;

                $isActive = false;

                // Check main route (ignore #)
                if ($routeName && $routeName !== '#') {
                    $isActive = request()->routeIs($routeName . '*');
                }

                // Check children routes
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
                       data-bs-toggle="collapse"
                       href="#menu-{{ \Illuminate\Support\Str::slug($item['label']) }}"
                       role="button"
                       aria-expanded="{{ $isActive ? 'true' : 'false' }}">

                        <span>
                            <i class="{{ $item['icon'] }} me-3 opacity-75"></i>
                            {{ $item['label'] }}
                        </span>

                        <i class="bi bi-chevron-right chevron-icon small"></i>
                    </a>

                    <div class="collapse {{ $isActive ? 'show' : '' }}"
                         id="menu-{{ \Illuminate\Support\Str::slug($item['label']) }}">

                        <ul class="nav flex-column ms-4 mt-1 border-start border-secondary border-opacity-25">

                            @foreach ($item['children'] as $child)
                                <li class="ms-3">
                                    <a href="{{ route($child['route']) }}"
                                       class="nav-link text-white-50 sidebar-child-link 
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
                    <a href="{{ $routeName !== '#' ? route($routeName) : '#' }}"
                       class="nav-link text-white d-flex align-items-center sidebar-link 
                       {{ $isActive ? 'active shadow-sm' : '' }}">
                        <i class="{{ $item['icon'] }} me-3 opacity-75"></i>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endif

        @endforeach
    </ul>

    <div class="mt-auto px-2">
        <div class="p-3 rounded-3 bg-white bg-opacity-10">
            <small class="d-block text-white-50">System Status</small>
            <div class="d-flex align-items-center text-white small">
                <span class="status-dot me-2"></span> Online & Secure
            </div>
        </div>
    </div>
</div>

<style>
    /* 1. BASE SIDEBAR STYLE (Desktop) */
    .sidebar {
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        width: 280px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* 2. MOBILE RESPONSIVENESS */
    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%); /* Hide off-screen by default */
        }

        /* This class is added via JavaScript when clicking the hamburger */
        .sidebar.active {
            transform: translateX(0);
            box-shadow: 15px 0 30px rgba(0,0,0,0.5);
        }
    }

    /* 3. BRAND & LINKS (Your existing styles) */
    .tracking-tight { letter-spacing: -0.05em; }

    .brand-icon {
        background: #4f46e5;
        padding: 5px 10px;
        border-radius: 8px;
    }

    .sidebar-link {
        font-weight: 500;
        padding: 0.8rem 1rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.8);
    }

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff !important;
    }

    .sidebar-link.active {
        background-color: #4f46e5 !important;
        color: #fff !important;
    }

    /* 4. CHILD LINKS & DOTS */
    .sidebar-child-link {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        transition: color 0.2s;
        text-decoration: none;
        display: block;
        color: rgba(255, 255, 255, 0.5);
    }

    .sidebar-child-link:hover { color: #fff !important; }

    .active-child {
        color: #818cf8 !important; 
        font-weight: 600;
    }

    .chevron-icon { transition: transform 0.3s ease; }
    .sidebar-link[aria-expanded="true"] .chevron-icon { transform: rotate(90deg); }

    .status-dot {
        height: 8px;
        width: 8px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 8px #10b981;
    }

    /* 5. CLOSE BUTTON (Only visible on Mobile) */
    .btn-close-sidebar {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        position: absolute;
        right: 15px;
        top: 15px;
    }

    @media (max-width: 991.98px) {
        .btn-close-sidebar { display: block; }
    }
</style>