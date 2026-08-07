<li class="side-nav-title mt-2">Playground</li>

<li class="side-nav-item">
 <a data-bs-toggle="collapse" href="#sidebarPlayground" aria-expanded="false" aria-controls="sidebarPlayground"
  class="side-nav-link {{ request()->is('playground*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="folder-code"></i></span>
  <span class="menu-text">Playground</span>
  <span class="menu-arrow"></span>
 </a>

 <div class="collapse" id="sidebarPlayground">
  <ul class="sub-menu">
   @php
   // Group semua item config('playground') berdasarkan 'section'
   // (Testing, Fixing, Setting, dst)
   $playgroundSections = collect(config('playground'))
   ->map(fn ($item, $key) => array_merge($item, ['key' => $key]))
   ->groupBy('section');
   @endphp

   <!-- Loop level 1: per section -->
   @foreach ($playgroundSections as $section => $sectionItems)
   @php
   // ID unik untuk collapse section, contoh: sidebarPlaygroundTesting
   $sectionId = 'sidebarPlayground' . Str::studly($section);
   @endphp

   <!-- Menu collapsible untuk tiap section -->
   <li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#{{ $sectionId }}" aria-expanded="false" aria-controls="{{ $sectionId }}"
     class="side-nav-link">
     <span class="menu-text"> {{ Str::headline($section) }}</span>
     <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="{{ $sectionId }}">
     <ul class="sub-menu">
      <!-- Loop level 2: per group di dalam section (bisa kosong '') -->
      @foreach ($sectionItems->groupBy('group') as $group => $groupItems)
      @if ($group === '')
      <!-- Tidak ada group -> item langsung tampil sebagai link -->
      @foreach ($groupItems as $item)
      <li class="side-nav-item">
       <a href="{{ route($item['route']) }}"
        class="side-nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
        <span class="menu-text">{{ Str::headline($item['label']) }}</span>
       </a>
      </li>
      @endforeach
      @else
      @php
      // ID unik untuk collapse group, contoh: sidebarPlaygroundSettingConfig
      $groupId = $sectionId . Str::studly($group);
      @endphp

      <!-- Ada group -> bungkus dalam collapse tambahan -->
      <li class="side-nav-item">
       <a data-bs-toggle="collapse" href="#{{ $groupId }}" aria-expanded="false" aria-controls="{{ $groupId }}"
        class="side-nav-link">
        <span class="menu-text"> {{ Str::headline($group) }}</span>
        <span class="menu-arrow"></span>
       </a>

       <div class="collapse" id="{{ $groupId }}">
        <ul class="sub-menu">
         <!-- Item-item di dalam group -->
         @foreach ($groupItems as $item)
         <li class="side-nav-item">
          <a href="{{ route($item['route']) }}"
           class="side-nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
           <span class="menu-text">{{ Str::headline($item['label']) }}</span>
          </a>
         </li>
         @endforeach
        </ul>
       </div>
      </li>
      @endif
      @endforeach
     </ul>
    </div>
   </li>
   @endforeach
  </ul>
 </div>
</li>