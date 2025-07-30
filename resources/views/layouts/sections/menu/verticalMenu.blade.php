<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <style>
    .pointer-events-none {
      pointer-events: none !important;
      cursor: not-allowed;
    }
  </style>

  <!-- ! Hide app brand if navbar-full -->
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-logo demo">@include('_partials.macros', ["width" => 25, "withbg" => 'var(--bs-primary)'])</span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('variables.templateName') }}</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @php
      $userRole = session('user_role', 'guest');
      $currentRouteName = Route::currentRouteName();
      $currentPath = request()->path(); // ✅ fix: define current path here

      $adminOnlySlugs = [
        'pages-account-settings',
        'pages-manage-residents',
        'cards-basic',
        'tables-basic',
        'tables-feedback-reports',
        'menu_list',
        'feedback',
        'student-point',
        'manage-pic',
         'admin.permissions'
      ];

      $technicianOnlySlugs = [
        'technician-tasks',
        'technician-history'
      ];
    @endphp

    @foreach ($menuData[0]->menu as $menu)
      @php
        $slug = $menu->slug ?? '';
        $role = $menu->role ?? null;
        $isRestricted = false;

        if ($userRole === 'admin' && (in_array($slug, $technicianOnlySlugs) || $role === 'technician')) {
          $isRestricted = true;
        }

        if ($userRole === 'technician' && (in_array($slug, $adminOnlySlugs) || $role === 'admin')) {
          $isRestricted = true;
        }

        // Check if active
        $activeClass = '';
        if (isset($menu->route) && Route::is($menu->route)) {
          $activeClass = 'active';
        } elseif (isset($menu->url) && trim($menu->url, '/') === $currentPath) {
          $activeClass = 'active';
        } elseif (isset($menu->submenu)) {
          foreach ($menu->submenu as $sub) {
            if ((isset($sub->route) && Route::is($sub->route)) || (isset($sub->url) && trim($sub->url, '/') === $currentPath)) {
              $activeClass = 'active open';
              break;
            }
          }
        }
      @endphp

      {{-- menu header --}}
      @if (isset($menu->menuHeader))
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
      @else
        {{-- main menu item --}}
        <li class="menu-item {{ $activeClass }}">
          <a href="{{ !$isRestricted ? url($menu->url ?? 'javascript:void(0);') : 'javascript:void(0);' }}"
            class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }} {{ $isRestricted ? 'text-muted pointer-events-none' : '' }}"
            @if ($isRestricted) onclick="return false;" @endif
            @if (isset($menu->target) && !empty($menu->target)) target="_blank" @endif>

            @isset($menu->icon)
              <i class="{{ $menu->icon }}"></i>
            @endisset
            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
            @isset($menu->badge)
              <div class="badge rounded-pill bg-{{ $menu->badge[0] }} text-uppercase ms-auto">{{ $menu->badge[1] }}</div>
            @endisset
          </a>

          {{-- submenu --}}
          @isset($menu->submenu)
            @include('layouts.sections.menu.submenu', [
              'menu' => $menu->submenu,
              'adminOnlySlugs' => $adminOnlySlugs,
              'technicianOnlySlugs' => $technicianOnlySlugs
            ])
          @endisset
        </li>
      @endif
    @endforeach
  </ul>
</aside>
