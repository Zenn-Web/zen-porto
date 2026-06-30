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
            <div class="about-layout-row row align-items-stretch">
                <!-- BIO COLUMN -->
                <div class="col-lg-6 about-bio-col animate-on-scroll">
                    <span class="about-eyebrow" data-i18n="about.title">Tentang Saya</span>
                    <h2 class="about-title animate-text text-reveal" data-i18n="about.title">Tentang Saya</h2>
                    <div class="about-divider animate-text"></div>
                    
                    <p class="about-desc animate-text mb-4" data-i18n="about.p1">
                        Hai! Saya Zenifen, seorang <strong>Front-End Developer</strong> yang fokus membangun antarmuka web interaktif. Berawal dari rasa penasaran dengan HTML, kini saya merancang arsitektur web modern menggunakan <strong>React, JavaScript modern, dan Tailwind CSS</strong>.
                    </p>
                    <p class="about-desc animate-text text-secondary" data-i18n="about.p2">
                        Bagi saya, <i>coding</i> bukan hanya soal sintaks, tapi tentang memecahkan masalah. Mulai dari <strong>optimasi performa</strong> (Core Web Vitals) hingga implementasi <strong>Design System</strong>, saya memastikan setiap baris kode memberi dampak nyata pada kenyamanan pengguna.
                    </p>
                    
                    <div class="mt-2 animate-buttons">
                        <a href="https://www.github.com/Zenn-Web" target="_blank" rel="noopener noreferrer" class="social-link github" aria-label="GitHub">
                            <i class="bi bi-github"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a" target="_blank" rel="noopener noreferrer" class="social-link linkedin" aria-label="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="https://www.instagram.com/zenagust_" target="_blank" rel="noopener noreferrer" class="social-link instagram" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- DEVELOPER HUD COLUMN -->
                <div class="col-lg-6 mt-4 mt-lg-0 animate-on-scroll">
                    <div class="about-hud-card">
                        <!-- Top HUD section (Profile & Meta info) -->
                        <div class="about-hud-top">
                            <div class="about-hud-image-wrap">
                                <div class="hud-image-frame">
                                    <img src="{{ asset('img/foto_about_me.jpeg') }}" alt="Profile Zenifen Agusti">
                                </div>
                            </div>
                            <div class="about-hud-meta">
                                <h3 class="hud-meta-name">Zenifen Agusti</h3>
                                <p class="hud-meta-role">Web Developer & Front End Enthusiast</p>
                            </div>
                        </div>

                        <!-- Goals/Aspirations HUD section -->
                        <div class="hud-goals-wrap">
                            <span class="hud-goals-label" data-i18n="about.vision.label">Visi & Dedikasi</span>
                            <p class="hud-goals-text" data-i18n="about.vision.text">
                                Beraspirasi membangun <strong>solusi digital yang bermanfaat</strong> untuk berbagai kebutuhan industri, serta berkontribusi aktif dalam memajukan ekosistem teknologi web masa depan.
                            </p>
                        </div>

                        <!-- Bottom HUD section (Grid stats) -->
                        <div class="about-hud-stats-grid">
                            <!-- Stat 1 -->
                            <div class="hud-stat-item">
                                <span class="hud-stat-number" data-i18n="about.stat1.number">5+</span>
                                <span class="hud-stat-label" data-i18n="about.stat1.label">Projek Nyata</span>
                            </div>
                            
                            <!-- Stat 2 -->
                            <div class="hud-stat-item">
                                <span class="hud-stat-number" data-i18n="about.stat2.number">100+</span>
                                <span class="hud-stat-label" data-i18n="about.stat2.label">Kode Commit</span>
                            </div>
                            
                            <!-- Stat 3 -->
                            <div class="hud-stat-item">
                                <span class="hud-stat-number" data-i18n="about.stat3.number">10+</span>
                                <span class="hud-stat-label" data-i18n="about.stat3.label">Teknologi</span>
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
                        <p class="skill-desc-classic mb-4" data-i18n="skills.programming.desc">Membangun antarmuka modern dan arsitektur backend yang kokoh untuk pengalaman pengguna terbaik.</p>

                        <div class="mb-4">
                            <h6 class="skill-subtitle-classic text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;" data-i18n="skills.fokus">Fokus Utama (Proficient)</h6>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge-tech-classic">HTML</span>
                                <span class="badge-tech-classic">CSS</span>
                                <span class="badge-tech-classic">JavaScript</span>
                                <span class="badge-tech-classic">PHP</span>
                                <span class="badge-tech-classic">Laravel</span>
                                <span class="badge-tech-classic">Tailwind CSS</span>
                                <span class="badge-tech-classic">Bootstrap</span>
                            </div>
                            
                            <h6 class="skill-subtitle-classic text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;" data-i18n="skills.eksplorasi">Eksplorasi (Familiar)</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge-tech-classic" style="opacity: 0.7; border-style: dashed; background-color: transparent; color: inherit;">React</span>
                                <span class="badge-tech-classic" style="opacity: 0.7; border-style: dashed; background-color: transparent; color: inherit;">Next.js</span>
                                <span class="badge-tech-classic" style="opacity: 0.7; border-style: dashed; background-color: transparent; color: inherit;">Node.js</span>
                                <span class="badge-tech-classic" style="opacity: 0.7; border-style: dashed; background-color: transparent; color: inherit;">Java</span>
                            </div>
                        </div>

                        <div>
                            <h6 class="skill-subtitle-classic mb-3" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;" data-i18n="skills.praktik">Praktik Implementasi</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="highlight-item-classic mb-2 d-flex align-items-start">
                                    <i class="bi bi-check2-square me-2 text-emerald mt-1"></i>
                                    <span data-i18n="skills.web.p1">Membangun komponen UI yang interaktif & <i>reusable</i></span>
                                </li>
                                <li class="highlight-item-classic mb-2 d-flex align-items-start">
                                    <i class="bi bi-check2-square me-2 text-emerald mt-1"></i>
                                    <span data-i18n="skills.web.p2">Menerapkan pola arsitektur MVC (Model-View-Controller)</span>
                                </li>
                                <li class="highlight-item-classic mb-2 d-flex align-items-start">
                                    <i class="bi bi-check2-square me-2 text-emerald mt-1"></i>
                                    <span data-i18n="skills.web.p3">Optimalisasi performa aset dan responsivitas layout</span>
                                </li>
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
                        <p class="skill-desc-classic mb-4" data-i18n="skills.tools.desc">Menggunakan ekosistem alat modern untuk memastikan produktivitas, kolaborasi, dan kualitas kode.</p>

                        <div class="mb-4">
                            <h6 class="skill-subtitle-classic text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;" data-i18n="skills.kontrol">Kontrol Versi & API</h6>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge-tech-classic">Git</span>
                                <span class="badge-tech-classic">GitHub</span>
                                <span class="badge-tech-classic">Postman</span>
                            </div>
                            
                            <h6 class="skill-subtitle-classic text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;" data-i18n="skills.lingkungan">Lingkungan Pengembangan</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge-tech-classic">VS Code</span>
                                <span class="badge-tech-classic">NPM</span>
                                <span class="badge-tech-classic">Vite</span>
                                <span class="badge-tech-classic">MCP (AI Tools)</span>
                            </div>
                        </div>

                        <div>
                            <h6 class="skill-subtitle-classic mb-3" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;" data-i18n="skills.praktik">Praktik Implementasi</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="highlight-item-classic mb-2 d-flex align-items-start">
                                    <i class="bi bi-check2-square me-2 text-emerald mt-1"></i>
                                    <span data-i18n="skills.tools.p1">Manajemen kode kolaboratif dengan prinsip <i>Git Flow</i></span>
                                </li>
                                <li class="highlight-item-classic mb-2 d-flex align-items-start">
                                    <i class="bi bi-check2-square me-2 text-emerald mt-1"></i>
                                    <span data-i18n="skills.tools.p2">Pengujian <i>endpoint</i> API untuk integrasi sistem yang aman</span>
                                </li>
                                <li class="highlight-item-classic mb-2 d-flex align-items-start">
                                    <i class="bi bi-check2-square me-2 text-emerald mt-1"></i>
                                    <span data-i18n="skills.tools.p3">Pemanfaatan AI <i>tools</i> untuk akselerasi <i>debugging</i></span>
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


