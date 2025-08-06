@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

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
<div class="row">
  {{-- Welcome Card --}}
  <div class="col-md-12 mb-3">
    <div class="card h-100">
      <div class="d-flex align-items-start flex-column flex-md-row justify-content-between">
        <div class="card-body">
          <h5 class="card-title text-primary mb-3">Welcome back {{ $user->name ?? 'User' }}! 🎉</h5>
          @if(isset($role))
            <p class="mb-4">You are logged in as <strong>{{ ucfirst($role) }}</strong>.</p>
          @else
            <p class="mb-4 text-muted">You are not logged in.</p>
          @endif
          <a href="javascript:;" class="btn btn-sm btn-outline-primary mt-5">Manage Profile</a>
        </div>
        <div class="text-center p-3">
          <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="175" class="scaleX-n1-rtl" alt="View Badge User">
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Summary Cards --}}
<div class="row mt-4">
  <div class="col-md-4 mb-3">
    <div class="card h-100">
      <div class="card-body d-flex flex-column justify-content-between h-100">
        <div>
          <h6 class="card-title">Active Residents</h6>
          <h2 class="fw-bold">{{ $activeResidentCount }}</h2>
        </div>
        <a href="{{ url('admin/manage-residents') }}" class="btn btn-sm btn-outline-primary w-auto align-self-start mt-2">Manage Residents</a>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card h-100">
      <div class="card-body d-flex flex-column justify-content-between h-100">
        <div>
          <h6 class="card-title">Pending Dorm Reports</h6>
          <h2 class="fw-bold">{{ $pendingReportsCount }}</h2>
        </div>
        <a href="{{ url('admin/reports') }}" class="btn btn-sm btn-outline-warning w-auto align-self-start mt-2">View Pending Reports</a>
      </div>
    </div>
  </div>



{{-- Dorm & Catering Issues + Shortcut --}}
<div class="row g-4">
  <div class="col-lg-6 col-12">
    <div class="card h-100">
      <div class="card-body d-flex flex-column justify-content-between h-100">
        <div>
          <h6 class="card-title mb-3">Latest Dorm Issue Reports</h6>
          <ul class="list-unstyled mb-0">
            @forelse ($latestDormReports as $report)
              <li>
                {{ $report->description }} -
                <small class="text-muted">{{ $report->submitted_at->format('H:i') }}</small>
              </li>
            @empty
              <li class="text-muted">No dorm issues reported.</li>
            @endforelse
          </ul>
        </div>
        <a href="{{ url('admin/feedback-reports') }}" class="btn btn-sm btn-outline-primary mt-auto w-auto align-self-start">View All Reports</a>
      </div>
    </div>
  </div>

  <div class="col-lg-6 col-12">
    <div class="card h-100">
      <div class="card-body d-flex flex-column justify-content-between h-100">
        <div>
          <h6 class="card-title mb-3">Latest Catering Issue Log</h6>
          <div class="table-responsive">
            <table class="table table-sm small mb-0 align-middle w-100" style="border-collapse: collapse;">
              <thead class="text-muted">
                <tr>
                  <th class="p-0 py-1 pe-2 text-start">Meal</th>
                  <th class="p-0 py-1 pe-2 text-center">Category</th>
                  <th class="p-0 py-1 pe-2 text-center" style="white-space: nowrap;">Time</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($latestCateringFeedbacks as $feedback)
                  <tr>
                    <td class="p-0 py-1 pe-2 align-middle">{{ ucfirst($feedback->meal->meal_type ?? '-') }}</td>
                    <td class="p-0 py-1 pe-2 text-center">
                      <span class="badge
                        @switch($feedback->category)
                          @case('Taste') bg-label-primary @break
                          @case('Hygiene') bg-label-success @break
                          @case('Food Quality') bg-label-info @break
                          @default bg-label-secondary
                        @endswitch
                        me-1">
                        {{ $feedback->category }}
                      </span>
                    </td>
                    <td class="p-0 py-1 pe-2 text-end text-muted" style="white-space: nowrap;">
                      {{ \Carbon\Carbon::parse($feedback->created_at)->translatedFormat('d F, H:i') }} WIB
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="p-0 py-1 text-muted">No catering issues reported.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        <a href="{{ url('admin/catering/issue_log') }}" class="btn btn-sm btn-outline-primary mt-3 w-auto align-self-start">Review Issues</a>
      </div>
    </div>
  </div>

  {{-- Latest Student Point Logs --}}
{{-- Latest Student Point Logs --}}
<div class="col-12">
  <div class="card h-100">
      <div class="card-body">
        <h6 class="card-title mb-3">Latest Student Points Log</h6>
        <table class="table table-sm small mb-0 align-middle" style="border-collapse: collapse;">
          <thead style="border-bottom: 1px solid #ccc;">
            <tr class="small">
              <th class="text-start py-1">Name</th>
              <th class="text-start py-1">Category</th>
              <th class="text-center py-1">Changed</th>
              <th class="text-end py-1">Time</th>
            </tr>
          </thead>
          <tbody>
            @forelse($latestPointLogs as $log)
              <tr style="border-bottom: 1px solid #ccc;">
                <td class="text-start py-1">{{ $log->student->name ?? 'Unknown' }}</td>
                <td class="text-start py-1">{{ $log->category }}</td>
                <td class="text-center py-1">
                  <span class="{{ $log->point_change > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $log->point_change > 0 ? '+' : '' }}{{ $log->point_change }}
                  </span>
                </td>
                <td class="text-end py-2">
                  <small class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</small>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-2">No logs found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
        <a href="{{ url('admin/point_tracker/student_point') }}" class="btn btn-sm btn-outline-primary mt-2">Manage Points</a>
      </div>
    </div>
  </div>

  {{-- Shortcut Card --}}
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title mb-4">🔗 Shortcut</h6>
        <div class="d-flex flex-row justify-content-between flex-wrap gap-3">

          <div class="text-center" style="min-width: 250px; flex: 1;">
            <p class="fw-bold mb-2">📋 Reporting Issue</p>
            <div class="d-flex flex-row flex-wrap gap-2 justify-content-center">
              <a href="{{ url('admin/reports') }}" class="btn btn-outline-secondary btn-sm">All Reports</a>
              <a href="{{ url('admin/feedback-reports') }}" class="btn btn-outline-secondary btn-sm">Feedback Reports</a>
            </div>
          </div>

          <div class="text-center" style="min-width: 250px; flex: 1;">
            <p class="fw-bold mb-2">🍽 Catering</p>
            <div class="d-flex flex-row flex-wrap gap-2 justify-content-center">
              <a href="{{ url('admin/catering/menu') }}" class="btn btn-outline-secondary btn-sm">Manage Menu</a>
              <a href="{{ url('admin/catering/dashboard') }}" class="btn btn-outline-secondary btn-sm">Catering Issue</a>
              <a href="{{ url('admin/database/pic') }}" class="btn btn-outline-secondary btn-sm">Vendor Database</a>
            </div>
          </div>

          <div class="text-center" style="min-width: 250px; flex: 1;">
            <p class="fw-bold mb-2">🎯 Point Tracker</p>
            <div class="d-flex flex-row flex-wrap gap-2 justify-content-center">
              <a href="{{ url('admin/point_tracker/student_point') }}" class="btn btn-outline-secondary btn-sm">Student Points</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection
