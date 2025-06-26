@extends('layouts/contentNavbarLayout')

@section('title', 'Task History')

@section('content')
<div class="row">
  <div class="col-12">
    <h5 class="mb-4">Task History</h5>

    @forelse ($tasks as $task)
    @php
      $report = $task->report;
      $resident = $report->resident;
      $status = $task->status;
      $badgeClass = match ($status) {
          'Pending' => 'warning',
          'Assigned' => 'info',
          'In Progress' => 'primary',
          'Completed' => 'success',
          default => 'secondary',
      };
    @endphp

    <div class="card mb-3 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-start flex-column flex-md-row w-100">
          <div class="me-3 mb-2 mb-md-0">
            <i class="bx bx-wrench bx-sm text-primary"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-1">Task #{{ $task->id }} - <span class="text-muted small">{{ $report->category }}</span></h6>
            <p class="mb-1">
              <strong>Resident:</strong> {{ $resident->name }} (Room {{ $resident->room_number }})<br>
              <strong>Description:</strong> {{ Str::limit($report->description, 100) }}<br>
              <strong>Assigned At:</strong> {{ $task->assigned_at ? \Carbon\Carbon::parse($task->assigned_at)->format('d M Y H:i') : '-' }}
            </p>
            <span class="badge bg-label-{{ $badgeClass }}">{{ $status }}</span>
          </div>
          <div class="text-end mt-2 mt-md-0">
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#taskDetailModal{{ $task->id }}">
              View
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="taskDetailModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
          <div class="modal-header">
            <h5 class="modal-title">Task Detail - #{{ $task->id }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p><strong>Resident:</strong> {{ $resident->name }}</p>
            <p><strong>Room:</strong> {{ $resident->room_number }}</p>
            <p><strong>Category:</strong> {{ $report->category }}</p>
            <p><strong>Description:</strong> {{ $report->description }}</p>
            <p><strong>Status:</strong> {{ $task->status }}</p>
            <p><strong>Assigned At:</strong> {{ $task->assigned_at }}</p>
            <p><strong>Last Updated:</strong> {{ $task->updated_at }}</p>

            {{-- Proof photo (if any) --}}
            @if ($task->proof_photo)
              <div class="mt-3">
                <p><strong>Proof Photo:</strong></p>
                <img src="{{ asset('storage/' . $task->proof_photo) }}" alt="Proof Photo" class="img-fluid rounded border" style="max-height: 300px;">
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    @empty
    <div class="alert alert-info">
      No task history available.
    </div>
    @endforelse

    <!-- Pagination (if needed) -->
    <div class="mt-3">
    {{ $tasks->withQueryString()->links() }}
    </div>
  </div>
</div>
@endsection
