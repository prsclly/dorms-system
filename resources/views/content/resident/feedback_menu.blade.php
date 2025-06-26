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
<style>
  table td, table th {
    font-size: 13px;
  }
</style>

<!-- Feedback Filter + Table -->
<div class="card p-3">
  <!-- Filter Form -->
  <form method="GET" action="{{ route('catering-feedback-history') }}">
    <div class="row align-items-end g-2 small mb-3">
      <div class="col-md-3">
        <label for="date" class="form-label">Date</label>
        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
      </div>
      <div class="col-md-3">
        <label for="meal_time" class="form-label">Meal Time</label>
        <select name="meal_time" class="form-select form-select-sm">
          <option value="">All</option>
          @foreach (['Breakfast', 'Lunch', 'Dinner'] as $meal)
            <option value="{{ $meal }}" {{ request('meal_time') == $meal ? 'selected' : '' }}>{{ $meal }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label for="category" class="form-label">Category</label>
        <select name="category" class="form-select form-select-sm">
          <option value="">All</option>
          @foreach (['Taste', 'Hygiene', 'Food Quality', 'Others'] as $cat)
            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 text-end">
        <button type="submit" class="btn btn-sm btn-primary me-1">Filter</button>
        <a href="{{ route('catering-feedback-history') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
      </div>
    </div>
  </form>

  <!-- Feedback Table -->
  <div class="table-responsive text-nowrap" style="max-height: 400px; overflow-y: auto; border: 1px solid #eee;">
    <table class="table table-striped mb-0">
      <thead>
        <tr>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Date</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Meal Time</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">PIC</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Category</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Description</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Submitted at</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($feedbacks as $feedback)
          <tr>
            <td>{{ \Carbon\Carbon::parse($feedback->date)->format('j M Y') }}</td>
            <td>{{ $feedback->meal->meal_type ?? '-' }}</td>
            <td>{{ $feedback->meal->pic->name ?? '-' }}</td>
            <td>
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
            <td>{{ $feedback->message }}</td>
            <td>{{ \Carbon\Carbon::parse($feedback->created_at)->format('H:i') }} WIB</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center">You haven't submitted any feedback yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

  <!-- Pagination Info -->
  <div class="card-body pt-2 d-flex justify-content-between align-items-center">
    <small class="text-muted">
      Showing {{ $feedbacks->count() }} of {{ $feedbacks->total() }} entries
    </small>
    <div>
      {{ $feedbacks->links() }}
    </div>
  </div>
@endsection
