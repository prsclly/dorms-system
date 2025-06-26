@extends('layouts/contentNavbarLayout')

@section('title', 'My Tasks')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">My Tasks</h5>
    <form method="GET" action="{{ route('technician.tasks') }}" class="d-flex gap-2">
      <select name="status" class="form-select form-select-sm">
        <option value="">All Statuses</option>
        @foreach($statuses as $status)
          <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-sm btn-primary">Filter</button>
    </form>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
      <tr>
        <th>#</th>
        <th>Task ID</th>
        <th>Resident Name</th>
        <th>Room Number</th>
        <th>Category</th>
        <th>Assigned At</th>
        <th>Status</th>
        <th>Updated At</th>
        <th>Action</th>
      </tr>
    </thead>
      <tbody>
      @forelse ($tasks as $index => $task)
      @php $report = $task->report; @endphp
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $task->id }}</td>
        <td>{{ $report->resident->name }}</td>
        <td>{{ $report->resident->room_number }}</td>
        <td>{{ $report->category }}</td>
        <td>
          {{ $task->assigned_at ? \Carbon\Carbon::parse($task->assigned_at)->format('Y-m-d H:i') : '-' }}
        </td>
        <td>
          @php
            $status = $task->status;
            $badgeClass = match ($status) {
                'Pending' => 'warning',
                'Assigned' => 'info',
                'In Progress' => 'primary',
                'Completed' => 'success',
                default => 'secondary',
            };
          @endphp
          <span class="badge bg-label-{{ $badgeClass }}">{{ $status }}</span>
        </td>
        <td>{{ $task->updated_at->format('Y-m-d H:i') }}</td>
        <td>
          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewTaskModal{{ $task->id }}">
            View
          </button>
        </td>
      </tr>

      <!-- View Modal -->
    <div class="modal fade" id="viewTaskModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content p-3">
          <div class="modal-header">
            <h5 class="modal-title">Task Detail - #{{ $task->id }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p><strong>Resident:</strong> {{ $report->resident->name }}</p>
            <p><strong>Room:</strong> {{ $report->resident->room_number }}</p>
            <p><strong>Category:</strong> {{ $report->category }}</p>
            <div class="mb-3">
              <strong>Description:</strong>
              <div style="white-space: pre-wrap; word-wrap: break-word;">{{ $report->description }}</div>
            </div>
            <p><strong>Status:</strong> {{ $task->status }}</p>

            {{-- Photo --}}
            <div class="mb-3 mt-3">
              <p><strong>Photo:</strong></p>
              @if ($report->photo)
                <img src="{{ asset('storage/' . $report->photo) }}" alt="Report Photo" class="img-fluid rounded border" style="max-height: 300px;">
              @else
                <p class="text-muted">No photo provided.</p>
              @endif
            </div>

            {{-- Form Update --}}
            @if (in_array($task->status, ['Assigned', 'In Progress']))
            <form method="POST" action="{{ route('technician.tasks.updateStatus', $task->id) }}">
              @csrf
              @method('PATCH')
              <div class="mb-3">
                <label class="form-label">Change Status</label>
                <select name="status" class="form-select" required>
                  @if($task->status == 'Assigned')
                    <option value="In Progress">In Progress</option>
                  @endif
                  @if($task->status != 'Completed')
                    <option value="Completed">Completed</option>
                  @endif
                </select>
              </div>
              <button type="submit" class="btn btn-success">Update</button>
            </form>
            @endif

          </div>
        </div>
      </div>
    </div>
      @endforeach
      </tbody>

    </table>
  </div>
  <div class="card-footer text-muted">
    Showing {{ $tasks->count() }} entries
  </div>
</div>
@endsection
