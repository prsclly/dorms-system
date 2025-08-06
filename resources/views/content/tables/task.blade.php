@extends('layouts/contentTechniciansLayout')

@section('title', 'My Tasks')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">My Tasks</h5>
    <form method="GET" action="{{ route('technician.tasks') }}" class="d-flex gap-2">
      <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
      <select name="status" class="form-select form-select-sm">
        <option value="">All Statuses</option>
        @foreach($statuses as $status)
          <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
        @endforeach
      </select>

      <a href="{{ url()->current() }}" class="text-danger d-flex align-items-center" style="font-size: 0.875rem;">
        <i class="bx bx-reset me-1"></i> Reset
      </a>

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
          <td>{{ $tasks->firstItem() + $index }}</td>
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
                @if ($task->status == 'Assigned')
                <form method="POST" action="{{ route('technician.tasks.updateStatus', $task->id) }}">
                  @csrf
                  @method('PATCH')
                  <div class="mb-3">
                    <label class="form-label">Change Status</label>
                    <select name="status" class="form-select" required>
                      <option value="In Progress">In Progress</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-success">Update</button>
                </form>
                @elseif ($task->status == 'In Progress')
                <form method="POST" action="{{ route('technician.tasks.updateStatus', $task->id) }}">
                  @csrf
                  @method('PATCH')
                  <div class="mb-3">
                    <label class="form-label">Change Status</label>
                    <select name="status" class="form-select" required>
                      <option value="Completed">Completed</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-success">Update</button>
                </form>
                @endif
              </div>
            </div>
          </div>
        </div>
        @empty
        <tr>
          <td colspan="9" class="text-center">No tasks found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card-footer d-flex justify-content-between align-items-center">
    <small>Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} entries</small>
    <div class="mt-2 mt-sm-0">
      {{ $tasks->withQueryString()->links() }}
    </div>
  </div>
</div>
@endsection
