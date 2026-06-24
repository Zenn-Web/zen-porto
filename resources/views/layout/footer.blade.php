<footer class="footer-section pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Column 1: Brand & Tagline -->
            <div class="col-lg-5 col-md-6 col-sm-12">
                <a class="footer-brand mb-3 d-inline-block" href="#home">
                    Zenifen<span class="dot">.</span>
                </a>
                <p class="footer-tagline mb-4" data-i18n="footer.tagline">
                    "Ini bukan tentang kamu tidak bisa melakukannya. Ini tentang kamu tidak melakukannya" - Zen
                </p>
                <div class="footer-socials d-flex gap-3">
                    <a href="https://www.github.com/Zenn-Web" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
                        <i class="bi bi-github"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="mailto:zenifenagusti70@gmail.com" aria-label="Email">
                        <i class="bi bi-envelope"></i>
                    </a>
                    <a href="https://wa.me/6285174344683" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Column 2: Navigation Links -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <h6 class="footer-heading mb-3" data-i18n="footer.navigation">NAVIGASI</h6>
                <ul class="footer-nav-links list-unstyled mb-0 d-flex flex-column gap-2">
                    <li><a href="#home" data-i18n="footer.home">Beranda</a></li>
                    <li><a href="#about" data-i18n="footer.about">Tentang</a></li>
                    <li><a href="#resources" data-i18n="footer.projects">Proyek</a></li>
                    <li><a href="#skills" data-i18n="footer.skills">Keahlian</a></li>
                    <li><a href="#contact" data-i18n="footer.contact">Kontak</a></li>
                </ul>
            </div>

            <!-- Column 3: Current Status & Lottie -->
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="footer-status-card p-3 d-flex justify-content-center align-items-center">
                    <dotlottie-player 
                        src="https://lottie.host/240d8004-d504-482d-ad7e-4a107671efed/tVAEi0CBbi.json" 
                        background="transparent" 
                        speed="1" 
                        style="width: 150px; height: 150px;" 
                        loop 
                        autoplay>
                    </dotlottie-player>
                </div>
            </div>
        </div>

        <hr class="footer-divider mb-4">

        <!-- Copyright Row -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <p class="footer-copyright mb-0" data-i18n="footer.rights">
                &copy; {{ date('Y') }} Zenifen. Seluruh hak dilindungi.
            </p>
            <p class="footer-handcrafted mb-0">
                Made by Zen The Trader
            </p>
        </div>
    </div>
</footer>