@extends('layouts/contentNavbarLayout')

@section('title', 'Manage Technicians')

@section('content')
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
  </div>
@endif

@if (session('success'))
  <div class="alert alert-success">{!! session('success') !!}</div>
@endif

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Manage Technicians</h5>
    <div class="d-flex gap-2">
      <form method="GET" action="{{ route('admin.manage.technicians') }}" class="d-flex gap-2 mb-0">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name/email..." class="form-control form-control-sm" style="min-width: 200px;">
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
      </form>
      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTechnicianModal">
        <i class="bx bx-plus me-1"></i> Add Technician
      </button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table text-center">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Specialization</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($technicians as $index => $technician)
        <tr>
          <td>{{ $index + 1 + ($technicians->currentPage() - 1) * $technicians->perPage() }}</td>
          <td>{{ $technician->name }}</td>
          <td>{{ $technician->email }}</td>
          <td>{{ $technician->specialization->name ?? '-' }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTechnicianModal{{ $technician->id }}">
                  <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
<!-- Trigger Delete Modal -->
<button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteTechnicianModal{{ $technician->id }}">
  <i class="bx bx-trash me-1"></i> Delete
</button>

              </div>
            </div>

            <!-- Delete Modal -->
<div class="modal fade" id="deleteTechnicianModal{{ $technician->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="{{ route('admin.manage.technicians.destroy', $technician->id) }}" class="modal-content text-start">
      @csrf @method('DELETE')

      <div class="modal-header">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-start">
        <p>Are you sure you want to delete this technician?</p>
        <p class="mb-0 fw-semibold text-danger">{{ $technician->name }} ({{ $technician->email }})</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary me-1" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editTechnicianModal{{ $technician->id }}" tabindex="-1">
              <div class="modal-dialog">
                <form method="POST" action="{{ route('admin.manage.technicians.update', $technician->id) }}" class="modal-content text-start">
                  @csrf @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Technician</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Name <span class="text-danger">*</span></label>
                      <input type="text" name="name" class="form-control" value="{{ $technician->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Email <span class="text-danger">*</span></label>
                      <input type="email" name="email" class="form-control" value="{{ $technician->email }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Specialization <span class="text-danger">*</span></label>
                      <select name="specialization_id" class="form-select" required>
                        @foreach ($specializations as $specialization)
                          <option value="{{ $specialization->id }}" @selected($technician->specialization_id == $specialization->id)>
                            {{ $specialization->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button"  class="btn btn-outline-secondary me-1" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
            </div>

          </td>
        </tr>
        @empty
          <tr>
            <td colspan="5" class="text-muted">No technicians found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card-footer d-flex justify-content-between align-items-center flex-column flex-md-row">
    <div class="mb-2 mb-md-0 text-muted">
      Showing {{ $technicians->firstItem() }} to {{ $technicians->lastItem() }} of {{ $technicians->total() }} entries
    </div>
    <div>
      {{ $technicians->withQueryString()->links() }}
    </div>
  </div>
</div>

<!-- Add Technician Modal -->
<div class="modal fade" id="addTechnicianModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.manage.technicians.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add Technician</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Specialization <span class="text-danger">*</span></label>
          <div class="input-group">
            <select name="specialization_id" class="form-select">
              <option value="">Select existing...</option>
              @foreach ($specializations as $specialization)
                <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
              @endforeach
            </select>
            <span class="input-group-text">or</span>
            <input type="text" name="new_specialization" class="form-control" placeholder="New specialization">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary me-1" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>


@endsection
