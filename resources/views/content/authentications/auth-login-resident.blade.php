@extends('layouts/blankLayout')

@section('title', 'Resident Login')

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Login Card -->
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros', ["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
              <span class="app-brand-text demo text-heading fw-bold">Resident Portal</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">Welcome Back, Resident! 👋</h4>
          <p class="mb-6">Please sign in to continue.</p>

          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          <form id="formAuthentication" class="mb-6" action="{{ route('resident.login.post') }}" method="POST">
            @csrf
            <div class="mb-4">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Enter your resident email"
                value="{{ old('email') }}"
                required
                autofocus
              >
            </div>

            <div class="mb-4 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  name="password"
                  placeholder="••••••••"
                  required
                />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>

            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </div>
          </form>
        </div>
      </div>
      <!-- /Login Card -->
    </div>
  </div>
</div>
@endsection
