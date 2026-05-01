{{-- Sidenav Menu :begin --}}
<div class="sidenav-menu">
    {{-- Sidenav menu content :begin --}}
    <div class="scrollbar" data-simplebar>
        {{-- User Info :begin --}}
        @php
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        @endphp
        <div class="sidenav-user text-nowrap border border-dashed rounded-3">
            <a href="#!" class="sidenav-user-name d-flex align-items-center">
                <img src="{{ asset('assets/images/profile.png') }}" width="36" class="rounded-circle me-2 d-flex" alt="user-image">
                <span>
                    <h5 class="my-0 fw-semibold">{{ $user->name ? $user->name : 'Please log in first!' }}</h5>
                    <h6 class="my-0 text-muted">{{ $role ? $role : '' }}</h6>
                </span>
            </a>
        </div>
        {{-- User Info :end --}}

        {{-- Menu :begin --}}
        <ul class="side-nav">
            @if (request()->is('tranfusion*'))
            @include('layouts.shared.sidenav.sidenav-item-tranfusion')
            @endif

            @if (request()->is('inventory*'))
            @include('layouts.shared.sidenav.sidenav-item-inventory')
            @endif

            @if (request()->is('donor*'))
            @include('layouts.shared.sidenav.sidenav-item-donor')
            @endif

            @if(request()->is('master*'))
            @include('layouts.shared.sidenav.sidenav-item-master')
            @endif
        </ul>
        {{-- Menu :end --}}
    </div>
    {{-- Sidenav menu content :end --}}

    {{-- Collapse button :begin --}}
    <div class="menu-collapse-box d-none d-xl-block">
        <button class="button-collapse-toggle">
            <i data-lucide="square-chevron-left" class="align-middle flex-shrink-0"></i> <span>Collapse Menu</span>
        </button>
    </div>
    {{-- Collapse button :end --}}
</div>
{{-- Sidenav Menu :end --}}