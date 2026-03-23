@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="container-fluid py-4 px-md-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">Users</h3>
                <p class="text-muted mb-0">Manage Azure-synced users, roles, and permission checkboxes for document actions.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('users.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-secondary small">SEARCH USER</label>
                            <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Search by name, email, or role...">
                        </div>
                        <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                            <button type="submit" class="btn btn-primary px-4">Search</button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Permissions</th>
                            <th>Provider</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->isAdmin() ? 'text-bg-primary' : 'text-bg-secondary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge text-bg-success">All Permissions</span>
                                    @else
                                        @forelse($user->permissions ?? [] as $permission)
                                            <span class="badge text-bg-light border text-dark mb-1">{{ $permissionOptions[$permission] ?? $permission }}</span>
                                        @empty
                                            <span class="text-muted small">No document permissions</span>
                                        @endforelse
                                    @endif
                                </td>
                                <td>{{ ucfirst($user->auth_provider ?? 'local') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
