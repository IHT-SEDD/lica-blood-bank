{{-- Dashboard --}}
<li class="side-nav-item {{ request()->routeIs('inventory.index.*') ? 'active' : '' }}">
  <a href="{{ route('inventory.index') }}"
    class="side-nav-link {{ request()->routeIs('inventory.index.*') ? 'active' : '' }}">
    <span class="menu-icon"><i data-lucide="circle-gauge"></i></span>
    <span class="menu-text">{{ __('Dashboard') }}</span>
  </a>
</li>

{{-- Blood Stock --}}
<li class="side-nav-item {{ request()->routeIs('inventory.blood-stock.*') ? 'active' : '' }}">
  <a href="{{ route('inventory.blood-stock.index') }}"
    class="side-nav-link {{ request()->routeIs('inventory.blood-stock.*') ? 'active' : '' }}">
    <span class="menu-icon"><i data-lucide="folder-heart"></i></span>
    <span class="menu-text">{{ __('Blood Stock') }}</span>
    <i data-lucide="circle-alert" class="menu-icon d-none text-danger fill-danger" id="danger_stock_alert_icon"
      data-bs-title="Some Stock in Danger Quantity" data-bs-toggle="tooltip" data-bs-trigger="hover"></i>
    <i data-lucide="triangle-alert" class="menu-icon d-none text-warning fill-warning" id="warning_stock_alert_icon"
      data-bs-title="Some Stock in Warning Quantity" data-bs-toggle="tooltip" data-bs-trigger="hover"></i>
  </a>
</li>

{{-- History Order --}}
<li class="side-nav-item {{ request()->routeIs('inventory.history-order.*') ? 'active' : '' }}">
  <a href="{{ route('inventory.history-order.index') }}"
    class="side-nav-link {{ request()->routeIs('inventory.history-order.*') ? 'active' : '' }}">
    <span class="menu-icon"><i data-lucide="file-heart"></i></span>
    <span class="menu-text">{{ __('History Order') }}</span>
  </a>
</li>

{{-- Stock In --}}
<li class="side-nav-item {{ request()->routeIs('inventory.stock-in.*') ? 'active' : '' }}">
  <a href="{{ route('inventory.stock-in.index') }}"
    class="side-nav-link {{ request()->routeIs('inventory.stock-in.*') ? 'active' : '' }}">
    <span class="menu-icon"><i data-lucide="heart-plus"></i></span>
    <span class="menu-text">{{ __('Stock In') }}</span>
  </a>
</li>

{{-- Stock Out --}}
<li class="side-nav-item {{ request()->routeIs('inventory.stock-out.*') ? 'active' : '' }}">
  <a href="{{ route('inventory.stock-out.index') }}"
    class="side-nav-link {{ request()->routeIs('inventory.stock-out.*') ? 'active' : '' }}">
    <span class="menu-icon"><i data-lucide="heart-minus"></i></span>
    <span class="menu-text">{{ __('Stock Out') }}</span>
  </a>
</li>

{{-- Destroy Blood --}}
<li class="side-nav-item {{ request()->routeIs('inventory.destroy-blood.*') ? 'active' : '' }}">
  <a href="{{ route('inventory.destroy-blood.index') }}"
    class="side-nav-link {{ request()->routeIs('inventory.destroy-blood.*') ? 'active' : '' }}">
    <span class="menu-icon"><i data-lucide="heart-off"></i></span>
    <span class="menu-text">{{ __('Blood Destroy') }}</span>
  </a>
</li>