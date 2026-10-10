<?php

test('home page returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('home page uses the correct view', function () {
    $response = $this->get('/');

    $response->assertViewIs('pages.home');
});

test('home page contains expected portfolio elements', function () {
    $response = $this->get('/');

    $response->assertSee('Zenifen Caesarof Agusti');
    $response->assertSee('Kontak Saya');
    $response->assertSee('Tentang Saya');
});

test('home page has contact section', function () {
    $response = $this->get('/');

    $response->assertSee('contact');
});

test('home page returns 404 for non-existent page', function () {
    $response = $this->get('/non-existent-page');

    $response->assertStatus(404);
});

test('home page links to every seeded project detail page', function () {
    $this->seed(Database\Seeders\ProjectSeeder::class);

    $slugs = App\Models\Project::pluck('slug');
    expect($slugs)->toHaveCount(3);

    $response = $this->get('/')->assertOk();

    foreach ($slugs as $slug) {
        $response->assertSee('href="'.route('project.show', $slug).'"', false);
    }
});

test('home page renders without any projects', function () {
    expect(App\Models\Project::count())->toBe(0);

    $this->get('/')
        ->assertOk()
        ->assertViewHas('projects', fn ($projects) => $projects->isEmpty());
});

test('the html lang attribute states the language the page is rendered in', function (string $locale) {
    // The language button and screen readers rely on it; it used to be a fixed "en".
    $this->withSession(['locale' => $locale])->get('/')
        ->assertOk()
        ->assertSee('<html lang="'.$locale.'"', false);
})->with(['id', 'en']);
