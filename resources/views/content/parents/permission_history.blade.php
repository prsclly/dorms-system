@extends('layouts/contentParentsLayout')

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
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Child</th>
              <th>NIM</th>
              <th>Type</th>
              <th>Reason</th>
              <th>Departure Date</th>
              <th>Return Date</th>
              <th>Status</th>
              <th>Rejection Reason</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($permissions as $p)
              @php
                $today = \Carbon\Carbon::today();
                $startDate = \Carbon\Carbon::parse($p->start_date);
                $isPastLeave = $today->greaterThan($startDate);
                $canEditDelete = $p->status === 'pending' && !$isPastLeave;
              @endphp
              <tr>
                <td>{{ $p->student->name }}</td>
                <td>{{ $p->student->nim }}</td>
                <td>
                  @if (strtoupper($p->type) === 'PESIAR')
                    Day Leave (Pesiar)
                  @elseif (strtoupper($p->type) === 'IB')
                    Overnight Leave (Izin Bermalam)
                  @else
                    {{ strtoupper($p->type) }}
                  @endif
                </td>
                <td>{{ $p->reason }}</td>
                <td>{{ \Carbon\Carbon::parse($p->start_date)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($p->end_date)->format('d M Y') }}</td>
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
                <td>
                  @if ($p->status === 'rejected')
                    {{ $p->rejection_reason ?? '-' }}
                  @else
                    -
                  @endif
                </td>
                <td>
                  @if ($canEditDelete)
                    <div class="dropdown">
                      <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                          <a class="dropdown-item" href="{{ route('parent.permissions.edit', $p->id) }}">
                            <i class="bx bx-edit-alt me-1"></i> Edit
                          </a>
                        </li>
                        <li>
                          <form action="{{ route('parent.permissions.destroy', $p->id) }}" method="POST" class="form-delete" data-id="{{ $p->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                              <i class="bx bx-trash me-1"></i> Delete
                            </button>
                          </form>
                        </li>
                      </ul>
                    </div>
                  @else
                    <button class="btn btn-sm btn-icon text-muted" type="button" disabled style="cursor: not-allowed;">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
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

<!-- SweetAlert script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('.form-delete');

    deleteForms.forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
          title: 'Are you sure?',
          text: "This leave request will be permanently deleted.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>
@endsection
