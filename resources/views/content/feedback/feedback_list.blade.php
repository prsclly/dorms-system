@extends('layouts/contentNavbarLayout')

@section('title', 'Issue Log')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="nav-align-top">
      <ul class="nav nav-pills flex-column flex-md-row mb-6">
        <li class="nav-item">
          <a class="nav-link" href="{{ url('admin/catering/dashboard') }}">
            <i class="bx bx-dock-top bx-sm me-2"></i>Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="javascript:void(0);">
            <i class="bx bx-list-ul bx-sm me-1_5"></i>Issue Log
          </a>
        </li>
      </ul>
    </div>

    <style>
      table td, table th {
        font-size: 13px;
      }

      .table-responsive thead th {
  position: sticky;
  top: 0;
  background-color: #ffff; /* Sesuaikan dengan warna background kamu */
  z-index: 2;
}

    </style>

<!-- Striped Rows -->
<div class="card p-3">
  <!-- Filter Form -->
  <form method="GET" action="{{ route('feedback-list') }}">
    <div class="row align-items-end g-2 small mb-3">
      <div class="col-md-2">
        <label for="date" class="form-label">Date</label>
        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
      </div>
      <div class="col-md-2">
        <label for="meal_time" class="form-label">Meal Time</label>
        <select name="meal_time" class="form-select form-select-sm">
          <option value="">All</option>
          @foreach (['Breakfast', 'Lunch', 'Dinner'] as $meal)
            <option value="{{ $meal }}" {{ request('meal_time') == $meal ? 'selected' : '' }}>{{ $meal }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label for="pic_id" class="form-label">PIC</label>
        <select name="pic_id" class="form-select form-select-sm">
          <option value="">All</option>
          @foreach ($allPics as $pic)
            <option value="{{ $pic->id }}" {{ request('pic_id') == $pic->id ? 'selected' : '' }}>
              {{ $pic->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label for="category" class="form-label">Category</label>
        <select name="category" class="form-select form-select-sm">
          <option value="">All</option>
          @foreach (['Taste', 'Hygiene', 'Food Quality', 'Others'] as $cat)
            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4 text-end">
        <button type="submit" class="btn btn-sm btn-primary me-1">Filter</button>
        <a href="{{ route('feedback-list') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        <a href="{{ route('feedback-list', array_merge(request()->query(), ['export' => true])) }}" class="btn btn-success btn-sm">
          Export to Excel
      </a>




      </div>
    </div>
  </form>

  <!-- Feedback Table -->
  <div class="table-responsive text-nowrap" style="max-height: 400px; overflow-y: auto; border: 1px solid #eee;">
    <table class="table table-striped mb-0">
      <thead>
        <tr>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Date</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Resident</th>
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
            <td>{{ $feedback->resident?->name ?? '-' }}</td>
            <td>{{ $feedback->meal?->meal_type ?? '-' }}</td>
            <td>{{ optional(optional($feedback->meal)->pic)->name ?? '-' }}</td>
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
            <td colspan="7" class="text-center">No feedback entries found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<!--/ Striped Rows -->

        <!-- Showing x of y entries + pagination -->
        <div class="card-body pt-2 d-flex justify-content-between align-items-center">
          <small class="text-muted">
            Showing {{ $feedbacks->count() }} of {{ $feedbacks->total() }} entries
          </small>
          <div>
            {{ $feedbacks->links() }}
          </div>
        </div>
      </div>
    </div>
    <!--/ Striped Rows -->
  </div>
@endsection
