@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    @php
        $profileImageUrl = $user->profileImageUrl();
        $provider = ucfirst($user->auth_provider ?? 'local');
        $role = ucfirst($user->role ?? 'user');
        $displayInitial = strtoupper(substr($user->name ?? 'U', 0, 1));
    @endphp

    <div class="container-fluid py-0 px-md-4 px-xl-0 profile-shell">
        @if(session('success'))
            <div id="profileSuccessAlert" class="alert alert-success border-0 shadow-sm mb-4 profile-alert fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4 profile-alert" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-banner mb-4">
            <div class="profile-banner-content">
                <span class="profile-kicker"><i class="bi bi-person-badge"></i> Account Center</span>
                <h1 class="profile-title">My Profile</h1>
                <p class="profile-copy">
                    Edit your public name, profile photo, and password from one polished space.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4 align-items-start">
                <div class="col-xl-8">
                    <div class="profile-card section-card mb-4">
                        <div class="section-head">
                            <div>
                                <h5 class="section-title mb-1">Profile Information</h5>
                                <p class="section-copy mb-0">These details are shown across the system.</p>
                            </div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Display Name</label>
                                <input type="text" name="name" class="form-control modern-control" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control modern-control" value="{{ $user->email }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Authentication Provider</label>
                                <input type="text" class="form-control modern-control" value="{{ $provider }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control modern-control" value="{{ $role }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card section-card">
                        <div class="section-head">
                            <div>
                                <h5 class="section-title mb-1">Change Password</h5>
                                <p class="section-copy mb-0">Set a new password if your account uses local sign-in.</p>
                            </div>
                        </div>

                        @if(($user->auth_provider ?? 'local') === 'azure')
                            <div class="alert alert-info border-0 shadow-sm mt-3 mb-0">
                                <i class="bi bi-info-circle me-2"></i>Your account uses Microsoft sign-in. Password changes are managed by Microsoft.
                            </div>
                        @else
                            <div class="row g-3 mt-1">
                                <div class="col-12">
                                    <label class="form-label">Current Password</label>
                                    <div class="input-group modern-group">
                                        <input id="current_password" type="password" name="current_password" class="form-control modern-control">
                                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#current_password">Show</button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <div class="input-group modern-group">
                                        <input id="new_password" type="password" name="password" class="form-control modern-control">
                                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#new_password">Show</button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="input-group modern-group">
                                        <input id="confirm_password" type="password" name="password_confirmation" class="form-control modern-control">
                                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#confirm_password">Show</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="profile-card section-card profile-sidecard">
                        <div class="section-head">
                            <div>
                                <h5 class="section-title mb-1">Profile Image</h5>
                                <p class="section-copy mb-0">Use Google, Microsoft, or upload a new image.</p>
                            </div>
                        </div>

                        <div class="image-upload-panel mt-3">
                            <div class="image-preview-box">
                                @if($profileImageUrl)
                                    <img
                                        src="{{ $profileImageUrl }}"
                                        alt="{{ $user->name }}"
                                        class="image-preview"
                                        referrerpolicy="no-referrer"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                    >
                                    <div class="image-preview-fallback" style="display:none;">
                                        {{ $displayInitial }}
                                    </div>
                                @else
                                    <div class="image-preview-fallback">
                                        {{ $displayInitial }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Upload New Image</label>
                                <div class="upload-control">
                                    <input
                                        type="file"
                                        name="profile_image"
                                        id="profile_image"
                                        class="d-none"
                                        accept="image/*"
                                    >
                                    <button type="button" class="btn upload-trigger" data-upload-trigger="#profile_image">
                                        <i class="bi bi-upload me-1"></i> Choose Image
                                    </button>
                                    <span class="upload-filename" data-upload-filename>No file chosen</span>
                                </div>
                                <div class="upload-note mt-2">Accepted image files only. Max size: 5MB.</div>
                            </div>
                        </div>

                        <div class="profile-actions mt-4">
                            <button type="submit" class="btn btn-primary profile-save-btn w-100">
                                <i class="bi bi-check2-circle me-1"></i> Save Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .profile-shell {
            max-width: 1800px;
        }

        .profile-banner {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(280px, 0.9fr);
            gap: 1.25rem;
            padding: 1.5rem;
            border-radius: 28px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.14), transparent 24%),
                radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.12), transparent 28%),
                linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 22px 48px rgba(15, 23, 42, 0.08);
        }

        .profile-banner-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .profile-kicker {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            width: fit-content;
            padding: .45rem .8rem;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.1);
            color: #1d4ed8;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .76rem;
            font-weight: 800;
            margin-bottom: .85rem;
        }

        .profile-title {
            font-size: clamp(2rem, 3vw, 2.8rem);
            font-weight: 900;
            letter-spacing: -.05em;
            color: #0f172a;
        }

        .profile-copy,
        .section-copy,
        .summary-meta,
        .upload-note {
            color: #64748b;
        }

        .profile-tags {
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
            margin-top: .75rem;
        }

        .profile-tag {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .65rem .9rem;
            border-radius: 999px;
            border: 1px solid #dbe4f0;
            background: rgba(255,255,255,.9);
            color: #1f2937;
            font-size: .88rem;
            font-weight: 700;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
        }

        .profile-card {
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            background: #ffffff;
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.06);
            padding: 1.35rem;
        }

        .profile-summary-card {
            align-self: center;
            max-width: 100%;
        }

        .summary-label {
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #64748b;
        }

        .summary-body {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .summary-avatar,
        .summary-avatar-fallback {
            width: 88px;
            height: 88px;
            border-radius: 26px;
            object-fit: cover;
        }

        .summary-avatar-fallback,
        .image-preview-fallback {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2563eb, #10b981);
            color: #fff;
            font-size: 1.65rem;
            font-weight: 900;
        }

        .summary-name {
            font-size: 1.08rem;
            font-weight: 900;
            color: #0f172a;
        }

        .section-card {
            overflow: hidden;
        }

        .section-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e8eef6;
        }

        .section-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 900;
            color: #0f172a;
        }

        .modern-control {
            min-height: 50px;
            border-radius: 14px;
            border: 1px solid #d9e4ef;
            background: #f8fbff;
        }

        .modern-control:focus {
            border-color: #7aa2ff;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .modern-group .btn {
            border-radius: 0 14px 14px 0;
        }

        .image-upload-panel {
            display: flex;
            flex-direction: column;
        }

        .upload-control {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.55rem;
            border-radius: 16px;
            border: 1px solid #d9e4ef;
            background: #f8fbff;
        }

        .upload-trigger {
            flex-shrink: 0;
            min-height: 44px;
            padding: 0.65rem 1rem;
            border: 1px solid #cfe0f2;
            border-radius: 12px;
            background: #ffffff;
            color: #10243d;
            font-weight: 800;
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.04);
        }

        .upload-trigger:hover {
            background: #e0ecff;
            color: #0f172a;
            border-color: #bcd5ff;
        }

        .upload-filename {
            min-width: 0;
            color: #64748b;
            font-size: 0.95rem;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .image-preview-box {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 260px;
            border-radius: 24px;
            background:
                radial-gradient(circle at top, rgba(59, 130, 246, 0.08), transparent 30%),
                linear-gradient(180deg, #f8fbff 0%, #eef6ff 100%);
            border: 1px dashed #cbd5e1;
        }

        .image-preview,
        .image-preview-fallback {
            width: 124px;
            height: 124px;
            border-radius: 30px;
        }

        .profile-sidecard {
            position: sticky;
            top: 96px;
        }

        .profile-actions {
            display: flex;
        }

        .profile-save-btn {
            min-height: 52px;
            border-radius: 14px;
            padding: .85rem 1.2rem;
            font-weight: 800;
            border: none;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.24);
        }

        .profile-alert {
            border-radius: 16px;
            transition: opacity 0.15s ease, transform 0.15s ease;
        }

        html[data-theme="night"] body {
            background: #050816;
        }

        html[data-theme="night"] .profile-banner,
        html[data-theme="night"] .profile-card {
            background: #0f172a;
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
        }

        html[data-theme="night"] .profile-banner {
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.16), transparent 24%),
                radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.14), transparent 28%),
                linear-gradient(135deg, #0f172a 0%, #111827 100%);
        }

        html[data-theme="night"] .profile-kicker {
            background: rgba(59, 130, 246, 0.18);
            color: #bfdbfe;
        }

        html[data-theme="night"] .profile-title,
        html[data-theme="night"] .section-title,
        html[data-theme="night"] .summary-name {
            color: #f8fafc;
        }

        html[data-theme="night"] .profile-copy,
        html[data-theme="night"] .section-copy,
        html[data-theme="night"] .summary-meta,
        html[data-theme="night"] .upload-note,
        html[data-theme="night"] .form-label {
            color: #94a3b8;
        }

        html[data-theme="night"] .profile-tag {
            background: rgba(15, 23, 42, 0.72);
            border-color: rgba(148, 163, 184, 0.18);
            color: #e2e8f0;
        }

        html[data-theme="night"] .section-head {
            border-bottom-color: rgba(148, 163, 184, 0.14);
        }

        html[data-theme="night"] .modern-control {
            background: #111827;
            border-color: rgba(148, 163, 184, 0.18);
            color: #e2e8f0;
        }

        html[data-theme="night"] .modern-control:focus {
            background: #0f172a;
            border-color: #7aa2ff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.16);
        }

        html[data-theme="night"] .modern-control::placeholder {
            color: #64748b;
        }

        html[data-theme="night"] .profile-banner-spotlight {
            background: rgba(15, 23, 42, 0.78);
            border-color: rgba(148, 163, 184, 0.16);
        }

        html[data-theme="night"] .upload-control {
            background: #111827;
            border-color: rgba(148, 163, 184, 0.18);
        }

        html[data-theme="night"] .upload-trigger {
            background: #0f172a;
            border-color: rgba(148, 163, 184, 0.22);
            color: #e2e8f0;
        }

        html[data-theme="night"] .upload-trigger:hover {
            background: #1f2937;
            color: #f8fafc;
            border-color: rgba(148, 163, 184, 0.32);
        }

        html[data-theme="night"] .upload-filename {
            color: #94a3b8;
        }

        html[data-theme="night"] .image-preview-box {
            background:
                radial-gradient(circle at top, rgba(59, 130, 246, 0.16), transparent 30%),
                linear-gradient(180deg, #0b1324 0%, #111827 100%);
            border-color: rgba(148, 163, 184, 0.18);
        }

        html[data-theme="night"] .summary-avatar-fallback,
        html[data-theme="night"] .image-preview-fallback {
            background: linear-gradient(135deg, #2563eb, #10b981);
        }

        html[data-theme="night"] .alert-info {
            background: rgba(14, 165, 233, 0.12) !important;
            color: #bfdbfe !important;
            border-color: rgba(56, 189, 248, 0.18) !important;
        }

        html[data-theme="night"] .profile-save-btn {
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.3);
        }

        html[data-theme="night"] .profile-save-btn:hover {
            filter: brightness(1.05);
        }

        html[data-theme="night"] .upload-control {
            background: #111827;
            border-color: rgba(148, 163, 184, 0.18);
        }

        html[data-theme="night"] .upload-trigger {
            background: #0f172a;
            border-color: rgba(148, 163, 184, 0.22);
            color: #e2e8f0;
        }

        html[data-theme="night"] .upload-trigger:hover {
            background: #1f2937;
            color: #f8fafc;
            border-color: rgba(148, 163, 184, 0.32);
        }

        html[data-theme="night"] .upload-filename {
            color: #94a3b8;
        }

        @media (max-width: 1199.98px) {
            .profile-banner {
                grid-template-columns: 1fr;
            }

            .profile-sidecard {
                position: static;
            }
        }

        @media (max-width: 767.98px) {
            .profile-banner {
                padding: 1.1rem;
            }

            .profile-card {
                padding: 1.1rem;
            }

            .section-head {
                flex-direction: column;
            }

            .summary-body {
                align-items: flex-start;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profileSuccessAlert = document.getElementById('profileSuccessAlert');

            if (profileSuccessAlert) {
                setTimeout(function () {
                    profileSuccessAlert.classList.remove('show');
                    profileSuccessAlert.classList.add('opacity-0');

                    setTimeout(function () {
                        profileSuccessAlert.classList.add('d-none');
                    }, 150);
                }, 3000);
            }

            document.querySelectorAll('.toggle-password').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const target = document.querySelector(this.getAttribute('data-target'));
                    if (!target) return;

                    if (target.type === 'password') {
                        target.type = 'text';
                        this.textContent = 'Hide';
                    } else {
                        target.type = 'password';
                        this.textContent = 'Show';
                    }
                });
            });

            document.querySelectorAll('[data-upload-trigger]').forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    const input = document.querySelector(this.getAttribute('data-upload-trigger'));
                    if (input) {
                        input.click();
                    }
                });
            });

            const profileImageInput = document.getElementById('profile_image');
            const uploadFilename = document.querySelector('[data-upload-filename]');

            if (profileImageInput && uploadFilename) {
                profileImageInput.addEventListener('change', function () {
                    uploadFilename.textContent = this.files && this.files.length
                        ? this.files[0].name
                        : 'No file chosen';
                });
            }
        });
    </script>
@endsection
