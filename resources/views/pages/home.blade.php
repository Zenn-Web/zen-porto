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
                    <div class="col-md-4 profile-img-container text-center py-4 py-md-5">
                        <div class="image-frame">
                            <div class="image-sweep">
                                <img src="{{ asset('img/foto_about_me.jpeg') }}" alt="Profile Zenifen Agusti">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body p-3 p-md-4 p-lg-5">
                            <h6 class="text-uppercase fw-bold mb-3 animate-text text-reveal" style="letter-spacing: 2px;" data-i18n="about.title">Tentang Saya</h6>
                            <p class="mb-3 animate-text" data-i18n="about.p1">Hai! Saya Zenifen, seorang Front-End Developer yang sedang menekuni dunia web development. Dari pertama kali menulis kode HTML hingga sekarang membangun aplikasi web interaktif, perjalanan ini selalu penuh kejutan dan pembelajaran baru yang menyenangkan.</p>
                            <p class="mb-3 animate-text" data-i18n="about.p2">Saat ini saya fokus mengembangkan kemampuan di React, JavaScript modern, dan menciptakan UI yang tidak hanya menarik secara visual, tetapi juga nyaman digunakan. Setiap project adalah kesempatan untuk belajar sesuatu yang baru, dan saya selalu mulai dari optimasi performa hingga implementasi design system.</p>
                            <p class="text-secondary mb-4 animate-text" data-i18n="about.p3">Bagi saya, menulis kode bukan cuma soal syntax yang benar, tapi tentang memecahkan masalah dengan cara yang efisien dan elegan. Saya percaya bahwa setiap developer punya journey unik, dan saya excited untuk terus berkembang di bidang ini.</p>
                            <div class="stats-grid-about mb-4 animate-text">
                                <div class="stat-item-about"><div class="stat-number-about" data-i18n="about.stat1.number">5+</div><div class="stat-label-about" data-i18n="about.stat1.label">Projek</div></div>
                                <div class="stat-item-about"><div class="stat-number-about stat-icon-about"><i class="bi bi-people-fill"></i></div><div class="stat-label-about" data-i18n="about.stat2.label">Terbuka untuk Kolaborasi</div></div>
                                <div class="stat-item-about"><div class="stat-number-about" data-i18n="about.stat3.number">5+</div><div class="stat-label-about" data-i18n="about.stat3.label">Teknologi</div></div>
                                <div class="stat-item-about"><div class="stat-number-about stat-icon-about"><i class="bi bi-arrow-up-circle-fill"></i></div><div class="stat-label-about" data-i18n="about.stat4.label">Terus Belajar</div></div>
                            </div>
                            <div class="mt-4 animate-buttons">
                                <a href="https://www.github.com/Zenn-Web" target="_blank" rel="noopener noreferrer" class="social-link github" aria-label="GitHub"><i class="bi bi-github"></i></a>
                                <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a" target="_blank" rel="noopener noreferrer" class="social-link linkedin" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                                <a href="https://www.instagram.com/zenagust_" target="_blank" rel="noopener noreferrer" class="social-link instagram" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
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
                @forelse($projects as $project)
                                @php
                                    $cleanTitle = str_replace([chr(150), chr(151)], ['&#8211;', '&#8211;'], $project->title ?? '');
                                    $cleanCategory = str_replace(chr(149), '&#8226;', $project->category ?? '');
                                @endphp
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stack-animated animate-on-scroll"><div
                            class="card card-elegant card-stack-main shadow-none h-100 overflow-hidden">

                            <div class="px-3 pt-3">
                                <div class="img-container">
                                    <img src="{{ asset($project->image) }}"
                                        class="img-fluid"
                                        style="width: 100%; height: auto; display: block; object-fit: contain; background: #1a1a1a;" alt="{!! str_replace([chr(150), chr(151)], ['&#8211;', '&#8211;'], $project->title) !!}">
                                </div>
                            </div>

                            <div class="card-body p-3 d-flex flex-column">
                                <div class="project-category-text" data-i18n="project.category.{{ $project->slug }}">{!! $cleanCategory !!}</div>
                                <div class="project-divider"></div>

                                <h6 class="fw-bold mb-1" data-i18n="project.title.{{ $project->slug }}">{!! $cleanTitle !!}</h6>
                                <p class="text-muted extra-small-text mb-2" data-i18n="project.description.{{ $project->slug }}">
                                    {{ $project->description }}
                                </p>

                                @if($project->tech_stack_badges)
                                <div class="mb-2 d-flex flex-wrap gap-1">
                                    @foreach(array_slice($project->tech_stack_badges, 0, 3) as $badge)
                                    <span class="card-tech-badge">
                                        {{ $badge["name"] }}
                                    </span>
                                    @endforeach
                                    @if(count($project->tech_stack_badges) > 3)
                                    <span class="card-tech-badge badge-count">
                                        +{{ count($project->tech_stack_badges) - 3 }}
                                    </span>
                                    @endif
                                </div>
                                @endif

                                <div class="mt-auto pt-2 d-flex flex-wrap gap-2 align-items-center">
                                    <a href="{{ route("project.show", $project->slug) }}"
                                        class="text-decoration-none fw-bold link-primary extra-small-text" data-i18n="projects.detail">Detail</a>
                                    @if($project->live_demo_url)
                                    <a href="{{ $project->live_demo_url }}" target="_blank" rel="noopener"
                                        class="text-decoration-none fw-bold extra-small-text" style="color: var(--accent-emerald, #10b981);">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Live Demo
                                    </a>
                                    @endif
                                    <span class="text-secondary extra-small-text ms-auto">{{ $project->year }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted" data-i18n="projects.empty">Belum ada project untuk ditampilkan.</p>
                </div>
                @endforelse
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
                    <a href="https://www.github.com/Zenn-Web" target="_blank" rel="noopener" class="social-link-rounded github" aria-label="GitHub">
                        <i class="bi bi-github"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a" target="_blank" rel="noopener" class="social-link-rounded linkedin" aria-label="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="https://www.instagram.com/zenagust_" target="_blank" rel="noopener" class="social-link-rounded instagram" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection


