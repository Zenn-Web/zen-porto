@extends('layout.welcome')

@section('content')

@php
    $cleanCategory = str_replace("\x95", '&#8226;', str_replace("\x96", '&#8211;', $project->category ?? ''));
    $cleanTitle = str_replace(["\x95", "\x96"], ['&#8226;', '&#8211;'], $project->title ?? '');
    $shortCategory = preg_replace('/[^A-Za-z0-9\/\+].*/', '', $project->category ?? '') ?: 'Project';
@endphp

{{-- ============================================ --}}
{{-- HERO --}}
{{-- ============================================ --}}
<section class="detail-hero-section position-relative overflow-hidden">
    <div class="detail-hero-bg">
        <img src="{{ asset($project->image) }}" alt="{{ $cleanTitle }}" class="detail-hero-bg-img">
        <div class="detail-hero-overlay"></div>
        <div class="detail-hero-gradient"></div>
    </div>

    <div class="container position-relative h-100 d-flex flex-column justify-content-end" style="z-index: 3;">
        <div class="row">
            <div class="col-lg-10 col-xl-9">
                <a href="{{ url('/#resources') }}" class="detail-back-link" style="animation-delay: 0.1s;">
                    <i class="bi bi-arrow-left me-2"></i>
                    <span data-i18n="project.back">Kembali ke Portfolio</span>
                </a>

                <div class="detail-hero-meta" style="animation-delay: 0.2s;">
                    <span class="detail-category-badge" data-i18n="project.category.{{ $project->slug }}">{!! $cleanCategory !!}</span>
                    <span class="detail-year-badge">{{ $project->year }}</span>
                </div>

                <h1 class="detail-hero-title text-reveal" style="animation-delay: 0.35s;" data-i18n="project.title.{{ $project->slug }}">{!! $cleanTitle !!}</h1>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- READING THREAD — connects hero to content --}}
{{-- ============================================ --}}
<div class="detail-thread-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="detail-thread-line"></div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- STATS BAR --}}
{{-- ============================================ --}}
<section class="detail-stats-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="detail-stats-bar" style="animation-delay: 0.15s;">
                    <div class="detail-stat-item">
                        <span class="detail-stat-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        </span>
                        <span class="detail-stat-value">{{ $shortCategory }}</span>
                    </div>
                    <span class="detail-stat-sep"></span>
                    <div class="detail-stat-item">
                        <span class="detail-stat-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                        <span class="detail-stat-value">{{ $project->year }}</span>
                    </div>
                    <span class="detail-stat-sep"></span>
                    <div class="detail-stat-item">
                        <span class="detail-stat-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                        </span>
                        <span class="detail-stat-value">{{ $project->tech_stack_badges ? count($project->tech_stack_badges) . ' tools' : '-' }}</span>
                    </div>
                    @if($project->github_url)
                    <span class="detail-stat-sep"></span>
                    <div class="detail-stat-item">
                        <span class="detail-stat-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
                        </span>
                        <span class="detail-stat-value">GitHub</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- EDITORIAL: Description + Tech Stack --}}
{{-- ============================================ --}}
<section class="detail-editorial-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-5">
                    <div class="col-lg-7">
                        <div class="detail-description-wrap" style="animation-delay: 0.1s;">
                            <span class="detail-eyebrow" data-i18n="project.about">Tentang Proyek</span>
                            <p class="detail-description-text" data-i18n="project.description.{{ $project->slug }}">{{ $project->description }}</p>
                        </div>
                    </div>

                    @if($project->tech_stack_badges)
                    <div class="col-lg-4 offset-lg-1">
                        <div class="detail-tech-wrap" style="animation-delay: 0.2s;">
                            <span class="detail-eyebrow" data-i18n="project.tech_stack">Tech Stack</span>
                            <div class="detail-tech-badges">
                                @foreach($project->tech_stack_badges as $badge)
                                    <span class="detail-tech-pill">
                                        {{ $badge["name"] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- SECTION DIVIDER --}}
{{-- ============================================ --}}
<div class="detail-divider-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="detail-section-divider">
                    <span class="detail-divider-diamond"></span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- NARRATIVE: Flow / Process --}}
{{-- ============================================ --}}
@if($project->flow_description)
<section class="detail-narrative-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="detail-narrative-header" style="animation-delay: 0.1s;">
                    <span class="detail-eyebrow" data-i18n="project.flow">Alur / Flow Description</span>
                </div>
                <div class="detail-narrative-body" style="animation-delay: 0.2s;">
                    <div class="detail-narrative-accent">
                        <span class="detail-narrative-dot"></span>
                    </div>
                    <div class="detail-narrative-text" data-i18n="project.flow_description.{{ $project->slug }}">
                        {!! nl2br(e($project->flow_description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ============================================ --}}
{{-- CLOSING THREAD --}}
{{-- ============================================ --}}
<div class="detail-closing-thread-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="detail-closing-thread"></div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- CTA --}}
{{-- ============================================ --}}
<section class="detail-cta-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="detail-cta-wrap" style="animation-delay: 0.15s;">
                    @if($project->live_demo_url)
                        <a href="{{ $project->live_demo_url }}" target="_blank" rel="noopener" class="detail-cta detail-cta-primary">
                            <span data-i18n="project.live_demo">Live Demo</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                    @endif

                    @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="detail-cta detail-cta-secondary">
                            <i class="bi bi-github"></i>
                            <span data-i18n="project.github">Lihat di GitHub</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
