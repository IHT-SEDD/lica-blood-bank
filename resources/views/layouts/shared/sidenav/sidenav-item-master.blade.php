<li class="side-nav-title mt-2" data-lang="master-title">Master Data</li>

<li class="side-nav-item">
 <a data-bs-toggle="collapse" href="#sidebarMasters" aria-expanded="false" aria-controls="sidebarMasters"
  class="side-nav-link {{ request()->Is('master*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="notebook-text"></i></span>
  <span class="menu-text" data-lang="masters">Masters</span>
  <span class="menu-arrow"></span>
 </a>

 <div class="collapse" id="sidebarMasters">
  <ul class="sub-menu">
   {{-- User --}}
   <li class="side-nav-item">
    <a href="{{ url('/master/user') }}" class="side-nav-link {{ request()->Is('master/user*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text" data-lang="user">Users</span>
    </a>
   </li>

   {{-- Storage --}}
   <li class="side-nav-item">
    <a href="{{ url('/master/storage') }}" class="side-nav-link {{ request()->Is('master/storage*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text" data-lang="storages">Storages</span>
    </a>
   </li>

   {{-- Storage Rack --}}
   <li class="side-nav-item">
    <a href="{{ url('/master/storage-rack') }}"
     class="side-nav-link {{ request()->Is('master/storage-rack*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text" data-lang="storage-racks">Storage Racks</span>
    </a>
   </li>

   {{-- Blood Pack --}}
   <li class="side-nav-item">
    <a href="{{ url('/master/blood-pack') }}"
     class="side-nav-link {{ request()->Is('master/blood-pack*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text" data-lang="blood-packs">Blood Packs</span>
    </a>
   </li>

   {{-- Patients --}}
   <li class="side-nav-item">
    <a href="{{ url('/master/patient') }}" class="side-nav-link {{ request()->Is('master/patient*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text" data-lang="patients">Patients</span>
    </a>
   </li>

   {{-- Insurance --}}
   <li class="side-nav-item">
    <a href="{{ url('/master/insurance') }}"
     class="side-nav-link {{ request()->Is('master/insurance*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text" data-lang="insurances">Insurances</span>
    </a>
   </li>
  </ul>
 </div>
</li>