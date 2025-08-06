@extends('layouts/contentResidentLayout')

@section('title', 'Leave History')

@section('content')
  <h4 class="fw-bold mb-4">Leave History</h4>

  <div class="card rounded-3 shadow-sm border-0">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Type</th>
            <th>Reason</th>
            <th>Departure Date</th>
            <th>Return Date</th>
            <th>Rejection Reason</th>
            <th>Status</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($permissions as $p)
            <tr>
              <td>
                @if ($p->type === 'pesiar')
                  Day Leave (Pesiar)
                @elseif ($p->type === 'ib')
                  Overnight Leave (Izin Bermalam)
                @else
                  {{ ucfirst($p->type) }}
                @endif
              </td>
              <td>{{ $p->reason }}</td>
              <td>{{ \Carbon\Carbon::parse($p->start_date)->translatedFormat('d F Y') }}</td>
              <td>{{ \Carbon\Carbon::parse($p->end_date)->translatedFormat('d F Y') }}</td>
              <td>
                {{ $p->status === 'rejected' ? ($p->rejection_reason ?? '-') : '-' }}
              </td>
              <td>
                @switch($p->status)
                  @case('approved')
                    <span class="badge bg-success">Approved</span>
                    @break

                  @case('rejected')
                    <span class="badge bg-danger">Rejected</span>
                    @break

                  @case('on_process')
                    <span class="badge bg-info text-dark">On Process</span>
                    @break

                  @default
                    <span class="badge bg-warning text-dark">Pending</span>
                @endswitch
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center">No leave history available.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
