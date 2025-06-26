
@extends('layouts/contentNavbarLayout')

@section('title', 'List Menu')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Kindly manage the daily meal entries using the tables below.</h5>

  <form method="GET" action="{{ route('menu_list') }}" class="d-flex align-items-center gap-3">
  <input type="week" name="week"
       value="{{ $weekStart->format('Y-\\WW') }}"
       class="form-control form-control-sm" style="max-width:160px;">
      <button class="btn btn-primary btn-sm" style="min-width: 90px;" type="submit">Show</button>
      <button type="button" class="btn btn-primary btn-sm" style="min-width: 130px;" data-bs-toggle="modal" data-bs-target="#entriesModal">New Entries</button>
  </form>
</div>

<hr class="my-3">

@php
    $picColors = [
        'Bu Sri' => 'badge bg-label-info me-1',    // Biru
        'Pak Dafio' => 'badge bg-label-success me-1', // Hijau
        'Pak Sigit' => 'badge bg-label-primary me-1', // Kuning
    ];
@endphp

@forelse($mealsByDate as $date => $meals)
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}
      </h5>

      <!-- Tombol titik tiga (dropdown) -->
      <div class="dropdown">
        <button type="button" class="btn p-0" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
          <a class="dropdown-item" href="#"
           data-bs-toggle="modal"
           data-bs-target="#addRowModal"
           data-date="{{ $date }}">
           <i class="bx bx-plus me-1"></i> Add New Meal
          </a>
          </li>

        </ul>
      </div>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Meal Time</th>
            <th>Time</th>
            <th>Menu</th>
            <th>PIC</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($meals as $meal)
            @php
              $colorClass = $picColors[$meal->pic->name ?? ''] ?? 'badge-secondary';
            @endphp
            <tr>
              <td>{{ $meal->meal_type }}</td>
              <td>{{ $meal->time }}</td>
              <td>{{ $meal->menu_description }}</td>
              <td>
                <span class="badge {{ $colorClass }}">
                  {{ $meal->pic->name ?? '—' }}
                </span>
              </td>
              <td>
                <button class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal"
                        data-id="{{ $meal->id }}"
                        data-meal-type="{{ $meal->meal_type }}"
                        data-time="{{ $meal->time }}"
                        data-description="{{ e($meal->menu_description) }}"
                        data-pic-id="{{ $meal->pic_id }}">
                  <i class="bx bx-edit"></i>
                </button>

                <button type="button" class="btn btn-sm btn-outline-danger"
        onclick="confirmDelete('{{ route('catering-daily-menu.destroy', $meal->id) }}')">
  <i class="bx bx-trash"></i>
</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@empty
  <div class="alert alert-warning">
    No entries for {{ $weekStart->isoFormat('D MMM') }} – {{ $weekEnd->isoFormat('D MMM YYYY') }}. Kindly update new entries soon.
  </div>
@endforelse
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editForm">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Meal Entry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">

          <div class="mb-3">
            <label for="edit-meal-type" class="form-label">Meal Time</label>
            <select name="meal_type" id="edit-meal-type" class="form-select" required>
              <option value="Breakfast">Breakfast</option>
              <option value="Lunch">Lunch</option>
              <option value="Dinner">Dinner</option>
            </select>
          </div>

          <div class="mb-3">
  <label for="edit-time" class="form-label">Time</label>
  <select class="form-select" name="time" id="edit-time" required>
  <option value="7:00 - 7:30">7:00 - 7:30</option>
  <option value="12:00 - 12:30">12:00 - 12:30</option>
  <option value="18:30 - 19:00">18:30 - 19:00</option>
</select>

