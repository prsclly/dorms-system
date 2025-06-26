@extends('layouts/contentNavbarLayout')

@section('title', 'All Reports')

@section('content')
<!-- All Reports Table -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">All Reports</h5>
    <form method="GET" action="{{ route('tables-basic') }}" class="d-flex gap-2">
      <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
      
      <select name="category" class="form-select form-select-sm">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
        @endforeach
      </select>

      <select name="status" class="form-select form-select-sm">
        <option value="">All Statuses</option>
        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
      </select>

      <a href="{{ url()->current() }}" class="text-danger d-flex align-items-center" style="font-size: 0.875rem;">
        <i class="bx bx-reset me-1"></i> Reset
      </a>

      <button type="submit" class="btn btn-sm btn-primary">Filter</button>
    </form>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table text-center">
      <thead>
        <tr>
          <th>#</th>
          <th>Report ID</th>
          <th>Resident Name</th>
          <th>Room Number</th>
          <th>Category</th>
          <th>Submitted At</th>
          <th>Status</th>
          <th>Updated At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($reports as $index => $report)
        @php $task = $report->task; @endphp
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $report->id }}</td>
          <td>{{ $report->resident->name }}</td>
          <td>{{ $report->resident->room_number }}</td>
          <td>{{ $report->category }}</td>
          <td>{{ $report->submitted_at->format('Y-m-d H:i') }}</td>
          <td>
            @php
              $status = $task->status ?? 'Pending';
              $badgeClass = match ($status) {
                'Pending' => 'warning',
                'In Progress' => 'primary',
                'Completed' => 'success',
                'Rejected' => 'danger',
                default => 'secondary',
              };
            @endphp
            <span class="badge bg-label-{{ $badgeClass }}">{{ $status }}</span>
          </td>
          <td>
            @if ($task)
              {{ $task->updated_at->format('Y-m-d H:i') }}
            @else
              -
            @endif
          </td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu">
                @if (!$task)
                  <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assignModal{{ $report->id }}">
                    <i class="bx bx-user-plus me-1"></i> Assign
                  </a>
                @else
                  <span class="dropdown-item disabled">Assigned</span>
                @endif
              </div>
            </div>
          </td>
        </tr>

        <!-- Modal Assign -->
        <div class="modal fade" id="assignModal{{ $report->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $report->id }}" aria-hidden="true">
          <div class="modal-dialog"> <!-- Kembali ke modal ukuran normal -->
            <form method="POST" action="{{ route('assign.technician', ['report' => $report->id]) }}">
              @csrf
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="assignModalLabel{{ $report->id }}">Assign Technician</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                  <div class="mb-2">
                    <strong>Category:</strong> {{ $report->category }}
                  </div>

                  <div class="mb-2">
                    <strong>Description:</strong>
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $report->description }}</p>
                  </div>

                  <div class="mb-3">
                    <strong>Photo:</strong><br>
                    @if ($report->photo)
                      <img src="{{ asset('storage/' . $report->photo) }}" alt="Report Photo" class="img-fluid rounded border" style="max-height: 200px;">
                    @else
                      <p>-</p>
                    @endif
                  </div>

                  <div class="mb-3">
                    <label for="technician_id" class="form-label">Select Technician</label>
                    <select class="form-select" name="technician_id" required>
                      @foreach ($technicians->where('specialization.name', $report->category) as $tech)
                        <option value="{{ $tech->id }}">{{ $tech->name }} ({{ $tech->specialization->name }})</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Assign</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        @empty
        <tr>
          <td colspan="9" class="text-center">No reports found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer text-muted text-center">
    Showing {{ $reports->count() }} of {{ $reports->count() }} entries
  </div>
</div>
<!--/ All Reports Table -->
@endsection
