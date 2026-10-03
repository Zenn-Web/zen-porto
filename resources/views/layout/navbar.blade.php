<nav class="custom-navbar fixed inset-x-0 top-0 z-[1030] flex flex-wrap items-center justify-between lg:flex-nowrap lg:justify-start"
     x-data="navMenu"
     @nav-close.window="close()">
    <div class="container flex flex-wrap items-center justify-between lg:flex-nowrap">
        <a class="brand-logo py-[0.3125rem] me-4 whitespace-nowrap no-underline" href="/#home">
            Zenifen<span class="dot">.</span>
        </a>

        <!-- HAMBURGER CUSTOM (Animated) -->
        <button class="custom-toggler lg:hidden" type="button"
                @click="toggle()"
                aria-controls="mainNavbar"
                aria-expanded="false"
                :aria-expanded="open.toString()"
                aria-label="Toggle navigation">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        {{-- No Bootstrap `collapse` class: Tailwind also ships a `.collapse` utility (visibility: collapse). --}}
        <div class="navbar-collapse basis-full grow items-center lg:basis-auto" id="mainNavbar" x-ref="menu"
             style="display: none"
             :class="{ 'show': open }"
             :style="{ display: mounted ? 'block' : 'none' }">
            <ul class="nav-links-group flex flex-col lg:flex-row ps-0 list-none">

                <li class="nav-item">
                    <a class="nav-link lg:block no-underline" href="/#home">
                        <i class="bi bi-house lg:hidden"></i>
                        <span data-i18n-id="{{ __('portfolio.nav_home', [], 'id') }}" data-i18n-en="{{ __('portfolio.nav_home', [], 'en') }}">{{ __('portfolio.nav_home') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lg:block no-underline" href="/#about">
                        <i class="bi bi-person lg:hidden"></i>
                        <span data-i18n-id="{{ __('portfolio.nav_about', [], 'id') }}" data-i18n-en="{{ __('portfolio.nav_about', [], 'en') }}">{{ __('portfolio.nav_about') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lg:block no-underline" href="/#skills">
                        <i class="bi bi-journal-code lg:hidden"></i>
                        <span data-i18n-id="{{ __('portfolio.nav_skills', [], 'id') }}" data-i18n-en="{{ __('portfolio.nav_skills', [], 'en') }}">{{ __('portfolio.nav_skills') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lg:block no-underline" href="/#resources">
                        <i class="bi bi-grid lg:hidden"></i>
                        <span data-i18n-id="{{ __('portfolio.nav_projects', [], 'id') }}" data-i18n-en="{{ __('portfolio.nav_projects', [], 'en') }}">{{ __('portfolio.nav_projects') }}</span>
                    </a>
                </li>
            </ul>

            <div class="nav-actions flex items-center gap-4 lg:gap-6">
                <div class="utility-toggles flex gap-2">
                    <!-- Language Toggle Button -->
                    <button id="lang-toggle" x-data @click="$store.lang.toggle()" class="btn-lang-toggle" aria-label="Switch Language">
                        <span class="lang-label">{{ app()->getLocale() === 'en' ? 'ID' : 'EN' }}</span>
                    </button>

                    <!-- Theme Toggle Button -->
                    <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle Theme"
                        x-data="{
                            theme: localStorage.getItem('theme') || 'light',
                            toggle() {
                                if (!document.getElementById('theme-force-style')) {
                                    const style = document.createElement('style');
                                    style.id = 'theme-force-style';
                                    style.textContent = 'html.theme-switching, html.theme-switching *, html.theme-switching *::before, html.theme-switching *::after { transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, opacity 0.3s ease, box-shadow 0.3s ease !important; transition-delay: 0s !important; animation: none !important; animation-delay: 0s !important; }';
                                    document.head.appendChild(style);
                                }
                                document.documentElement.classList.add('theme-switching');
                                document.documentElement.offsetHeight;
                                this.theme = this.theme === 'light' ? 'dark' : 'light';
                                document.documentElement.setAttribute('data-theme', this.theme);
                                localStorage.setItem('theme', this.theme);
                                setTimeout(() => document.documentElement.classList.remove('theme-switching'), 300);
                            }
                        }"
                        x-init="document.documentElement.setAttribute('data-theme', theme)"
                        @click="toggle()">
                        <i class="bi bi-sun-fill sun-icon"></i>
                        <i class="bi bi-moon-fill moon-icon"></i>
                    </button>
                </div>

                <a href="/#contact" class="btn-contact-me">
                    <i class="bi bi-chat-dots me-2 lg:hidden"></i>
                    <span data-i18n-id="{{ __('portfolio.nav_contact', [], 'id') }}" data-i18n-en="{{ __('portfolio.nav_contact', [], 'en') }}">{{ __('portfolio.nav_contact') }}</span>
                </a>
            </div>
        </div>
    </div>
</nav>