@extends('layouts/contentNavbarLayout')

@section('title', 'Leave Request Detail')

@section('content')
<h4 class="fw-bold">Leave Request Detail</h4>

@if (session('success'))
  <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card mt-3 rounded-3 shadow-sm">
  <div class="card-body">
    <div class="mb-3 text-muted"><strong>Name:</strong> <span class="text-dark">{{ $permission->student->name }}</span></div>
    <div class="mb-3 text-muted"><strong>NIM:</strong> <span class="text-dark">{{ $permission->student->nim }}</span></div>
    <div class="mb-3 text-muted"><strong>Leave Type:</strong>
      <span class="text-dark">
        @if ($permission->type === 'pesiar')
          Day Leave (Pesiar)
        @elseif ($permission->type === 'ib')
          Overnight Leave (Izin Bermalam)
        @else
          {{ ucfirst($permission->type) }}
        @endif
      </span>
    </div>
    <div class="mb-3 text-muted"><strong>Departure Date:</strong> <span class="text-dark">{{ \Carbon\Carbon::parse($permission->start_date)->translatedFormat('d F Y') }}</span></div>
    <div class="mb-3 text-muted"><strong>Return Date:</strong> <span class="text-dark">{{ \Carbon\Carbon::parse($permission->end_date)->translatedFormat('d F Y') }}</span></div>
    <div class="mb-3 text-muted"><strong>Reason:</strong> <span class="text-dark">{{ $permission->reason }}</span></div>

    <div class="mb-3 text-muted">
      <strong>Status:</strong>
      <span class="badge bg-{{ $permission->status === 'approved' ? 'success' : ($permission->status === 'rejected' ? 'danger' : ($permission->status === 'on_process' ? 'primary' : 'warning text-dark')) }}">
        {{ ucfirst(str_replace('_', ' ', $permission->status)) }}
      </span>
    </div>

    @if ($permission->status === 'rejected' && $permission->rejection_reason)
      <div class="mb-3 text-muted"><strong>Rejection Reason:</strong> <span class="text-dark">{{ $permission->rejection_reason }}</span></div>
    @endif

    {{-- Tombol hanya muncul saat pending --}}
    @if ($permission->status === 'pending')
      <form action="{{ route('admin.permissions.process', $permission->id) }}" method="POST" class="d-inline-block">
        @csrf
        <button type="submit" class="btn btn-warning text-dark">Mark as On Process</button>
      </form>
    @endif

    {{-- Tombol approve/reject hanya muncul saat status on_process --}}
    @if ($permission->status === 'on_process')
      <form action="{{ route('admin.permissions.approve', $permission->id) }}" method="POST" class="d-inline-block me-2">
        @csrf
        <button type="submit" class="btn btn-success">Approve</button>
      </form>

      <!-- Reject Modal Trigger -->
      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
        Reject
      </button>

      <!-- Modal -->
      <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content rounded-3">
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
