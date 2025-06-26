
@extends('layouts/contentNavbarLayout')

@section('title', 'Catering Dashboard')
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
        <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx  bx-dock-top bx-sm me-2"></i> Feedback Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="{{url('admin/catering/issue_log')}}"> <i class="bx bx-list-ul bx-sm me-1_5"></i>Feedback Log</a></li>
      </ul>
    </div>
<!-- Row: Log Summary Cards -->
<div class="row mb-4">
  {{-- Total Log --}}
  <div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between mb-4">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-success">
              <i class="bx bx-pie-chart-alt bx-sm"></i>
            </span>
          </div>
        </div>
        <p class="mb-1">Total Log</p>
        <h4 class="card-title mb-3">{{ $totalLog }}</h4>
      </div>
    </div>
  </div>

  {{-- This Month --}}
  <div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between mb-4">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="bx bx-calendar-alt bx-sm"></i>
            </span>
          </div>
        </div>
        <p class="mb-1">This Month</p>
        <h4 class="card-title mb-3">{{ $thisMonthLog }}</h4>
      </div>
    </div>
  </div>

  {{-- This Week Log + today info --}}
  <div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between mb-4">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-info">
              <i class="bx bx-time-five bx-sm"></i>
            </span>
          </div>
        </div>
        <p class="mb-1">This Week</p>
        <div class="d-flex align-items-center justify-content-between">
          <h4 class="card-title mb-0">{{ $thisWeekLog }}</h4>
          <small class="text-success fw-semibold">+{{ $todayLog }} today</small>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Row: Trendline Chart -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Reporting Monthly Trendline ({{ $selectedYear }})</h5>
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
        <div id="trendlineChart" style="height: 300px;"></div> <!-- Tambahkan ini -->
      </div>
    </div>
  </div>
</div>



<!-- Row 2: Category Distribution & PIC Performance -->
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
        <div id="donutChartWrapper" class="position-relative" style="min-height: 200px;">
          <div id="categoryDonutChart"></div>
          <div id="donutCenterText" class="position-absolute top-50 start-50 translate-middle text-center" style="pointer-events: none; z-index: 10;">
            <h4 id="donutCount" class="mb-0 fw-bold" style="transition: 0.3s ease; font-size: 24px;">0</h4>
            <small id="donutCategory" class="text-muted" style="transition: 0.3s ease;">Loading...</small>
          </div>
        </div>
        <ul id="categoryList" class="p-0 m-0 mt-2">
          <li class="text-muted text-center">Loading data...</li>
        </ul>
      </div>
    </div>
  </div>

<!-- PIC Performance -->
<div class="col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="card-title mb-0">
        <h5 class="mb-1 me-2">PIC Performance</h5>
      </div>
      <div>
        <select id="timeFilter" class="form-select form-select-sm">
          <option value="week">This Week</option>
          <option value="month">This Month</option>
          <option value="semester">This Semester</option>
        </select>
      </div>
    </div>
    <div class="card-body">
      <div id="picPerformanceChart" style="height: 300px;"></div>
    </div>
  </div>
</div>


    </div>
  </div>
</div>

@endsection


@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')

<!-- PIC Performance -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Data dari controller
    const dataSets = {
      week: @json($chartPicWeek),
      month: @json($chartPicMonth),
      semester: @json($chartPicSemester),
    };

    // Inisialisasi chart
    const options = {
      chart: { type: 'bar', height: 350 },
      series: [{
        name: 'Issue Count',
        data: Object.values(dataSets.week)
      }],
      xaxis: {
        categories: Object.keys(dataSets.week),
        labels: { style: { fontSize: '12px' } }
      },
      plotOptions: {
        bar: {
          borderRadius: 4,
          horizontal: false,
          columnWidth: '50%',
          distributed: true
        }
      },
      dataLabels: { enabled: false },
      colors: ['#FF7F7F', '#66D9B3', '#A38EDC', '#FFBB7F', '#6CA6CD']
    };

    const chart = new ApexCharts(document.querySelector("#picPerformanceChart"), options);
    chart.render();

    // Event handler dropdown
    document.getElementById('timeFilter').addEventListener('change', function () {
      const selected = this.value;
      const newData = dataSets[selected];

      chart.updateOptions({
        xaxis: { categories: Object.keys(newData) },
        series: [{ data: Object.values(newData) }]
      });
    });
  });
</script>

