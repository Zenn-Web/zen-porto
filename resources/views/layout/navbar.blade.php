<nav class="navbar navbar-expand-lg navbar-light custom-navbar fixed-top">
    <div class="container">
        <a class="navbar-brand brand-logo" href="#home">
            Zenifen<span class="dot">.</span>
        </a>

        <!-- HAMBURGER CUSTOM (Animated) -->
        <button class="navbar-toggler custom-toggler" type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto nav-links-group">

                <li class="nav-item">
                    <a class="nav-link" href="#about">
                        <i class="bi bi-person d-lg-none"></i>
                        <span data-i18n="nav.about">Tentang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#skills">
                        <i class="bi bi-journal-code d-lg-none"></i>
                        <span data-i18n="nav.skills">Keahlian</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#resources">
                        <i class="bi bi-grid d-lg-none"></i>
                        <span data-i18n="nav.projects">Projek</span>
                    </a>
                </li>
            </ul>

            <div class="nav-actions d-flex align-items-center gap-3 gap-lg-4">
                <div class="utility-toggles d-flex gap-2">
                    <!-- Language Toggle Button -->
                    <button id="lang-toggle" class="btn-lang-toggle" aria-label="Switch Language">
                        <span class="lang-label">EN</span>
                    </button>

                    <!-- Theme Toggle Button -->
                    <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle Theme">
                        <i class="bi bi-sun-fill sun-icon"></i>
                        <i class="bi bi-moon-fill moon-icon"></i>
                    </button>
                </div>

                <a href="#contact" class="btn-contact-me">
                    <i class="bi bi-chat-dots me-2 d-lg-none"></i>
                    <span data-i18n="nav.contact">Kontak Saya</span>
                </a>
            </div>
        </div>
    </div>
</nav>