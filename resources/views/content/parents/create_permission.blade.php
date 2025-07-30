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
    <form action="{{ route('parent.permissions.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label for="student_id" class="form-label">Select Child <span class="text-danger">*</span></label>
        <select name="student_id" id="student_id" class="form-control" required>
          <option value="">-- Select --</option>
          @foreach ($students as $student)
            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->nim }})</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label for="type" class="form-label">Leave Type <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-control" required>
          <option value="pesiar">Day Leave</option>
          <option value="ib">Overnight Leave (IB)</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="start_date" class="form-label">Departure Date <span class="text-danger">*</span></label>
        <input type="date" name="start_date" class="form-control" required>
      </div>

      <div class="mb-3" id="endDateGroup">
        <label for="end_date" class="form-label">Return Date (only for IB)</label>
        <input type="date" name="end_date" class="form-control">
      </div>

      <div class="mb-3">
        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
        <textarea name="reason" rows="3" class="form-control" required></textarea>
      </div>

      <div class="mb-3">
        <label for="attachment" class="form-label">Supporting Document (optional)</label>
        <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
      </div>

      <button type="submit" class="btn btn-primary">Submit Leave Request</button>
    </form>
  </div>
</div>

@push('scripts')
<script>
  document.getElementById('type').addEventListener('change', function () {
    const endDateGroup = document.getElementById('endDateGroup');
    if (this.value === 'pesiar') {
      endDateGroup.style.display = 'none';
    } else {
      endDateGroup.style.display = 'block';
    }
  });
  document.getElementById('type').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
