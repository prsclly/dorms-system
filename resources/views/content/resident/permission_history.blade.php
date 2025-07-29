@extends('layouts/contentResidentLayout')

@section('title', 'Leave History')

@section('content')
  <h4 class="fw-bold mb-4">Leave History</h4>

  <div class="card">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Reason</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($permissions as $p)
            <tr>
              <td>{{ \Carbon\Carbon::parse($p->start_date)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($p->end_date)->translatedFormat('d F Y') }}</td>
              <td>
                @if ($p->type === 'pesiar')
                  <span class="badge bg-info">Day Leave</span>
                @elseif ($p->type === 'ib')
                  <span class="badge bg-primary">Overnight Leave</span>
                @else
                  {{ ucfirst($p->type) }}
                @endif
              </td>
              <td>{{ $p->reason }}</td>
              <td>
                <span class="badge bg-{{ $p->status === 'approved' ? 'success' : ($p->status === 'rejected' ? 'danger' : 'warning text-dark') }}">
                  {{ ucfirst($p->status) }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">No leave history available.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
