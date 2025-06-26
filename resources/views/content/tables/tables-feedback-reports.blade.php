@extends('layouts.contentNavbarLayout')

@section('title', 'Feedback')

@section('content')
  <div class="card">
    <h5 class="card-header">Feedback (Issue Reports)</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Feedback ID</th>
            <th>Report ID</th>
            <th>Resident</th>
            <th>Category</th>
            <th>Comment</th>
            <th>Submitted At</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse($feedbacks as $index => $feedback)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $feedback->id }}</td>
              <td>{{ $feedback->report_id }}</td>
              <td>{{ $feedback->resident->name ?? '-' }}</td>
              <td>{{ $feedback->report->category ?? '-' }}</td>
              <td>{{ $feedback->comment ?? 'No Comment' }}</td>
              <td>{{ $feedback->submitted_at }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center">No feedback available</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer text-muted">
      Showing {{ $feedbacks->count() }} of {{ $feedbacks->count() }} entries
    </div>
  </div>
@endsection
