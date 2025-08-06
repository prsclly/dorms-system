@extends('layouts/contentTechniciansLayout')

@section('title', 'Technician Dashboard')

@section('vendor-style')
  @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
  @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
  @vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
<div class="row mb-4">
  <div class="col-xxl-8">
    <div class="card h-100">
      <div class="d-flex align-items-start row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary mb-3">Welcome {{ $user->name ?? 'Technician' }}! 🛠️</h5>
            <p class="mb-6">You are logged in as <strong>{{ ucfirst($role) }}</strong>.</p>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left pe-0">
          <div class="card-body pb-0 px-0 px-md-4">
            <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="175" class="scaleX-n1-rtl" alt="View Badge User">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
  {{-- Total Task --}}
  <div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between mb-4">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="bx bx-briefcase bx-sm"></i>
            </span>
          </div>
        </div>
        <p class="mb-1">Total Tasks</p>
        <h4 class="card-title mb-3">{{ $totalTask }}</h4>
      </div>
    </div>
  </div>

  {{-- Assigned Task --}}
  <div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between mb-4">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="bx bx-task bx-sm"></i>
            </span>
          </div>
        </div>
        <p class="mb-1">Assigned Tasks</p>
        <h4 class="card-title mb-3">{{ $assignedTask }}</h4>
        <small class="text-success fw-semibold">+{{ $assignedToday }} today</small>
      </div>
    </div>
  </div>

  {{-- In Progress Task --}}
  <div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between mb-4">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-info">
              <i class="bx bx-loader-circle bx-sm"></i>
            </span>
          </div>
        </div>
        <p class="mb-1">In Progress</p>
        <h4 class="card-title mb-3">{{ $inProgressTask }}</h4>
      </div>
    </div>
  </div>
</div>

  <div class="row mb-4">
  {{-- Latest Feedback --}}
  <div class="col-md-6 col-12">
    <div class="card h-100">
      <div class="card-body d-flex flex-column justify-content-between h-100">
        <div>
          <h6 class="card-title mb-3">Latest Feedback</h6>
          <ul class="list-unstyled mb-0">
            @forelse ($latestFeedbacks as $feedback)
              <li class="mb-2">
                {{ Str::limit($feedback->comment, 100) }}
                <small class="text-muted d-block">{{ \Carbon\Carbon::parse($feedback->submitted_at)->format('d M Y H:i') }}</small>
              </li>
            @empty
              <li class="text-muted">No feedback submitted yet.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>

  {{-- Latest Tasks --}}
  <div class="col-md-6 d-flex">
    <div class="card flex-fill h-100 d-flex flex-column">
      <div class="card-header pb-0">
        <h6 class="card-title mb-3">Latest Tasks</h6>
      </div>
      <div class="card-body flex-grow-1">
        <table class="table table-sm mb-0">
          <thead>
            <tr>
              <th>Date</th>
              <th>Category</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($latestTasks as $task)
              @php
                $status = $task->status ?? 'Pending';
                $badgeClass = match ($status) {
                  'Pending' => 'warning',
                  'Assigned' => 'primary',
                  'In Progress' => 'info text-white',
                  'Completed' => 'success',
                  default => 'secondary',
                };
              @endphp
              <tr>
                <td>{{ \Carbon\Carbon::parse($task->created_at)->format('d M Y') }}</td>
                <td>{{ $task->report->category ?? '-' }}</td>
                <td><span class="badge bg-{{ $badgeClass }}">{{ $status }}</span></td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted">No tasks found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer text-start pt-0">
        <a href="{{ url('technician/task-history') }}" class="btn btn-sm btn-outline-primary">View Details</a>
      </div>
    </div>
  </div>
</div>

@endsection
