@extends('layouts/contentResidentLayout')

@section('title', 'Resident Dashboard')

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
            <h5 class="card-title text-primary mb-3">Welcome {{ $user->name ?? 'Resident' }}! 🏠</h5>
            <p class="mb-6">You are logged in as <strong>{{ ucfirst($role) }}</strong>.</p>
            <a href="{{ route('resident.profile') }}" class="btn btn-sm btn-outline-primary">Manage Account</a>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-6">
            <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="175" class="scaleX-n1-rtl" alt="View Badge User">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Row 1: Issue Status & Latest Reports --}}
<div class="row gx-4 gy-4 mb-4">
  <div class="col-md-6 d-flex">
    <div class="card flex-fill h-100 d-flex flex-column">
      <div class="card-header pb-0">
        <h5 class="card-title mb-3">Issue Report Status</h5>
      </div>
      <div class="card-body flex-grow-1">
        <table class="table table-sm">
          <tbody>
            <tr><td><span class="badge bg-warning">Pending</span></td><td class="text-end">{{ $reportStatusCount['Pending'] ?? 0 }}</td></tr>
            <tr><td><span class="badge bg-info text-white">In Progress</span></td><td class="text-end">{{ $reportStatusCount['In Progress'] ?? 0 }}</td></tr>
            <tr><td><span class="badge bg-success">Completed</span></td><td class="text-end">{{ $reportStatusCount['Completed'] ?? 0 }}</td></tr>
            <tr><td><span class="badge bg-danger">Rejected</span></td><td class="text-end">{{ $reportStatusCount['Rejected'] ?? 0 }}</td></tr>
          </tbody>
        </table>
      </div>
      <div class="card-footer text-start pt-0">
        <a href="{{ url('resident/report-history') }}" class="btn btn-sm btn-outline-primary">View All Reports</a>
      </div>
    </div>
  </div>

  <div class="col-md-6 d-flex">
    <div class="card flex-fill h-100 d-flex flex-column">
      <div class="card-header pb-0">
        <h5 class="card-title mb-3">Latest Reports</h5>
      </div>
      <div class="card-body flex-grow-1">
        <table class="table table-sm">
          <thead>
            <tr><th>Date</th><th>Category</th><th>Status</th></tr>
          </thead>
          <tbody>
            @forelse ($latestReports as $report)
              @php
                $status = $report->task->status ?? 'Pending';
                $badgeClass = match ($status) {
                  'Pending' => 'warning',
                  'In Progress' => 'info text-white',
                  'Completed' => 'success',
                  'Rejected' => 'danger',
                  default => 'secondary',
                };
              @endphp
              <tr>
                <td>{{ \Carbon\Carbon::parse($report->submitted_at)->format('d F Y') }}</td>
                <td>{{ $report->category }}</td>
                <td><span class="badge bg-{{ $badgeClass }}">{{ $status }}</span></td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center">No reports found</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer text-start pt-0">
        <a href="{{ url('resident/report') }}" class="btn btn-sm btn-outline-primary">View Details</a>
      </div>
    </div>
  </div>
</div>

{{-- Row 2: Today's Menu & Latest Feedback --}}
<div class="row gx-4 gy-4 mb-4">
  <div class="col-md-6 d-flex">
    <div class="card flex-fill h-100 d-flex flex-column">
      <div class="card-header pb-0">
        <h5 class="card-title mb-3">Today's Menu</h5>
      </div>
      <div class="card-body flex-grow-1">
        <table class="table table-sm">
          <thead><tr><th>Meal Time</th><th>Menu</th></tr></thead>
          <tbody>
            @php $mealOrder = ['Breakfast', 'Lunch', 'Dinner']; @endphp
            @foreach ($mealOrder as $mealType)
              <tr>
                <td>{{ $mealType }}</td>
                <td>
                  {{ isset($todayMeals[$mealType]) && $todayMeals[$mealType]->first()
                    ? $todayMeals[$mealType]->first()->menu_description
                    : 'Not set' }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="card-footer text-start pt-0">
        <a href="{{ url('resident/daily_menu') }}" class="btn btn-sm btn-outline-primary">View Menu</a>
      </div>
    </div>
  </div>

  <div class="col-md-6 d-flex">
    <div class="card flex-fill h-100 d-flex flex-column">
      <div class="card-header pb-0">
        <h5 class="card-title mb-3">Latest Feedback</h5>
      </div>
      <div class="card-body flex-grow-1">
        <table class="table table-sm">
          <thead><tr><th>Date</th><th>Meal Time</th><th>Category</th></tr></thead>
          <tbody>
            @forelse($latestFeedback as $feedback)
              <tr>
                <td>{{ \Carbon\Carbon::parse($feedback->date)->format('d F Y') }}</td>
                <td>{{ $feedback->meal->meal_type ?? '-' }}</td>
                <td>
                  <span class="badge
                    @switch($feedback->category)
                      @case('Taste') bg-label-primary @break
                      @case('Hygiene') bg-label-success @break
                      @case('Food Quality') bg-label-info @break
                      @default bg-label-secondary
                    @endswitch
                  ">
                    {{ $feedback->category }}
                  </span>
                </td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-muted text-center">No feedback available</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer text-start pt-0">
        <a href="{{ url('resident/feedback_menu') }}" class="btn btn-sm btn-outline-primary">View All Feedback</a>
      </div>
    </div>
  </div>
</div>

{{-- Row 3: Points & History --}}
<div class="row gx-4 gy-4 mb-4">
  <div class="col-md-6 d-flex">
    <div class="card flex-fill h-100 d-flex flex-column text-center">
      <div class="card-header pb-0">
        <h5 class="card-title mb-3">My Points</h5>
      </div>
      <div class="card-body flex-grow-1 d-flex flex-column justify-content-center">
        <p class="text-muted mb-1">Current Total Points</p>
        <h1 class="text-primary fw-bold mb-2" style="font-size: 3rem;">{{ $latestPoint }}</h1>
        <p class="text-muted small">Points reflect your behavior and participation.</p>
      </div>
      <div class="card-footer text-start pt-0">
        <a href="{{ url('resident/StudentPoint') }}" class="btn btn-sm btn-outline-primary">View Point Details</a>
      </div>
    </div>
  </div>

  <div class="col-md-6 d-flex">
    <div class="card flex-fill h-100 d-flex flex-column">
      <div class="card-header pb-0">
        <h5 class="card-title mb-3">Latest Point History</h5>
      </div>
      <div class="card-body flex-grow-1">
        <table class="table table-sm">
          <thead><tr><th>Date</th><th>Category</th><th class="text-end">Point</th></tr></thead>
          <tbody>
            @forelse ($latestPointLogs as $log)
              <tr>
                <td>{{ \Carbon\Carbon::parse($log->date)->format('d F Y') }}</td>
                <td>{{ $log->category }}</td>
                <td class="text-end">
                  <span class="{{ $log->point_change >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $log->point_change > 0 ? '+' : '' }}{{ $log->point_change }}
                  </span>
                </td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted">No point history found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer text-start pt-0">
        <a href="{{ url('resident/StudentPoint') }}" class="btn btn-sm btn-outline-primary">View History</a>
      </div>
    </div>
  </div>
</div>
@endsection
