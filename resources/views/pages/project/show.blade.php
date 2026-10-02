@extends('layout.welcome')

@section('content')

@php
    // Project fields are untrusted plain text (legacy entities decoded by
    // Project::displayText); always output them escaped with {{ }}.
    $isEn = app()->getLocale() === 'en';
    $cleanTitleId = $project->displayText('title');
    $cleanTitleEn = $project->displayText('title_en', 'title');
    // Multi-line text is kept as plain text; .dp-narrative-text uses white-space: pre-line.
    $flowId = $project->flow_description ?? '';
    $flowEn = $project->flow_description_en ?? $project->flow_description ?? '';
@endphp

<div class="dp-split-section">
    <div class="container-fluid px-lg-5">
        <div class="row g-lg-5">
            
            {{-- ============================================ --}}
            {{-- LEFT COLUMN: Scrollable Content               --}}
            {{-- ============================================ --}}
            <div class="col-lg-7 dp-content-col order-2 order-lg-1">
                
                {{-- 1. Showcase Image --}}
                <div class="dp-showcase-wrap" data-dp-anim="slide-right">
                    <div class="dp-showcase-frame">
                        <img src="{{ asset($project->image) }}"
                             alt="{{ $cleanTitleId }}"
                             class="dp-showcase-img"
                             loading="lazy">
                    </div>
                </div>

                {{-- 2. Narrative/Workflow Section --}}
                @if($project->flow_description)
                <div class="dp-narrative-wrap">
                    <div class="dp-narrative-body">
                        {{-- Timeline line draws on scroll --}}
                        <div class="dp-narrative-timeline" aria-hidden="true">
                            <div class="dp-narrative-timeline-track"></div>
                            <div class="dp-narrative-timeline-dot"></div>
                        </div>

                        <div class="dp-narrative-header" data-dp-anim="fade-up">
                            <span class="dp-eyebrow"
                                  data-i18n-id="{{ __('portfolio.project_flow', [], 'id') }}"
                                  data-i18n-en="{{ __('portfolio.project_flow', [], 'en') }}">{{ __('portfolio.project_flow') }}</span>
                        </div>

                        <div class="dp-narrative-text" data-dp-anim="fade-up"
                             data-i18n-id="{{ $flowId }}"
                             data-i18n-en="{{ $flowEn }}">{{ $isEn ? $flowEn : $flowId }}</div>
                    </div>
                </div>
                @endif

            </div>

            {{-- ============================================ --}}
            {{-- RIGHT COLUMN: Sticky Sidebar                  --}}
            {{-- ============================================ --}}
            <div class="col-lg-5 dp-sidebar-col order-1 order-lg-2">
                <div class="dp-sidebar-sticky">
                    
                    {{-- Back to Portfolio --}}
                    <div class="dp-sidebar-element" data-dp-anim="fade-up">
                        <a href="{{ url('/#resources') }}" class="dp-back-btn">
                            <i class="bi bi-arrow-left me-2"></i>
                            <span data-i18n-id="{{ __('portfolio.project_back', [], 'id') }}"
                                  data-i18n-en="{{ __('portfolio.project_back', [], 'en') }}">{{ __('portfolio.project_back') }}</span>
                        </a>
                    </div>

                    {{-- Title --}}
                    <div class="dp-sidebar-element" data-dp-anim="fade-up">
                        <h1 class="dp-project-title"
                            data-i18n-id="{{ $cleanTitleId }}"
                            data-i18n-en="{{ $cleanTitleEn }}">{{ $isEn ? $cleanTitleEn : $cleanTitleId }}</h1>
                    </div>

                    {{-- Description --}}
                    <div class="dp-sidebar-element" data-dp-anim="fade-up">
                        <p class="dp-project-desc"
                           data-i18n-id="{{ $project->description }}"
                           data-i18n-en="{{ $project->description_en ?? $project->description }}">{{ app()->getLocale() === 'en' ? ($project->description_en ?? $project->description) : $project->description }}</p>
                    </div>

                    {{-- Tech Stack (Plain Text) --}}
                    @if($project->tech_stack_badges)
                    <div class="dp-sidebar-element" data-dp-anim="fade-up">
                        <p class="dp-tech-plain-text">
                            {{ implode(', ', array_column($project->tech_stack_badges, 'name')) }}
                        </p>
                    </div>
                    @endif

                    {{-- CTA Action Buttons --}}
                    <div class="dp-sidebar-element dp-cta-wrap" data-dp-anim="fade-up">
                        @if($project->safeUrl('live_demo_url'))
                            <a href="{{ $project->safeUrl('live_demo_url') }}" target="_blank" rel="noopener" class="dp-btn-cta dp-btn-cta--primary">
                                <span data-i18n-id="{{ __('portfolio.project_live_demo', [], 'id') }}"
                                      data-i18n-en="{{ __('portfolio.project_live_demo', [], 'en') }}">{{ __('portfolio.project_live_demo') }}</span>
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                        @endif

                        @if($project->safeUrl('github_url'))
                            <a href="{{ $project->safeUrl('github_url') }}" target="_blank" rel="noopener" class="dp-btn-cta dp-btn-cta--secondary">
                                <i class="bi bi-github"></i>
                                <span data-i18n-id="{{ __('portfolio.project_github', [], 'id') }}"
                                      data-i18n-en="{{ __('portfolio.project_github', [], 'en') }}">{{ __('portfolio.project_github') }}</span>
                            </a>
                        @else
                            <div class="dp-btn-cta dp-btn-cta--locked" title="Repository ini bersifat privat untuk menjaga kerahasiaan data agensi/klien.">
                                <i class="bi bi-lock-fill"></i>
                                <span data-i18n-id="{{ __('portfolio.project_private_repo', [], 'id') }}"
                                      data-i18n-en="{{ __('portfolio.project_private_repo', [], 'en') }}">{{ __('portfolio.project_private_repo') }}</span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
    @vite(['resources/sass/detail-project.scss', 'resources/js/detail-animations.js'])
@endpush
