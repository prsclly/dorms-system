
@extends('layouts/contentNavbarLayout')

@section('title', 'Report Dashboard')
@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="nav-align-top">
      <ul class="nav nav-pills flex-column flex-md-row mb-6">
        <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx  bx-dock-top bx-sm me-2"></i> Report Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="{{url('admin/reports')}}"> <i class="bx bx-list-ul bx-sm me-1_5"></i>Report Log</a></li>
      </ul>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
      <!-- Total Report -->
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="bx bx-file bx-sm"></i>
                </span>
              </div>
            </div>
            <p class="mb-1">Total Reports</p>
            <h4 class="card-title mb-3">{{ $totalReports }}</h4>
          </div>
        </div>
      </div>

      <!-- Today / Pending Reports -->
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-warning">
                  <i class="bx bx-time bx-sm"></i>
                </span>
              </div>
            </div>
            <p class="mb-1">Pending Reports (Today)</p>
            <h4 class="card-title mb-3">{{ $todayPendingReports }}</h4>
          </div>
        </div>
      </div>

      <!-- In Progress Reports -->
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
            <h4 class="card-title mb-3">{{ $inProgressReports }}</h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Monthly Trendline Chart -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Monthly Report Trendline ({{ $selectedYear }})</h5>
            <form method="GET" class="d-flex align-items-center">
              <label for="yearFilter" class="me-2 mb-0 small text-muted">Year:</label>
              <select name="year" id="yearFilter" class="form-select form-select-sm" onchange="this.form.submit()">
                @foreach ($availableYears as $year)
                  <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
              </select>
            </form>
          </div>
          <div class="card-body">
            <div id="reportTrendlineChart" style="height: 300px;"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Category Distribution & Total Feedback -->
    <div class="row">
      <!-- Category Distribution -->
      <div class="col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
              <h5 class="mb-1 me-2">Category Distribution</h5>
            </div>
            <div style="width: 150px;">
              <select id="categoryTimeFilter" class="form-select form-select-sm">
                <option value="week" selected>This Week</option>
                <option value="month">This Month</option>
                <option value="semester">This Semester</option>
              </select>
            </div>
          </div>
          <div class="card-body">
            <div id="categoryReportDonutChart" style="height: 250px;"></div>
          </div>
        </div>
      </div>

      <!-- Total Feedback -->
      <div class="col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-success">
                  <i class="bx bx-message-rounded-dots bx-sm"></i>
                </span>
              </div>
            </div>
            <p class="mb-1">Total Feedback</p>
            <h4 class="card-title mb-3">{{ $totalFeedbacks }}</h4>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
@endsection
