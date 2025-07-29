@php
    $menuJson = file_get_contents(resource_path('menu/verticalMenuParents.json'));
    $menuData = json_decode($menuJson);
@endphp


<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <div class="app-brand demo">
    <a href="{{ route('parent.dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">@include('_partials.macros', ["width" => 25])</span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">Parent</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sam d-flex align-items-center justify-content-center"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)
      @if (isset($menu->menuHeader))
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
      @else
      @php
      $activeClass = '';
      $currentRouteName = Route::currentRouteName();

      if ($currentRouteName === $menu->slug) {
          $activeClass = 'active';
      } elseif (isset($menu->submenu)) {
          foreach ($menu->submenu as $child) {
              if ($currentRouteName === $child->slug) {
                  $activeClass = 'active open';
                  break;
              }
          }
      }
    @endphp


        <li class="menu-item {{ $activeClass }}">
          <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
            class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
            @if (!empty($menu->target)) target="_blank" @endif>
            @isset($menu->icon)
              <i class="{{ $menu->icon }}"></i>
            @endisset
            <div>{{ __($menu->name ?? '') }}</div>
            @isset($menu->badge)
              <div class="badge rounded-pill bg-{{ $menu->badge[0] }} text-uppercase ms-auto">
                {{ $menu->badge[1] }}
              </div>
            @endisset
          </a>

          @isset($menu->submenu)
            @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
          @endisset
        </li>
      @endif
    @endforeach
  </ul>

</aside>
