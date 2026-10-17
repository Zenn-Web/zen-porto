<?php

use Database\Seeders\ProjectSeeder;

// The navbar is transparent over the hero photo and solid again once the About section reaches it.

function navClasses(string $html): string
{
    preg_match('/<nav class="([^"]*custom-navbar[^"]*)"/', $html, $m);

    return $m[1] ?? '';
}

test('the home page starts with the transparent over-hero navbar so there is no flash on load', function () {
    expect(navClasses($this->get('/')->getContent()))->toContain('is-over-hero');
});

test('pages without a hero keep the solid navbar', function () {
    $this->seed(ProjectSeeder::class);

    $html = $this->get('/project/company-website-eyegil')->assertOk()->getContent();

    expect(navClasses($html))->not->toBe('')->and(navClasses($html))->not->toContain('is-over-hero');
});

test('the over-hero styles make the bar transparent and the content light, desktop-only for the links', function () {
    $css = file_get_contents(resource_path('css/components/navbar.css'));

    expect($css)->toContain('.custom-navbar.is-over-hero {')
        ->and($css)->toMatch('/\.custom-navbar\.is-over-hero\s*\{[^}]*background-color:\s*transparent/s')
        ->and($css)->toContain('.custom-navbar.is-over-hero .brand-logo')
        ->and($css)->toContain('.custom-navbar.is-over-hero .btn-contact-me')
        ->and($css)->toMatch('/max-width: 991px\) \{[^}]*\.custom-navbar\.is-over-hero \.navbar-collapse \{\s*background: var\(--bg-card\)/s');

    // The open mobile menu is its own solid panel with dark text: the light link colours must stay desktop-only.
    preg_match('/@media \(min-width: 992px\) \{\s*\/\* over-hero desktop \*\/(.*?)\n\}\n/s', $css, $desktop);
    expect($desktop[1] ?? '')->toContain('.is-over-hero .nav-link');
    expect(substr_count($css, '.is-over-hero .nav-link'))->toBe(substr_count($desktop[1] ?? '', '.is-over-hero .nav-link'));
});

test('the script only toggles the navbar on pages that have a hero', function () {
    $js = file_get_contents(resource_path('js/app.js'));

    expect($js)->toContain("getElementById('home')")
        ->and($js)->toContain("classList.toggle('is-over-hero'");
});
