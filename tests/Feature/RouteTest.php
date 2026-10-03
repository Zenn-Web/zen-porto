<?php

use App\Models\Project;
use Database\Seeders\ProjectSeeder;
use Illuminate\Support\Facades\Route;

/**
 * Slugs shipped by ProjectSeeder. Public project URLs are part of the site's
 * contract (links, search engines), so they must keep resolving.
 */
function seededProjectSlugs(): array
{
    return [
        'company-website-eyegil',
        'umkm-business-tumbuh',
        'training-platform-amazain',
    ];
}

/**
 * Application routes as "METHODS uri" strings, excluding framework/vendor routes.
 */
function applicationRouteSignatures(): array
{
    return collect(Route::getRoutes()->getRoutes())
        ->reject(fn ($route) => in_array($route->uri(), ['up', 'storage/{path}'], true)
            || str_starts_with($route->uri(), 'sanctum/')
            || str_starts_with($route->uri(), '_boost/')
            // Livewire's own asset/update endpoints (the prefix carries an install-specific hash).
            || preg_match('#^livewire(-[0-9a-f]+)?/#', $route->uri()) === 1)
        ->map(fn ($route) => implode('|', $route->methods()).' '.$route->uri())
        ->sort()
        ->values()
        ->all();
}

test('application routes are properly registered', function () {
    $routes = collect(Route::getRoutes()->getRoutes());

    $homeRoute = $routes->first(fn ($r) => $r->uri() === '/' && $r->methods() === ['GET', 'HEAD']);
    expect($homeRoute)->not->toBeNull();

    $contactRoute = $routes->first(fn ($r) => $r->uri() === 'contact' && in_array('POST', $r->methods()));
    expect($contactRoute)->not->toBeNull();
    expect($contactRoute->getName())->toEqual('contact.store');
});

test('contact route uses POST method only', function () {
    $routes = collect(Route::getRoutes()->getRoutes());
    $contactRoute = $routes->first(fn ($r) => $r->uri() === 'contact');

    expect($contactRoute)->not->toBeNull();
    expect($contactRoute->methods())->toContain('POST');
    expect($contactRoute->methods())->not->toContain('GET');
});

test('public route table matches the documented compatibility surface', function () {
    expect(applicationRouteSignatures())->toEqualCanonicalizing([
        'GET|HEAD /',
        'GET|HEAD api/projects',
        'GET|HEAD api/projects/{slug}',
        'POST contact',
        'POST lang/{locale}',
        'GET|HEAD project/{project}',
    ]);
});

test('named routes keep their public urls', function () {
    expect(route('contact.store', absolute: false))->toBe('/contact');
    expect(route('lang.switch', 'en', absolute: false))->toBe('/lang/en');
    expect(route('project.show', 'company-website-eyegil', absolute: false))
        ->toBe('/project/company-website-eyegil');
});

test('the removed api contact endpoint is not registered for any method', function () {
    $contactApiRoutes = collect(Route::getRoutes()->getRoutes())
        ->filter(fn ($route) => $route->uri() === 'api/contact');

    expect($contactApiRoutes)->toBeEmpty();
});

test('seeded project slugs resolve to their detail pages', function (string $slug) {
    $this->seed(ProjectSeeder::class);

    $this->get("/project/{$slug}")
        ->assertOk()
        ->assertViewIs('pages.project.show')
        ->assertViewHas('project', fn (Project $project) => $project->slug === $slug);
})->with(seededProjectSlugs());

test('unknown project slugs return 404 alongside seeded projects', function () {
    $this->seed(ProjectSeeder::class);

    $this->get('/project/not-a-real-project')->assertNotFound();
});

test('api exposes every seeded project by slug', function () {
    $this->seed(ProjectSeeder::class);

    $listed = collect($this->getJson('/api/projects')->assertOk()->json('data'))->pluck('slug');

    expect($listed->all())->toEqualCanonicalizing(seededProjectSlugs());

    foreach (seededProjectSlugs() as $slug) {
        $this->getJson("/api/projects/{$slug}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', $slug);
    }
});

test('language switch stores supported locales and answers with no content', function (string $locale) {
    $this->post(route('lang.switch', $locale))
        ->assertNoContent()
        ->assertSessionHas('locale', $locale);
})->with(['id', 'en']);

test('language switch ignores unsupported locales', function () {
    $this->post(route('lang.switch', 'fr'))
        ->assertNoContent()
        ->assertSessionMissing('locale');
});

test('language switch does not accept GET requests', function () {
    $this->get('/lang/en')->assertMethodNotAllowed();
});
