<header class="app-topbar-welcome">
    <div class="container-fluid topbar-welcome-menu">
        <div class="d-flex align-items-center justify-content-center gap-2">
            {{-- Logout button --}}
            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button href="{{ route('logout') }}" class="topbar-item px-2 ms-2 btn btn-dark"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="ti ti-logout-2 me-2 fs-19 align-middle"></i>
                    <span class="align-middle">{{ __('Logout') }}</span>
                </button>
            </form>
            @endauth

            {{-- Login button --}}
            @guest
            <a href="{{ route('login') }}" class="topbar-item d-lg-flex">
                <i class="ti ti-login me-2 fs-19 align-middle"></i>
                <span class="align-middle">{{ __('Login') }}</span>
            </a>
            @endguest
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Language Button -->
            <div class="topbar-welcome-item">
                <div class="dropdown">
                    <button class="topbar-welcome-link fw-semibold" data-bs-toggle="dropdown" data-bs-offset="0,19"
                        type="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('assets/images/flags/' . (app()->getLocale() === 'id' ? 'id' : 'us') . '.svg') }}"
                            alt="flag" class="w-100 rounded me-2" height="18" id="selected-language-image">
                        <span id="selected-language-code">{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0);" class="dropdown-item language-switcher" data-lang="en"
                            data-flag="{{ asset('assets/images/flags/us.svg') }}" title="English">
                            <img src="{{ asset('assets/images/flags/us.svg') }}" alt="English" class="me-1 rounded"
                                height="18">
                            <span class="align-middle">English</span>
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item language-switcher" data-lang="id"
                            data-flag="{{ asset('assets/images/flags/id.svg') }}" title="Indonesia">
                            <img src="{{ asset('assets/images/flags/id.svg') }}" alt="Indonesia" class="me-1 rounded"
                                height="18">
                            <span class="align-middle">Indonesia</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Dark / Light Mode --}}
            <div class="topbar-welcome-item d-none d-sm-flex">
                <button class="topbar-welcome-link" id="light-dark-mode" type="button">
                    <i data-lucide="moon" class="fs-xxl mode-light-moon"></i>
                    <i data-lucide="sun" class="fs-xxl mode-light-sun"></i>
                </button>
            </div>

            {{-- User Dropdown --}}
            <div class="topbar-welcome-item nav-user">
                <div class="dropdown">
                    <a class="topbar-link dropdown-toggle drop-arrow-none px-2 text-center" data-bs-toggle="dropdown"
                        data-bs-offset="0,13" href="#!" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('assets/images/profile.png') }}" width="30"
                            class="rounded-circle d-flex align-items-center justify-content-center" alt="user-image">
                    </a>

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

                        {{-- Lock Screen --}}
                        <form method="POST" action="{{ route('lock') }}">
                            @csrf
                            <button href="{{ route('lock') }}" class="dropdown-item"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="ti ti-lock me-2 fs-17 align-middle"></i>
                                <span class="align-middle">{{ __('Lock Screen') }}</span>
                            </button>
                        </form>
                        @endauth


                        @guest
                        {{-- Header --}}
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome!</h6>
                        </div>

                        <a href="{{ route('login') }}" class="dropdown-item">
                            <i class="ti ti-login me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Login</span>
                        </a>

                        <a href="{{ route('register') }}" class="dropdown-item">
                            <i class="ti ti-user-plus me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Register</span>
                        </a>
                        @endguest
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>

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