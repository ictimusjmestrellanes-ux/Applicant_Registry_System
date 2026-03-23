<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: #0f172a;
        }

        body {
            overflow-x: hidden;
            background: #f1f5f9;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        /* SIDEBAR INITIAL STATE */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            transition: transform 0.3s ease;
        }

        /* MAIN CONTENT AREA */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .content {
            padding: 20px;
        }

        /* OVERLAY FOR MOBILE */
        #sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        /* RESPONSIVE BREAKPOINTS */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                /* Hide sidebar off-screen */
            }

            .main-content {
                margin-left: 0;
                /* Content takes full width */
            }

            /* Toggle class for mobile */
            .sidebar.active {
                transform: translateX(0);
            }

            #sidebar-overlay.active {
                display: block;
            }

            .content {
                padding: 15px;
            }
        }

        /* Desktop specific padding */
        @media (min-width: 992px) {
            .content {
                padding: 30px;
            }
        }
    </style>
</head>

<body>
    <div id="sidebar-overlay"></div>

    @include('partials.sidebar')

    <div class="main-content">
        @include('partials.navbar')

        <div class="content">
            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function toggleSidebar() {
            $('.sidebar').toggleClass('active');
            $('#sidebar-overlay').toggleClass('active');
        }

        $(document).ready(function () {
            $('#sidebarToggle, #sidebar-overlay').on('click', function () {
                toggleSidebar();
            });
        });
    </script>
    @vite('resources/js/app.js')
</body>

</html>
