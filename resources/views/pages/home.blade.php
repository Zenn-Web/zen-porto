@extends('layout.welcome')


@section('content')

@php
    // Static translation keys that intentionally contain markup are rendered
    // server-side in both languages; the language switcher only toggles which
    // variant is visible (see resources/js/alpine-init.js).
    $i18nIsEn = app()->getLocale() === 'en';
@endphp

    <section id="home" class="hero-classic-section hero-bg-section position-relative overflow-hidden">
        <!-- Full Background Image -->
        <div class="hero-bg-image-wrapper">
            <img src="{{ asset('img/hero-classical-muse.png') }}" alt="" class="hero-bg-image" aria-hidden="true" width="1920" height="1080" style="aspect-ratio: 16/9;">
            <!-- Dark Overlay for readability -->
            <div class="hero-bg-overlay"></div>
        </div>



        <div class="container position-relative" style="z-index: 3;">
            <div class="row justify-content-center">
                <!-- TEXT COLUMN — Centered -->
                <div class="col-lg-8 col-xl-7 text-center">
                    <!-- Eyebrow Badge Pill -->
                    <div class="hero-classic-badge-wrapper animate-on-scroll">
                        <span class="hero-classic-badge-pill hero-badge-light"><span data-i18n-lang="id" @if($i18nIsEn) hidden @endif>{!! __('portfolio.hero_role', [], 'id') !!}</span><span data-i18n-lang="en" @unless($i18nIsEn) hidden @endunless>{!! __('portfolio.hero_role', [], 'en') !!}</span></span>
                    </div>

                    <!-- Name / Main Heading -->
                    <h1 class="hero-classic-title hero-title-light fw-semibold animate-on-scroll text-reveal">
                        Zenifen Caesarof Agusti
                    </h1>

                    <!-- Sub Heading / Paragraph -->
                    <p class="hero-classic-bio hero-bio-light animate-on-scroll mx-auto"
                       data-i18n-id="{{ __('portfolio.hero_extra', [], 'id') }}"
                       data-i18n-en="{{ __('portfolio.hero_extra', [], 'en') }}">
                        {{ __('portfolio.hero_extra') }}
                    </p>

                    <!-- CTA Buttons -->
                    <div class="hero-classic-cta hero-cta-center animate-buttons">
                        <a href="#resources" class="btn-classic-solid btn-solid-light"
                           data-i18n-id="{{ __('portfolio.hero_btn_projects', [], 'id') }}"
                           data-i18n-en="{{ __('portfolio.hero_btn_projects', [], 'en') }}">{{ __('portfolio.hero_btn_projects') }}</a>
                        <a href="#contact" class="btn-classic-outline btn-outline-light"
                           data-i18n-id="{{ __('portfolio.hero_btn_contact', [], 'id') }}"
                           data-i18n-en="{{ __('portfolio.hero_btn_contact', [], 'en') }}">{{ __('portfolio.hero_btn_contact') }}</a>
                    </div>


                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about-section reveal-section">
        <div class="container">
            <div class="row align-items-center justify-content-center g-4 g-lg-5 mx-auto" style="max-width: 1050px;">
                <!-- BIO COLUMN -->
                <div class="col-lg-6 about-bio-col animate-on-scroll">
                    <span class="about-eyebrow"
                          data-i18n-id="{{ __('portfolio.about_title', [], 'id') }}"
                          data-i18n-en="{{ __('portfolio.about_title', [], 'en') }}">{{ __('portfolio.about_title') }}</span>
                    <h2 class="about-title animate-text text-reveal"
                        data-i18n-id="{{ __('portfolio.about_title', [], 'id') }}"
                        data-i18n-en="{{ __('portfolio.about_title', [], 'en') }}">{{ __('portfolio.about_title') }}</h2>
                    <div class="about-divider animate-text"></div>
                    
                    <p class="about-desc animate-text mb-4">
                        <span data-i18n-lang="id" @if($i18nIsEn) hidden @endif>{!! __('portfolio.about_p1', [], 'id') !!}</span>
                        <span data-i18n-lang="en" @unless($i18nIsEn) hidden @endunless>{!! __('portfolio.about_p1', [], 'en') !!}</span>
                    </p>
                    <p class="about-desc animate-text text-secondary mb-4">
                        <span data-i18n-lang="id" @if($i18nIsEn) hidden @endif>{!! __('portfolio.about_p2', [], 'id') !!}</span>
                        <span data-i18n-lang="en" @unless($i18nIsEn) hidden @endunless>{!! __('portfolio.about_p2', [], 'en') !!}</span>
                    </p>
                    
                    <div class="animate-buttons">
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

                <!-- PROFILE PHOTO COLUMN (Centered Showcase - Hidden on Mobile) -->
                <div class="col-lg-5 offset-lg-1 col-xl-4 offset-xl-1 mt-4 mt-lg-0 animate-on-scroll d-none d-lg-flex justify-content-center">
                    <div class="about-clean-photo-card text-center">
                        <div class="about-clean-image-wrap mb-3">
                            <img src="{{ asset('img/foto_about_me.jpeg') }}" alt="Zenifen Agusti" class="about-clean-img" width="400" height="500" style="aspect-ratio: 4/5;">
                        </div>
                        <h3 class="about-clean-name">Zenifen Agusti</h3>
                        <p class="about-clean-role mb-0"><span data-i18n-lang="id" @if($i18nIsEn) hidden @endif>{!! __('portfolio.hero_role', [], 'id') !!}</span><span data-i18n-lang="en" @unless($i18nIsEn) hidden @endunless>{!! __('portfolio.hero_role', [], 'en') !!}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="skills" class="skills-section reveal-section">
        <div class="container" style="max-width: 800px;">

            <div class="mb-5 text-center">
                <h2 class="fw-bold mb-0 animate-on-scroll text-reveal d-inline-block"
                    data-i18n-id="{{ __('portfolio.skills_title', [], 'id') }}"
                    data-i18n-en="{{ __('portfolio.skills_title', [], 'en') }}">{{ __('portfolio.skills_title') }}</h2>
                <div class="mx-auto mt-2 animate-on-scroll" style="width: 50px; height: 3px; background: var(--accent-emerald);"></div>
            </div>

            <!-- Vertical Stack of 3 Clean Skill Groups (Centered) -->
            <div class="d-flex flex-column gap-4 animate-on-scroll">
                
                <!-- Group 1: Front-End -->
                <div class="skills-clean-block">
                    <span class="skills-clean-label">FRONT-END</span>
                    <p class="skills-clean-text mb-2">HTML, CSS, JavaScript, React, Tailwind CSS, Bootstrap</p>
                    <p class="skills-clean-desc text-secondary mb-0" style="font-size: 0.88rem; line-height: 1.6;"
                       data-i18n-id="{{ __('portfolio.skills_frontend_desc', [], 'id') }}"
                       data-i18n-en="{{ __('portfolio.skills_frontend_desc', [], 'en') }}">
                        {{ __('portfolio.skills_frontend_desc') }}
                    </p>
                </div>

                <!-- Group 2: Back-End & Database -->
                <div class="skills-clean-block">
                    <span class="skills-clean-label">BACK-END &amp; DATABASE</span>
                    <p class="skills-clean-text mb-2">Laravel, PHP, Java, MySQL, PostgreSQL, Go</p>
                    <p class="skills-clean-desc text-secondary mb-0" style="font-size: 0.88rem; line-height: 1.6;"
                       data-i18n-id="{{ __('portfolio.skills_backend_desc', [], 'id') }}"
                       data-i18n-en="{{ __('portfolio.skills_backend_desc', [], 'en') }}">
                        {{ __('portfolio.skills_backend_desc') }}
                    </p>
                </div>

                <!-- Group 3: Tools & Workflow -->
                <div class="skills-clean-block">
                    <span class="skills-clean-label">TOOLS &amp; WORKFLOW</span>
                    <p class="skills-clean-text mb-2">Git, GitHub, Bruno, Vite, VS Code, IntelliJ IDEA, Laragon, Canva</p>
                    <p class="skills-clean-desc text-secondary mb-0" style="font-size: 0.88rem; line-height: 1.6;"
                       data-i18n-id="{{ __('portfolio.skills_tools_desc', [], 'id') }}"
                       data-i18n-en="{{ __('portfolio.skills_tools_desc', [], 'en') }}">
                        {{ __('portfolio.skills_tools_desc') }}
                    </p>
                </div>

            </div>

        </div>
    </section>


    <section id="resources" class="projects-section reveal-section">
        <div class="container" style="max-width: 800px;">

            <div class="mb-5 text-center">
                <h2 class="fw-bold mb-0 animate-on-scroll text-reveal d-inline-block"
                    data-i18n-id="{{ __('portfolio.projects_title', [], 'id') }}"
                    data-i18n-en="{{ __('portfolio.projects_title', [], 'en') }}">{{ __('portfolio.projects_title') }}</h2>
                <div class="mx-auto mt-2 animate-on-scroll" style="width: 50px; height: 3px; background: var(--accent-emerald);"></div>
            </div>

            <div class="d-flex flex-column gap-5 text-start">
                @forelse($projects as $project)
                    @php
                        // Plain text (legacy entities decoded); always output escaped with {{ }}.
                        $cleanTitleId = $project->displayText('title');
                        $cleanTitleEn = $project->displayText('title_en', 'title');
                        $cleanCategoryId = $project->displayText('category');
                        $cleanCategoryEn = $project->displayText('category_en', 'category');
                    @endphp
                    <div class="project-centered-item animate-on-scroll w-100 pb-4 {{ !$loop->last ? 'border-bottom border-secondary border-opacity-10' : '' }}">
                        <!-- Developer Role & Project Domain Lines -->
                        <div class="mb-2">
                            <div class="project-role-badge mb-1">
                                FrontEnd &bull; UI/UX &bull; Contributor GIT
                            </div>
                            <div class="project-meta-line d-flex flex-wrap align-items-center gap-2">
                                <span data-i18n-id="{{ $cleanCategoryId }}" data-i18n-en="{{ $cleanCategoryEn }}">{{ $i18nIsEn ? $cleanCategoryEn : $cleanCategoryId }}</span>
                                <span>&bull;</span>
                                <span>{{ $project->year }}</span>
                            </div>
                        </div>

                        <!-- Title -->
                        <h3 class="project-split-title fw-bold mb-3"
                            data-i18n-id="{{ $cleanTitleId }}"
                            data-i18n-en="{{ $cleanTitleEn }}">
                            {{ $i18nIsEn ? $cleanTitleEn : $cleanTitleId }}
                        </h3>

                        <!-- Description -->
                        <p class="text-secondary mb-3" style="line-height: 1.7;"
                           data-i18n-id="{{ $project->description }}"
                           data-i18n-en="{{ $project->description_en ?? $project->description }}">
                            {{ app()->getLocale() === 'en' ? ($project->description_en ?? $project->description) : $project->description }}
                        </p>

                        <!-- Tech Stack Pills -->
                        @if($project->tech_stack_badges)
                        <div class="mb-4 d-flex flex-wrap gap-2">
                            @foreach($project->tech_stack_badges as $badge)
                            <span class="project-split-tech-pill">
                                {{ $badge["name"] }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <!-- CTA Links -->
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <a href="{{ route('project.show', $project->slug) }}" class="btn-project-link-primary">
                                <span data-i18n-id="{{ __('portfolio.projects_detail', [], 'id') }}"
                                      data-i18n-en="{{ __('portfolio.projects_detail', [], 'en') }}">{{ __('portfolio.projects_detail') }}</span> <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            @if($project->live_demo_url)
                            <a href="{{ $project->live_demo_url }}" target="_blank" rel="noopener" class="btn-project-link-secondary">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Live Demo
                            </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted"
                           data-i18n-id="{{ __('portfolio.projects_empty', [], 'id') }}"
                           data-i18n-en="{{ __('portfolio.projects_empty', [], 'en') }}">{{ __('portfolio.projects_empty') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <section id="contact" class="contact-section reveal-section">
        <div class="container d-flex justify-content-center px-4">
            <div class="contact-card-classic p-4 p-md-5 text-center animate-on-scroll">
                <!-- Eyebrow -->
                <p class="contact-eyebrow-classic mb-2"
                   data-i18n-id="{{ __('portfolio.contact_eyebrow', [], 'id') }}"
                   data-i18n-en="{{ __('portfolio.contact_eyebrow', [], 'en') }}">{{ __('portfolio.contact_eyebrow') }}</p>
                
                <!-- Title -->
                <h2 class="contact-title-classic mb-3 text-reveal"
                    data-i18n-id="{{ __('portfolio.contact_title', [], 'id') }}"
                    data-i18n-en="{{ __('portfolio.contact_title', [], 'en') }}">{{ __('portfolio.contact_title') }}</h2>
                
                <!-- Subtitle -->
                <p class="contact-subtitle-classic mb-4 mb-md-5"
                   data-i18n-id="{{ __('portfolio.contact_subtitle', [], 'id') }}"
                   data-i18n-en="{{ __('portfolio.contact_subtitle', [], 'en') }}">{{ __('portfolio.contact_subtitle') }}</p>

                <!-- Clean Contact Buttons Grid/Row -->
                <div class="d-flex flex-wrap justify-content-center gap-3 mb-0">
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
            </div>
        </div>
    </section>

@endsection
