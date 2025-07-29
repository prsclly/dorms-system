@extends('layouts/contentNavbarLayout')

@section('title', 'Leave Request Detail')

@section('content')
<h4 class="fw-bold">Leave Request Detail</h4>

@if (session('success'))
  <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card mt-3">
  <div class="card-body">
    <p><strong>Student Name:</strong> {{ $permission->student->name }}</p>
    <p><strong>NIM:</strong> {{ $permission->student->nim }}</p>
    <p><strong>Leave Type:</strong> {{ strtoupper($permission->type) === 'PESIAR' ? 'Day Leave' : 'Overnight Leave' }}</p>
    <p><strong>Date:</strong>
      {{ \Carbon\Carbon::parse($permission->start_date)->translatedFormat('d F Y') }}
      -
      {{ \Carbon\Carbon::parse($permission->end_date)->translatedFormat('d F Y') }}
    </p>
    <p><strong>Reason:</strong> {{ $permission->reason }}</p>

    @if ($permission->attachment)
      <p><strong>Attachment:</strong> <a href="{{ asset('storage/' . $permission->attachment) }}" target="_blank">View File</a></p>
    @endif

    <p><strong>Status:</strong>
      <span class="badge bg-{{ $permission->status === 'approved' ? 'success' : ($permission->status === 'rejected' ? 'danger' : 'warning text-dark') }}">
        {{ ucfirst($permission->status) }}
      </span>
    </p>

    @if ($permission->status === 'rejected' && $permission->rejection_reason)
      <p><strong>Rejection Reason:</strong> {{ $permission->rejection_reason }}</p>
    @endif

    @if ($permission->status === 'pending')
      <form action="{{ route('admin.permissions.approve', $permission->id) }}" method="POST" class="d-inline-block me-2">
        @csrf
        <button type="submit" class="btn btn-success">Approve</button>
      </form>

      <!-- Button to trigger modal -->
      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
        Reject
      </button>

      <!-- Modal -->
      <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="{{ route('admin.permissions.reject', $permission->id) }}" method="POST">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Rejection Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <div class="mb-3">
                  <label for="rejection_reason" class="form-label">Please enter the reason for rejection:</label>
                  <input type="text" name="rejection_reason" id="rejection_reason" class="form-control" required>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Confirm Reject</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endif

    @if ($permission->status === 'approved')
      <a href="{{ route('admin.permissions.download', $permission->id) }}" class="btn btn-outline-primary mt-3">Download PDF</a>
    @endif
  </div>
</div>
@endsection
