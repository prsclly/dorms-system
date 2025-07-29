@extends('layouts/contentNavbarLayout')

@section('title', 'Manage Admins')

@section('content')

{{-- Success Modal --}}
@if (session('success'))
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow bg-white">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold text-center w-100 text-primary" id="successModalLabel">Success</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center fw-semibold text-dark fs-6">
          {!! session('success') !!}
        </div>
        <div class="modal-footer justify-content-center border-0">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
@endif

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Manage Admins</h5>
    <div class="d-flex gap-2">
      <form method="GET" action="{{ route('admin.manage.admins') }}" class="d-flex gap-2 mb-0">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name/email..." class="form-control form-control-sm" style="min-width: 200px;">
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
      </form>
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAdminModal">
        <i class="bx bx-plus me-1"></i> Add Admin
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
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($admins as $index => $admin)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $admin->name }}</td>
          <td>{{ $admin->email }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editAdminModal{{ $admin->id }}">
                  <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
                <form action="{{ route('admin.manage.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Delete this admin?')">
                  @csrf @method('DELETE')
                  <button class="dropdown-item text-danger">
                    <i class="bx bx-trash me-1"></i> Delete
                  </button>
                </form>
              </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editAdminModal{{ $admin->id }}" tabindex="-1">
              <div class="modal-dialog">
                <form method="POST" action="{{ route('admin.manage.admins.update', $admin->id) }}" class="modal-content">
                  @csrf @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Name <span class="text-danger">*</span></label>
                      <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Email <span class="text-danger">*</span></label>
                      <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="4">No admins found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($admins->hasPages())
    <div class="card-footer">
      {{ $admins->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
  @endif
</div>

<!-- Modal Add Admin -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.manage.admins.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add Admin</h5>
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
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>

{{-- Auto show success modal --}}
@if (session('success'))
  @push('scripts')
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      const successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    });
  </script>
  @endpush
@endif

@endsection
