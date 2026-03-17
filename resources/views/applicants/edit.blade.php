@extends('layouts.app')

@section('content')

    @if(session('created_success'))

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                Swal.fire({
                    title: 'Applicant Successfully Created',
                    html: `
                                                                                                                                                                                                <div style="font-size:14px;">
                                                                                                                                                                                                    <p class="mb-2">The applicant profile has been saved successfully.</p>
                                                                                                                                                                                                    <p class="text-muted">Would you like to continue editing the applicant requirements?</p>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            `,
                    icon: 'success',
                    background: '#ffffff',
                    color: '#333',
                    width: 420,
                    showCancelButton: true,

                    confirmButtonText: '<i class="fa-solid fa-pen-to-square me-2"></i> Continue Editing',
                    cancelButtonText: '<i class="fa-solid fa-arrow-left me-2"></i> Back to List',

                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',

                    buttonsStyling: true,
                    reverseButtons: true,

                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }

                }).then((result) => {

                    if (!result.isConfirmed) {
                        window.location.href = "{{ route('applicants.index') }}";
                    }

                });

            });
        </script>

    @endif

    <style>
        .d-flex .btn {
            white-space: nowrap;
        }

        .btn-outline-primary {
            border-radius: 8px;
            transition: .2s;
        }

        .btn-outline-primary:hover {
            background: #2563eb;
            color: #fff;
        }

        /* ================================
                                        GLOBAL STYLES
                                        ================================ */

        body {
            background: linear-gradient(135deg, #eef2f7, #e8edf5);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ================================
                                        MAIN WRAPPER
                                        ================================ */

        .applicant-wrapper {
            max-width: 1500px;
        }

        /* ================================
                                        PAGE HEADER
                                        ================================ */

        .page-header {
            background: linear-gradient(135deg, #ffffff, #f6f9ff);
            padding: 20px 25px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e6ecf5;
        }

        .page-header h2 {
            font-weight: 700;
            color: #2c3e50;
        }

        /* ================================
                                        MAIN CARD CONTAINER
                                        ================================ */

        .requirements-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        /* ================================
                                        SECTION TITLES
                                        ================================ */

        .section-title {
            font-weight: 700;
            font-size: 15px;
            color: #3b4a6b;
            letter-spacing: .3px;
            margin-bottom: 15px;
        }

        /* ================================
                                        NAV TABS
                                        ================================ */

        .nav-tabs {
            border: none;
            gap: 10px;
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            background: #f4f6fb;
            color: #5b6b8b;
            font-weight: 600;
            transition: all .3s ease;
        }

        .nav-tabs .nav-link:hover {
            background: #e9efff;
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #4a7dff, #5fa8ff);
            color: white;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
        }

        /* ================================
                                        TAB CONTENT
                                        ================================ */

        .tab-content {
            border-radius: 14px;
            background: white;
            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        /* ================================
                                        FORM CARD
                                        ================================ */

        .form-card {
            padding: 10px 5px;
        }

        /* ================================
                                        FORM LABEL
                                        ================================ */

        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #44526f;
        }

        /* ================================
                                        INPUTS
                                        ================================ */

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #dce3ef;
            padding: 10px 12px;
            font-size: 14px;
            transition: all .25s ease;
            background: #f9fbff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #5fa8ff;
            background: white;
            box-shadow: 0 0 0 3px rgba(90, 150, 255, 0.15);
        }

        /* ================================
                                        FILE INPUTS
                                        ================================ */

        input[type=file] {
            background: #f5f8ff;
            border: 1px dashed #c9d5f2;
        }

        /* ================================
                                        BUTTONS
                                        ================================ */

        .btn {
            border-radius: 10px;
            font-weight: 600;
            transition: all .3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a7dff, #6aa8ff);
            border: none;
            box-shadow: 0 6px 15px rgba(74, 125, 255, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(74, 125, 255, 0.35);
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border: none;
            box-shadow: 0 6px 15px rgba(39, 174, 96, 0.35);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(39, 174, 96, 0.45);
        }

        .btn-outline-secondary {
            border-radius: 10px;
        }

        /* ================================
                                        FORM GRID RESPONSIVE
                                        ================================ */

        @media(max-width:1200px) {

            .col-md-2 {
                flex: 0 0 33%;
                max-width: 33%;
            }

        }

        @media(max-width:768px) {

            .col-md-2,
            .col-md-3,
            .col-md-4,
            .col-md-5,
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .page-header {
                flex-direction: column;
                gap: 10px;
            }

        }

        /* ================================
                                        3D CARD EFFECT
                                        ================================ */

        .requirements-container:hover {
            transform: translateY(-2px);
            box-shadow:
                0 18px 50px rgba(0, 0, 0, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: .4s;
        }

        /* ================================
                                        REQUIRED MARK
                                        ================================ */

        .required-mark {
            color: #e74c3c;
            font-weight: bold;
        }

        /* ================================
                                        ANIMATIONS
                                        ================================ */

        .tab-pane {
            animation: fadeSlide .35s ease;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ================================
                                        SCROLL SMOOTH
                                        ================================ */

        html {
            scroll-behavior: smooth;
        }
    </style>
    {{-- <style>
        /* ===============================
                                                GLOBAL
                                                ================================ */

        :root {
            /* Primary Theme: Ocean Blue */
            --primary-color: #0284c7;
            --primary-hover: #0369a1;
            /* Success Theme: Emerald Green */
            --success-color: #10b981;
            --success-hover: #059669;
            /* UI Neutrals */
            --bg-light: #f0f4f8;
            --border-radius: 12px;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--text-main);
            line-height: 1.6;
        }

        .applicant-wrapper {
            max-width: 1700px;
            margin: 40px auto;
            padding-bottom: 100px;
        }

        /* ===============================
                                                HEADER
                                                ================================ */

        .page-header {
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .08);
        }

        /* ===============================
                                                MAIN CARD
                                                ================================ */

        .requirements-container {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .08);
        }

        /* ===============================
                                                FORM CARD
                                                ================================ */

        .form-card {
            background: white;
            border-radius: 18px;
            box-shadow:
                0 10px 25px rgba(0, 0, 0, .08),
                inset 0 1px 0 rgba(255, 255, 255, .9);
        }

        /* ===============================
                                                SECTION TITLE
                                                ================================ */

        .section-title {
            font-weight: 700;
            color: #1e293b;
            border-left: 5px solid #2563eb;
            padding-left: 12px;
            margin-bottom: 20px;
        }

        /* ===============================
                                                FLOATING LABEL
                                                ================================ */

        .form-floating>.form-control,
        .form-floating>.form-select {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .15);
        }

        /* ===============================
                                                TABS
                                                ================================ */

        .nav-tabs {
            border: none;
            gap: 8px;
        }

        .nav-tabs .nav-link {
            background: #eef2f7;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            border: none;
            transition: .3s;
        }

        .nav-tabs .nav-link.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 6px 15px rgba(0, 0, 0, .15);
        }

        /* ===============================
                                                FILE UPLOAD
                                                ================================ */

        .file-preview {
            margin-top: 6px;
            font-size: 12px;
            color: #16a34a;
        }

        /* ===============================
                                                PROFILE AVATAR
                                                ================================ */

        .avatar-box {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #e2e8f0;
        }

        .avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===============================
                                                BUTTON
                                                ================================ */

        .btn-primary {
            background: #2563eb;
            border: none;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-success {
            border-radius: 10px;
        }

        .required-mark {
            color: #ef4444;
        }

        /* ===============================
                                                PROGRESS BAR
                                                ================================ */

        .progress {
            height: 8px;
            border-radius: 10px;
        }
    </style> --}}
    {{-- Personal Information --}}
    {{-- <style>
        :root {
            /* Primary Theme: Ocean Blue */
            --primary-color: #0284c7;
            --primary-hover: #0369a1;
            /* Success Theme: Emerald Green */
            --success-color: #10b981;
            --success-hover: #059669;
            /* UI Neutrals */
            --bg-light: #f0f4f8;
            --border-radius: 12px;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--text-main);
            line-height: 1.6;
        }

        .applicant-wrapper {
            max-width: 1700px;
            margin: 40px auto;
            padding-bottom: 100px;
        }

        .applicant-pane {
            background: #f6f8fb;
            padding: 35px;
            border-radius: 14px;
        }

        /* CARD */
        .form-card {
            background: white;
            border-radius: 14px;

            box-shadow: 0 10px 25px rgba(0, 0, 0, .06);
            border: 1px solid #eef1f6;
        }

        /* SECTION TITLE */

        .section-title {
            font-size: 15px;
            font-weight: 600;
            letter-spacing: .4px;
            color: #3a6df0;
            border-left: 4px solid #3a6df0;
            padding-left: 10px;
            margin-bottom: 25px;
        }

        /* INPUT STYLE */

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #dfe4ec;
            padding: 9px 12px;
            font-size: 14px;
            transition: .25s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3a6df0;
            box-shadow: 0 0 0 3px rgba(58, 109, 240, .08);
        }

        /* LABEL */

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
        }

        /* REQUIRED MARK */

        .required-mark {
            color: #e11d48;
        }

        /* BUTTONS */

        .btn-success {
            background: #3a6df0;
            border: none;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-success:hover {
            background: #2956cc;
        }

        .btn-light {
            border-radius: 8px;
        }

        /* GRID SPACE */

        .row.g-4>div {
            margin-bottom: 5px;
        }
    </style> --}}

    {{-- <style>
        :root {
            /* Primary Theme: Ocean Blue */
            --primary-color: #0284c7;
            --primary-hover: #0369a1;
            /* Success Theme: Emerald Green */
            --success-color: #10b981;
            --success-hover: #059669;
            /* UI Neutrals */
            --bg-light: #f0f4f8;
            --border-radius: 12px;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--text-main);
            line-height: 1.6;
        }

        .applicant-wrapper {
            max-width: 1700px;
            margin: 40px auto;
            padding-bottom: 100px;
        }

        .page-header h2 {
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.025em;
        }

        /* Main Card Container */
        .main-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.1);
            background: #ffffff;
            overflow: hidden;
        }

        /* Requirements Section Styling */
        .requirements-container {
            background-color: #f8fafc;
            border-radius: var(--border-radius);
            border: 1px solid #e2e8f0;
            margin-bottom: 2.5rem;
        }

        /* Modern Tabs */
        .nav-tabs {
            border-bottom: 2px solid #e2e8f0;
            gap: 5px;
        }

        .nav-tabs .nav-link {
            border: none;
            color: var(--text-muted);
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            transition: all 0.2s ease;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: #ffffff;
            border-bottom: 3px solid var(--primary-color);
        }

        .nav-tabs .nav-link:hover:not(.active) {
            background-color: #f1f5f9;
            color: var(--primary-hover);
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.15);
        }

        /* File Preview Box - The Blue Accent */
        .file-status-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f0f9ff;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #bae6fd;
            margin-top: 8px;
        }

        .file-name {
            font-size: 0.8rem;
            color: #0369a1;
            font-weight: 500;
        }

        /* Buttons */
        .btn-success {
            background-color: var(--success-color);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: transform 0.1s ease, background-color 0.2s ease;
        }

        .btn-success:hover {
            background-color: var(--success-hover);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Section Headings */
        .section-title {
            position: relative;
            padding-left: 15px;
            border-left: 4px solid var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 700;
            color: #0f172a;
        }

        /* Special text colors */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-success {
            color: var(--success-color) !important;
        }

        .btn-generate {
            background-color: #0284c7;
            border-color: #0284c7;
            color: white;
            transition: all 0.2s ease;
        }

        .btn-generate:hover {
            background-color: #0369a1;
            border-color: #0369a1;
            color: white;
            transform: translateY(-1px);
        }

        .btn-generate:active {
            background-color: #0369a1;
        }

        /* Health Card */
        .upload-group .file-label {
            max-width: 700px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 13px;
        }

        .upload-group .btn {
            border-radius: 0 6px 6px 0;
        }

        .upload-group input[type="file"] {
            cursor: pointer;
        }

        /* Responsive Fixes */
        @media (max-width: 768px) {
            .nav-tabs {
                flex-direction: column;
            }

            .btn-generate {
                width: 100%;
                margin-top: 15px;
            }
        }
    </style> --}}

    {{-- <style>
        :root {
            /* New Refined Palette: Indigo & Slate */
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --success: #10b981;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-light: #64748b;
            --border-soft: #e2e8f0;
            --radius: 12px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--text-body);
            -webkit-font-smoothing: antialiased;
        }

        .applicant-wrapper {
            max-width: 1400px;
            /* More contained for better readability */
            margin: 2rem auto;
            padding: 0 1.5rem 5rem 1.5rem;
        }

        /* Page Header */
        .page-header h2 {
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-heading);
        }

        /* Modern Card Overhaul */
        .main-card {
            border: 1px solid var(--border-soft);
            border-radius: var(--radius);
            background: var(--bg-card);
            box-shadow: var(--shadow-md);
            padding: 1.5rem;
        }

        /* Requirements Container - "The Feature Box" */
        .requirements-container {
            background-color: #f1f5f9;
            border-radius: var(--radius);
            border: 1px dashed #cbd5e1;
            /* Dashed looks better for lists/notes */
            padding: 1.5rem;
            transition: border-color 0.3s ease;
        }

        /* Nav Tabs - Underline Style */
        .nav-tabs {
            border-bottom: 2px solid var(--border-soft);
            gap: 2rem;
        }

        .nav-tabs .nav-link {
            border: none;
            color: var(--text-light);
            font-weight: 600;
            font-size: 0.95rem;
            padding: 1rem 0;
            background: transparent;
            position: relative;
            transition: color 0.2s ease;
        }

        .nav-tabs .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary);
            background: transparent;
        }

        .nav-tabs .nav-link.active::after {
            width: 100%;
        }

        /* Form Controls - Minimalist */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-heading);
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .form-control,
        .form-select {
            border: 1.5px solid var(--border-soft);
            padding: 0.6rem 0.8rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        /* File Status Box - Modernized blue */
        .file-status-box {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            color: #4338ca;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
        }

        /* Buttons - Elevated Primary */
        .btn-success {
            background: var(--success);
            border: none;
            box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.3);
            padding: 10px 24px;
            font-weight: 600;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.4);
        }

        .btn-generate {
            background: var(--primary);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
        }

        /* Section Title - Cleaner indicator */
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 18px;
            background: var(--primary);
            border-radius: 10px;
            display: inline-block;
        }

        /* Animations */
        .tab-pane {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .nav-tabs {
                gap: 1rem;
            }

            .nav-tabs .nav-link {
                padding: 0.5rem 0;
            }
        }
    </style> --}}
    {{-- <style>
        :root {
            /* Professional Palette: Deep Slates & Refined Cobalt */
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-600: #475569;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;

            --brand-primary: #2563eb;
            /* Professional Cobalt */
            --brand-success: #059669;

            --radius-sm: 6px;
            --radius-md: 8px;
            --font-main: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #f4f7fa;
            font-family: var(--font-main);
            color: var(--neutral-800);
            font-size: 14px;
            /* Standard enterprise font size */
        }

        /* Container Refinement */
        .applicant-wrapper {
            max-width: 1440px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header: Clean & Authoritative */
        .page-header h2 {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--neutral-900);
            letter-spacing: -0.02em;
        }

        /* Main Card: Flat with subtle border (The "System" Look) */
        .main-card {
            background: #ffffff;
            border: 1px solid var(--neutral-200);
            border-radius: var(--radius-md);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        /* Requirements Section: Information Density */
        .requirements-container {
            background-color: var(--neutral-50);
            border: 1px solid var(--neutral-200);
            border-left: 4px solid var(--brand-primary);
            /* Indication of importance */
            border-radius: var(--radius-sm);
            padding: 1.25rem;
        }

        /* Tabs: Minimalist Navigation */
        .nav-tabs {
            border-bottom: 1px solid var(--neutral-200);
            gap: 0;
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: var(--neutral-600);
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0;
            margin-bottom: -1px;
            transition: all 0.2s;
        }

        .nav-tabs .nav-link:hover {
            background: var(--neutral-100);
            color: var(--neutral-900);
        }

        .nav-tabs .nav-link.active {
            color: var(--brand-primary);
            background: transparent;
            border-bottom: 2px solid var(--brand-primary);
            font-weight: 600;
        }

        /* Inputs: Focused & Sharp */
        .form-label {
            font-weight: 500;
            color: var(--neutral-600);
            margin-bottom: 0.4rem;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--neutral-200);
            border-radius: var(--radius-sm);
            padding: 0.5rem 0.75rem;
            transition: border-color 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 1px var(--brand-primary);
            /* Sharp focus, no glow */
        }

        /* File Status: Information Alert Style */
        .file-status-box {
            background: #f0f7ff;
            border: 1px solid #dbeafe;
            color: #1e40af;
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Action Buttons: Solid & Trustworthy */
        .btn-success {
            background-color: var(--brand-success);
            border: 1px solid #047857;
            font-weight: 600;
            border-radius: var(--radius-sm);
            padding: 0.6rem 1.5rem;
            font-size: 14px;
        }

        .btn-success:hover {
            background-color: #059669;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-generate {
            background: white;
            color: var(--brand-primary);
            border: 1px solid var(--brand-primary);
            font-weight: 500;
        }

        .btn-generate:hover {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #1d4ed8;
        }

        /* Section Titles: Clear Hierarchy */
        .section-title {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--neutral-600);
            margin-bottom: 1.25rem;
            display: block;
            border-bottom: 1px solid var(--neutral-100);
            padding-bottom: 0.5rem;
        }
    </style> --}}

    <div class="container applicant-wrapper">
        <div class="page-header d-md-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa-solid fa-user-pen me-2 text-primary"></i>Update Applicant</h2>
            <a href="{{ route('applicants.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="requirements-container p-4">

            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-1">Document Compliance</h5>
                    <p class="text-muted small mb-0">Manage Mayor's Permit, Clearance, and Referral Requirements.</p>
                </div>
            </div>

            <ul class="nav nav-tabs mb-3" id="mayorTabs">

                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal">
                        Personal Information
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#permit">
                        Mayor's Permit to Work
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#clearance">
                        Mayor's Clearance
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#referral">
                        Mayor's Referral
                    </button>
                </li>

            </ul>

            <div class="tab-content bg-white p-4 rounded-3 border shadow-sm">

                <!-- ===================================================== -->
                <!-- PERSONAL INFORMATION -->
                <!-- ===================================================== -->

                <div class="tab-pane fade show active" id="personal">

                    <div class="form-card">

                        <form action="{{ route('applicants.update', $applicant->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <h6 class="section-title  text-primary mb-4">Personal Information</h6>

                            <div class="row g-4">

                                {{-- FIRST TIME JOB SEEKER --}}
                                <div class="col-md-2">
                                    <label class="form-label">First Time Job Seeker?</label>
                                    <select name="first_time_job_seeker" class="form-select">
                                        <option value="No" {{ $applicant->first_time_job_seeker == "No" ? 'selected' : '' }}>
                                            No
                                        </option>
                                        <option value="Yes" {{ $applicant->first_time_job_seeker == "Yes" ? 'selected' : '' }}>Yes
                                        </option>
                                    </select>
                                </div>

                                {{-- FIRST NAME --}}
                                <div class="col-md-2">
                                    <label class="form-label">First Name <span class="required-mark">*</span></label>
                                    <input type="text" name="first_name" value="{{ $applicant->first_name }}"
                                        class="form-control" required>
                                </div>

                                {{-- MIDDLE NAME --}}
                                <div class="col-md-2">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" value="{{ $applicant->middle_name }}"
                                        class="form-control">
                                </div>

                                {{-- LAST NAME --}}
                                <div class="col-md-2">
                                    <label class="form-label">Last Name <span class="required-mark">*</span></label>
                                    <input type="text" name="last_name" value="{{ $applicant->last_name }}"
                                        class="form-control" required>
                                </div>

                                {{-- SUFFIX --}}
                                <div class="col-md-2">
                                    <label class="form-label">Suffix</label>
                                    <select name="suffix" class="form-select">
                                        <option value="">None</option>
                                        <option value="Jr." {{ $applicant->suffix == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                        <option value="Sr." {{ $applicant->suffix == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                        <option value="II" {{ $applicant->suffix == 'II' ? 'selected' : '' }}>II</option>
                                        <option value="III" {{ $applicant->suffix == 'III' ? 'selected' : '' }}>III</option>
                                        <option value="IV" {{ $applicant->suffix == 'IV' ? 'selected' : '' }}>IV</option>
                                    </select>
                                </div>

                                {{-- AGE --}}
                                <div class="col-md-2">
                                    <label class="form-label">Age <span class="required-mark">*</span></label>
                                    <input type="number" name="age" value="{{ $applicant->age }}" class="form-control"
                                        required>
                                </div>

                                {{-- GENDER --}}
                                <div class="col-md-2">
                                    <label class="form-label">Gender <span class="required-mark">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="Male" {{ $applicant->gender == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female" {{ $applicant->gender == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                </div>

                                {{-- CIVIL STATUS --}}
                                <div class="col-md-2">
                                    <label class="form-label">Civil Status<span class="required-mark">*</span></label>
                                    <select name="civil_status" class="form-select" required>
                                        <option value="Single" {{ $applicant->civil_status == 'Single' ? 'selected' : '' }}>
                                            Single</option>
                                        <option value="Married" {{ $applicant->civil_status == 'Married' ? 'selected' : '' }}>
                                            Married</option>
                                        <option value="Widowed" {{ $applicant->civil_status == 'Widowed' ? 'selected' : '' }}>
                                            Widowed</option>
                                    </select>
                                </div>

                                {{-- PWD --}}
                                <div class="col-md-1">
                                    <label class="form-label">PWD<span class="required-mark">*</span></label>
                                    <select name="pwd" class="form-select" required>
                                        <option value="No" {{ $applicant->pwd == "No" ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ $applicant->pwd == "Yes" ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>

                                {{-- 4PS --}}
                                <div class="col-md-1">
                                    <label class="form-label">4Ps<span class="required-mark">*</span></label>
                                    <select name="four_ps" class="form-select" required>
                                        <option value="No" {{ $applicant->four_ps == "No" ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ $applicant->four_ps == "Yes" ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>

                                {{-- CONTACT --}}
                                <div class="col-md-3">
                                    <label class="form-label">Contact No<span class="required-mark">*</span></label>
                                    <input type="text" name="contact_no" value="{{ $applicant->contact_no }}"
                                        class="form-control" required>
                                </div>

                                {{-- ADDRESS --}}
                                <div class="col-md-5">
                                    <label class="form-label">Complete Address<span class="required-mark">*</span></label>
                                    <input type="text" name="address_line" value="{{ $applicant->address_line }}"
                                        class="form-control" required>
                                </div>

                                {{-- PROVINCE --}}
                                <div class="col-md-2">
                                    <label class="form-label">Province<span class="required-mark">*</span></label>
                                    <select name="province" id="province" class="form-select" required></select>
                                </div>

                                {{-- CITY --}}
                                <div class="col-md-2">
                                    <label class="form-label">City<span class="required-mark">*</span></label>
                                    <select name="city" id="city" class="form-select" required></select>
                                </div>

                                {{-- BARANGAY --}}
                                <div class="col-md-2">
                                    <label class="form-label">Barangay<span class="required-mark">*</span></label>
                                    <select name="barangay" id="barangay" class="form-select" required></select>
                                </div>

                                {{-- EDUCATION --}}
                                <div class="col-md-4">
                                    <label class="form-label">Educational Attainment<span
                                            class="required-mark">*</span></label>
                                    <input type="text" name="educational_attainment"
                                        value="{{ $applicant->educational_attainment }}" class="form-control" required>
                                </div>

                                {{-- COMPANY --}}
                                <div class="col-md-4">
                                    <label class="form-label">Hiring Company<span class="required-mark">*</span></label>
                                    <input type="text" name="hiring_company" value="{{ $applicant->hiring_company }}"
                                        class="form-control" required>
                                </div>

                                {{-- POSITION --}}
                                <div class="col-md-4">
                                    <label class="form-label">Position Hired<span class="required-mark">*</span></label>
                                    <input type="text" name="position_hired" value="{{ $applicant->position_hired }}"
                                        class="form-control" required>
                                </div>

                            </div>


                            <div class="d-flex gap-3 pt-4 mt-4 border-top">

                                <button type="submit" class="btn btn-success px-5 py-2">
                                    <i class="fa-solid fa-check me-2"></i>
                                    Update Applicant Profile
                                </button>

                                <a href="{{ route('applicants.index') }}" class="btn btn-light border px-4 py-2">
                                    Cancel
                                </a>

                            </div>

                        </form>

                    </div>
                </div>

                <!-- ===================================================== -->
                <!-- PERMIT -->
                <!-- ===================================================== -->

                <div class="tab-pane fade" id="permit">

                    <form action="{{ route('permits.update', $applicant->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php
                            $permit = optional($applicant->permit);
                            $isImusResident = stripos($applicant->city, 'City of Imus') !== false;
                        @endphp

                        <h6 class="section-title text-primary mb-3">Mayor’s Permit to Work Requirements</h6>

                        <div class="row g-4">

                            {{-- Health Card --}}
                            <div class="col-md-6">
                                <label class="form-label">Health Card<span class="required-mark">*</span></label>
                                <input type="file" name="health_card" class="form-control" >
                            </div>

                            {{-- Cedula --}}
                            <div class="col-md-6">
                                <label class="form-label">Cedula<span class="required-mark">*</span></label>
                                <input type="file" name="Cedula" class="form-control" >
                            </div>

                            {{-- NBI / Police Clearance --}}
                            <div class="col-md-6">
                                <label class="form-label">NBI or Police Clearance<span class="required-mark">*</span></label>

                                <!-- DROPDOWN -->
                                <select id="clearance_type" class="form-select mb-2">
                                    <option value="">Select Clearance Type</option>
                                    <option value="nbi">NBI Clearance</option>
                                    <option value="police">Police Clearance</option>
                                </select>

                                <!-- NBI INPUT -->
                                <div id="nbi_input" style="display:none;">
                                    <input type="file" name="nbi_clearance" class="form-control">
                                </div>

                                <!-- POLICE INPUT -->
                                <div id="police_input" style="display:none;">
                                    <input type="file" name="police_clearance" class="form-control">
                                </div>

                            </div>

                            {{-- Referral Letter --}}

                            <div class="col-md-6">
                                <label class="form-label">Referral Letter<span class="required-mark">*</span></label>
                                <input type="file" name="referral_letter" class="form-control" {{ $isImusResident ? 'disabled' : '' }}>
                            </div>
                        </div>

                        <h6 class="section-title text-primary mt-4">Permit to Work ID Details</h6>

                        <div class="row g-3 mt-3">

                            {{-- OR NUMBER --}}
                            <div class="col-md-2">
                                <label class="form-label">O.R No. <span class="required-mark">*</span></label>
                                <input type="text" name="permit_or_no" value="{{ $permit->permit_or_no }}"
                                    class="form-control" required>
                            </div>

                            {{-- Peso ID No --}}
                            <div class="col-md-2">
                                <label class="form-label">Peso ID No.<span class="required-mark">*</span></label>
                                <input type="text" name="peso_id_no" class="form-control"
                                    value="{{ $permit->peso_id_no ?? '' }}" readonly>
                            </div>
                            {{-- Community Tax No --}}
                            <div class="col-md-2">
                                <label class="form-label">Community Tax No.<span class="required-mark">*</span></label>
                                <input type="text" name="community_tax_no" class="form-control"
                                    value="{{$permit->community_tax_no}}" required>
                            </div>

                            {{-- Issued On --}}
                            <div class="col-md-2">
                                <label class="form-label">Permit Issued On<span class="required-mark">*</span></label>
                                <input type="date" name="permit_issued_on" class="form-control"
                                    value="{{$permit->permit_issued_on}}" required>
                            </div>
                            {{-- Permit Date --}}
                            <div class="col-md-2">
                                <label class="form-label">Permit Date<span class="required-mark">*</span></label>
                                <input type="date" id="permit_date" name="permit_date" class="form-control" value="{{$permit->permit_date}}"
                                    required>
                            </div>

                            {{-- Expiration --}}
                            <div class="col-md-2">
                                <label class="form-label">Expires On<span class="required-mark">*</span></label>
                                <input type="date" id="expires_on" name="expires_on" class="form-control" value="{{$permit->expires_on}}"
                                    readonly>
                            </div>

                            {{-- Documentary Stamp --}}
                            <div class="col-md-2">
                                <label class="form-label">Documentary Stamp Control No.<span
                                        class="required-mark">*</span></label>
                                <input type="text" name="permit_doc_stamp_control_no" class="form-control"
                                    value="{{$permit->permit_doc_stamp_control_no}}" required>
                            </div>
                            {{-- Date of Payment --}}
                            <div class="col-md-2">
                                <label class="form-label">Date of Payment<span class="required-mark">*</span></label>
                                <input type="date" name="permit_date_of_payment" class="form-control"
                                    value="{{$permit->permit_date_of_payment}}">
                            </div>
                        </div>

                        <div class="pt-4 border-top mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                Save Permit
                            </button>
                        </div>

                    </form>

                </div>

                <!-- ===================================================== -->
                <!-- CLEARANCE -->
                <!-- ===================================================== -->

                <div class="tab-pane fade" id="clearance">

                    <form action="{{ route('clearances.update', $applicant->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php $clearance = optional($applicant->clearance); @endphp

                        <h6 class="section-title text-primary">Mayor's Clearance Requirements</h6>

                        <div class="row g-4">

                            <div class="col-md-6">
                                <label class="form-label">Prosecutor Clearance<span class="required-mark">*</span></label>
                                <input type="file" name="prosecutor_clearance" class="form-control" >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Municipal Trial Court Clearance<span
                                        class="required-mark">*</span></label>
                                <input type="file" name="mtc_clearance" class="form-control" >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Regional Trial Court Clearance<span
                                        class="required-mark">*</span></label>
                                <input type="file" name="rtc_clearance" class="form-control" >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NBI Clearance<span class="required-mark">*</span></label>
                                <input type="file" name="nbi_clearance" class="form-control" >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Barangay Clearance<span class="required-mark">*</span></label>
                                <input type="file" name="barangay_clearance" class="form-control" >
                            </div>

                        </div>
                        <h6 class="section-title text-primary mb-0 mt-4">Mayor’s Clearance Letter Details</h6>
                        <div class="row g-3 mt-3">
                            {{-- Official Receipt No --}}
                            <div class="col-md-2">
                                <label class="form-label">O.R. No.<span class="required-mark">*</span></label>
                                <input type="text" name="clearance_or_no" class="form-control"
                                    value="{{$clearance->clearance_or_no}}" required>
                            </div>
                            {{-- PESO Control No --}}
                            <div class="col-md-2">
                                <label class="form-label">PESO Control No.<span class="required-mark">*</span></label>
                                <input type="text" name="peso_id_no" class="form-control" value="{{ $clearance->clearance_peso_control_no }}" readonly>
                            </div>
                            {{-- Hired Company --}}
                            <div class="col-md-2">
                                <label class="form-label">Hired Company<span class="required-mark">*</span></label>
                                <input type="text" name="clearance_hired_company" class="form-control"
                                    value="{{$clearance->clearance_hired_company}}" required>
                            </div>

                            {{-- Issued On --}}
                            <div class="col-md-2">
                                <label class="form-label">Issued On<span class="required-mark">*</span></label>
                                <input type="date" name="clearance_issued_on" class="form-control"
                                    value="{{$clearance->clearance_issued_on}}" required>
                            </div>
                            {{-- Issued In --}}
                            <div class="col-md-2">
                                <label class="form-label">Issued In<span class="required-mark">*</span></label>
                                <input type="text" name="clearance_issued_in" class="form-control"
                                    value="{{$clearance->clearance_issued_in}}" required>
                            </div>

                            {{-- Documentary Stamp Control No --}}
                            <div class="col-md-2">
                                <label class="form-label">Documentary Stamp Control No.<span
                                        class="required-mark">*</span></label>
                                <input type="text" name="clearance_doc_stamp_control_no" class="form-control"
                                    value="{{$clearance->clearance_doc_stamp_control_no}}" required>
                            </div>
                            {{-- Date of Payment --}}
                            <div class="col-md-2">
                                <label class="form-label">Date of Payment<span class="required-mark">*</span></label>
                                <input type="date" name="clearance_date_of_payment" class="form-control"
                                    value="{{$clearance->clearance_date_of_payment}}" required>
                            </div>
                        </div>

                        <div class="pt-4 border-top mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                Save Clearance
                            </button>
                        </div>

                    </form>

                </div>

                <!-- ===================================================== -->
                <!-- REFERRAL -->
                <!-- ===================================================== -->

                <div class="tab-pane fade" id="referral">

                    <form action="{{ route('referrals.update', $applicant->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php $referral = optional($applicant->referral); @endphp

                        <h6 class="section-title text-primary">Mayor's Referral Requirements</h6>

                        <div class="mb-4">
                            <label class="form-label">Resume / Bio-data</label>
                            <input type="file" name="resume" class="form-control">
                        </div>

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">Barangay Clearance</label>
                                <input type="file" name="ref_barangay_clearance" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Police Clearance</label>
                                <input type="file" name="ref_police_clearance" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">NBI Clearance</label>
                                <input type="file" name="ref_nbi_clearance" class="form-control">
                            </div>

                        </div>

                        <!-- INNER TABS -->
                        <div class="mt-4">
                            <h6 class="section-title text-primary">Mayor's Referral Requirements</h6>

                            <ul class="nav nav-tabs" id="referralTabs" role="tablist">


                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="peso-office-tab" data-bs-toggle="tab"
                                        data-bs-target="#peso-office" type="button" role="tab">
                                        PESO Office
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="other-city-tab" data-bs-toggle="tab"
                                        data-bs-target="#other-city" type="button" role="tab">
                                        Referral for Other City Government
                                    </button>
                                </li>

                            </ul>


                            <div class="tab-content border border-top-0 p-4">

                                <!-- TAB 1 -->
                                <div class="tab-pane fade show active" id="peso-office" role="tabpanel">

                                    <div class="row g-3">

                                        <div class="col-md-2">
                                            <label class="form-label">O.R No.</label>
                                            <input type="text" name="ref_or_no" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Mayor's First Name</label>
                                            <input type="text" name="ref_mayor_recipient_firstname" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Mayor's Middle Name</label>
                                            <input type="text" name="ref_mayor_recipient_middlename" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Mayor's Last Name</label>
                                            <input type="text" name="ref_mayor_recipient_lastname" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">City Government</label>
                                            <select name="ref_city_gov" id="cityGovernment" class="form-select">
                                                <option value="">Select City Government</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label"> City Address</label>
                                            <input type="text" name="ref_place" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Hired Company</label>
                                            <input type="text" name="ref_hired_company" class="form-control">
                                        </div>

                                    </div>

                                </div>

                                <!-- TAB 2 -->

                                <div class="tab-pane fade" id="other-city" role="tabpanel">

                                    <div class="row g-3">

                                        <div class="col-md-3">
                                            <label class="form-label">O.R No.</label>
                                            <input type="text" name="ref_peso_or_no" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Recipient Name</label>
                                            <input type="text" name="ref_recipient" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Company Address</label>
                                            <input type="text" name="ref_place" class="form-control">
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="pt-4 border-top mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                Save Referral
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection
{{-- City Government--}}
<script>

    document.addEventListener("DOMContentLoaded", function () {

        const cityDropdown = document.getElementById("cityGovernment");

        const allowedRegions = [
            "130000000", // NCR
            "040000000"  // Region 4A (CALABARZON)
        ];

        fetch("https://psgc.gitlab.io/api/cities-municipalities/")
            .then(response => response.json())
            .then(data => {

                data.forEach(city => {

                    if (allowedRegions.includes(city.regionCode)) {

                        const option = document.createElement("option");

                        option.value = "City Government of " + city.name;
                        option.text = "City Government of " + city.name;

                        cityDropdown.appendChild(option);

                    }

                });

            })
            .catch(error => console.error("Error loading cities:", error));

    });

</script>
{{-- Upload file name --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {

        document.querySelectorAll(".file-input").forEach(input => {
            input.addEventListener("change", function () {

                const previewId = this.dataset.preview;
                const previewContainer = document.getElementById(previewId);

                if (!previewContainer) return;

                if (this.files && this.files[0]) {

                    const file = this.files[0];
                    const fileURL = URL.createObjectURL(file);

                    previewContainer.innerHTML = `
                    <a href="${fileURL}" 
                       target="_blank"
                       class="badge bg-success text-white border px-3 py-2">
                        <i class="bi bi-file-earmark"></i>
                        ${file.name}
                    </a>
                `;
                }
            });

        });

    });
</script>
{{-- City Address --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');


        // SAVED VALUES
        let savedProvince = "{{ $applicant->province }}";
        let savedCity = "{{ $applicant->city }}";
        let savedBarangay = "{{ $applicant->barangay }}";



        // ---------- LOAD PROVINCES ----------
        function loadProvinces() {

            provinceSelect.innerHTML = '<option>Loading provinces...</option>';

            fetch('https://psgc.gitlab.io/api/provinces/')
                .then(response => response.json())
                .then(data => {

                    provinceSelect.innerHTML = '<option value="">Select Province</option>';

                    data.sort((a, b) => a.name.localeCompare(b.name));

                    data.forEach(province => {

                        let option = document.createElement('option');

                        option.value = province.name;
                        option.textContent = province.name;
                        option.dataset.code = province.code;

                        if (province.name === savedProvince) {
                            option.selected = true;
                            loadCities(province.code);
                        }

                        provinceSelect.appendChild(option);

                    });

                });

        }



        // ---------- LOAD CITIES ----------
        function loadCities(provinceCode) {

            citySelect.innerHTML = '<option>Loading cities...</option>';

            fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`)
                .then(response => response.json())
                .then(data => {

                    citySelect.innerHTML = '<option value="">Select City</option>';

                    data.sort((a, b) => a.name.localeCompare(b.name));

                    data.forEach(city => {

                        let option = document.createElement('option');

                        option.value = city.name;
                        option.textContent = city.name;
                        option.dataset.code = city.code;

                        if (city.name === savedCity) {
                            option.selected = true;
                            loadBarangays(city.code);
                        }

                        citySelect.appendChild(option);

                    });

                });

        }



        // ---------- LOAD BARANGAYS ----------
        function loadBarangays(cityCode) {

            barangaySelect.innerHTML = '<option>Loading barangays...</option>';

            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
                .then(response => response.json())
                .then(data => {

                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                    data.sort((a, b) => a.name.localeCompare(b.name));

                    data.forEach(barangay => {

                        let option = document.createElement('option');

                        option.value = barangay.name;
                        option.textContent = barangay.name;

                        if (barangay.name === savedBarangay) {
                            option.selected = true;
                        }

                        barangaySelect.appendChild(option);

                    });

                });

        }



        // ---------- EVENTS ----------
        provinceSelect.addEventListener('change', function () {

            let code = this.options[this.selectedIndex].dataset.code;

            if (code) {

                loadCities(code);

            } else {

                citySelect.innerHTML = '<option>Select City</option>';
                barangaySelect.innerHTML = '<option>Select Barangay</option>';

            }

        });


        citySelect.addEventListener('change', function () {

            let code = this.options[this.selectedIndex].dataset.code;

            if (code) {

                loadBarangays(code);

            } else {

                barangaySelect.innerHTML = '<option>Select Barangay</option>';

            }

        });



        // ---------- INIT ----------
        loadProvinces();

    });

</script>
{{-- Archived File--}}
<script>
    function clearFile(applicantId, field) {

        if (!confirm("Remove this file?")) return;

        fetch(`/permit/${applicantId}/${field}/delete`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Server error");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById("file-" + field).remove();
                } else {
                    alert("Error removing file.");
                }
            })
            .catch(error => {
                console.error(error);
                alert("Error removing file.");
            });
    }
</script>
{{-- Expires On --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const permitDate = document.getElementById("permit_date");
    const expiresOn = document.getElementById("expires_on");

    permitDate.addEventListener("change", function () {

        if (!this.value) return;

        let date = new Date(this.value);

        // Add 6 months
        date.setMonth(date.getMonth() + 6);

        // Fix date format (YYYY-MM-DD)
        let formatted = date.toISOString().split('T')[0];

        expiresOn.value = formatted;

    });

});
</script>
{{-- nbi or police --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const dropdown = document.getElementById("clearance_type");
    const nbiInput = document.getElementById("nbi_input");
    const policeInput = document.getElementById("police_input");

    dropdown.addEventListener("change", function () {

        // Hide both first
        nbiInput.style.display = "none";
        policeInput.style.display = "none";

        // Show selected
        if (this.value === "nbi") {
            nbiInput.style.display = "block";
        }

        if (this.value === "police") {
            policeInput.style.display = "block";
        }

    });

});
</script>