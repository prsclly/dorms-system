@extends('layouts/contentNavbarLayout')

@section('title', 'Manage Parents')

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
    <h5 class="mb-0">Manage Parents</h5>
    <div class="d-flex gap-2">
      <form method="GET" action="{{ route('admin.manage.parents') }}" class="d-flex gap-2 mb-0">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name/email..." class="form-control form-control-sm" style="min-width: 200px;">
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
      </form>
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addParentModal">
        <i class="bx bx-plus me-1"></i> Add Parent
      </button>
    </div>
  </div>

  <div class="table-responsive text-nowrap">
  <table class="table text-center">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Children</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody class="table-border-bottom-0">
      @forelse ($parents as $index => $parent)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $parent->name }}</td>
        <td>{{ $parent->email }}</td>
        <td>
          @foreach ($parent->students as $student)
            <span class="badge bg-label-primary me-1">{{ $student->name }}</span>
          @endforeach
        </td>
        <td>
          <div class="dropdown">
            <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editParentModal{{ $parent->id }}">
                <i class="bx bx-edit-alt me-1"></i> Edit
              </a>
              <form action="{{ route('admin.manage.parents.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('Delete this parent?');">
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
      <tr><td colspan="5">No parents found.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Modal Edit Dipindah ke Luar Tabel --}}
@foreach ($parents as $parent)
<div class="modal fade" id="editParentModal{{ $parent->id }}" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.manage.parents.update', $parent->id) }}" class="modal-content">
      @csrf @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Parent</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-start">
        <div class="mb-3">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input name="name" type="text" class="form-control" value="{{ $parent->name }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input name="email" type="email" class="form-control" value="{{ $parent->email }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Select Children <span class="text-danger">*</span></label>
          <input type="text" id="search-student-edit-{{ $parent->id }}" class="form-control mb-2" placeholder="Search student...">
          <div class="form-check d-flex flex-column gap-1 student-checkbox-list-edit-{{ $parent->id }}">
            @foreach ($allStudents as $student)
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                  {{ in_array($student->id, $parent->students->pluck('id')->toArray()) ? 'checked' : '' }}>
                {{ $student->name }} ({{ $student->nim }})
              </label>
            @endforeach
          </div>
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

<div class="card-footer text-center text-muted">
  Showing {{ $parents->count() }} of {{ $parents->total() }} entries
</div>

<!-- Add Modal -->
<div class="modal fade" id="addParentModal" tabindex="-1" aria-labelledby="addParentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.manage.parents.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add Parent</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input name="name" type="text" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input name="email" type="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Select Children <span class="text-danger">*</span></label>
          <input type="text" id="search-student-add" class="form-control mb-2" placeholder="Search student...">
          <div class="form-check d-flex flex-column gap-1 student-checkbox-list-add">
            @foreach ($students as $student)
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->id }}">
                {{ $student->name }} ({{ $student->nim }})
              </label>
            @endforeach
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Add Parent</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('vendor-script')
  <script>
    // FUNGSI FILTER SEARCH
    function filterCheckboxList(inputId, listClass) {
      const input = document.getElementById(inputId);
      input.addEventListener('keyup', function () {
        const filter = input.value.toLowerCase();
        const checkboxes = document.querySelectorAll(.${listClass} label);
        checkboxes.forEach(label => {
          const text = label.textContent.toLowerCase();
          label.style.display = text.includes(filter) ? 'block' : 'none';
        });
      });
    }

    // JALANKAN SAAT DOM SIAP
    document.addEventListener('DOMContentLoaded', function () {
      filterCheckboxList('search-student-add', 'student-checkbox-list-add');

      @foreach ($parents as $parent)
        filterCheckboxList('search-student-edit-{{ $parent->id }}', 'student-checkbox-list-edit-{{ $parent->id }}');
      @endforeach
    });
  </script>
@endsection
