@extends('layout.welcome')

@section('title', __('portfolio.contact_page_title') . ' · Zenifen Agusti')

@section('content')
    <section class="contact-section contact-page">
        <div class="container flex justify-center px-6">
            <div class="contact-card-classic p-6 md:p-12 text-center">
                <a href="/" class="contact-page-back mb-6">
                    <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                    <span data-i18n-id="{{ __('portfolio.contact_page_back', [], 'id') }}"
                          data-i18n-en="{{ __('portfolio.contact_page_back', [], 'en') }}">{{ __('portfolio.contact_page_back') }}</span>
                </a>

                <p class="contact-eyebrow-classic mb-2"
                   data-i18n-id="{{ __('portfolio.contact_eyebrow', [], 'id') }}"
                   data-i18n-en="{{ __('portfolio.contact_eyebrow', [], 'en') }}">{{ __('portfolio.contact_eyebrow') }}</p>

                <h1 class="contact-title-classic mb-4"
                    data-i18n-id="{{ __('portfolio.contact_page_title', [], 'id') }}"
                    data-i18n-en="{{ __('portfolio.contact_page_title', [], 'en') }}">{{ __('portfolio.contact_page_title') }}</h1>

                <p class="contact-subtitle-classic mb-6"
                   data-i18n-id="{{ __('portfolio.contact_page_lead', [], 'id') }}"
                   data-i18n-en="{{ __('portfolio.contact_page_lead', [], 'en') }}">{{ __('portfolio.contact_page_lead') }}</p>

                <div class="contact-divider-classic mx-auto mb-8" aria-hidden="true"></div>

                <livewire:contact-form />
            </div>
        </div>
    </section>
@endsection
