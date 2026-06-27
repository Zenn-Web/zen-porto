@extends('layout.welcome')

@section('content')

{{-- Hero Section --}}
<section class="project-hero-section position-relative overflow-hidden">
    <div class="project-hero-bg">
        <div class="classic-grid-pattern"></div>
        <div class="ornament-sparkle sparkle-1">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0L15 9L24 12L15 15L12 24L9 15L0 12L9 9L12 0Z"/></svg>
        </div>
        <div class="ornament-sparkle sparkle-2">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0L15 9L24 12L15 15L12 24L9 15L0 12L9 9L12 0Z"/></svg>
        </div>
    </div>

    <div class="container position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- Back Link --}}
                <a href="{{ url('/#resources') }}" class="project-back-link">
                    <i class="bi bi-arrow-left me-2"></i>
                    <span data-i18n="project.back">Kembali ke Portfolio</span>
                </a>

                {{-- Project Image --}}
                <div class="project-image-wrapper animate-on-scroll">
                    <img src="{{ asset($project->image) }}" alt="{{ $project->title }}" class="img-fluid project-hero-image">
                </div>

                {{-- Project Meta --}}
                <div class="project-meta-wrapper animate-on-scroll">
                    <div class="project-category-badge" data-i18n="project.category.{{ $project->slug }}">{{ $project->category }}</div>
                    <span class="project-year-badge">{{ $project->year }}</span>
                </div>

                {{-- Title --}}
                <h1 class="project-title animate-on-scroll text-reveal" data-i18n="project.title.{{ $project->slug }}">{{ $project->title }}</h1>

                {{-- Description --}}
                <p class="project-description animate-on-scroll" data-i18n="project.description.{{ $project->slug }}">{{ $project->description }}</p>

                {{-- Tech Stack Badges --}}
                @if($project->tech_stack_badges)
                    <div class="tech-stack-wrapper animate-on-scroll">
                        <h5 class="tech-stack-label" data-i18n="project.tech_stack">Tech Stack</h5>
                        <div class="tech-badges-group">
                            @foreach($project->tech_stack_badges as $badge)
                                <span class="tech-badge"
                                      style="--badge-color: {{ $badge["color"] }};">
                                    {{ $badge["name"] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Flow Description Section --}}
@if($project->flow_description)
<section class="project-flow-section reveal-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="flow-card animate-on-scroll">
                    <h5 class="flow-label" data-i18n="project.flow">Alur / Flow Description</h5>
                    <div class="flow-divider"></div>
                    <div class="flow-content" data-i18n="project.flow_description.{{ $project->slug }}">
                        {!! nl2br(e($project->flow_description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- Action Buttons Section --}}
<section class="project-action-section reveal-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="action-buttons-wrapper animate-on-scroll">
                    @if($project->live_demo_url)
                        <a href="{{ $project->live_demo_url }}" target="_blank" rel="noopener" class="btn-action btn-live-demo">
                            <i class="bi bi-globe2 me-2"></i>
                            <span data-i18n="project.live_demo">Live Demo</span>
                        </a>
                    @endif

                    @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn-action btn-github">
                            <i class="bi bi-github me-2"></i>
                            <span data-i18n="project.github">Lihat di GitHub</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Custom Styles --}}
@push("styles")
<style>
/* Hero Section */
.project-hero-section {
    padding: 140px 0 60px;
    min-height: 60vh;
}

.project-hero-bg {
    position: absolute;
    inset: 0;
    overflow: hidden;
    opacity: 0.15;
}

.project-back-link {
    display: inline-flex;
    align-items: center;
    color: var(--text-color, #333);
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 30px;
    transition: color 0.3s ease;
    font-size: 0.95rem;
}

.project-back-link:hover {
    color: var(--primary-color, #0d6efd);
}

.project-image-wrapper {
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
}

.project-hero-image {
    width: 100%;
    display: block;
}

.project-meta-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.project-category-badge {
    background: var(--primary-color, #0d6efd);
    color: #fff;
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.project-year-badge {
    background: var(--card-bg, #f0f0f0);
    color: var(--text-muted, #666);
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
}

.project-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: var(--heading-color, #1a1a2e);
}

.project-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-color, #555);
    margin-bottom: 30px;
}

/* Tech Stack */
.tech-stack-wrapper {
    margin-top: 30px;
}

.tech-stack-label {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: var(--heading-color, #1a1a2e);
}

.tech-badges-group {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.tech-badge {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    background: color-mix(in srgb, var(--badge-color) 15%, transparent);
    color: var(--badge-color);
    border: 1.5px solid color-mix(in srgb, var(--badge-color) 40%, transparent);
    transition: all 0.3s ease;
}

.tech-badge:hover {
    background: color-mix(in srgb, var(--badge-color) 25%, transparent);
    transform: translateY(-2px);
}

/* Flow Description */
.project-flow-section {
    padding: 60px 0;
}

.flow-card {
    background: var(--card-bg, #fff);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.05);
    border: 1px solid var(--border-color, rgba(0,0,0,0.06));
}

.flow-label {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--heading-color, #1a1a2e);
}

.flow-divider {
    width: 60px;
    height: 3px;
    background: var(--primary-color, #0d6efd);
    border-radius: 2px;
    margin-bottom: 25px;
}

.flow-content {
    font-size: 1rem;
    line-height: 1.9;
    color: var(--text-color, #555);
    white-space: pre-line;
}

/* Action Buttons */
.project-action-section {
    padding: 0 0 80px;
}

.action-buttons-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    padding: 14px 32px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-live-demo {
    background: var(--primary-color, #0d6efd);
    color: #fff;
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
}

.btn-live-demo:hover {
    background: var(--primary-dark, #0b5ed7);
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(13, 110, 253, 0.4);
}

.btn-github {
    background: var(--card-bg, #f0f0f0);
    color: var(--text-color, #333);
    border: 1.5px solid var(--border-color, rgba(0,0,0,0.1));
}

.btn-github:hover {
    background: var(--primary-color, #0d6efd);
    color: #fff;
    border-color: var(--primary-color, #0d6efd);
    transform: translateY(-3px);
}

/* Dark mode adjustments */
[data-theme="dark"] .project-image-wrapper {
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

[data-theme="dark"] .flow-card {
    background: var(--card-bg, #1e1e2f);
    border-color: var(--border-color, rgba(255,255,255,0.06));
}

[data-theme="dark"] .btn-github {
    border-color: var(--border-color, rgba(255,255,255,0.1));
}

[data-theme="dark"] .tech-badge {
    border-color: color-mix(in srgb, var(--badge-color) 50%, transparent);
}

/* Responsive */
@media (max-width: 768px) {
    .project-hero-section {
        padding: 110px 0 40px;
    }

    .project-title {
        font-size: 1.8rem;
    }

    .project-description {
        font-size: 1rem;
    }

    .flow-card {
        padding: 25px;
    }

    .btn-action {
        width: 100%;
        justify-content: center;
    }

    .action-buttons-wrapper {
        flex-direction: column;
    }
}
</style>
@endpush

@endsection
