@extends('layouts/contentParentLayout')

@section('title', 'Leave Request History')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Leave Request History</h5>
    <form action="{{ route('parent.permissions.history') }}" method="GET" class="d-flex" role="search">
      <input type="text" name="search" class="form-control" placeholder="Search by name or NIM" value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary ms-2">Search</button>
    </form>
  </div>

  <div class="card-body">
    @if ($permissions->isEmpty())
      <p>No leave requests have been submitted yet.</p>
    @else
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Child</th>
              <th>NIM</th>
              <th>Type</th>
              <th>Reason</th>
              <th>Departure Date</th>
              <th>Return Date</th>
              <th>Status</th>
              <th>Rejection Reason</th>
              <th>Attachment</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($permissions as $p)
              <tr>
                <td>{{ $p->student->name }}</td>
                <td>{{ $p->student->nim }}</td>
                <td>{{ strtoupper($p->type) }}</td>
                <td>{{ $p->reason }}</td>
                <td>{{ \Carbon\Carbon::parse($p->start_date)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($p->end_date)->format('d M Y') }}</td>
                <td>
                  @if ($p->status === 'approved')
                    <span class="badge bg-success">Approved</span>
                  @elseif ($p->status === 'rejected')
                    <span class="badge bg-danger">Rejected</span>
                  @else
                    <span class="badge bg-warning text-dark">Pending</span>
                  @endif
                </td>
                <td>
                  @if ($p->status === 'rejected')
                    {{ $p->rejection_reason ?? '-' }}
                  @else
                    -
                  @endif
                </td>
                <td>
                  @if ($p->attachment)
                    <a href="{{ asset('storage/' . $p->attachment) }}" target="_blank">View</a>
                  @else
                    -
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
@endsection
