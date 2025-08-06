@extends('layouts/contentNavbarLayout')

@section('title', 'Leave Request List')

@section('content')
<style>
  .badge-status {
    padding: 6px 12px;
    font-weight: 500;
    border-radius: 6px;
    color: white !important;
  }

  .badge-warning {
    color: #000 !important;
  }

  .table thead th {
    background-color: #f5f5f9;
    color: #6c757d;
  }

  .card {
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }

  .form-select,
  .form-control {
    border-radius: 8px;
  }
</style>

<h4 class="fw-bold mb-3">Leave Request List</h4>

@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Filter Section -->
<div class="card p-3 mb-4">
  <form method="GET" action="{{ route('admin.permissions.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-3">
        <label for="type" class="form-label mb-1">Type</label>
        <select name="type" id="type" class="form-select">
          <option value="">All</option>
          <option value="pesiar" {{ request('type') == 'pesiar' ? 'selected' : '' }}>Day Leave (Pesiar)</option>
          <option value="ib" {{ request('type') == 'ib' ? 'selected' : '' }}>Overnight Leave (IB)</option>
        </select>
      </div>
      <div class="col-md-3">
        <label for="status" class="form-label mb-1">Status</label>
        <select name="status" id="status" class="form-select">
          <option value="">All</option>
          <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="on_process" {{ request('status') == 'on_process' ? 'selected' : '' }}>On Process</option>
          <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
          <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
      </div>
      <div class="col-md-4 ms-auto">
        <label for="search" class="form-label mb-1">Search by Name/NIM</label>
        <div class="input-group">
          <input type="text" name="search" id="search" class="form-control" placeholder="Enter name or NIM..." value="{{ request('search') }}">
        </div>
      </div>
      <div class="col-md-2 text-end">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary w-100 mt-2">Reset</a>
      </div>
    </div>
  </form>
</div>

<div class="mb-3">
  <h5>Total Leave Requests: {{ $permissions->total() }}</h5>
</div>

<!-- Table -->
<div class="card p-3">
  <div class="table-responsive text-nowrap">
    <table class="table table-bordered mb-0">
      <thead>
        <tr>
          <th>Name</th>
          <th>NIM</th>
          <th>Type</th>
          <th>Reason</th>
          <th>Departure Date</th>
          <th>Return Date</th>
          <th class="text-center">Status</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($permissions as $p)
          <tr>
            <td>{{ $p->student->name }}</td>
            <td>{{ $p->student->nim }}</td>
            <td>
              {{ $p->type === 'pesiar' ? 'Day Leave (Pesiar)' : 'Overnight Leave (IB)' }}
            </td>
            <td>{{ $p->reason }}</td>
            <td>{{ \Carbon\Carbon::parse($p->start_date)->format('d M Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($p->end_date)->format('d M Y') }}</td>
            <td class="text-center">
              @php
                $badgeClass = match($p->status) {
                  'approved' => 'success',
                  'rejected' => 'danger',
                  'on_process' => 'info',
                  default => 'warning badge-warning'
                };
              @endphp
              <span class="badge-status bg-{{ $badgeClass }}">
  {{ ucfirst(str_replace('_', ' ', $p->status)) }}
</span>
            </td>
            <td class="text-center">
              <a href="{{ route('admin.permissions.show', $p->id) }}" class="btn btn-sm btn-primary">Detail</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center">No leave requests found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
