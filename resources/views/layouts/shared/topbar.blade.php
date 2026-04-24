{{-- Topbar :begin --}}
<header class="app-topbar">
    <div class="container-fluid topbar-menu">
        {{-- Left Side :begin --}}
        <div class="d-flex align-items-center justify-content-center gap-2">
            {{-- Topbar logo :begin --}}
            <div class="logo-topbar">
                <a href="{{ route('welcome') }}" class="logo-dark">
                    <span class="d-flex align-items-center gap-1">
                        <img alt="Logo LICA Blood Bank" class="img-center"
                            src="{{ asset('assets/images/logos/logo-lica-bb.png') }}" height="30" />
                    </span>
                </a>
                <a href="{{ route('welcome') }}" class="logo-light">
                    <span class="d-flex align-items-center gap-1">
                        <img alt="Logo LICA Blood Bank" class="img-center"
                            src="{{ asset('assets/images/logos/logo-lica-bb.png') }}" height="30" />
                    </span>
                </a>
            </div>
            {{-- Topbar logo :end --}}

            {{-- Topbar logo mobile :begin --}}
            <div class="d-lg-none d-flex mx-1">
                <a href="{{ route('welcome') }}">
                    <img alt="Logo LICA Blood Bank" class="img-center"
                        src="{{ asset('assets/images/logos/logo-lica-bb-mini.png') }}" height="28" />
                </a>
            </div>
            {{-- Topbar logo mobile :end --}}

            {{-- Sidebar collapse button mobile --}}
            <button class="button-collapse-toggle d-xl-none">
                <i data-lucide="menu" class="fs-22 align-middle"></i>
            </button>

            {{-- Topbar app version --}}
            <div class="topbar-item d-none d-lg-flex">
                <a href="" class="topbar-link btn shadow-none btn-link px-2 disabled">v1.0.0</a>
            </div>

            {{-- Blood Tranfusion Menu --}}
            @if (!request()->is('blood-tranfusion*'))
            <div class="topbar-item d-none d-lg-flex">
                <a href="{{ route('blood-tranfusion.index') }}" class="topbar-link btn shadow-none btn-link px-2">Blood
                    Tranfusion</a>
            </div>
            @endif

            {{-- Inventory Menu --}}
            @if (!request()->is('inventory*'))
            <div class="topbar-item d-none d-lg-flex">
                <a href="{{ route('inventory.index') }}" class="topbar-link btn shadow-none btn-link px-2">Inventory</a>
            </div>
            @endif

            {{-- Donor Menu --}}
            @if (!request()->is('donor*'))
            <div class="topbar-item d-none d-lg-flex">
                <a href="#!" class="topbar-link btn shadow-none btn-link px-2">Donor</a>
            </div>
            @endif

            {{-- Master Menu --}}
            @if (!request()->is('master*') && auth()->user()->hasRole('superadmin'))
            <div class="topbar-item d-none d-lg-flex">
                {{-- Dropdown Master :begin --}}
                <div class="dropdown">
                    {{-- Dropdown Button --}}
                    <button class="topbar-link btn shadow-none btn-link px-2 dropdown-toggle drop-arrow-none"
                        data-bs-auto-close="true" data-bs-toggle="dropdown" data-bs-offset="0,13" type="button"
                        aria-haspopup="false" aria-expanded="false">
                        Master <i class="ti ti-chevron-down ms-1"></i>
                    </button>

                    {{-- Dropdown Menu :begin --}}
                    <ul class="dropdown-menu">
                        @foreach(config('master') as $key => $item)
                        <li>
                            <a class="dropdown-item {{ request()->is('master/'.$key.'*') ? 'active' : '' }}"
                                href="{{ route('master.index', $key) }}">
                                {{ Str::headline($key) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    {{-- Dropdown Menu :end --}}
                </div>
                {{-- Dropdown Master :end --}}
            </div>
            @endif
        </div>
        {{-- Left Side :end --}}

        <div class="d-flex align-items-center gap-2">
            <!-- Light/Dark Mode Button -->
            <div class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" id="light-dark-mode" type="button">
                    <i data-lucide="moon" class="fs-xxl mode-light-moon"></i>
                    <i data-lucide="sun" class="fs-xxl mode-light-sun"></i>
                </button>
            </div>

            <!-- User Dropdown -->
            <div class="topbar-item nav-user">
                <div class="dropdown">
                    {{-- Dropdown button --}}
                    <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                        data-bs-offset="0,13" href="#!" aria-haspopup="false" aria-expanded="false">
                        <img src="/images/users/user-2.jpg" width="32" class="rounded-circle d-flex" alt="user-image">
                    </a>

                    {{-- Dropdown menu :begin --}}
                    <div class="dropdown-menu dropdown-menu-end">
                        @auth
                        @php
                        $user = auth()->user();
                        $role = $user->load('roles')->name;
                        @endphp

                        {{-- Header --}}
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome back!</h6>
                        </div>

                        {{-- User Info --}}
                        <div class="dropdown-item fw-semibold mb-0">
                            <h5 class="mb-1">{{ $user->name ?? 'Please set your name!' }}</h5>
                            <h6 class="mb-0">Role: {{ $role ?? 'Please set your role!' }}</h6>
                        </div>

                        <div class="dropdown-divider"></div>

                        {{-- Profile --}}
                        <a href="#!" class="dropdown-item">
                            <i class="ti ti-user-circle me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Profile</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        {{-- Lock Screen --}}
                        <form method="POST" action="{{ route('lock') }}">
                            @csrf
                            <button href="{{ route('lock') }}" class="dropdown-item"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class=" ti ti-lock me-2 fs-17 align-middle"></i>
                                <span class="align-middle">Lock Screen</span>
                            </button>
                        </form>

                        {{-- log Out --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="dropdown-item text-danger fw-semibold">
                                <i class="ti ti-logout-2 me-2 fs-17 align-middle"></i>
                                <span class="align-middle">Log Out</span>
                            </button>
                        </form>
                        @endauth


                        @guest
                        {{-- Header --}}
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Please sign in first!</h6>
                        </div>

                        {{-- Login --}}
                        <a href="{{ route('login') }}" class="dropdown-item">
                            <i class="ti ti-login me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Login</span>
                        </a>

                        {{-- Register --}}
                        <a href="{{ route('register') }}" class="dropdown-item">
                            <i class="ti ti-user-plus me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Register</span>
                        </a>
                        @endguest
                    </div>
                    {{-- Dropdown menu :end --}}
                </div>
            </div>
        </div>
    </div>
</header>
{{-- Topbar :end --}}

<script>
    // Skin Dropdown
    document.querySelectorAll('[data-dropdown="custom"]').forEach(dropdown => {
        const trigger = dropdown.querySelector('a[data-bs-toggle="dropdown"], button[data-bs-toggle="dropdown"]');
        const items = dropdown.querySelectorAll('button[data-skin]');

        const triggerImg = trigger.querySelector('[data-trigger-img]');
        const triggerLabel = trigger.querySelector('[data-trigger-label]');

        const config = JSON.parse(JSON.stringify(window.config?? {}));

        const currentSkin = config.skin;

        items.forEach(item => {
            const itemSkin = item.getAttribute('data-skin');
            const itemImg = item.querySelector('img')?.getAttribute('src');
            const itemText = item.querySelector('span')?.textContent.trim();

            // Set active on load
            if (itemSkin === currentSkin) {
                item.classList.add('drop-custom-active');
                if (triggerImg && itemImg) triggerImg.setAttribute('src', itemImg);
                if (triggerLabel && itemText) triggerLabel.textContent = itemText;
            } else {
                item.classList.remove('drop-custom-active');
            }

            // Click handler
            item.addEventListener('click', function () {
                items.forEach(i => i.classList.remove('drop-custom-active'));
                this.classList.add('drop-custom-active');

                const newImg = this.querySelector('img')?.getAttribute('src');
                const newText = this.querySelector('span')?.textContent.trim();

                if (triggerImg && newImg) triggerImg.setAttribute('src', newImg);
                if (triggerLabel && newText) triggerLabel.textContent = newText;

                if (typeof layoutCustomizer !== 'undefined') {
                    layoutCustomizer.changeSkin(itemSkin);
                }
            });
        });
    });
</script>