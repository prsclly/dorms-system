@extends('layouts/contentResidentLayout')

@section('title', 'Resident Dashboard')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Please refer to these tables for daily meal entries.</h5>

      <form method="GET" action="{{ route('catering-daily-menu') }}" class="d-flex align-items-center gap-3">
        <input type="date" name="date" value="{{ $date }}" class="form-control form-control-sm" style="max-width: 190px;">
        <button class="btn btn-primary btn-sm" style="min-width: 90px;" type="submit">Show</button>
      </form>
    </div>

    @if(session('success'))
    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="transition: opacity 0.5s ease;">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <hr class="my-3">

@php
    $getPicColorClass = function($name) {
        $colors = [
            'bg-label-primary',
            'bg-label-success',
            'bg-label-info',
            'bg-label-warning',
            'bg-label-danger',
            'bg-label-secondary',
            'bg-label-dark',
        ];

        $index = crc32($name) % count($colors);
        return 'badge ' . $colors[$index] . ' me-1';
    };
@endphp

    @if($meals->isEmpty())
    <div class="alert alert-warning">
        Meals for {{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM YYYY') }} have not been added yet. Kindly check again later.
    </div>
    @else
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}</h5>
        <button type="button" class="btn btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reportIssueModal" title="Report Meal Problem">
          <i class="bx bx-error-circle"></i>
        </button>
      </div>

      <div class="table-responsive text-nowrap">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Meal Time</th>
              <th>Time</th>
              <th>Menu</th>
              <th>Vendor</th>
            </tr>
          </thead>
          <tbody>
          @foreach($meals as $meal)
@php
  $colorClass = isset($meal->pic->name) ? $getPicColorClass($meal->pic->name) : 'badge bg-label-secondary me-1';
@endphp

            <tr>
 <td>{{ $meal->meal_type }}</td>
  <td>{{ $meal->time }}</td>
  <td>{{ $meal->menu_description }}</td>
  <td>
    <span class="{{ $colorClass }}">
      {{ $meal->pic->name ?? '—' }}
    </span>
  </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    <!-- Modal Report Issue -->
    <div class="modal fade" id="reportIssueModal" tabindex="-1" aria-labelledby="reportIssueModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reportIssueModalLabel">Meal Issue Report</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Got any issue/problem related to your meals?<br>
            Log your feedback here and let us know!
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
            <a href="{{ url('resident/feedback_form') }}" class="btn btn-primary">Access Form</a>
          </div>
        </div>
      </div>
    </div>
    <!-- End Modal -->

@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.querySelector('.toast');
    if (toastEl) {
      const toast = new bootstrap.Toast(toastEl, {
        delay: 3000,
        autohide: true
      });
      toast.show();
    }
  });

  setTimeout(() => {
    const alert = document.getElementById('success-alert');
    if (alert) {
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }
  }, 3000);
</script>
@endsection
