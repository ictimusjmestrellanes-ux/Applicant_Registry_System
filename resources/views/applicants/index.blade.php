@extends('layouts.app')

@section('content')

    <div class="container-fluid">


        <!-- PAGE HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="page-title">Applicants</h3>
                <small class="text-muted">
                    Manage registered applicants
                </small>
            </div>


            <a href="{{ route('applicants.create') }}" class="btn btn-save">

                <i class="bi bi-person-plus"></i>
                Add Applicant

            </a>

        </div>



        <!-- SEARCH BAR -->
        <div class="card search-card mb-3">

            <div class="card-body">

                <form method="GET">

                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" name="search" class="form-control form-input"
                            placeholder="Search applicant name or contact number..." value="{{ $search }}">


                        <button class="btn btn-primary">

                            Search

                        </button>

                    </div>

                </form>

            </div>

        </div>



        <!-- SUCCESS MESSAGE -->

        @if(session('success'))

            <div class="alert alert-success shadow-sm">

                {{ session('success') }}

            </div>

        @endif



        <!-- TABLE CARD -->
        <div class="card table-card">

            <div class="card-body p-0">


                <table class="table table-modern mb-0">

                    <thead>
                        <tr>
                            <th width="70">ID</th>
                            <th>Applicant Name</th>
                            <th width="150">Contact</th>
                            <th>Address</th>

                            <th width="180">Actions</th>
                        </tr>
                    </thead>



                    <tbody>

                        @forelse($applicants as $applicant)

                            <tr>

                                <td class="text-muted">
                                    #{{ $applicant->id }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $applicant->first_name }}
                                        {{ $applicant->middle_name }}
                                        {{ $applicant->last_name }}
                                        {{ $applicant->suffix }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $applicant->contact_no }}
                                </td>

                                <td class="text-muted">
                                    {{ $applicant->address_line }},
                                    {{ $applicant->barangay }},
                                    {{ $applicant->city }},
                                    {{ $applicant->province }}
                                </td>

                                <td>
                                    <a href="{{ route('applicants.edit', $applicant->id) }}" class="btn btn-edit btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('applicants.destroy', $applicant->id) }}" method="POST"
                                        style="display:inline-block">

                                        @csrf
                                        @method('DELETE')

                                        <button onclick="return confirm('Archive applicant?')" class="btn btn-delete btn-sm">
                                            <i class="bi bi-archive"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center p-4 text-muted">
                                    No applicants found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>


            </div>

        </div>



        <!-- PAGINATION -->

        <div class="mt-3 d-flex justify-content-end">

            {{ $applicants->links() }}

        </div>


    </div>



@endsection