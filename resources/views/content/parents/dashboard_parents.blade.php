
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
        <div class="col-xxl-8 mb-6 order-0">
            <div class="card">
                <div class="d-flex align-items-start row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                Welcome {{ Auth::guard('parent')->user()->name }}! 👨‍👩‍👧‍👦
                            </h5>
                            <p class="mb-6">
                                You're now viewing your children’s point activity dashboard.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-6">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="175"
                                alt="Parent Illustration">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Siswa dan Poin --}}
    <div class="row">
        @foreach ($students as $student)
            <div class="col-md-6 col-xl-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $student->name }}</h5>
                        <small class="text-muted">Total Points: <strong>{{ $student->pointLogs->sum('point') }}</strong></small>
                    </div>
                    <div class="card-body">
                        @if ($student->pointLogs->isEmpty())
                            <p class="text-muted mb-0">No point history available.</p>
                        @else
                            <ul class="list-unstyled mb-0">
                                @foreach ($student->pointLogs->take(3) as $log)
                                    <li>
                                        <span class="fw-semibold">{{ $log->created_at->format('d M Y') }}:</span>
                                        {{ $log->description }} ({{ $log->point > 0 ? '+' : '' }}{{ $log->point }} pts)
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
