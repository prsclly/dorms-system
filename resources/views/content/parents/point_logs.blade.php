@extends('layouts/ContentParentLayout')

@section('title', 'Point History')

@section('content')
    <h4 class="mb-4">Your Children’s Point History</h4>

    @foreach ($students as $student)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ $student->name }}</h5>
                <small>Total Points: <strong>{{ $student->total_point }}</strong></small>
            </div>
            <div class="card-body">
                @if ($student->pointLogs->isEmpty())
                    <p class="text-muted">No point history found.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Point</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($student->pointLogs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if($log->point_change > 0)
                                                <span class="badge bg-success">Appreciation</span>
                                            @else
                                                <span class="badge bg-danger">Violation</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $log->point_change > 0 ? '+' : '' }}{{ $log->point_change }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endsection
