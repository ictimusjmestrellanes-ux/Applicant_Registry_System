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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('role');
        const permissionsPanel = document.getElementById('permissionsPanel');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

        function syncPermissionsState() {
            const isAdmin = roleSelect.value === 'admin';

            permissionCheckboxes.forEach((checkbox) => {
                checkbox.disabled = isAdmin;

                if (isAdmin) {
                    checkbox.checked = true;
                }
            });

            permissionsPanel.style.opacity = isAdmin ? '0.65' : '1';
        }

        syncPermissionsState();
        roleSelect.addEventListener('change', syncPermissionsState);
    });
</script>
