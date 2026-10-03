<footer class="footer-section">
    <div class="mx-auto w-full px-6 md:px-12">

        <!-- Center Stack: Brand Logo -> Inline Navigation Menu -> Social Icons -->
        <div class="flex flex-col items-center justify-center text-center">

            <!-- 1. Brand Logo -->
            <a class="footer-brand mb-4 inline-block" href="/#home">
                Zenifen<span class="dot">.</span>
            </a>

            <!-- 2. Centered Horizontal Navigation Menu -->
            <ul class="footer-nav-inline list-none ps-0 flex flex-wrap justify-center gap-4 md:gap-6 mb-4">
                <li><a href="/#home" data-i18n-id="{{ __('portfolio.footer_home', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_home', [], 'en') }}">{{ __('portfolio.footer_home') }}</a></li>
                <li><a href="/#about" data-i18n-id="{{ __('portfolio.footer_about', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_about', [], 'en') }}">{{ __('portfolio.footer_about') }}</a></li>
                <li><a href="/#skills" data-i18n-id="{{ __('portfolio.footer_skills', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_skills', [], 'en') }}">{{ __('portfolio.footer_skills') }}</a></li>
                <li><a href="/#resources" data-i18n-id="{{ __('portfolio.footer_projects', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_projects', [], 'en') }}">{{ __('portfolio.footer_projects') }}</a></li>
                <li><a href="/#contact" data-i18n-id="{{ __('portfolio.footer_contact', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_contact', [], 'en') }}">{{ __('portfolio.footer_contact') }}</a></li>
            </ul>

            <!-- 3. Social Media Icons -->
            <div class="footer-socials flex gap-4 justify-center mb-6">
                <a href="https://www.github.com/Zenn-Web" target="_blank" rel="noopener noreferrer" class="social-link-rounded github" aria-label="GitHub">
                    <i class="bi bi-github"></i>
                </a>
                <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a" target="_blank" rel="noopener noreferrer" class="social-link-rounded linkedin" aria-label="LinkedIn">
                    <i class="bi bi-linkedin"></i>
                </a>
                <a href="mailto:zenifenagusti70@gmail.com" class="social-link-rounded email" aria-label="Email">
                    <i class="bi bi-envelope"></i>
                </a>
                <a href="https://wa.me/6285174344683" target="_blank" rel="noopener noreferrer" class="social-link-rounded whatsapp" aria-label="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
            </div>

        </div>

        <hr class="footer-divider mb-4">

        <!-- Copyright Row (Full width corner-to-corner) -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-2">
            <p class="footer-copyright mb-0">
                &copy; {{ date('Y') }} Zenifen. <span data-i18n-id="{{ __('portfolio.footer_rights', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_rights', [], 'en') }}">{{ __('portfolio.footer_rights') }}</span>
            </p>
            <p class="footer-handcrafted mb-0">
                Made by Zen
            </p>
        </div>
    </div>
</footer>