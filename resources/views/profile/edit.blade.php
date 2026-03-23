@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    @php
        $profileImageUrl = $user->profileImageUrl();
    @endphp

    <div class="container-fluid py-4 px-md-4 px-xl-5 profile-page">
        
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-form-card">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-xl-8">
                        <div class="profile-section h-100">
                            <h5 class="section-title mb-3">Profile Information</h5>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Display Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Authentication Provider</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->auth_provider ?? 'local') }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role ?? 'user') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="profile-section h-100">
                            <h5 class="section-title mb-3">Profile Image</h5>

                            <div class="image-upload-panel">
                                <div class="image-preview-box">
                                    @if($profileImageUrl)
                                        <img src="{{ $profileImageUrl }}" alt="{{ $user->name }}" class="image-preview">
                                    @else
                                        <div class="image-preview-fallback">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
                                    @endif
                                </div>

                                <label class="form-label mt-3">Upload New Image</label>
                                <input type="file" name="profile_image" class="form-control" accept="image/*">
                                <div class="upload-note mt-2">Accepted image files only. Max size: 5MB.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-actions">
                    <button type="submit" class="btn btn-primary profile-save-btn">Save Profile</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .profile-page {
            max-width: 1500px;
        }

        .profile-hero {
            background:
                radial-gradient(circle at top right, rgba(16, 185, 129, 0.16), transparent 28%),
                linear-gradient(135deg, #ffffff, #eff6ff);
            border: 1px solid rgba(191, 219, 254, 0.8);
            border-radius: 26px;
            padding: 2rem;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        }

        .profile-kicker {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.78rem;
            font-weight: 800;
            margin-bottom: 0.9rem;
        }

        .profile-title {
            font-size: clamp(1.9rem, 3vw, 2.5rem);
            font-weight: 800;
            color: #0f172a;
        }

        .profile-copy,
        .profile-preview-meta,
        .upload-note {
            color: #64748b;
        }

        .profile-preview-card,
        .profile-form-card,
        .profile-section {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 22px;
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.06);
        }

        .profile-preview-card {
            padding: 1.4rem;
            height: 100%;
        }

        .profile-preview-label,
        .section-title {
            font-weight: 800;
            color: #0f172a;
        }

        .profile-preview-wrap {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .profile-preview-image,
        .profile-preview-fallback,
        .image-preview,
        .image-preview-fallback {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            object-fit: cover;
        }

        .profile-preview-fallback,
        .image-preview-fallback {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            font-size: 1.4rem;
            font-weight: 800;
        }

        .profile-preview-name {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
        }

        .profile-form-card {
            padding: 1.5rem;
        }

        .profile-section {
            padding: 1.4rem;
            height: 100%;
        }

        .form-control {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid #d9e4ef;
            background: #f8fbff;
        }

        .form-control:focus {
            border-color: #7aa2ff;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .image-upload-panel {
            display: flex;
            flex-direction: column;
        }

        .image-preview-box {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 180px;
            border-radius: 20px;
            background: linear-gradient(180deg, #f8fbff, #eef6ff);
            border: 1px dashed #cbd5e1;
        }

        .profile-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .profile-save-btn {
            border-radius: 14px;
            padding: 0.85rem 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.22);
        }

        @media (max-width: 991.98px) {
            .profile-hero,
            .profile-form-card,
            .profile-section {
                padding: 1.25rem;
            }
        }

        @media (max-width: 767.98px) {
            .profile-actions {
                justify-content: stretch;
            }

            .profile-save-btn {
                width: 100%;
            }
        }
    </style>
@endsection
