{{-- Archive --}}
<li class="side-nav-item {{ request()->routeIs('blood-transfusion.archive.*') ? 'active' : '' }}">
 <a href="{{ route('blood-transfusion.archive.index') }}"
  class="side-nav-link {{ request()->routeIs('blood-transfusion.archive.*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="archive"></i></span>
  <span class="menu-text">{{ __('Archive') }}</span>
 </a>
</li>