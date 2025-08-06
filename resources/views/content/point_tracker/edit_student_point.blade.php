@extends('layouts/contentNavbarLayout')

@section('title', 'Student Point History')

@section('content')

@php
    use Carbon\Carbon;
@endphp

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

    .dropdown-item.disabled {
        pointer-events: none;
        background-color: #f1f1f1;
        color: #999;
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student->pointLogs as $index => $history)
                                @php
                                    $expired = Carbon::parse($history->created_at)->diffInMinutes(now()) > 60;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ Carbon::parse($history->date)->format('Y-m-d H:i') }}</td>
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
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="dropdownMenu{{ $history->id }}"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-4 text-secondary"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu{{ $history->id }}">
                                                @if (!$expired)
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('edit_log', ['student_id' => $student->id, 'log_id' => $history->id]) }}">
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#confirmDelete{{ $history->id }}">
                                                            Delete
                                                        </button>
                                                    </li>
                                                @else
                                                    <li><span class="dropdown-item disabled">Edit</span></li>
                                                    <li><span class="dropdown-item disabled">Delete</span></li>
                                                @endif
                                            </ul>
                                        </div>

                                        {{-- Modal Delete Confirmation --}}
                                        <div class="modal fade" id="confirmDelete{{ $history->id }}" tabindex="-1" aria-labelledby="confirmDeleteLabel{{ $history->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah kamu yakin ingin menghapus log ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('delete_log', ['student_id' => $student->id, 'log_id' => $history->id]) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

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
