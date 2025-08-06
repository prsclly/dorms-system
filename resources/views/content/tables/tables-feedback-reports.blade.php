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
              <td>{{ $feedbacks->firstItem() + $index }}</td>
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

    <div class="card-footer d-flex justify-content-between align-items-center flex-column flex-md-row">
  <div class="mb-2 mb-md-0 text-muted">
    Showing {{ $feedbacks->firstItem() }} to {{ $feedbacks->lastItem() }} of {{ $feedbacks->total() }} entries
  </div>
  <div>
    {{ $feedbacks->links() }}
  </div>
</div>
@endsection
