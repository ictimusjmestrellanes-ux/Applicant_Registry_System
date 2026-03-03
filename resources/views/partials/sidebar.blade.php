<div class="sidebar bg-dark text-white p-3" style="width: 300px; height: 100vh; position: fixed; top: 0; left: 0;">
    <h5 class="text-center mb-4">Applicant Registry System</h5>
    <ul class="nav flex-column">
        @foreach ($menuItems as $item)
            @php
                $isActive = request()->routeIs($item['route'] ?? '') 
                    || collect($item['children'] ?? [])->pluck('route')->contains(fn($r) => request()->routeIs($r));
            @endphp

            @if (!empty($item['children']))
                <li class="nav-item">
                    <a class="nav-link text-white d-flex justify-content-between {{ $isActive ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#menu-{{ \Illuminate\Support\Str::slug($item['label']) }}" role="button" aria-expanded="{{ $isActive ? 'true' : 'false' }}" aria-controls="menu-{{ \Illuminate\Support\Str::slug($item['label']) }}">
                        <span><i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse {{ $isActive ? 'show' : '' }}" id="menu-{{ \Illuminate\Support\Str::slug($item['label']) }}">
                        <ul class="nav flex-column ms-3">
                            @foreach ($item['children'] as $child)
                                <li>
                                    <a href="{{ route($child['route']) }}" class="nav-link text-white {{ request()->routeIs($child['route']) ? 'active fw-bold' : '' }}">
                                        {{ $child['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @else
                <li>
                    <a href="{{ route($item['route']) }}" class="nav-link text-white {{ $isActive ? 'active fw-bold' : '' }}">
                        <i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>