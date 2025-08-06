@extends('layouts/contentParentsLayout')

@section('title', 'Submit Leave Request')

@section('content')
@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
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
  <div class="card-header">
    <h5 class="mb-0">Leave Request Form</h5>
    <small class="text-muted">Fields marked with <span class="text-danger">*</span> are required.</small>
  </div>
  <div class="card-body">
    <form id="leaveForm" action="{{ route('parent.permissions.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label for="student_id" class="form-label">Select Child <span class="text-danger">*</span></label>
        <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
          <option value="">-- Select --</option>
          @foreach ($students as $student)
            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
              {{ $student->name }} ({{ $student->nim }})
            </option>
          @endforeach
        </select>
        @error('student_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="type" class="form-label">Leave Type <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
          <option value="">-- Select Type --</option>
          <option value="pesiar" {{ old('type') == 'pesiar' ? 'selected' : '' }}>Day Leave (Pesiar)</option>
          <option value="ib" {{ old('type') == 'ib' ? 'selected' : '' }}>Overnight Leave (Izin Bermalam)</option>
        </select>
        @error('type')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="start_date" class="form-label">Departure Date <span class="text-danger">*</span></label>
        <input type="date" name="start_date" id="start_date"
               class="form-control @error('start_date') is-invalid @enderror"
               required min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}">
        @error('start_date')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3" id="endDateGroup">
        <label for="end_date" class="form-label">Return Date <span class="text-danger">*</span></label>
        <input type="date" name="end_date" id="end_date"
               class="form-control @error('end_date') is-invalid @enderror"
               min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}">
        @error('end_date')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
        <textarea name="reason" rows="3" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason') }}</textarea>
        @error('reason')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="attachment" class="form-label">Attachment (Optional)</label>
        <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror">
        @error('attachment')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="alert alert-danger d-none" id="formError"></div>

      <button type="submit" class="btn btn-primary">Submit Leave Request</button>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const typeSelect = document.getElementById('type');
  const startDateInput = document.getElementById('start_date');
  const endDateInput = document.getElementById('end_date');
  const form = document.getElementById('leaveForm');

  const isPastDate = (dateStr) => {
    const input = new Date(dateStr);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return input < today;
  };

  const syncEndDate = () => {
    if (typeSelect.value === 'pesiar') {
      endDateInput.value = startDateInput.value;
      endDateInput.readOnly = true;
    } else {
      endDateInput.readOnly = false;
    }
  };

  typeSelect.addEventListener('change', syncEndDate);

  startDateInput.addEventListener('input', () => {
    if (typeSelect.value === 'pesiar') {
      endDateInput.value = startDateInput.value;
    }
    endDateInput.min = startDateInput.value;
  });

  // Call once saat load
  syncEndDate();
});
</script>
@endpush
@endsection
