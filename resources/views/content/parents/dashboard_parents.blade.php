@extends('layouts.ContentParentsLayout')

@section('title', 'Parent Dashboard')

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
    <div class="col-12 mb-4">
        <div class="card">
            <div class="row g-0 align-items-center">
                <div class="col-md-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3">Welcome, Parents! 👨‍👩‍👧‍👦</h5>
                        <p class="mb-0">
                            Monitor your child’s activities, including points, leave requests, and daily meals.
                        </p>
                    </div>
                </div>
                <div class="col-md-5 text-center">
                    <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" class="img-fluid p-3" alt="Parent Illustration" style="max-height: 175px;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Cards untuk setiap anak --}}
    @foreach ($students as $student)
        <div class="col-sm-6 col-md-4 mb-4 d-flex">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ $student->name }}</h5>
                    <small class="text-muted">Total Points: <strong>{{ $student->pointLogs->sum('new_point') }}</strong></small>
                </div>
                <div class="card-body">
                    @if ($student->pointLogs->isEmpty())
                        <p class="text-muted mb-0">No point history available.</p>
                    @else
                        <ul class="list-unstyled mb-0">
                            @foreach ($student->pointLogs->take(3) as $log)
                                <li>
                                    <span class="fw-semibold">{{ $log->created_at->format('d M Y') }}:</span>
                                    {{ $log->description }} ({{ $log->point_change > 0 ? '+' : '' }}{{ $log->point_change }} pts)
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    {{-- Card Catering --}}
    <div class="col-sm-6 col-md-4 mb-4 d-flex">
        <div class="card w-100">
            <div class="card-body d-flex flex-column justify-content-between h-100">
                <div>
                    <h6 class="card-title">Today's Catering</h6>
                    @php $mealOrder = ['Breakfast', 'Lunch', 'Dinner']; @endphp
                    @forelse ($mealOrder as $mealType)
                        <div class="mb-1">
                            <strong>{{ $mealType }}:</strong>
                            <span>
                                {{ isset($todayMeals[$mealType]) && $todayMeals[$mealType]->first() ? $todayMeals[$mealType]->first()->menu_description : 'Not set' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-1">Menu has not been added yet.</p>
                    @endforelse
                </div>
                <a href="{{ url('parent/weekly-menu') }}" class="btn btn-sm btn-outline-danger mt-2">View All Menu</a>
            </div>
        </div>
    </div>
</div>
@endsection
