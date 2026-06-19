<li class="side-nav-title mt-2">Laporan</li>

<li class="side-nav-item">
 <a data-bs-toggle="collapse" href="#sidebarReport" aria-expanded="false" aria-controls="sidebarReport"
  class="side-nav-link {{ request()->Is('report*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="notebook-text"></i></span>
  <span class="menu-text">Laporan</span>
  <span class="menu-arrow"></span>
 </a>

 <div class="collapse" id="sidebarReport">
  <ul class="sub-menu">
   @foreach(config('report') as $key => $item)
   <li class="side-nav-item">
    <a href="{{ route('report.index', $key) }}"
     class="side-nav-link {{ request()->is('report/'.$key.'*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="dot"></i></span>
     <span class="menu-text" data-lang="{{ $key }}">{{ Str::headline($item['label']) }}</span>
    </a>
   </li>
   @endforeach
  </ul>
 </div>
</li>