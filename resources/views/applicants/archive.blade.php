@extends('layouts.app')

@section('content')

    <div class="container">

        <h3>Archived Applicants</h3>

        <table class="table table-bordered">

            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Action</th>
            </tr>

            @foreach($applicants as $applicant)

                <tr>

                    <td>
                        {{ $applicant->first_name }}
                        {{ $applicant->last_name }}
                    </td>

                    <td>
                        {{ $applicant->contact_no }}
                    </td>

                    <td>

                        <form method="POST" action="{{ route('applicants.restore', $applicant->id) }}">
                            @csrf

                            <button class="btn btn-success btn-sm">
                                Restore
                            </button>

                        </form>

                    </td>

                </tr>

            @endforeach

        </table>

    </div>

@endsection