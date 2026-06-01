<nav class="navbar dashboard-navbar">
    @php
        $navProfileImageUrl = auth()->user()?->profileImageUrl();
        $navIsAdminUser = auth()->check() && auth()->user()?->isAdmin();

        if ($navIsAdminUser) {
            $navUnreadNotifications = \Illuminate\Support\Facades\DB::table('notifications')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($notification) {
                    return (object) [
                        'id' => $notification->id,
                        'data' => json_decode($notification->data ?? '[]', true) ?: [],
                        'read_at' => $notification->read_at,
                    ];
                });
            $navUnreadCount = \Illuminate\Support\Facades\DB::table('notifications')
                ->whereNull('read_at')
                ->count();
        } else {
            $navUnreadNotifications = auth()->check() ? auth()->user()->unreadNotifications : collect();
            $navUnreadCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
        }
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
            <div class="dropdown">
                <button class="nav-notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                    aria-label="Notifications">
                    <i class="bi bi-bell"></i>
                    @if($navUnreadCount > 0)
                        <span class="notification-count">{{ $navUnreadCount > 99 ? '99+' : $navUnreadCount }}</span>
                    @else
                        <span class="notification-dot" aria-hidden="true"></span>
                    @endif
                </button>

                <div class="dropdown-menu dropdown-menu-end notification-menu shadow-sm border-0">
                    <div class="notification-menu-header">
                        <div>
                            <div class="notification-menu-title">Notifications</div>
                            <div class="notification-menu-subtitle">Updates from the applicant portal</div>
                        </div>
                    </div>
                    @if($navUnreadNotifications->count() > 0)
                        <div class="notification-list">
                            @foreach($navUnreadNotifications->take(5) as $notification)
                                @php
                                    $notificationData = $notification->data ?? [];
                                    $notificationUrl = $navIsAdminUser
                                        ? data_get($notificationData, 'url', route('dashboard'))
                                        : route('notifications.read', $notification->id);
                                @endphp
                                <div class="notification-item">
                                    <a class="notification-item-main" href="{{ $notificationUrl }}">
                                        <div class="notification-item-icon">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="notification-item-body">
                                            <div class="notification-item-title">
                                                {{ data_get($notificationData, 'title', 'New notification') }}
                                            </div>
                                            <div class="notification-item-text">
                                                {{ data_get($notificationData, 'message', 'You have a new update.') }}
                                            </div>
                                        </div>
                                    </a>

                                    <div class="notification-item-actions">
                                        @if(empty($notification->read_at))
                                            <a class="notification-mark-read" href="{{ route('notifications.read', $notification->id) }}">
                                                Mark as read
                                            </a>
                                        @endif
                                    </div>

                                    @if(empty($notification->read_at))
                                        <span class="notification-read-indicator"></span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="notification-empty">
                            <i class="bi bi-bell-slash notification-empty-icon"></i>
                            <div class="fw-semibold text-dark">No new notifications</div>
                            <div class="text-muted small">New updates will appear here.</div>
                        </div>
                    @endif
                    @if($navUnreadCount > 0)
                        <div class="notification-menu-footer">
                            <form action="{{ route('notifications.read-all') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="notification-mark-all-btn">Mark all as read</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <button
                type="button"
                class="nav-theme-btn"
                data-theme-toggle
                onclick="toggleAppTheme()"
                aria-label="Switch to night mode"
                title="Switch to night mode"
            >
                <i class="bi bi-moon-stars"></i>
            </button>

            <div class="nav-utility d-none d-md-flex">
                <div class="utility-icon">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div>
                    <div class="utility-label">{{ now()->format('l') }}</div>
                    <div class="utility-value">{{ now()->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const unreadCount = @json($navUnreadCount);
        const userId = @json(auth()->id());
        const storageKey = `app-notification-tone:${userId}`;
        const previousCount = Number(localStorage.getItem(storageKey) || '0');

        const playTone = () => {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (!AudioContextClass) {
                return;
            }

            const audioContext = new AudioContextClass();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, audioContext.currentTime);
            oscillator.frequency.exponentialRampToValueAtTime(660, audioContext.currentTime + 0.18);

            gainNode.gain.setValueAtTime(0.0001, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.18, audioContext.currentTime + 0.02);
            gainNode.gain.exponentialRampToValueAtTime(0.0001, audioContext.currentTime + 0.22);

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            oscillator.start();
            oscillator.stop(audioContext.currentTime + 0.24);

            oscillator.onended = () => audioContext.close();
        };

        if (unreadCount > previousCount) {
            try {
                playTone();
            } catch (error) {
                // If autoplay or audio init is blocked, keep the UI functional.
            }
        }

        localStorage.setItem(storageKey, String(unreadCount));
    });
</script>

