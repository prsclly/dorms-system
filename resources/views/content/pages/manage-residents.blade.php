@extends('layouts/contentNavbarLayout')

@section('title', 'Manage Residents')

@section('content')
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

@if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Manage Residents</h5>

    <div class="d-flex gap-2 align-items-center">
      <form method="GET" action="{{ route('admin.manage.residents') }}" class="d-flex gap-2 align-items-center mb-0">
        <input
          type="text"
          name="search"
          class="form-control form-control-sm"
          placeholder="Search by name..."
          value="{{ request('search') }}"
          style="min-width: 200px;">
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
      </form>

      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addResidentModal">
        <i class="bx bx-plus me-1"></i> Add Resident
      </button>
    </div>
  </div>


  <div class="table-responsive text-nowrap">
  <table class="table text-center">
    <thead>
      <tr>
        <th>#</th>
        <th>Resident ID</th>
        <th>Name</th>
        <th>NIM</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Room Number</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody class="table-border-bottom-0">
      @forelse ($residents as $index => $resident)
      <tr>
        <td>{{ $residents->firstItem() + $index }}</td>
        <td>{{ $resident->id }}</td>
        <td>{{ $resident->name }}</td>
        <td>{{ $resident->student->nim ?? '-' }}</td>
        <td>{{ $resident->email }}</td>
        <td>{{ $resident->phone_number ?? '-' }}</td>
        <td>{{ $resident->room_number ?? '-' }}</td>
        <td>
          <div class="dropdown">
            <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editResidentModal{{ $resident->id }}">
                <i class="bx bx-edit-alt me-1"></i> Edit
              </a>
              <form action="{{ route('admin.manage.residents.destroy', $resident->id) }}" method="POST" onsubmit="return confirm('Delete this resident?');">
                @csrf @method('DELETE')
                <button class="dropdown-item text-danger">
                  <i class="bx bx-trash me-1"></i> Delete
                </button>
              </form>
            </div>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="8" class="text-center">No residents found.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Modal Edit --}}
@foreach ($residents as $resident)
<div class="modal fade" id="editResidentModal{{ $resident->id }}" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.manage.residents.update', $resident->id) }}" class="modal-content">
      @csrf @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Resident</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-start">
        <div class="mb-3">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input name="name" type="text" class="form-control" value="{{ $resident->name }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input name="email" type="email" class="form-control" value="{{ $resident->email }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">NIM <span class="text-danger">*</span></label>
          <input name="nim" type="text" class="form-control" value="{{ $resident->student->nim ?? '' }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Phone Number <span class="text-danger">*</span></label>
          <input name="phone_number" type="text" class="form-control" value="{{ $resident->phone_number }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Room Number <span class="text-danger">*</span></label>
          <input name="room_number" type="text" class="form-control" value="{{ $resident->room_number }}" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>
@endforeach

<div class="card-footer d-flex justify-content-between align-items-center flex-column flex-md-row">
  <div class="mb-2 mb-md-0 text-muted">
    Showing {{ $residents->firstItem() }} to {{ $residents->lastItem() }} of {{ $residents->total() }} entries
  </div>
  <div>
    {{ $residents->links() }}
  </div>
</div>

<!--/ Manage Residents Table -->


{{-- Modal Add Resident --}}
<div class="modal fade" id="addResidentModal" tabindex="-1" aria-labelledby="addResidentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.manage.residents.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addResidentModalLabel">Add New Resident</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Name --}}
        <div class="mb-3">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" required>
        </div>
        {{-- Email --}}
        <div class="mb-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control" required>
        </div>
        {{-- NIM --}}
        <div class="mb-3">
          <label class="form-label">NIM <span class="text-danger">*</span></label>
          <input type="text" name="nim" class="form-control" required>
        </div>
        {{-- Phone Number --}}
        <div class="mb-3">
          <label class="form-label">Phone Number <span class="text-danger">*</span></label>
          <input type="text" name="phone_number" class="form-control" required>
        </div>
        {{-- Room Number --}}
        <div class="mb-3">
          <label class="form-label">Room Number <span class="text-danger">*</span></label>
          <input type="text" name="room_number" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary me-1" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Resident</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert');

    alerts.forEach(alert => {
      setTimeout(() => {
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 500); // remove element after fade out
      }, 5000); // 5 seconds
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 500);
      }, 5000);
    });

    // Delete modal setup
    const deleteModal = document.getElementById('deleteModal');
    const residentNameEl = document.getElementById('residentName');
    const deleteForm = document.getElementById('deleteResidentForm');

    deleteModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const residentId = button.getAttribute('data-resident-id');
      const residentName = button.getAttribute('data-resident-name');

      // Update modal content
      residentNameEl.textContent = residentName;

      // Set action URL dynamically
      deleteForm.action = `/admin/manage/residents/${residentId}`;
    });
  });
</script>

@endpush

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" class="modal-content" id="deleteResidentForm">
      @csrf
      @method('DELETE')
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <strong id="residentName"></strong>'s data?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary me-1" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>


@endsection
