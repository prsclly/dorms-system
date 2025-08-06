@extends('layouts/contentNavbarLayout')

@section('title', 'Student Point Table')

@section('content')

<!-- Card with softer corner -->
<div class="card shadow-sm" style="border-radius: 1rem;">
    <h5 class="card-header" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">Student Point Table</h5>
    <div class="card-body">

        <!-- Search Form -->
        <form method="GET" action="{{ route('student_point') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by Name or Student ID"
                    value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead style="background-color: #f2f2f2; font-weight: bold;">
                    <tr>
                        <th>No</th>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Total Point</th>
                        <th>Last Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->nim }}</td>
                            <td>{{ $student->total_point }} Points</td>
                            <td>{{ \Carbon\Carbon::parse($student->updated_at)->locale('id')->translatedFormat('d F Y') }}</td>
                            <td>
                                <a href="{{ route('edit_student_point', ['id' => $student->id]) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No student data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/ Bordered Table -->

@endsection
