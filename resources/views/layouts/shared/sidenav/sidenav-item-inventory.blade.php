{{-- Dashboard --}}
<li class="side-nav-item">
 <a href="{{ route('inventory.index') }}"
  class="side-nav-link {{ request()->routeIs('inventory.index.*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="circle-gauge"></i></span>
  <span class="menu-text" data-lang="dashboard">Dashboard</span>
 </a>
</li>

{{-- Blood Stock --}}
<li class="side-nav-item">
 <a href="{{ route('inventory.blood-stock.index') }}"
  class="side-nav-link {{ request()->routeIs('inventory.blood-stock.*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="folder-heart"></i></span>
  <span class="menu-text" data-lang="blood-stock">Blood Stock</span>
 </a>
</li>

{{-- Stock In --}}
<li class="side-nav-item">
 <a href="{{ route('inventory.stock-in.index') }}"
  class="side-nav-link {{ request()->routeIs('inventory.stock-in.*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="heart-plus"></i></span>
  <span class="menu-text" data-lang="stock-in">Stock In</span>
 </a>
</li>

{{-- Stock Out --}}
<li class="side-nav-item">
 <a href="{{ route('inventory.stock-out.index') }}"
  class="side-nav-link {{ request()->routeIs('inventory.stock-out.*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="heart-minus"></i></span>
  <span class="menu-text" data-lang="stock-out">Stock Out</span>
 </a>
</li>

{{-- History Order --}}
<li class="side-nav-item">
 <a href="{{ route('inventory.history-order.index') }}"
  class="side-nav-link {{ request()->routeIs('inventory.history-order.*') ? 'active' : '' }}">
  <span class="menu-icon"><i data-lucide="file-heart"></i></span>
  <span class="menu-text" data-lang="history-order">History Order</span>
 </a>
</li>