</div>

          <div class="mb-3">
            <label for="edit-menu" class="form-label">Menu Description</label>
            <textarea class="form-control" name="menu_description" id="edit-menu" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label for="edit-pic" class="form-label">PIC</label>
            <select name="pic_id" id="edit-pic" class="form-select">
              <option value="">Choose One</option>
              @foreach($pics as $pic)
                <option value="{{ $pic->id }}">{{ $pic->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ───── New Entries Modal ───── -->
<div class="modal fade" id="entriesModal" tabindex="-1" aria-labelledby="entriesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('catering-daily-menu.storeBulk') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="entriesModalLabel">Add Meal Entries</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          {{-- TANGGAL --}}
          <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required
                   value="{{ now()->toDateString() }}">
          </div>

          {{-- ==== BREAKFAST ==== --}}
          <h6 class="mt-4">Breakfast <small class="text-muted">(07:00 – 07:30)</small></h6>
          <input type="hidden" name="meals[breakfast][meal_type]" value="Breakfast">
          <input type="hidden" name="meals[breakfast][time]"      value="7:00 - 7:30">

          <div class="row g-2 mb-3">
            <div class="col-md-8">
            <input type="text" class="form-control" placeholder="Menu Description" name="meals[breakfast][menu_description]" required>
            </div>
            <div class="col-md-4">
              <select class="form-select" name="meals[breakfast][pic_id]" required>
                <option value="" disabled selected>PIC</option>
                @foreach($pics as $pic)
                  <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- ==== LUNCH ==== --}}
          <h6 class="mt-3">Lunch <small class="text-muted">(12:00 – 12:30)</small></h6>
          <input type="hidden" name="meals[lunch][meal_type]" value="Lunch">
          <input type="hidden" name="meals[lunch][time]"      value="12:00 - 12:30">

          <div class="row g-2 mb-3">
            <div class="col-md-8">
            <input type="text" class="form-control" placeholder="Menu Description" name="meals[lunch][menu_description]" required>
            </div>
            <div class="col-md-4">
              <select class="form-select" name="meals[lunch][pic_id]" required>
                <option value="" disabled selected>PIC</option>
                @foreach($pics as $pic)
                  <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- ==== DINNER ==== --}}
          <h6 class="mt-3">Dinner <small class="text-muted">(18:30 – 19:00)</small></h6>
          <input type="hidden" name="meals[dinner][meal_type]" value="Dinner">
          <input type="hidden" name="meals[dinner][time]"      value="18:30 - 19:00">

          <div class="row g-2">
            <div class="col-md-8">
            <input type="text" class="form-control" placeholder="Menu Description" name="meals[dinner][menu_description]" required>
            </div>
            <div class="col-md-4">
              <select class="form-select" name="meals[dinner][pic_id]" required>
                <option value="" disabled selected>PIC</option>
                @foreach($pics as $pic)
                  <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ───── Delete Confirmation Modal ───── -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title text-danger">Delete Entry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this meal entry?</p>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ───── Add Row Modal ───── -->
<div class="modal fade" id="addRowModal" tabindex="-1" aria-labelledby="addRowModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('catering-daily-menu.storeSingle') }}">
      @csrf
      <input type="hidden" name="date" id="add-date">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addRowModalLabel">Add New Meal Entry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="add-meal-type" class="form-label">Meal Time</label>
            <select name="meal_type" id="add-meal-type" class="form-select" required>
              <option value="Breakfast">Breakfast</option>
              <option value="Lunch">Lunch</option>
              <option value="Dinner">Dinner</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="add-time" class="form-label">Time</label>
            <select name="time" id="add-time" class="form-select" required>
              <option value="7:00 - 7:30">7:00 - 7:30</option>
              <option value="12:00 - 12:30">12:00 - 12:30</option>
              <option value="18:30 - 19:00">18:30 - 19:00</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="add-menu" class="form-label">Menu Description</label>
            <textarea class="form-control" name="menu_description" id="add-menu" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label for="add-pic" class="form-label">PIC</label>
            <select name="pic_id" id="add-pic" class="form-select" required>
              <option value="" disabled selected>Choose One</option>
              @foreach($pics as $pic)
                <option value="{{ $pic->id }}">{{ $pic->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </div>
    </form>
  </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const editModal = document.getElementById('editModal');
  editModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const mealId = button.getAttribute('data-id');
    const mealType = button.getAttribute('data-meal-type');
    const time = button.getAttribute('data-time');
    const description = button.getAttribute('data-description');
    const picId = button.getAttribute('data-pic-id');

    // Populate form fields
    editModal.querySelector('#edit-id').value = mealId;
    editModal.querySelector('#edit-meal-type').value = mealType;
    editModal.querySelector('#edit-time').value = time;
    editModal.querySelector('#edit-menu').value = description;
    editModal.querySelector('#edit-pic').value = picId;

    // Ambil query string dari URL saat ini (termasuk week param)
    const currentParams = new URLSearchParams(window.location.search);
    const weekParam = currentParams.get('week');

    // Set form action dengan query week
    const form = document.getElementById('editForm');
    let actionUrl = `/catering-daily-menu/${mealId}`;
    if (weekParam) {
    actionUrl += `?week=${weekParam}`;
}
form.action = actionUrl;
  });
});
</script>

{{-- SCRIPT untuk mem-trigger toast (hanya dirender jika session success) --}}
@if(session('success'))
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const toastEl = document.getElementById('successToast');
    if (toastEl) new bootstrap.Toast(toastEl).show();
  });
</script>
@endif

{{-- SCRIPT untuk mem-trigger toast error --}}
@if(session('error'))
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const toastEl = document.getElementById('errorToast');
    if (toastEl) new bootstrap.Toast(toastEl).show();
  });
</script>
@endif


<script>
  function confirmDelete(actionUrl) {
    const form = document.getElementById('deleteForm');
    form.action = actionUrl;
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    modal.show();
  }
</script>

@endpush

@if(session('success'))
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1055">
    <div id="successToast" class="toast align-items-center text-bg-success border-0"
         role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          {{ session('success') }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
@endif

{{-- Toast Error --}}
@if(session('error'))
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1055">
    <div id="errorToast" class="toast align-items-center text-bg-danger border-0"
         role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          {{ session('error') }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
@endif


<script>
document.addEventListener('DOMContentLoaded', function () {
  const addRowModal = document.getElementById('addRowModal');
  addRowModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const date = button.getAttribute('data-date');
    addRowModal.querySelector('#add-date').value = date;
  });
});
</script>

@endsection