<style>
    /* NAVBAR + SIDEBAR UI updates: white background, black text/icons, subtle surfaces */
    :root {
        --bg-white: #ffffff;
        --navbar-surface: #f3f4f6;
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
        background: var(--navbar-surface);
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

    .nav-theme-btn {
        width: 50px;
        height: 50px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--bg-white);
        color: var(--ink);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: all 0.15s ease;
    }

    .nav-theme-btn:hover {
        transform: translateY(-1px);
        background: rgba(0,0,0,0.03);
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

    .nav-notification-btn {
        position: relative;
        width: 50px;
        height: 50px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--bg-white);
        color: var(--ink);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .notification-dot {
        position: absolute;
        top: 9px;
        right: 10px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ef4444;
        box-shadow: 0 0 0 2px #fff;
    }

    .notification-count {
        position: absolute;
        top: -6px;
        right: -8px;
        min-width: 20px;
        height: 20px;
        padding: 0 5px;
        border-radius: 999px;
        background: #ef4444;
        color: #fff;
        font-size: 0.68rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 2px #fff;
    }

    .notification-menu {
        width: 320px;
        padding: 0;
        border-radius: 18px;
        overflow: hidden;
    }

    .notification-menu-header {
        padding: 14px 16px;
        border-bottom: 1px solid var(--line);
        background: linear-gradient(180deg, rgba(13,110,253,0.08), rgba(13,110,253,0.02));
    }

    .notification-menu-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--ink);
    }

    .notification-menu-subtitle {
        font-size: 0.75rem;
        color: var(--muted);
    }

    .notification-empty {
        padding: 18px 16px 20px;
        text-align: center;
    }

    .notification-menu-footer {
        padding: 12px 16px 14px;
        border-top: 1px solid var(--line);
        background: #fff;
    }

    .notification-list {
        max-height: 340px;
        overflow-y: auto;
    }

    .notification-item {
        display: flex;
        gap: 12px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--line);
        transition: background-color .15s ease;
        align-items: flex-start;
    }

    .notification-item:hover {
        background: rgba(13,110,253,0.04);
    }

    .notification-item-main {
        display: flex;
        gap: 12px;
        flex: 1;
        min-width: 0;
        text-decoration: none;
    }

    .notification-item:last-child {
        border-bottom: 0;
    }

    .notification-item-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: rgba(13,110,253,0.08);
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .notification-item-body {
        min-width: 0;
        flex: 1;
    }

    .notification-item-title {
        color: var(--ink);
        font-size: 0.88rem;
        font-weight: 800;
        line-height: 1.2;
    }

    .notification-item-text {
        color: var(--muted);
        font-size: 0.8rem;
        line-height: 1.35;
        margin-top: 2px;
    }

    .notification-item-actions {
        display: flex;
        align-items: center;
        flex: 0 0 auto;
        margin-left: 8px;
    }

    .notification-mark-read {
        color: #0d6efd;
        font-size: 0.72rem;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
    }

    .notification-mark-read:hover {
        text-decoration: underline;
    }

    .notification-mark-all-btn {
        border: 0;
        background: transparent;
        color: #0d6efd;
        font-size: 0.78rem;
        font-weight: 800;
        padding: 0;
    }

    .notification-mark-all-btn:hover {
        text-decoration: underline;
    }

    .notification-read-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ef4444;
        flex: 0 0 auto;
        align-self: center;
    }

    .notification-empty-icon {
        display: inline-flex;
        width: 44px;
        height: 44px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(0,0,0,0.04);
        margin-bottom: 10px;
        font-size: 1.1rem;
        color: var(--muted);
    }

    .utility-icon {
        width: 40px;
        height: 40px;
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
        .notification-menu { width: 290px; }
    }

    html[data-theme="night"] {
        --bg-white: #0f172a;
        --navbar-surface: #0b1220;
        --muted: #94a3b8;
        --line: rgba(148, 163, 184, 0.18);
        --ink: #e2e8f0;
        --surface: #1e293b;
        --accent-dark: #f8fafc;
        --danger: #f87171;
    }

    html[data-theme="night"] .dashboard-navbar {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.35);
    }

    html[data-theme="night"] .nav-toggle-btn,
    html[data-theme="night"] .nav-notification-btn,
    html[data-theme="night"] .nav-theme-btn,
    html[data-theme="night"] .nav-utility {
        box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.03);
    }

    html[data-theme="night"] .nav-theme-btn:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    html[data-theme="night"] .notification-menu {
        background: #0f172a;
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    html[data-theme="night"] .notification-menu-header {
        background: linear-gradient(180deg, rgba(59, 130, 246, 0.16), rgba(59, 130, 246, 0.02));
    }

    html[data-theme="night"] .notification-menu-footer {
        background: #0f172a;
    }

    html[data-theme="night"] .notification-item:hover {
        background: rgba(59, 130, 246, 0.08);
    }

    html[data-theme="night"] .notification-item-icon {
        background: rgba(59, 130, 246, 0.16);
        color: #93c5fd;
    }

    html[data-theme="night"] .notification-empty-icon {
        background: rgba(255, 255, 255, 0.04);
        color: #94a3b8;
    }

    html[data-theme="night"] .notification-empty .text-dark {
        color: #e2e8f0 !important;
    }

    html[data-theme="night"] .notification-empty .text-muted {
        color: #94a3b8 !important;
    }

    html[data-theme="night"] .notification-count,
    html[data-theme="night"] .notification-dot {
        box-shadow: 0 0 0 2px #0b1220;
    }

    html[data-theme="night"] .user-menu-link:hover,
    html[data-theme="night"] .navbar-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }

</style>
