@extends('layouts/contentResidentLayout')

@section('title', 'Resident Dashboard')

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
<style>
  table td, table th {
    font-size: 13px;
  }
</style>

{{-- Success Alert --}}
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

{{-- Error Alert --}}
@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif


<!-- Feedback Filter + Table -->
<div class="card p-3">
 <!-- Filter Form -->
<form method="GET" action="{{ route('catering-feedback-history') }}">
  <div class="row align-items-end g-2 small mb-3">
    <div class="col-md-3">
      <label for="date" class="form-label">Date</label>
      <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
    </div>

    <div class="col-md-3">
      <label for="meal_time" class="form-label">Meal Time</label>
      <select name="meal_time" class="form-select form-select-sm">
        <option value="">All</option>
        @foreach (['Breakfast', 'Lunch', 'Dinner'] as $meal)
          <option value="{{ $meal }}" {{ request('meal_time') == $meal ? 'selected' : '' }}>{{ $meal }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3">
      <label for="category" class="form-label">Category</label>
      <div class="d-flex align-items-center gap-2">
        <select name="category" class="form-select form-select-sm">
          <option value="">All</option>
          @foreach (['Taste', 'Hygiene', 'Food Quality', 'Others'] as $cat)
            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-sm btn-primary px-2 py-1" title="Filter">
          <i class="bx bx-filter-alt"></i>
        </button>
        <a href="{{ route('catering-feedback-history') }}" class="btn btn-sm btn-outline-secondary px-2 py-1" title="Reset">
          <i class="bx bx-reset"></i>
        </a>
      </div>
    </div>
  </div>
</form>


  <!-- Feedback Table -->
  <div class="table-responsive text-nowrap" style="max-height: 400px; overflow-y: auto; border: 1px solid #eee;">
    <table class="table table-striped mb-0">
      <thead>
        <tr>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Date</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Meal Time</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">PIC</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Category</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Description</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Submitted at</th>
          <th style="position: sticky; top: 0; background-color: #fff; z-index: 2;">Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($feedbacks as $feedback)
          <tr>
            <td>{{ \Carbon\Carbon::parse($feedback->date)->format('j M Y') }}</td>
            <td>{{ $feedback->meal->meal_type ?? '-' }}</td>
            <td>{{ $feedback->meal->pic->name ?? '-' }}</td>
            <td>
              <span class="badge
                @switch($feedback->category)
                  @case('Taste') bg-label-primary @break
                  @case('Hygiene') bg-label-success @break
                  @case('Food Quality') bg-label-info @break
                  @default bg-label-secondary
                @endswitch
              me-1">
                {{ $feedback->category }}
              </span>
            </td>
            <td style="max-width: 250px; white-space: normal; word-wrap: break-word;">
              {{ $feedback->message }}
            </td>
            <td>
              {{ \Carbon\Carbon::parse($feedback->created_at)->format('H:i') }} WIB
              @if($feedback->is_edited)
                <span class="badge bg-label-warning ms-1">Edited</span>
              @endif
            </td>


            @if(!$feedback->is_edited && \Carbon\Carbon::parse($feedback->created_at)->diffInMinutes(now()) <= 60)

            <td class="text-nowrap position-relative">
              <!-- Trigger Button (3 dots) -->
              <button class="btn btn-sm p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bx bx-dots-vertical-rounded fs-6"></i>
              </button>

              <!-- Custom Dropdown Menu -->
              <ul class="dropdown-menu dropdown-menu-end mt-1 shadow-sm" style="min-width: 120px;">
                <li>
                  <button class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $feedback->id }}">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                  </button>
                </li>
                <li>
<!-- Trigger Delete Modal -->
<button type="button"
  class="dropdown-item text-danger"
  data-bs-toggle="modal"
  data-bs-target="#deleteConfirmModal"
  data-action="{{ route('feedbackmenu.destroy', $feedback->id) }}">
  <i class="bx bx-trash me-1"></i> Delete
</button>



                </li>
              </ul>
            </td>
            @else
            <td class="text-muted small">Locked</td>
            @endif


<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $feedback->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $feedback->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('feedbackmenu.update', $feedback->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel{{ $feedback->id }}">Edit Feedback</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Info Text -->
          <div class="alert alert-warning small py-2 px-3 mb-3" role="alert">
            <i class="bx bx-info-circle me-1"></i>
            You can only edit your feedback once. After editing, it can no longer be modified or deleted.
          </div>

          <!-- Category -->
          <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
              @foreach (['Taste', 'Hygiene', 'Food Quality', 'Others'] as $cat)
                <option value="{{ $cat }}" {{ $feedback->category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
              @endforeach
            </select>
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="message" class="form-control description-textarea" rows="3" required minlength="25" maxlength="150" data-target="desc-counter-{{ $feedback->id }}">{{ $feedback->message }}</textarea>
            <div class="d-flex justify-content-between">
              <small class="text-muted">Min. 25 & max. 150 characters.</small>
              <small id="desc-counter-{{ $feedback->id }}" class="text-muted">0/150 chars</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
        @empty
          <tr>
            <td colspan="7" class="text-center">You haven't submitted any feedback yet.</td>
          </tr>
        @endforelse

        <!-- ───── Delete Confirmation Modal ───── -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title text-danger">Delete Feedback</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this feedback?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

      </tbody>
    </table>
  </div>
</div>

<!-- Pagination Info -->
<div class="card-body pt-2 d-flex justify-content-between align-items-center">
  <small class="text-muted">
    Showing {{ $feedbacks->count() }} of {{ $feedbacks->total() }} entries
  </small>
  <div>
    {{ $feedbacks->links() }}
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.description-textarea').forEach(function (textarea) {
      const counterId = textarea.dataset.target;
      const counterEl = document.getElementById(counterId);

      const updateCount = () => {
        counterEl.textContent = `${textarea.value.length}/150 chars`;
      };

      textarea.addEventListener('input', updateCount);
      updateCount(); // initial load
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteForm = document.getElementById('deleteForm');

    deleteModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const action = button.getAttribute('data-action');
      deleteForm.setAttribute('action', action);
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
      document.querySelectorAll('.alert').forEach(alert => {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 500);
      });
    }, 3000);
  });
</script>

@endsection
