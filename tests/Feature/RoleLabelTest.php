<?php

// The portfolio presents its owner as a Backend Developer x DevOps, everywhere the role is stated.

test('the role label is Backend Developer x DevOps in both languages', function (string $locale) {
    expect(__('portfolio.hero_role', [], $locale))->toBe('Backend Developer × DevOps');
})->with(['id', 'en']);

test('the home page states the new role and no longer calls the owner a front-end developer', function (string $locale) {
    $html = $this->withSession(['locale' => $locale])->get('/')->assertOk()->getContent();

    expect($html)->toContain('Backend Developer × DevOps')
        ->and($html)->toContain('<strong>Backend Developer</strong>');

    foreach (['Front-End Developer', 'Front End Enthusiast', 'Web Developer'] as $old) {
        expect($html)->not->toContain($old);
    }
})->with(['id', 'en']);

test('the role label shows in the hero badge and on the About photo card, in both languages', function () {
    $html = $this->get('/')->getContent();

    expect(substr_count($html, 'Backend Developer × DevOps'))->toBeGreaterThanOrEqual(4);
});

test('the about text is consistent with the stack: backend work, DevOps still being grown into', function (string $locale) {
    $about = __('portfolio.about_p1', [], $locale).' '.__('portfolio.about_p2', [], $locale).' '.__('portfolio.hero_extra', [], $locale);

    expect($about)->toContain('Laravel')->and($about)->toContain('DevOps');
    foreach (['React', 'Tailwind', 'Core Web Vitals', 'Design System', 'antarmuka'] as $frontendClaim) {
        expect($about)->not->toContain($frontendClaim);
    }
})->with(['id', 'en']);

test('the About text never claims DevOps mastery', function () {
    $en = strtolower(__('portfolio.about_p1', [], 'en').' '.__('portfolio.about_p2', [], 'en'));

    foreach (['expert', 'master', 'senior', 'years of experience'] as $overclaim) {
        expect($en)->not->toContain($overclaim);
    }
    expect($en)->toMatch('/growing|learning|step by step/');
});

test('the past project roles are left as they were (they describe those projects, not the owner today)', function () {
    $this->seed(Database\Seeders\ProjectSeeder::class);

    expect($this->get('/')->getContent())->toContain('FrontEnd');
});
