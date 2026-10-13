<li class="side-nav-item">
 <a data-bs-toggle="collapse" href="#sidebarApis" aria-expanded="false" aria-controls="sidebarApis"
  class="side-nav-link {{ request()->Is('master*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="notebook-text"></i></span>
  <span class="menu-text">SIMRS</span>
  <span class="menu-arrow"></span>
 </a>

 <div class="collapse" id="sidebarApis">
  <ul class="sub-menu">
   <li class="side-nav-item">
    <a href="#" class="side-nav-link">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text">{{ Str::headline('New Blood Request') }}</span>
    </a>
   </li>
   <li class="side-nav-item">
    <a href="#" class="side-nav-link">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text">{{ Str::headline('Send Result') }}</span>
    </a>
   </li>
  </ul>
 </div>
</li>