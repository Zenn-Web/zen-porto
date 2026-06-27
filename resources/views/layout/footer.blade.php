<footer class="footer-section pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Column 1: Brand & Tagline -->
            <div class="col-lg-5 col-md-6 col-sm-12 text-center text-md-start mb-5 mb-md-0">
                <a class="footer-brand mb-3 d-inline-block" href="#home">
                    Zenifen<span class="dot">.</span>
                </a>
                <p class="footer-tagline mb-4 mx-auto mx-md-0" data-i18n="footer.tagline">
                    "Ini bukan tentang kamu tidak bisa melakukannya. Ini tentang kamu tidak melakukannya" - Zen
                </p>
                <div class="footer-socials d-flex gap-3 mt-4 justify-content-center justify-content-md-start">
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

            <!-- Column 2: Navigation Links -->
            <div class="col-lg-3 col-md-6 col-sm-12 text-center text-md-start mb-5 mb-md-0">
                <h6 class="footer-heading mb-4" data-i18n="footer.navigation">NAVIGASI</h6>
                <ul class="footer-nav-links list-unstyled mb-0 d-flex flex-column gap-3 align-items-center align-items-md-start">
                    <li><a href="#home" data-i18n="footer.home">Beranda</a></li>
                    <li><a href="#about" data-i18n="footer.about">Tentang</a></li>
                    <li><a href="#resources" data-i18n="footer.projects">Proyek</a></li>
                    <li><a href="#skills" data-i18n="footer.skills">Keahlian</a></li>
                    <li><a href="#contact" data-i18n="footer.contact">Kontak</a></li>
                </ul>
            </div>

            <!-- Column 3: Current Status & Image Widget -->
            <div class="col-lg-4 col-md-12 col-sm-12 d-flex flex-column justify-content-center align-items-center mt-2 mt-lg-0">
                <div class="position-relative overflow-hidden rounded shadow-sm mb-2" style="width: 240px; aspect-ratio: 16/9; border: 1px solid rgba(255,255,255,0.1);">
                    <!-- Image -->
                    <img src="{{ asset('img/focus-man.png') }}" alt="Focus" class="img-fluid w-100 h-100" style="object-fit: cover;">
                    <!-- Hover Overlay -->
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0, 0, 0, 0.7); opacity: 0; transition: opacity 0.3s ease; cursor: default;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                        <span class="text-white fw-bold" style="letter-spacing: 1px; font-size: 0.9rem;" data-i18n="footer.still_making">Masih Membuat Website</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="footer-divider mb-4">

        <!-- Copyright Row -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
            <p class="footer-copyright mb-0">
                &copy; {{ date('Y') }} Zenifen. <span data-i18n="footer.rights">Seluruh hak dilindungi.</span>
            </p>
            <p class="footer-handcrafted mb-0">
                Made by Zen 
            </p>
        </div>
    </div>
</footer>