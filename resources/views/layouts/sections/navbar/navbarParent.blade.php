@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
@endif
@if(isset($navbarDetached) && $navbarDetached == '')
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="{{$containerNav}}">
    @endif

      @if(isset($navbarFull))
      <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{url('/')}}" class="app-brand-link gap-2">
          <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
          <span class="app-brand-text demo menu-text fw-bold text-heading">{{config('variables.templateName')}}</span>
        </a>
      </div>
      @endif

      @if(!isset($navbarHideToggle))
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
          <i class="bx bx-menu bx-md"></i>
        </a>
      </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Hari dan Tanggal -->
        <div class="navbar-nav align-items-center me-auto">
          <div class="nav-item d-flex align-items-center">
            <i class="bx bx-calendar bx-md me-2"></i>
            <span class="fw-semibold">
              {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
          </div>
        </div>

        <!-- User Info -->
        <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online position-relative" style="width: 40px; height: 40px;">
            <img src="{{ asset('assets/img/avatars/7.png') }}" alt class="rounded-circle w-100 h-100" style="object-fit: cover;">
          </div>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="javascript:void(0);">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="{{ asset('assets/img/avatars/7.png') }}" alt class="w-px-40 h-auto rounded-circle">
                  </div>
                </div>
                <div class="flex-grow-1">
                  @php
                    $user = Auth::guard('parent')->user();
                  @endphp
                  <h6 class="mb-0">{{ $user->name }}</h6>
                  <small class="text-muted text-capitalize">Parent</small>
                </div>
              </div>
            </a>
          </li>
          <li><div class="dropdown-divider my-1"></div></li>
          <li>
            <form id="logout-form" action="{{ route('parent.logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
            <a href="#" class="dropdown-item"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
            </a>
          </li>
        </ul>
      </div>

      @if(!isset($navbarDetached))
    </div>
    @endif
</nav>
<!-- / Navbar -->
