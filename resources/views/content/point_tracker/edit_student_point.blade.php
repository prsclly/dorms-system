@extends('layouts/contentNavbarLayout')

@section('title', 'Student Point History')

@section('content')

    <style>
        .bg-appreciation,
        .bg-violation {
            padding: 6px 16px;
            border-radius: 8px;
            display: inline-block;
            min-width: 120px;
            text-align: center;
            font-weight: 500;
        }

        .bg-appreciation {
            background-color: #d1e7dd !important;
            color: #0f5132;
        }

        .bg-violation {
            background-color: #f8d7da !important;
            color: #842029;
        }
    </style>


    <div class="row">
        <div class="col-xl-10 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Student Point History - {{ $student->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="student_name">Student Name</label>
                        <input type="text" class="form-control" id="student_name" value="{{ $student->name }}" readonly />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="student_nim">Student NIM</label>
                        <input type="text" class="form-control" id="student_nim" value="{{ $student->nim }}" readonly />
                    </div>

                    <h5 class="mt-4">Point History</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Change Date</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Points Changed</th>
                                    <th>Previous Point</th>
                                    <th>New Point</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($student->pointLogs as $index => $history)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($history->date)->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if ($history->category === 'Appreciation')
                                                <span class="bg-appreciation">{{ $history->category }}</span>
                                            @elseif ($history->category === 'Violation')
                                                <span class="bg-violation">{{ $history->category }}</span>
                                            @else
                                                <span>{{ $history->category ?? '-' }}</span>
                                            @endif
                                        </td>
                                        <td style="max-width: 200px; white-space: normal;">{{ $history->description }}</td>
                                        <td class="{{ $history->point_change > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $history->point_change > 0 ? '+' : '' }}{{ $history->point_change }}
                                        </td>
                                        <td>{{ $history->previous_point }}</td>
                                        <td>{{ $history->new_point }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Add New & Back dalam satu baris -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('add_log', ['id' => $student->id]) }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Add New
                        </a>

                        <a href="{{ route('student_point') }}" class="btn btn-secondary">
                            Back
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection