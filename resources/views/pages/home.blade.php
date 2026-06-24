@extends('layout.welcome')


@section('content')


    <section id="home" class="hero-classic-section position-relative overflow-hidden">
        <!-- Background Grid Pattern & Ornaments -->
        <div class="hero-classic-bg">
            <div class="classic-grid-pattern"></div>
            
            <!-- Sparkle Star Ornaments (4-Pointed Stars) -->
            <div class="ornament-sparkle sparkle-1">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0L15 9L24 12L15 15L12 24L9 15L0 12L9 9L12 0Z"/></svg>
            </div>
            <div class="ornament-sparkle sparkle-2">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0L15 9L24 12L15 15L12 24L9 15L0 12L9 9L12 0Z"/></svg>
            </div>
            <div class="ornament-sparkle sparkle-3">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0L15 9L24 12L15 15L12 24L9 15L0 12L9 9L12 0Z"/></svg>
            </div>

            <!-- Halftone Dot Clusters -->
            <div class="ornament-halftone"></div>
        </div>
        
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center justify-content-between g-5">

                <!-- TEXT COLUMN -->
                <div class="col-lg-6 text-start">
                    <!-- Eyebrow Badge Pill -->
                    <div class="hero-classic-badge-wrapper animate-on-scroll">
                        <span class="hero-classic-badge-pill" data-i18n="hero.role">Web Developer &amp; Front End Enthusiast</span>
                    </div>

                    <!-- Name / Main Heading 1 -->
                    <h1 class="hero-classic-title fw-semibold animate-on-scroll text-reveal">
                        Zenifen Caesarof Agusti
                    </h1>

                    <!-- Sub Heading / Paragraph -->
                    <p class="hero-classic-bio animate-on-scroll" data-i18n="hero.extra">
                        Saya membuat website yang menggabungkan desain modern, performa tinggi, dan pengalaman pengguna yang berkesan
                    </p>

                    <!-- CTA Buttons -->
                    <div class="hero-classic-cta animate-buttons">
                        <a href="#resources" class="btn-classic-solid" data-i18n="hero.btn.projects">Lihat Karya Saya</a>
                        <a href="#contact" class="btn-classic-outline" data-i18n="hero.btn.contact">Mari Ngobrol</a>
                    </div>
                </div>

                <!-- IMAGE COLUMN (Tall Rounded Card with Glassmorphic Overlay) -->
                <div class="col-lg-6 d-flex justify-content-center justify-content-lg-end">
                    <div class="hero-classic-card animate-on-scroll">
                        <div class="hero-classic-card-image-wrapper">
                            <img src="{{ asset('img/hero-classical-muse.png') }}" class="img-fluid hero-classic-card-image" alt="Zenifen Caesarof Agusti">
                        </div>
                        
                        <!-- Floating Glassmorphic Overlay Badge -->
                        <div class="hero-classic-glass-badge">
                            <div class="glass-badge-header">
                                <span class="glass-dot-active"></span>
                                <span class="glass-badge-title" data-i18n="hero.glass.badge">STATUS UTAMA</span>
                            </div>
                            <p class="glass-badge-text" data-i18n="hero.glass.text">
                                Sedang mengembangkan aplikasi web performa tinggi dengan arsitektur modern.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="about" class="about-section reveal-section">
        <div class="container">
            <div class="card profile-card-horizontal animate-on-scroll">
                <div class="row g-0 align-items-center">

                    <!-- IMAGE COLUMN -->
                    <div class="col-md-4 profile-img-container text-center py-4 py-md-5">
                        <div class="image-frame">
                            <div class="image-sweep">
                                <img src="{{ asset('img/foto_about_me.jpeg') }}" alt="Profile Zenifen Agusti">
                            </div>
                        </div>
                    </div>

                    <!-- TEXT COLUMN -->
                    <div class="col-md-8">
                        <div class="card-body p-3 p-md-4 p-lg-5">

                            <!-- HEADER -->
                            <h6 class="text-uppercase fw-bold mb-3 animate-text text-reveal" style="letter-spacing: 2px;" data-i18n="about.title">
                                Tentang Saya
                            </h6>

                            <!-- PARAGRAPH 1 -->
                            <p class="mb-3 animate-text" data-i18n="about.p1">
                                Lahir pada 2007, saya tumbuh besar di era digital. Saat ini saya sedang mendalami Teknik Informatika di UII Yogyakarta, sambil terus mengeksplorasi titik temu antara dunia software engineering dan analisis pasar finansial.
                            </p>

                            <!-- PARAGRAPH 2 -->
                            <p class="mb-3 animate-text" data-i18n="about.p2">
                                Bagi saya, pemrograman adalah cara untuk menyelesaikan masalah, dan analisis adalah cara untuk memahaminya. Saya senang membangun sesuatu yang tidak hanya terlihat bagus, tapi juga fungsional dan berbasis data.
                            </p>

                            <!-- PARAGRAPH 3 -->
                            <p class="text-secondary mb-4 animate-text" data-i18n="about.p3">
                                Saya selalu terbuka untuk diskusi mengenai teknologi, market, atau ide-ide baru yang menantang.
                            </p>

                            <!-- SOCIAL BUTTONS -->
                            <div class="mt-4 animate-buttons">
                                <a href="https://www.github.com/Zenn-Web" target="_blank" rel="noopener noreferrer"
                                    class="social-link github" aria-label="GitHub">
                                    <i class="bi bi-github"></i>
                                </a>

                                <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a" target="_blank"
                                    rel="noopener noreferrer" class="social-link linkedin" aria-label="LinkedIn">
                                    <i class="bi bi-linkedin"></i>
                                </a>

                                <a href="https://www.instagram.com/zenagust_" target="_blank" rel="noopener noreferrer"
                                    class="social-link instagram" aria-label="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>

                                <a href="https://www.tradingview.com/" target="_blank" rel="noopener noreferrer"
                                    class="social-link tradingview" aria-label="TradingView">
                                    <svg viewBox="0 0 28 21" width="1.2em" height="1.2em" xmlns="http://www.w3.org/2000/svg" style="fill: currentColor; vertical-align: middle;"><path d="M14 21l-5.6-10.4L2.8 21H0l8.4-15.6L14 15.8l6.3-11.7-1.4-2.6H21l4.2 7.8L14 21z"/><circle cx="23.5" cy="4.5" r="4.5"/></svg>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>



    <section id="skills" class="skills-section reveal-section">
        <div class="container">

            <div class="mb-5 text-center">
                <h2 class="fw-bold mb-0 animate-on-scroll text-reveal d-inline-block" data-i18n="skills.title">Keahlian Saya</h2>
                <div class="mx-auto mt-2 animate-on-scroll" style="width: 50px; height: 3px; background: var(--accent-emerald);"></div>
            </div>

            <div class="row g-4">
                <!-- WEB DEVELOPMENT CARD -->
                <div class="col-lg-6">
                    <div class="card card-skill-classic h-100 p-4 animate-on-scroll">
                        <div class="skill-icon-classic mb-3">
                            <i class="bi bi-code-slash fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-2 ls-tight" data-i18n="skills.programming">PEMROGRAMAN WEB</h4>
                        <p class="skill-desc-classic mb-4" data-i18n="skills.programming.desc">Membangun arsitektur web yang skalabel dan clean code untuk keberlanjutan bisnis jangka panjang.</p>

                        <div class="mb-4">
                            <h6 class="skill-subtitle-classic" data-i18n="skills.tech">Teknologi</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge-tech-classic">HTML</span>
                                <span class="badge-tech-classic">CSS</span>
                                <span class="badge-tech-classic">JAVASCRIPT</span>
                                <span class="badge-tech-classic">NODE.JS</span>
                                <span class="badge-tech-classic">REACT</span>
                                <span class="badge-tech-classic">NEXT.JS</span>
                                <span class="badge-tech-classic">PHP</span>
                                <span class="badge-tech-classic">LARAVEL</span>
                                <span class="badge-tech-classic">JAVA</span>
                                <span class="badge-tech-classic">TAILWIND CSS</span>
                                <span class="badge-tech-classic">BOOTSTRAP</span>
                                <span class="badge-tech-classic-more" title="Terus mengeksplorasi teknologi baru lainnya" data-i18n="skills.learning">& LAINNYA...</span>
                            </div>
                        </div>

                        <div>
                            <h6 class="skill-subtitle-classic" data-i18n="skills.highlights">Poin Utama</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="highlight-item-classic"><i class="bi bi-arrow-right-short me-2 text-emerald"></i><span data-i18n="skills.h1">pemecahan masalah</span></li>
                                <li class="highlight-item-classic"><i class="bi bi-arrow-right-short me-2 text-emerald"></i><span data-i18n="skills.h2">kode bersih</span></li>
                                <li class="highlight-item-classic"><i class="bi bi-arrow-right-short me-2 text-emerald"></i><span data-i18n="skills.h3">pemahaman UI/UX</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- TOOLS & WORKFLOW CARD -->
                <div class="col-lg-6">
                    <div class="card card-skill-classic h-100 p-4 animate-on-scroll">
                        <div class="skill-icon-classic mb-3">
                            <i class="bi bi-tools fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-2 ls-tight" data-i18n="skills.tools">ALAT &amp; ALUR KERJA</h4>
                        <p class="skill-desc-classic mb-4" data-i18n="skills.tools.desc">Menggunakan alat bantu modern dan alur kerja terstruktur untuk membangun produk web performa tinggi.</p>

                        <div class="mb-4">
                            <h6 class="skill-subtitle-classic" data-i18n="skills.toolset">Alat Kerja</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge-tech-classic">GIT</span>
                                <span class="badge-tech-classic">GITHUB</span>
                                <span class="badge-tech-classic">POSTMAN</span>
                                <span class="badge-tech-classic">9ROUTER</span>
                                <span class="badge-tech-classic">VS CODE</span>
                                <span class="badge-tech-classic">NPM</span>
                                <span class="badge-tech-classic">Model Context Protocol (MCP)</span>
                                <span class="badge-tech-classic-more" title="Terus mengeksplorasi alat lainnya" data-i18n="skills.learning2">& LAINNYA...</span>
                            </div>
                        </div>

                        <div>
                            <h6 class="skill-subtitle-classic" data-i18n="skills.highlights">Poin Utama</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="highlight-item-classic"><i class="bi bi-arrow-right-short me-2 text-emerald"></i><span data-i18n="skills.h4">kontrol versi</span></li>
                                <li class="highlight-item-classic"><i class="bi bi-arrow-right-short me-2 text-emerald"></i><span data-i18n="skills.h5">pengujian API</span></li>
                                <li class="highlight-item-classic"><i class="bi bi-arrow-right-short me-2 text-emerald"></i><span data-i18n="skills.h7">otomasi alur kerja</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="resources" class="projects-section reveal-section">
        <div class="container">

            <div class="mb-5 text-center">
                <h2 class="fw-bold mb-0 animate-on-scroll text-reveal d-inline-block" data-i18n="projects.title">Projek Saya</h2>
                <div class="mx-auto mt-2 animate-on-scroll" style="width: 50px; height: 3px; background: var(--accent-emerald);"></div>
            </div>

            <div class="row g-3">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stack-animated animate-on-scroll">
                        <div class="card-stack-bg-3"></div>
                        <div class="card-stack-bg-2"></div>
                        <div
                            class="card card-elegant card-stack-main shadow-none h-100 overflow-hidden">

                            <div class="px-3 pt-3">
                                <div class="img-container">
                                    <img src="{{ asset('img/eyegilv2.porto.png') }}"
                                        class="img-fluid border border-opacity-10"
                                        style="aspect-ratio: 16/10; object-fit: cover; width: 100%;" alt="Project 1">
                                </div>
                            </div>

                            <div class="card-body p-3">
                                <div class="project-category-text">UI/UX • DIGITAL BUSINESS • Enterprise Solutions Provider • Custom Software Development Services</div>
                                <div class="project-divider"></div>

                                <h6 class="fw-bold mb-1">Company Website Development – Eyegil.com</h6>
                                <p class="text-muted extra-small-text mb-2" data-i18n="projects.p1.desc">
                                  Membangun tulang punggung digital yang meningkatkan efisiensi operasional perusahaan hingga 40% melalui sistem terintegrasi.
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                    <a href="https://eyegil.com"
                                        class="text-decoration-none fw-bold link-primary extra-small-text" data-i18n="projects.view">Lihat Project</a>
                                    <span class="text-secondary extra-small-text">2025</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stack-animated animate-on-scroll">
                        <div class="card-stack-bg-3"></div>
                        <div class="card-stack-bg-2"></div>
                        <div
                            class="card card-elegant card-stack-main shadow-none h-100 overflow-hidden">

                            <div class="px-3 pt-3">
                                <div class="img-container">
                                    <img src="{{ asset('img/UMKM-tumbuh.porto.png') }}"
                                        class="img-fluid border border-opacity-10"
                                        style="aspect-ratio: 16/10; object-fit: cover; width: 100%;" alt="Project 2">
                                </div>
                            </div>

                            <div class="card-body p-3">
                                <div class="project-category-text">UI/UX • DEVELOPMENT • Personal Growth Platform • AI Growth Partner</div>
                                <div class="project-divider"></div>

                                <h6 class="fw-bold mb-1">UMKM Business Management – TUMBUH</h6>
                                <p class="text-muted extra-small-text mb-2" data-i18n="projects.p2.desc">
                                    Eksperimen AI yang mengakselerasi pertumbuhan UMKM melalui pengolahan data dan laporan keuangan otomatis.
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                    <a href="https://tumbuh-app-web.vercel.app"
                                        class="text-decoration-none fw-bold link-primary extra-small-text" data-i18n="projects.view2">Lihat Project</a>
                                    <span class="text-secondary extra-small-text">2026</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stack-animated animate-on-scroll">
                        <div class="card-stack-bg-3"></div>
                        <div class="card-stack-bg-2"></div>
                        <div
                            class="card card-elegant card-stack-main shadow-none h-100 overflow-hidden">

                            <div class="px-3 pt-3">
                                <div class="img-container">
                                    <img src="{{ asset('img/Amazaincompro.porto.png') }}"
                                        class="img-fluid border border-opacity-10"
                                        style="aspect-ratio: 16/10; object-fit: cover; width: 100%;" alt="Project 3">
                                </div>
                            </div>

                            <div class="card-body p-3">
                                <div class="project-category-text">UI/UX • DEVELOPMENT • Training Consultant • Human Capital Development </div>
                                <div class="project-divider"></div>

                                <h6 class="fw-bold mb-1">Training Platform – AMAZAIN</h6>
                                <p class="text-muted extra-small-text mb-2" data-i18n="projects.p3.desc">
                                    Platform pendidikan digital yang didesain untuk mempercepat penguasaan skill strategis di era digital.
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                    <a href="https://amazaintraining.com/"
                                        class="text-decoration-none fw-bold link-primary extra-small-text" data-i18n="projects.view3">Lihat Project</a>
                                    <span class="text-secondary extra-small-text">2026</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="contact" class="contact-section reveal-section">
        <div class="container d-flex justify-content-center px-4">
            <div class="contact-card-classic p-4 p-md-5 text-center animate-on-scroll">
                <!-- Eyebrow -->
                <p class="contact-eyebrow-classic mb-2" data-i18n="contact.eyebrow">Hubungi Saya</p>
                
                <!-- Title -->
                <h2 class="contact-title-classic mb-3 text-reveal" data-i18n="contact.title">Kontak Saya</h2>
                
                <!-- Subtitle -->
                <p class="contact-subtitle-classic mb-4 mb-md-5" data-i18n="contact.subtitle">Mari bekerjasama untuk solusi digitalmu.</p>

                <!-- Clean Contact Buttons Grid/Row -->
                <div class="d-flex flex-wrap justify-content-center gap-3 mb-4 mb-md-5">
                    <!-- Email Method -->
                    <a href="mailto:zenifenagusti70@gmail.com" class="contact-btn-classic">
                        <i class="bi bi-envelope-fill me-2 text-emerald"></i>
                        <span>zenifenagusti70@gmail.com</span>
                    </a>
                    
                    <!-- WhatsApp Method -->
                    <a href="https://wa.me/6285174344683" target="_blank" rel="noopener" class="contact-btn-classic">
                        <i class="bi bi-whatsapp me-2 text-emerald"></i>
                        <span>WhatsApp</span>
                    </a>

                    <!-- Location Method -->
                    <span class="contact-btn-classic no-link">
                        <i class="bi bi-geo-alt-fill me-2 text-emerald"></i>
                        <span>Yogyakarta, Indonesia</span>
                    </span>
                </div>

                <!-- Divider -->
                <div class="contact-divider-classic mx-auto mb-4 mb-md-5"></div>

                <!-- Social Links Row -->
                <div class="d-flex justify-content-center gap-4">
                    <a href="https://www.github.com/Zenn-Web" target="_blank" rel="noopener" class="social-icon-classic" aria-label="GitHub">
                        <i class="bi bi-github"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a" target="_blank" rel="noopener" class="social-icon-classic" aria-label="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="https://www.instagram.com/zenagust_" target="_blank" rel="noopener" class="social-icon-classic" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://www.tradingview.com/" target="_blank" rel="noopener" class="social-icon-classic" aria-label="TradingView">
                        <svg viewBox="0 0 28 21" width="1.2em" height="1.2em" xmlns="http://www.w3.org/2000/svg" style="fill: currentColor; vertical-align: middle;"><path d="M14 21l-5.6-10.4L2.8 21H0l8.4-15.6L14 15.8l6.3-11.7-1.4-2.6H21l4.2 7.8L14 21z"/><circle cx="23.5" cy="4.5" r="4.5"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection