<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body {
            overflow-x: hidden;
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
        }


        /* CONTENT AREA */
        .content {
            margin-left: 300px;
            padding: 30px;
            transition: 0.3s;
        }



        /* SIDEBAR */

        .sidebar {

            background: linear-gradient(180deg, #0f172a, #1e293b);

            color: #fff;

            width: 300px;

            height: 100vh;

            position: fixed;

            top: 0;
            left: 0;

            padding: 25px 15px;

            overflow-y: auto;

            border-right: 1px solid rgba(255, 255, 255, 0.05);

            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.15);

        }



        /* SYSTEM TITLE */

        .sidebar h4 {

            font-weight: 600;

            color: #f8fafc;

            margin-bottom: 30px;

            text-align: center;

            letter-spacing: 0.5px;

        }



        /* MENU LINKS */

        .sidebar .nav-link {

            color: #cbd5e1;

            font-size: 15px;

            padding: 12px 15px;

            margin-bottom: 6px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            gap: 10px;

            transition: all 0.25s ease;

        }



        /* HOVER EFFECT */

        .sidebar .nav-link:hover {

            background: #334155;

            color: white;

            transform: translateX(4px);

        }



        /* ACTIVE LINK */

        .sidebar .nav-link.active {

            background: #2563eb;

            color: white !important;

            font-weight: 600;

            box-shadow: 0px 5px 12px rgba(0, 0, 0, 0.25);

        }



        /* SUB MENU */

        .sidebar .collapse .nav-link {

            padding-left: 40px;

            font-size: 14px;

            color: #cbd5e1;

        }



        /* SUBMENU HOVER */

        .sidebar .collapse .nav-link:hover {

            background: #334155;

            color: white;

        }



        /* ACTIVE SUBMENU */

        .sidebar .collapse .nav-link.active {

            background: #1d4ed8;

            color: white !important;

        }



        /* ICONS */

        .sidebar i {

            width: 22px;

            text-align: center;

            font-size: 16px;

        }



        /* COLLAPSE ARROW */

        .sidebar .bi-chevron-down {

            font-size: 12px;

            opacity: 0.7;

            margin-left: auto;

            transition: 0.3s;

        }



        /* ROTATE ARROW */

        .sidebar .nav-link:not(.collapsed) .bi-chevron-down {

            transform: rotate(180deg);

        }



        /* SCROLLBAR */

        .sidebar::-webkit-scrollbar {

            width: 6px;

        }

        .sidebar::-webkit-scrollbar-thumb {

            background: #475569;

            border-radius: 10px;

        }



        /* CARD LOOK FOR CONTENT */

        .card {

            border-radius: 12px;

            border: none;

            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.08);

        }



        /* BUTTONS */

        .btn {

            border-radius: 8px;

            padding: 8px 18px;

            font-weight: 500;

        }



        /* PAGE TITLES */

        h3 {

            font-weight: 600;

            color: #1e293b;

            margin-bottom: 20px;

        }

        /* PAGE TITLE */

        .page-title {

            font-weight: 600;

            color: #1e293b;

        }



        /* FORM CARD */

        .form-card {

            border-radius: 14px;

            border: none;

            box-shadow: 0px 5px 18px rgba(0, 0, 0, 0.08);

            overflow: hidden;

        }



        /* SECTION HEADERS */

        .section-header {

            background: #f8fafc;

            padding: 14px 20px;

            font-weight: 600;

            border-bottom: 1px solid #e2e8f0;

            color: #1e293b;

        }



        .section-header i {

            margin-right: 8px;

            color: #2563eb;

        }



        /* SECTION BODY */

        .section-body {

            padding: 20px;

        }



        /* INPUTS */

        .form-input {

            border-radius: 8px;

            border: 1px solid #cbd5e1;

            padding: 10px;

            transition: 0.2s;

        }



        .form-input:focus {

            border-color: #2563eb;

            box-shadow: 0px 0px 5px rgba(37, 99, 235, 0.3);

        }



        /* FOOTER */

        .form-footer {

            padding: 20px;

            border-top: 1px solid #e2e8f0;

            background: #f8fafc;

        }



        /* BUTTONS */

        .btn-save {

            background: #2563eb;

            color: white;

            padding: 10px 22px;

            border-radius: 8px;

            font-weight: 600;

        }



        .btn-save:hover {

            background: #1d4ed8;

            color: white;

        }



        .btn-cancel {

            margin-left: 10px;

            border-radius: 8px;

            padding: 10px 22px;

            background: #e5e7eb;

        }



        .btn-cancel:hover {

            background: #d1d5db;

        }

        /* SEARCH CARD */

        .search-card {

            border-radius: 12px;
            border: none;
            box-shadow: 0px 3px 12px rgba(0, 0, 0, 0.06);

        }



        /* TABLE CARD */

        .table-card {

            border-radius: 14px;
            border: none;

            box-shadow: 0px 5px 18px rgba(0, 0, 0, 0.08);

            overflow: hidden;

        }



        /* MODERN TABLE */

        .table-modern {

            margin: 0;

        }



        .table-modern thead {

            background: #1e293b;
            color: white;

        }



        .table-modern th {

            padding: 14px;

            font-weight: 600;

            border: none;

        }



        .table-modern td {

            padding: 14px;

            vertical-align: middle;

            border-top: 1px solid #e2e8f0;

        }



        .table-modern tbody tr:hover {

            background: #f1f5f9;

        }



        /* BUTTONS */

        .btn-edit {

            background: #f59e0b;
            color: white;
            border-radius: 6px;
        }

        .btn-edit:hover {

            background: #d97706;
            color: white;

        }



        .btn-delete {

            background: #dc2626;
            color: white;
            border-radius: 6px;
        }

        .btn-delete:hover {

            background: #b91c1c;
            color: white;

        }

        .dashboard-navbar {

            margin-left: 300px;
            height: 60px;

            border-bottom: 1px solid #eee;

        }


        .user-menu {

            font-weight: 500;
            color: #333 !important;

        }


        .user-menu:hover {

            color: #0d6efd !important;

        }

        /* ===== GLOBAL ===== */
        body {
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            font-family: 'Inter', sans-serif;
        }

        .container {
            max-width: 1200px;
        }

        /* ===== PAGE TITLE ===== */
        .page-title {
            font-weight: 700;
            font-size: 26px;
            color: #0f172a;
            letter-spacing: 0.5px;
        }

        /* ===== MAIN CARD ===== */
        .premium-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        /* ===== SECTION HEADERS ===== */
        .section-title {
            font-weight: 600;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 20px;
        }

        /* ===== TABS (Modern Pills) ===== */
        .nav-tabs {
            border: none;
            gap: 10px;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
            background: #f1f5f9;
            color: #334155;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            background: #e2e8f0;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        /* ===== INPUTS ===== */
        .form-control {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 12px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        /* ===== FILE INPUT CUSTOM ===== */
        input[type="file"] {
            background: #f8fafc;
            cursor: pointer;
        }

        /* ===== SECTION DIVIDER ===== */
        .section-divider {
            margin: 40px 0 20px;
            border-top: 1px solid #e2e8f0;
        }

        /* ===== BUTTONS ===== */
        .btn-premium {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            color: white;
            padding: 12px 26px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        }

        .btn-light-premium {
            background: #e2e8f0;
            border-radius: 12px;
            padding: 12px 26px;
        }

        .btn-light-premium:hover {
            background: #cbd5e1;
        }
    </style>
</head>

<body>

    @include('partials.sidebar')

    <div class="main-content">
        @include('partials.navbar')

        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (Required) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>