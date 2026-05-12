{{-- Archive --}}
<li class="side-nav-item {{ request()->routeIs('blood-transfusion.archive.*') ? 'active' : '' }}">
 <a href="{{ route('blood-transfusion.archive.index') }}"
  class="side-nav-link {{ request()->routeIs('blood-transfusion.archive.*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="archive"></i></span>
  <span class="menu-text">{{ __('Archive') }}</span>
 </a>
</li>

{{-- Reports --}}
<li class="side-nav-title mt-2">{{ __('Reports') }}</li>

<li class="side-nav-item">
 <a data-bs-toggle="collapse" href="#sidebarReports" aria-expanded="false" aria-controls="sidebarReports"
  class="side-nav-link {{ request()->Is('blood-transfusion/report*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="file-text"></i></span>
  <span class="menu-text">{{ __('Reports') }}</span>
  <span class="menu-arrow"></span>
 </a>

 <div class="collapse" id="sidebarReports">
  <ul class="sub-menu">
   {{-- Incompatible --}}
   <li class="side-nav-item">
    <a href="{{ url('/blood-transfusion/report/incompatible') }}"
     class="side-nav-link {{ request()->is('blood-transfusion/report/incompatible*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text">{{ __('Incompatible') }}</span>
    </a>
   </li>

   {{-- Blood Request --}}
   <li class="side-nav-item">
    <a href="{{ url('/blood-transfusion/report/blood-request') }}"
     class="side-nav-link {{ request()->is('blood-transfusion/report/blood-request*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text">{{ __('Blood Request') }}</span>
    </a>
   </li>
  </ul>
 </div>
</li>