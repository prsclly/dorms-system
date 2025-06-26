@extends('layouts/contentResidentLayout')

@section('title', 'Meal Feedback Form')

@section('content')

      {{-- Success Alert --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      {{-- Error Alert --}}
      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Submission failed:</strong>
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

<div class="container mt-4">
  <div class="card">
    <h5 class="card-header">Meal Feedback Form</h5>
    <div class="card-body">

      <form method="POST" action="{{ route('feedbackmenu.store') }}">
        @csrf

        <!-- Date -->
        <div class="mb-3">
          <label for="date" class="form-label">Date</label>
          <input
            type="date"
            name="date"
            id="date"
            class="form-control"
            value="{{ old('date', \Carbon\Carbon::now()->format('Y-m-d')) }}"
            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
            required
          >
          <small class="text-muted">*You can only report issues related to today's meals.</small>
        </div>

        <!-- Meal Time -->
        <div class="mb-3">
          <label for="meal_time" class="form-label">Meal Time</label>
          <select name="meal_time" id="meal_time" class="form-select" required>
            <option value="" disabled selected>Choose one</option>
            <option value="Breakfast" {{ old('meal_time') == 'Breakfast' ? 'selected' : '' }}>Breakfast</option>
            <option value="Lunch" {{ old('meal_time') == 'Lunch' ? 'selected' : '' }}>Lunch</option>
            <option value="Dinner" {{ old('meal_time') == 'Dinner' ? 'selected' : '' }}>Dinner</option>
          </select>
        </div>

        <!-- Category -->
        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <select name="category" id="category" class="form-select" required>
            <option value="" disabled selected>Choose one</option>
            <option value="Hygiene" {{ old('category') == 'Hygiene' ? 'selected' : '' }}>Hygiene</option>
            <option value="Food Quality" {{ old('category') == 'Food Quality' ? 'selected' : '' }}>Food Quality</option>
            <option value="Taste" {{ old('category') == 'Taste' ? 'selected' : '' }}>Taste</option>
            <option value="Others" {{ old('category') == 'Others' ? 'selected' : '' }}>Others</option>
          </select>
        </div>

        <!-- Description -->
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea
            name="description"
            id="description"
            class="form-control"
            rows="2"
            placeholder="Write your problem here..."
            required
          >{{ old('description') }}</textarea>
        </div>

        <!-- Submit Button -->
        <div class="text-end">
          <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
          <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 500); // Tunggu transisi selesai
      });
    }, 3000); // 3 detik
  });
</script>
@endsection