<!-- category Distribution-->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const categoryDataSets = {
  week: {
    labels: @json(array_keys($categoryDistributionWeekRaw->toArray())),
    counts: @json(array_values($categoryDistributionWeekRaw->toArray())),
    percentages: @json($categoryDistributionWeek->toArray())
  },
  month: {
    labels: @json(array_keys($categoryDistributionMonthRaw->toArray())),
    counts: @json(array_values($categoryDistributionMonthRaw->toArray())),
    percentages: @json($categoryDistributionMonth->toArray())
  },
  semester: {
    labels: @json(array_keys($categoryDistributionSemesterRaw->toArray())),
    counts: @json(array_values($categoryDistributionSemesterRaw->toArray())),
    percentages: @json($categoryDistributionSemester->toArray())
  }
};


    let currentSet = 'week';
    let lockedIndex = null;

    const countEl = document.getElementById("donutCount");
    const catEl = document.getElementById("donutCategory");

    function setCenterText(labels, counts, index = null) {
      if (index === null) {
        const total = counts.reduce((a, b) => a + b, 0);
        countEl.innerText = total;
        catEl.innerText = "Total Feedback";
      } else {
        countEl.innerText = counts[index];
        catEl.innerText = labels[index];
      }
    }

    function renderCategoryList(percentages) {
      const container = document.getElementById("categoryList");
      container.innerHTML = "";

      const categoryStyles = {
        'Hygiene': { icon: 'bx bx-dish', bg: 'bg-label-success' },
        'Taste': { icon: 'bx bx-restaurant', bg: 'bg-label-warning' },
        'Food Quality': { icon: 'bx bxs-bowl-hot', bg: 'bg-label-primary' },
        'Others': { icon: 'bx bx-dots-horizontal-rounded', bg: 'bg-label-secondary' }
      };

      const keys = Object.keys(percentages);

      if (keys.length === 0) {
        container.innerHTML = '<li class="text-muted text-center">No feedback data available.</li>';
        return;
      }

      keys.forEach(category => {
        const percent = percentages[category];
        const style = categoryStyles[category] || { icon: 'bx bx-category', bg: 'bg-label-info' };

        const itemHTML = `
          <li class="d-flex align-items-center mb-1 small">
            <div class="avatar flex-shrink-0 me-2">
              <span class="avatar-initial rounded ${style.bg} d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i class='${style.icon}' style="font-size: 14px;"></i>
              </span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">${category}</h6>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">${percent}%</h6>
              </div>
            </div>
          </li>
        `;
        container.insertAdjacentHTML('beforeend', itemHTML);
      });
    }

    let chart = null;

    function renderChart(labels, counts) {
  setCenterText(labels, counts, null);

  const options = {
    chart: {
      type: 'donut',
      height: 200,
      animations: {
        enabled: true,
        easing: 'easeinout',
        speed: 500
      },
      events: {
        dataPointSelection: function (event, chartContext, config) {
          const i = config.dataPointIndex;
          lockedIndex = i;
          setCenterText(labels, counts, i);
        },
        dataPointMouseEnter: function (event, chartContext, config) {
          if (lockedIndex === null) {
            const i = config.dataPointIndex;
            setCenterText(labels, counts, i);
          }
        },
        dataPointMouseLeave: function () {
          if (lockedIndex === null) {
            setCenterText(labels, counts, null);
          }
        }
      }
    },
    series: counts,
    labels: labels,
    colors: ['#28c76f', '#ff9f43', '#7367f0', '#a8aaae'],
    dataLabels: { enabled: false },
    legend: { show: false },
    plotOptions: {
      pie: { donut: { size: '75%' } }
    },
    tooltip: {
      enabled: true,
      y: {
        formatter: function (value) {
          return value + ' reports';
        }
      }
    }
  };

  // Destroy old chart before creating new one to force animation
  if (chart) {
    chart.destroy(); // Hapus chart lama
    chart = null;
  }

  chart = new ApexCharts(document.querySelector("#categoryDonutChart"), options);
  chart.render();
    }

    // Initial render
    renderChart(categoryDataSets.week.labels, categoryDataSets.week.counts);
    renderCategoryList(categoryDataSets.week.percentages);

    // Dropdown filter handler
    document.getElementById('categoryTimeFilter').addEventListener('change', function () {
      const selected = this.value;

      currentSet = selected;
      lockedIndex = null;

      const data = categoryDataSets[selected];
      renderChart(data.labels, data.counts);
      renderCategoryList(data.percentages);
    });
  });
</script>

<script>
  const trendMonthlyData = @json(array_values($trendMonthly->toArray()));
  const trendMonthlyLabels = @json(array_keys($trendMonthly->toArray()));

  const trendChartOptions = {
    chart: {
      type: 'line',
      height: 300,
      toolbar: { show: false }
    },
    series: [{
      name: 'Feedbacks',
      data: trendMonthlyData
    }],
    xaxis: {
      categories: trendMonthlyLabels
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    colors: ['#696CFF'],
    markers: {
      size: 4
    }
  };

  const trendChart = new ApexCharts(document.querySelector("#trendlineChart"), trendChartOptions);
  trendChart.render();
</script>





@endsection

