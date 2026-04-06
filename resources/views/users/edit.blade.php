@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <style>
        :root {
            --user-edit-ink: #10243d;
            --user-edit-slate: #5f7088;
            --user-edit-line: #d9e4ef;
            --user-edit-panel: rgba(255, 255, 255, 0.96);
            --user-edit-primary: #1d4ed8;
            --user-edit-primary-soft: #dbeafe;
            --user-edit-success: #059669;
            --user-edit-success-soft: #d1fae5;
            --user-edit-warm: #b45309;
            --user-edit-warm-soft: #fef3c7;
        }

        .user-edit-page {
            max-width: 1500px;
        }

        .user-edit-shell {
            display: grid;
            gap: 1rem;
        }

        .user-edit-hero,
        .user-edit-panel {
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.78);
            background: var(--user-edit-panel);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .user-edit-hero {
            position: relative;
            overflow: hidden;
            padding: 30px;
        }

        .user-edit-hero::after {
            content: "";
            position: absolute;
            right: -70px;
            top: -70px;
            width: 240px;
            height: 240px;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.08);
        }

        .user-edit-hero > * {
            position: relative;
            z-index: 1;
        }

        .page-kicker,
        .meta-label,
        .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .page-kicker {
            margin-bottom: 10px;
            padding: 7px 12px;
            border-radius: 999px;
            background: var(--user-edit-primary-soft);
            color: var(--user-edit-primary);
        }

        .hero-top,
        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .user-edit-hero h2 {
            margin-bottom: 6px;
            color: var(--user-edit-ink);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .page-subtitle,
        .panel-copy,
        .meta-copy {
            color: var(--user-edit-slate);
        }

        .hero-meta-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 22px;
        }

        .hero-meta-card {
            padding: 18px;
            border-radius: 20px;
            border: 1px solid #e2ebf4;
            background: #ffffff;
            box-shadow: 0 12px 30px rgba(15, 34, 58, 0.05);
        }

        .meta-label {
            color: var(--user-edit-slate);
            margin-bottom: 8px;
        }

        .meta-value {
            color: var(--user-edit-ink);
            font-size: 1.08rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .meta-copy {
            display: block;
            margin-top: 6px;
            font-size: 0.82rem;
        }

        .user-edit-panel {
            padding: 22px;
        }

        .section-kicker {
            margin-bottom: 0.45rem;
            color: var(--user-edit-slate);
        }

        .panel-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: var(--user-edit-success-soft);
            color: var(--user-edit-success);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .edit-form-shell {
            padding: 1.25rem;
            border-radius: 22px;
            border: 1px solid #e2ebf4;
            background: linear-gradient(180deg, #fbfdff 0%, #f8fbff 100%);
        }

        .error-card {
            border-radius: 20px;
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #9f1239;
            box-shadow: 0 12px 28px rgba(244, 63, 94, 0.08);
        }

        .error-card ul {
            padding-left: 1.1rem;
        }

        .form-helper-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .helper-card {
            padding: 1rem 1.1rem;
            border-radius: 18px;
            border: 1px solid #e2ebf4;
            background: #ffffff;
        }

        .helper-title {
            color: var(--user-edit-ink);
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .helper-copy {
            color: var(--user-edit-slate);
            font-size: 0.88rem;
            margin: 0;
        }

        .btn-back-directory {
            border-radius: 14px;
            padding: 10px 18px;
            font-weight: 700;
            background: #f4f6fb;
            color: #5b6b8b;
            border: 1px solid #dce3ef;
            text-decoration: none;
        }

        .btn-back-directory:hover {
            background: #e9efff;
            color: #2c3e50;
        }

        @media (max-width: 1200px) {
            .hero-meta-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .user-edit-hero,
            .user-edit-panel {
                padding: 18px;
            }

            .user-edit-hero h2 {
                font-size: 1.55rem;
            }

            .hero-top,
            .panel-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .edit-form-shell {
                padding: 1rem;
            }

            .form-helper-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container-fluid user-edit-page py-4 px-md-4 px-xl-5">
        <div class="user-edit-shell">
            <section class="user-edit-hero">
                <span class="page-kicker">
                    <i class="bi bi-sliders"></i>
                    User settings
                </span>

                <div class="hero-top">
                    <div>
                        <h2>Edit User</h2>
                        <p class="page-subtitle mb-0">Update role access, password details, and document permissions for the selected account.</p>
                    </div>

                    <a href="{{ route('users.index') }}" class="btn-back-directory">
                        <i class="bi bi-arrow-left me-2"></i>Back to Users
                    </a>
                </div>
            </section>

            @if ($errors->any())
                <div class="alert error-card mb-0">
                    <div class="fw-bold mb-2">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Please review the form errors
                    </div>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="user-edit-panel">
                <div class="panel-header">
                    <div>
                        <div class="section-kicker">Access Controls</div>
                        <h5 class="fw-bold mb-1">User account settings</h5>
                        <p class="panel-copy mb-0">Use the form below to manage account details and document permission coverage.</p>
                    </div>
                    <span class="panel-chip">
                        <i class="bi bi-shield-check me-2"></i>Permissions editor
                    </span>
                </div>

                <div class="form-helper-grid">
                    <article class="helper-card">
                        <div class="helper-title">Role behavior</div>
                        <p class="helper-copy">Selecting the admin role grants full permission coverage and disables the document permission checkboxes automatically.</p>
                    </article>

                    <article class="helper-card">
                        <div class="helper-title">Password update</div>
                        <p class="helper-copy">Leave the password fields blank if you want to keep the user’s current password unchanged.</p>
                    </article>
                </div>

                <div class="edit-form-shell">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        @include('users._form', [
                            'submitLabel' => 'Save Changes',
                        ])
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
