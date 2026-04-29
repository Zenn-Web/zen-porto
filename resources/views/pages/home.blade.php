@extends('layout.welcome')


@section('content')


    <section id="home" class="reveal-section position-relative overflow-hidden">
        <!-- Background Ornaments -->
        <div class="hero-bg-ornaments">
            <div class="bg-grid-pattern"></div>
            <div class="bg-big-text">ZEN</div>
        </div>
        
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center justify-content-center">

                <div class="col-lg-6 text-lg-start text-center mb-5 mb-lg-0">
                    <div class="hero-location-badge animate-on-scroll">
                        <div class="icon-container">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <span>Yogyakarta, Indonesia</span>
                    </div>
                    <h1 class="display-4 fw-bold animate-on-scroll"><span class="text-gradient-emerald">Zenifen</span> Caesarof Agusti</h1>
                    <p class="mb-3 animate-on-scroll hero-role-text">
                        <span class="lead fw-bold d-block mb-1">Web Developer & Front End Engineer.</span>
                        <span class="text-muted">Juga memiliki minat mendalam di Analisis Forex.</span>
                    </p>
                    <p class="text-muted small mb-4 animate-on-scroll">Membangun solusi digital berkualitas tinggi dengan presisi dan kode.</p>

                    <div class="row g-3 justify-content-center justify-content-lg-start mt-3">
                        <div class="col-auto">
                            <h5 class="fw-bold mb-0">5+</h5>
                            <small class="text-muted">Teknologi</small>
                        </div>
                        <div class="col-auto border-start ps-3">
                            <h5 class="fw-bold mb-0">Terbuka</h5>
                            <small class="text-muted">Untuk Kolaborasi</small>
                        </div>
                    </div>
                    <div class="hero-cta-group animate-on-scroll mt-4 d-flex gap-3 justify-content-center justify-content-lg-start">
                        <a href="#resources" class="btn-hero-primary">Lihat Projek</a>
                        <a href="#contact" class="btn-hero-secondary">Hubungi Saya</a>
                    </div>
                </div>

                <div class="col-lg-6 d-flex justify-content-center">
                    <div class="profile-animation-wrapper">

                        <div class="animated-gradient-bg"></div>
                        <div class="pulsing-circle-outer"></div>
                        <div class="pulsing-circle-inner"></div>
                        <div class="profile-picture-container">
                            <img src="{{ asset('img/foto_portofolio_animate.png') }}" class="img-fluid profile-image"
                                alt="Zenifen">
                        </div>
                        <div class="ornamen-box computer">🖥️</div>
                        <div class="ornamen-box greek">🏛</div>
                        <div class="status-badge">
                            <span class="dot"></span>
                            <span class="status-text">Fokus pada Pertumbuhan</span>
                            <span id="live-clock">00:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about-section reveal-section py-5">
        <div class="container">
            <div class="card profile-card-horizontal">
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
                            <h6 class="text-uppercase fw-bold mb-3 animate-text" style="letter-spacing: 2px;">
                                Tentang Saya
                            </h6>

                            <!-- PARAGRAPH 1 -->
                            <p class="mb-3 animate-text">
                                Saya adalah seorang web developer yang memiliki ketertarikan besar pada dunia pengembangan
                                website dan teknologi digital. Saya lahir di Jakarta pada 29 Agustus 2007, dan saat ini
                                menjalani kehidupan serta aktivitas sehari-hari di Yogyakarta.
                            </p>

                            <!-- PARAGRAPH 2 -->
                            <p class="mb-3 animate-text">
                                Saat ini, saya sedang menempuh pendidikan di Universitas Islam Indonesia dengan jurusan
                                Teknik Informatika, di mana saya terus mengembangkan pemahaman tentang teknologi,
                                pemrograman, dan konsep dasar pengembangan perangkat lunak.
                            </p>

                            <!-- PARAGRAPH 3 -->
                            <p class="text-secondary mb-4 animate-text">
                                Saya memiliki ketertarikan besar dalam mempelajari berbagai bahasa pemrograman, khususnya
                                HTML, CSS, JavaScript, Laravel, dan lain sebagainya. Dalam proses belajar dan mengembangkan proyek,
                                saya selalu berusaha memahami tidak hanya bagaimana sebuah fitur dibuat, tetapi juga alasan
                                di balik penggunaannya.
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
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>



    <section id="skills" class="py-5 d-flex align-items-center"
        style="scroll-margin-top: 90px; min-height: 85vh;">
        <div class="container py-2">

            <div class="mb-4">
                <h2 class="fw-bold mb-0">Keahlian Saya</h2>
            </div>

            <div class="row g-3">
                <!-- PROGRAMMING CARD -->
                <div class="col-lg-6">
                    <div class="card card-skill-v2 h-100 p-3 p-md-4 rounded-0">
                        <div class="skill-icon-v2 mb-3">
                            <i class="bi bi-code-slash fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-2 ls-tight">PEMROGRAMAN</h4>
                        <p class="text-secondary small mb-3">Membangun aplikasi web yang bersih dan skalabel.</p>

                        <div class="mb-3">
                            <h6 class="text-uppercase fw-bold xsmall-text mb-2">Teknologi</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge-tech">HTML</span>
                                <span class="badge-tech">CSS</span>
                                <span class="badge-tech">JAVASCRIPT</span>
                                <span class="badge-tech">PHP</span>
                                <span class="badge-tech">LARAVEL</span>
                            </div>
                        </div>

                        <div>
                            <h6 class="text-uppercase fw-bold xsmall-text mb-2">Poin Utama</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i>pemecahan masalah
                                </li>
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i>kode bersih</li>
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i>pemahaman UI/UX
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- FOREX ANALYST CARD -->
                <div class="col-lg-6">
                    <div class="card card-skill-v2 h-100 p-3 p-md-4 rounded-0">
                        <div class="skill-icon-v2 mb-3">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-2 ls-tight">ANALIS FOREX</h4>
                        <p class="text-secondary small mb-3">Menganalisis tren pasar dan mengidentifikasi peluang trading probabilitas tinggi.</p>

                        <div class="mb-0">
                            <h6 class="text-uppercase fw-bold xsmall-text mb-2">Poin Utama</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i>struktur pasar</li>
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i>price action
                                </li>
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i>entry timing
                                </li>
                                <li class="highlight-item small"><i class="bi bi-arrow-right-short me-2"></i>manajemen risiko
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="resources" class="py-5 reveal-section d-flex align-items-center"
        style="scroll-margin-top: 90px; min-height: 85vh;">
        <div class="container">

            <div class="mb-3 animate-on-scroll">
                <h4 class="fw-bold mb-0">Projek Saya</h4>
            </div>

            <div class="row g-3">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stack-animated animate-on-scroll">
                        <div class="card-stack-bg-3 rounded-0"></div>
                        <div class="card-stack-bg-2 rounded-0"></div>
                        <div
                            class="card card-elegant card-stack-main rounded-0 shadow-none h-100 overflow-hidden">

                            <div class="px-3 pt-3">
                                <img src="{{ asset('img/eyegilv2.porto.png') }}"
                                    class="img-fluid border border-opacity-10"
                                    style="aspect-ratio: 16/10; object-fit: cover; width: 100%;" alt="Project 1">
                            </div>

                            <div class="card-body p-3">
                                <div class="project-category-text">UI/UX • DIGITAL BUSINESS • Enterprise Solutions Provider • Custom Software Development Services</div>
                                <div class="project-divider"></div>

                                <h6 class="fw-bold mb-1">Company Website Development – Eyegil.com</h6>
                                <p class="text-muted extra-small-text mb-2">
                                  Platform yang membantu perusahaan meningkatkan efisiensi melalui solusi teknologi dan sistem digital yang terintegrasi.
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                    <a href="https://eyegil.com"
                                        class="text-decoration-none fw-bold link-primary extra-small-text">Lihat Project</a>
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
                                <img src="{{ asset('img/UMKM-tumbuh.porto.png') }}"
                                    class="img-fluid border border-opacity-10"
                                    style="aspect-ratio: 16/10; object-fit: cover; width: 100%;" alt="Project 2">
                            </div>

                            <div class="card-body p-3">
                                <div class="project-category-text">UI/UX • DEVELOPMENT • Personal Growth Platform • AI Growth Partner</div>
                                <div class="project-divider"></div>

                                <h6 class="fw-bold mb-1">UMKM Business Management – TUMBUH</h6>
                                <p class="text-muted extra-small-text mb-2">
                                    Platform AI untuk membantu UMKM mengelola bisnis, penjualan, dan analisis data.
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                    <a href="https://tumbuh-app-web.vercel.app"
                                        class="text-decoration-none fw-bold link-primary extra-small-text">Lihat Project</a>
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
                                <img src="{{ asset('img/Amazaincompro.porto.png') }}"
                                    class="img-fluid border border-opacity-10"
                                    style="aspect-ratio: 16/10; object-fit: cover; width: 100%;" alt="Project 3">
                            </div>

                            <div class="card-body p-3">
                                <div class="project-category-text">UI/UX • DEVELOPMENT • Training Consultant • Human Capital Development </div>
                                <div class="project-divider"></div>

                                <h6 class="fw-bold mb-1">Training Platform – AMAZAIN</h6>
                                <p class="text-muted extra-small-text mb-2">
                                    Platform untuk meningkatkan skill digital marketing dan mengkonsultasikan strategi bisnis.
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                    <a href="https://amazaintraining.com/"
                                        class="text-decoration-none fw-bold link-primary extra-small-text">Lihat Project</a>
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
                <p class="contact-eyebrow">Hubungi Saya</p>
                <h2 class="contact-title">Kontak Saya</h2>
                <p class="contact-subtitle">Mari bekerjasama untuk solusi digitalmu.</p>

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
                            <label for="first_name">Nama Depan</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="@error('first_name') is-invalid @enderror">
                            @error('first_name')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="last_name">Nama Belakang</label>
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
                        <label for="message">Pesan</label>
                        <textarea id="message" name="message" rows="5" class="@error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="button-wrapper">
                        <button type="submit" class="btn-send-contact">Kirim Pesan</button>
                    </div>

                </form>
            </div>

        </div>
    </section>

@endsection