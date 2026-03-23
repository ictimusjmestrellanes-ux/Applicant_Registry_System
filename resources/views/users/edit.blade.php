@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="container-fluid py-4 px-md-5">
        <div class="mb-4">
            <h3 class="fw-bold mb-1">Edit User</h3>
            <p class="text-muted mb-0">Update the selected Azure-synced user's role and document permissions.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    @include('users._form', [
                        'submitLabel' => 'Save Changes',
                    ])
                </form>
            </div>
        </div>
    </div>
@endsection
