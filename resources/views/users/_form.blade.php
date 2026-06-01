<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="role" id="role" class="form-select" required>
            @foreach(\App\Models\User::roles() as $role)
                <option value="{{ $role }}" {{ old('role', $user->role ?? \App\Models\User::ROLE_USER) === $role ? 'selected' : '' }}>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Auth Provider</label>
        <input type="text" class="form-control" value="{{ ucfirst($user->auth_provider ?? 'local') }}" readonly>
    </div>

    <div class="col-md-6">
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-control">
        <small class="text-muted">Leave blank to keep the current password.</small>
    </div>

    <div class="col-md-6">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
</div>

<div class="permission-panel mt-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h5 class="mb-1">Document Permissions</h5>
            <p class="text-muted small mb-0">Choose which actions this user is allowed to do.</p>
        </div>
        <span class="badge rounded-pill text-bg-light border px-3 py-2">Checkbox Access Control</span>
    </div>

    <div id="permissionsPanel" class="row g-3">
        @foreach($permissionOptions as $permission => $label)
            <div class="col-md-6">
                <label class="permission-card">
                    <input
                        class="form-check-input permission-checkbox"
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $permission }}"
                        {{ in_array($permission, old('permissions', $selectedPermissions ?? []), true) ? 'checked' : '' }}
                    >
                    <span>
                        <span class="permission-title">{{ $label }}</span>
                        <span class="permission-key">{{ $permission }}</span>
                    </span>
                </label>
            </div>
        @endforeach
    </div>

    <div id="permissionsEmptyState" class="mt-3 alert alert-light border mb-0 d-none">
        <strong>User role</strong> does not receive document permissions.
    </div>
</div>

<div class="d-flex gap-2 mt-4 pt-3 border-top">
    <button type="submit" class="btn btn-primary px-4">{{ $submitLabel }}</button>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
</div>

<style>
    .permission-panel {
        padding: 20px;
        border-radius: 20px;
        border: 1px solid #dce6f0;
        background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
    }

    .permission-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        height: 100%;
        padding: 16px;
        border: 1px solid #d9e5f0;
        border-radius: 16px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.18s ease;
    }

    .permission-card:hover {
        border-color: #b7cdf4;
        box-shadow: 0 10px 20px rgba(15, 34, 58, 0.05);
    }

    .permission-title {
        display: block;
        color: #10243d;
        font-weight: 700;
    }

    .permission-key {
        display: block;
        color: #6b7f95;
        font-size: 0.78rem;
        margin-top: 3px;
    }

    html[data-theme="night"] .permission-panel {
        background: #0f172a;
        border-color: rgba(148, 163, 184, 0.16);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
    }

    html[data-theme="night"] .permission-card {
        background: #111827;
        border-color: rgba(148, 163, 184, 0.18);
    }

    html[data-theme="night"] .permission-card:hover {
        border-color: rgba(96, 165, 250, 0.28);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.28);
    }

    html[data-theme="night"] .permission-title {
        color: #f8fafc;
    }

    html[data-theme="night"] .permission-key {
        color: #94a3b8;
    }

    html[data-theme="night"] .permission-panel h5 {
        color: #f8fafc;
    }

    html[data-theme="night"] .permission-panel .text-muted {
        color: #f8fafc !important;
    }

    html[data-theme="night"] .user-edit-page small.text-muted {
        color: #f8fafc !important;
    }

    html[data-theme="night"] .permission-panel .badge.text-bg-light {
        background: rgba(15, 23, 42, 0.9) !important;
        color: #cbd5e1 !important;
        border-color: rgba(148, 163, 184, 0.18) !important;
    }

    html[data-theme="night"] .permission-panel .alert-light {
        background: rgba(15, 23, 42, 0.95) !important;
        border-color: rgba(148, 163, 184, 0.16) !important;
        color: #e2e8f0 !important;
    }

    html[data-theme="night"] .permission-panel .form-check-input {
        background-color: #0b1220;
        border-color: rgba(148, 163, 184, 0.35);
    }

    html[data-theme="night"] .permission-panel .form-check-input:checked {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    html[data-theme="night"] .permission-panel ~ .d-flex.gap-2.mt-4.pt-3.border-top {
        border-top-color: rgba(148, 163, 184, 0.18) !important;
    }

    html[data-theme="night"] .permission-panel ~ .d-flex.gap-2.mt-4.pt-3.border-top .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
    }

    html[data-theme="night"] .permission-panel ~ .d-flex.gap-2.mt-4.pt-3.border-top .btn-outline-secondary {
        background: #111827;
        color: #cbd5e1;
        border-color: rgba(148, 163, 184, 0.18);
    }

    html[data-theme="night"] .permission-panel ~ .d-flex.gap-2.mt-4.pt-3.border-top .btn-outline-secondary:hover {
        background: #1f2937;
        color: #f8fafc;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('role');
        const permissionsPanel = document.getElementById('permissionsPanel');
        const permissionsEmptyState = document.getElementById('permissionsEmptyState');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

        function syncPermissionsState() {
            const isAdmin = roleSelect.value === 'admin';
            const isUser = roleSelect.value === 'user';

            permissionCheckboxes.forEach((checkbox) => {
                checkbox.disabled = isAdmin || isUser;

                if (isAdmin) {
                    checkbox.checked = true;
                } else if (isUser) {
                    checkbox.checked = false;
                }
            });

            permissionsPanel.classList.toggle('d-none', isUser);
            permissionsPanel.style.opacity = isAdmin ? '0.65' : '1';
            if (permissionsEmptyState) {
                permissionsEmptyState.classList.toggle('d-none', !isUser);
            }
        }

        syncPermissionsState();
        roleSelect.addEventListener('change', syncPermissionsState);
    });
</script>
