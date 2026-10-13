{{-- API --}}
<li class="side-nav-item {{ request()->routeIs('playground.api.*') ? 'active' : '' }}">
 <a href="{{ route('playground.api.index') }}"
  class="side-nav-link {{ request()->routeIs('playground.api.*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="webhook"></i></span>
  <span class="menu-text">{{ __('API') }}</span>
 </a>
</li>

{{-- Uji Coba --}}
{{-- <li class="side-nav-item {{ request()->routeIs('playground.api.*') ? 'active' : '' }}">
 <a href="{{ route('playground.api.index') }}"
  class="side-nav-link {{ request()->routeIs('playground.api.*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="flask-conical"></i></span>
  <span class="menu-text">{{ __('Uji Coba') }}</span>
 </a>
</li> --}}

{{-- Pengaturan --}}
{{-- <li class="side-nav-item {{ request()->routeIs('playground.api.*') ? 'active' : '' }}">
 <a href="{{ route('playground.api.index') }}"
  class="side-nav-link {{ request()->routeIs('playground.api.*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="settings"></i></span>
  <span class="menu-text">{{ __('Pengaturan') }}</span>
 </a>
</li> --}}