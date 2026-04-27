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
   @foreach(config('master') as $key => $item)
   <li class="side-nav-item">
    <a href="{{ route('master.index', $key) }}"
     class="side-nav-link {{ request()->is('master/'.$key.'*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text" data-lang="{{ $key }}">{{ Str::headline($key) }}</span>
    </a>
   </li>
   @endforeach
  </ul>
 </div>
</li>