@extends('layouts/contentNavbarLayout')

@section('title', 'Manage PIC')

@section('vendor-style')
  @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
  @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('content')
<div class="container">
  <h4 class="mb-4 fw-bold">PIC Database - Add, Edit, or Delete PIC Records</h4>

  @if(session('success'))
<div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="transition: opacity 0.5s ease;">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Form Add --}}
<div class="card mb-4">
  <div class="card-body">
    <form action="{{ route('pic.store') }}" method="POST">
      @csrf
      <div class="row g-2 align-items-center">
        <div class="col-md-2">
          <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" required>
        </div>
        <div class="col-md-3">
          <input type="email" name="email" class="form-control form-control-sm" placeholder="Email">
        </div>
        <div class="col-md-2">
          <input type="text" name="phone" class="form-control form-control-sm" placeholder="Phone">
        </div>
        <div class="col-md-2">
          <select name="status" class="form-select form-select-sm" required>
            <option value="active" selected>Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-primary btn-sm w-100">+ Add PIC</button>
        </div>
      </div>
    </form>
  </div>
</div>


{{-- Table --}}
<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mb-0">
      <thead class="text-center">
  <tr>
    <th style="width: 160px;">Name</th>
    <th style="width: 250px;">Email</th> <!-- Lebarin -->
    <th style="width: 175px;">Phone</th>
    <th style="width: 145px;">Status</th>
    <th style="width: 140px;">Actions</th>
  </tr>
</thead>

        <tbody>
  @foreach($pics as $pic)
  <tr>
    <form action="{{ route('pic.update', $pic->id) }}" method="POST" class="d-flex align-items-center">
      @csrf
      <td>
        <input type="text" name="name" value="{{ $pic->name }}" class="form-control form-control-sm" required>
      </td>
      <td>
        <input type="email" name="email" value="{{ $pic->email }}" class="form-control form-control-sm">
      </td>
      <td>
        <input type="text" name="phone" value="{{ $pic->phone }}" class="form-control form-control-sm">
      </td>
      <td>
        <select name="status" class="form-select form-select-sm">
          <option value="active" {{ $pic->status === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ $pic->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </td>
      <td class="d-flex gap-2 align-items-center">
        <button class="btn btn-sm btn-success">Update</button>
    </form>


            <!-- Tombol Delete -->
        <button type="button" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center"
        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $pic->id }}" style="width: 32px; height: 32px;">
        <i class="bx bx-trash"></i>
        </button>


            <!-- Modal Delete -->
            <div class="modal fade" id="deleteModal{{ $pic->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $pic->id }}" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $pic->id }}">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    Are you sure you want to delete PIC <strong>{{ $pic->name }}</strong>?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('pic.destroy', $pic->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

              </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@section('page-script')
<script>
  setTimeout(() => {
    const alert = document.getElementById('success-alert');
    if (alert) {
      alert.style.opacity = '0';  // Mulai fade out
      // Setelah transisi 0.5 detik selesai, hapus elemen
      setTimeout(() => alert.remove(), 500);
    }
  }, 3000);
</script>
@endsection
