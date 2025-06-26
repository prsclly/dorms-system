@extends('layouts/contentResidentLayout')

@section('title', 'Resident Profile')

@section('page-script')
@if(session('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger alert-dismissible" role="alert">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const editBtn = document.getElementById('editBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const inputs = document.querySelectorAll('#formProfile input');
    const actionButtons = document.getElementById('actionButtons');
    const passwordInput = document.getElementById('password');

    editBtn.addEventListener('click', () => {
        inputs.forEach(input => input.disabled = false);
        passwordInput.value = '';          // kosongkan biar user isi password baru
        passwordInput.disabled = false;    // enable input password
        actionButtons.classList.remove('d-none');
    });

    cancelBtn.addEventListener('click', () => {
      location.reload();
    });
  });
</script>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Resident Profile</h5>
          <button type="button" id="editBtn" class="btn btn-outline-primary">Edit</button>
        </div>

        <form id="formProfile" method="POST" action="{{ route('resident.profile.update') }}" class="mt-4">
          @csrf
          <div class="row g-4">
            <div class="col-md-6">
              <label for="name" class="form-label">Name</label>
              <input class="form-control" type="text" id="name" name="name" value="{{ $resident->name }}" disabled />
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input class="form-control" type="email" id="email" name="email" value="{{ $resident->email }}" disabled />
            </div>
            <div class="col-md-6">
              <label for="phone_number" class="form-label">Phone Number</label>
              <input class="form-control" type="text" id="phone_number" name="phone_number" value="{{ $resident->phone_number }}" disabled />
            </div>
            <div class="col-md-6">
              <label for="room_number" class="form-label">Room Number</label>
              <input class="form-control" type="text" id="room_number" name="room_number" value="{{ $resident->room_number }}" disabled />
            </div>
            <div class="col-md-6">
              <label for="nim" class="form-label">NIM</label>
              <input class="form-control" type="text" id="nim" name="nim" value="{{ $student->nim ?? '' }}" disabled />
            </div>
            <div class="col-md-6">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input
                  class="form-control"
                  type="password"
                  id="password"
                  name="password"
                  placeholder="Enter new password (optional)"
                  disabled />
              </div>
              @error('password')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>

          <div class="mt-4 d-none" id="actionButtons">
            <button type="submit" class="btn btn-primary me-2">Save Changes</button>
            <button type="button" class="btn btn-outline-secondary" id="cancelBtn">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
