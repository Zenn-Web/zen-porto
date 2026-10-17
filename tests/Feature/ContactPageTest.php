<?php

use App\Livewire\ContactForm;

// The message form lives on its own page (/contact); the home page only links to it.

function homeContactSection(string $html): string
{
    preg_match('#<section id="contact".*?</section>#s', $html, $m);

    return $m[0] ?? '';
}

test('the contact page renders the form with the site layout', function () {
    $this->get('/contact')
        ->assertOk()
        ->assertViewIs('pages.contact')
        ->assertSeeLivewire(ContactForm::class);
});

test('the home page no longer carries the form, only a link to the contact page', function () {
    $response = $this->get('/')->assertOk()->assertDontSeeLivewire(ContactForm::class);
    $section = homeContactSection($response->getContent());

    expect($section)->not->toContain('wire:id=')
        ->and($section)->toContain('href="'.route('contact.show').'"')
        ->and($section)->toContain('data-i18n-en="'.__('portfolio.contact_open_form', [], 'en').'"')
        ->and($section)->toContain('data-i18n-id="'.__('portfolio.contact_open_form', [], 'id').'"');
});

test('the home contact shortcuts (email, WhatsApp, location) are untouched', function () {
    $section = homeContactSection($this->get('/')->getContent());

    expect($section)->toContain('mailto:')->and($section)->toContain('wa.me/')->and($section)->toContain('Yogyakarta');
});

test('the contact page has its own title, a heading and a way back to the home page', function () {
    $html = $this->get('/contact')->getContent();

    expect($html)->toContain('<title>'.__('portfolio.contact_page_title').' · Zenifen Agusti</title>')
        ->and($html)->toContain('<h1')
        ->and($html)->toContain(__('portfolio.contact_page_lead'))
        ->and($html)->toMatch('#<a[^>]*class="[^"]*contact-page-back[^"]*"[^>]*href="/"|<a[^>]*href="/"[^>]*class="[^"]*contact-page-back#');
});

test('other pages keep the default title', function () {
    expect($this->get('/')->getContent())->toContain('<title>Zenifen Agusti</title>');
});

test('the navbar on the contact page is solid, as there is no hero under it', function () {
    preg_match('/<nav class="([^"]*custom-navbar[^"]*)"/', $this->get('/contact')->getContent(), $m);

    expect($m[1] ?? '')->not->toBe('')->and($m[1])->not->toContain('is-over-hero');
});

test('the contact page follows the chosen language', function (string $locale) {
    $html = $this->withSession(['locale' => $locale])->get('/contact')->getContent();

    expect($html)->toContain('<html lang="'.$locale.'"')
        ->and($html)->toContain(__('portfolio.contact_page_title', [], $locale));
})->with(['id', 'en']);

test('the contact page still posts through the Livewire component and the legacy POST route is kept', function () {
    expect(route('contact.show', absolute: false))->toBe('/contact')
        ->and(route('contact.store', absolute: false))->toBe('/contact');
});

test('every string of the contact page exists in both languages', function (string $key) {
    $id = __("portfolio.{$key}", [], 'id');
    $en = __("portfolio.{$key}", [], 'en');

    expect($id)->not->toBe("portfolio.{$key}")->and($en)->not->toBe("portfolio.{$key}")->and($id)->not->toBe($en);
})->with(['contact_open_form', 'contact_page_title', 'contact_page_lead', 'contact_page_back']);
