@extends('layouts/contentNavbarLayout')

@section('title', 'Leave Request List')

@section('content')
  <style>
    .bg-day-leave,
    .bg-overnight {
      padding: 6px 12px;
      border-radius: 6px;
      display: inline-block;
      text-align: center;
      font-weight: 500;
    }

    .bg-day-leave {
      background-color: #cff4fc;
      color: #055160;
    }

    .bg-overnight {
      background-color: #e2e3ff;
      color: #383d6b;
    }

    .badge-status {
      padding: 6px 12px;
      font-weight: 500;
      border-radius: 6px;
    }
  </style>

  <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h4 class="fw-bold mb-0">Leave Request List</h4>

    {{-- Search bar aligned to right --}}
    <form method="GET" action="{{ route('admin.permissions.index') }}">
      <div class="input-group" style="width: 300px;">
        <input type="text" name="search" class="form-control" placeholder="Search by name or NIM..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-primary">Search</button>
      </div>
    </form>
  </div>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Date</th>
            <th>Name</th>
            <th>NIM</th>
            <th class="text-center">Type</th>
            <th class="text-center">Status</th>
            <th>Reason</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($permissions as $p)
            <tr>
              <td>{{ \Carbon\Carbon::parse($p->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($p->end_date)->format('d M Y') }}</td>

              <td>{{ $p->student->name }}</td>
              <td>{{ $p->student->nim }}</td>

              {{-- Type badge --}}
              <td class="text-center">
                @if ($p->type === 'pesiar')
                  <span class="bg-day-leave">Day Leave</span>
                @elseif ($p->type === 'ib')
                  <span class="bg-overnight">Overnight Leave</span>
                @else
                  <span>{{ ucfirst($p->type) }}</span>
                @endif
              </td>

              {{-- Status badge --}}
              <td class="text-center">
                <span class="badge-status bg-{{ $p->status === 'approved' ? 'success' : ($p->status === 'rejected' ? 'danger' : 'warning text-dark') }}">
                  {{ ucfirst($p->status) }}
                </span>
              </td>

              <td>{{ $p->reason }}</td>

              <td class="text-center">
                <a href="{{ route('admin.permissions.show', $p->id) }}" class="btn btn-sm btn-primary">Detail</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center">No leave requests found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
