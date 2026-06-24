@extends('layout.welcome')


@section('content')


    <section id="home" class="reveal-section position-relative overflow-hidden">
        <!-- Background Ornaments -->
        <div class="hero-bg-ornaments">
            <div class="bg-grid-pattern"></div>
        </div>
        
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center justify-content-between g-5">

                <!-- TEXT COLUMN -->
                <div class="col-lg-6 text-lg-start text-center">

                    <!-- Name -->
                    <h1 class="hero-name fw-bold animate-on-scroll text-reveal">
                        <span class="text-gradient-emerald">Zenifen</span><br>
                        Caesarof Agusti
                    </h1>

                    <!-- Role -->
                    <p class="hero-role-label animate-on-scroll" data-i18n="hero.role">
                        Web Developer &amp; Front End Enthusiast
                    </p>

                    <!-- Short bio -->
                    <p class="hero-bio text-muted animate-on-scroll" data-i18n="hero.extra">
                        Building aesthetic, functional digital interfaces backed by clean, maintainable code.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="hero-cta animate-buttons">
                        <a href="#resources" class="btn-hero-compact btn-hero-filled" data-i18n="hero.btn.projects">Lihat Karya Saya</a>
                        <a href="#contact" class="btn-hero-compact btn-hero-outline" data-i18n="hero.btn.contact">Mari Ngobrol</a>
                    </div>
                </div>

                <!-- IMAGE COLUMN -->
                <div class="col-lg-5 d-flex justify-content-center justify-content-lg-end order-first order-lg-last">
                    <div class="profile-animation-wrapper">
                        <div class="pulsing-circle-outer"></div>
                        <div class="profile-picture-container">
                            <img src="{{ asset('img/foto_portofolio_animate.png') }}" class="img-fluid profile-image"
                                alt="Zenifen">
                        </div>
                        <div class="status-badge">
                            <span class="dot"></span>
                            <span class="status-text" data-i18n="hero.status">Siap untuk Tantangan Baru</span>
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

            <div class="row g-3">
                <!-- PROGRAMMING CARD -->
                <div class="col-lg-6">
                    <div class="card card-skill-v2 h-100 p-3 p-md-4 rounded-0 animate-on-scroll">
                        <div class="skill-icon-v2 mb-3">
                            <i class="bi bi-code-slash fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-2 ls-tight" data-i18n="skills.programming">PEMROGRAMAN</h4>
                        <p class="text-secondary small mb-3" data-i18n="skills.programming.desc">Membangun arsitektur web yang skalabel dan clean code untuk keberlanjutan bisnis jangka panjang.</p>

                        <div class="mb-3">
                            <h6 class="text-uppercase fw-bold xsmall-text mb-2" data-i18n="skills.tech">Teknologi</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge-tech">HTML</span>
                                <span class="badge-tech">CSS</span>
                                <span class="badge-tech">JAVASCRIPT</span>
                                <span class="badge-tech">PHP</span>
                                <span class="badge-tech">LARAVEL</span>
                                <span class="badge-tech-learning" title="Terus mengeksplorasi teknologi baru lainnya" data-i18n="skills.learning">& LAINNYA...</span>
                            </div>
                        </div>

                        <div>
                            <h6 class="text-uppercase fw-bold xsmall-text mb-2" data-i18n="skills.highlights">Poin Utama</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i><span data-i18n="skills.h1">pemecahan masalah</span>
                                </li>
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i><span data-i18n="skills.h2">kode bersih</span></li>
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i><span data-i18n="skills.h3">pemahaman UI/UX</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- TECHNICAL ANALYST CARD -->
                <div class="col-lg-6">
                    <div class="card card-skill-v2 h-100 p-3 p-md-4 rounded-0 animate-on-scroll">
                        <div class="skill-icon-v2 mb-3">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-2 ls-tight">TECHNICAL ANALYST</h4>
                        <p class="text-secondary small mb-3" data-i18n="skills.analyst.desc">Menganalisis tren pasar global dengan manajemen risiko ketat untuk mendukung pengambilan keputusan berbasis data.</p>

                        <div class="mb-3">
                            <h6 class="text-uppercase fw-bold xsmall-text mb-2" data-i18n="skills.method">Metode</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge-tech">CRT</span>
                                <span class="badge-tech">SMC</span>
                                <span class="badge-tech-learning" title="Terus mengeksplorasi metode lainnya..." data-i18n="skills.learning2">& LAINNYA...</span>
                            </div>
                        </div>

                        <div class="mb-0">
                            <h6 class="text-uppercase fw-bold xsmall-text mb-2" data-i18n="skills.highlights2">Poin Utama</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i><span data-i18n="skills.h4">struktur pasar</span></li>
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i><span data-i18n="skills.h5">price action</span>
                                </li>
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i><span data-i18n="skills.h7">entry timing</span>
                                </li>
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i><span data-i18n="skills.h6">manajemen risiko</span>
                                </li>
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
                        <div class="card-stack-bg-3 rounded-0"></div>
                        <div class="card-stack-bg-2 rounded-0"></div>
                        <div
                            class="card card-elegant card-stack-main rounded-0 shadow-none h-100 overflow-hidden">

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
                        <div class="card-stack-bg-3 rounded-0"></div>
                        <div class="card-stack-bg-2 rounded-0"></div>
                        <div
                            class="card card-elegant card-stack-main rounded-0 shadow-none h-100 overflow-hidden">

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
                        <div class="card-stack-bg-3 rounded-0"></div>
                        <div class="card-stack-bg-2 rounded-0"></div>
                        <div
                            class="card card-elegant card-stack-main rounded-0 shadow-none h-100 overflow-hidden">

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
        <div class="contact-container">

            {{-- KIRI: Info --}}
            <div class="contact-info">
                <p class="contact-eyebrow animate-on-scroll" data-i18n="contact.eyebrow">Hubungi Saya</p>
                <h2 class="contact-title animate-on-scroll text-reveal" data-i18n="contact.title">Kontak Saya</h2>
                <p class="contact-subtitle animate-on-scroll" data-i18n="contact.subtitle">Mari bekerjasama untuk solusi digitalmu.</p>

                <div class="contact-divider animate-on-scroll"></div>

                <div class="contact-details">
                    <div class="contact-detail-row animate-on-scroll">
                        <i class="bi bi-geo-alt"></i>
                        <span>Yogyakarta, Indonesia</span>
                    </div>
                    <div class="contact-detail-row animate-on-scroll">
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:zenifenagusti70@gmail.com">zenifenagusti70@gmail.com</a>
                    </div>
                    <div class="contact-detail-row animate-on-scroll">
                        <i class="bi bi-whatsapp"></i>
                        <a href="https://wa.me/6285174344683" target="_blank" rel="noopener" title="Hubungi via WhatsApp">WhatsApp</a>
                    </div>
                </div>

                <div class="contact-social-row animate-on-scroll">
                    <a href="https://www.github.com/Zenn-Web" target="_blank" rel="noopener" class="contact-social-link" aria-label="GitHub">
                        <i class="bi bi-github"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a" target="_blank" rel="noopener" class="contact-social-link" aria-label="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="https://www.instagram.com/zenagust_" target="_blank" rel="noopener" class="contact-social-link" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://www.tradingview.com/" target="_blank" rel="noopener" class="contact-social-link" aria-label="TradingView">
                        <svg viewBox="0 0 28 21" width="1.2em" height="1.2em" xmlns="http://www.w3.org/2000/svg" style="fill: currentColor; vertical-align: middle;"><path d="M14 21l-5.6-10.4L2.8 21H0l8.4-15.6L14 15.8l6.3-11.7-1.4-2.6H21l4.2 7.8L14 21z"/><circle cx="23.5" cy="4.5" r="4.5"/></svg>
                    </a>
                </div>
            </div>

            {{-- KANAN: Form --}}
            <div class="contact-form-wrapper">
                <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                    @csrf

                    @if(session('success'))
                        <div class="alert alert-success mb-4">{{ session('success') }}</div>
                    @endif

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name" data-i18n="contact.firstname">Nama Depan</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="@error('first_name') is-invalid @enderror">
                            @error('first_name')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="last_name" data-i18n="contact.lastname">Nama Belakang</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="@error('last_name') is-invalid @enderror">
                            @error('last_name')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="@error('email') is-invalid @enderror">
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message" data-i18n="contact.message">Pesan</label>
                        <textarea id="message" name="message" rows="5" class="@error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="button-wrapper">
                        <button type="submit" class="btn-send-contact" data-i18n="contact.submit">Kirim Pesan</button>
                    </div>

                </form>
            </div>

        </div>
    </section>

@endsection