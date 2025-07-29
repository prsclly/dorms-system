@extends('layouts/contentParentsLayout')

@section('title', 'Weekly Menu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Weekly Meal Menu</h5>

  <form method="GET" action="{{ route('parent.weekly-menu') }}" class="d-flex align-items-center gap-3">
    <input type="week" name="week"
           value="{{ $weekStart->format('o-\WW') }}"

           class="form-control form-control-sm" style="max-width:160px;">
    <button class="btn btn-primary btn-sm" type="submit">Show</button>
  </form>
</div>

<hr class="my-3">

@php
$picColors = [
  'Bu Sri' => 'badge bg-label-info me-1',
  'Pak Dafio' => 'badge bg-label-success me-1',
  'Pak Sigit' => 'badge bg-label-primary me-1',
];
@endphp

@forelse($mealsByDate as $date => $meals)
  <div class="card mb-4">
    <div class="card-header">
      <h6 class="mb-0">{{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}</h6>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Meal Time</th>
            <th>Time</th>
            <th>Menu</th>
            <th>PIC</th>
          </tr>
        </thead>
        <tbody>
          @foreach($meals as $meal)
            @php
              $colorClass = $picColors[$meal->pic->name ?? ''] ?? 'badge-secondary';
            @endphp
            <tr>
              <td>{{ $meal->meal_type }}</td>
              <td>{{ $meal->time }}</td>
              <td>{{ $meal->menu_description }}</td>
              <td><span class="badge {{ $colorClass }}">{{ $meal->pic->name ?? '—' }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@empty
  <div class="alert alert-warning">
    No menu available for this week ({{ $weekStart->isoFormat('D MMM') }} – {{ $weekEnd->isoFormat('D MMM YYYY') }}).
  </div>
@endforelse
@endsection
