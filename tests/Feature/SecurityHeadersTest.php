<?php

use App\Models\Project;
use Illuminate\Support\Facades\Route;

function assertBaselineSecurityHeaders(Illuminate\Testing\TestResponse $response): void
{
    $response
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN');

    $policy = $response->headers->get('Permissions-Policy');

    expect($policy)->not->toBeNull();

    foreach (['camera', 'microphone', 'geolocation', 'payment', 'usb'] as $feature) {
        expect($policy)->toContain("{$feature}=()");
    }

    // Report-only or enforcing CSP must not be shipped silently by this middleware.
    expect($response->headers->has('Content-Security-Policy'))->toBeFalse();
}

test('web pages send baseline security headers', function () {
    Project::create(['title' => 'Probe', 'slug' => 'header-probe', 'year' => '2026']);

    assertBaselineSecurityHeaders($this->get('/')->assertOk());
    assertBaselineSecurityHeaders($this->get('/project/header-probe')->assertOk());
});

test('not found responses send baseline security headers', function () {
    assertBaselineSecurityHeaders($this->get('/project/does-not-exist')->assertNotFound());
});

test('no content responses send baseline security headers', function () {
    assertBaselineSecurityHeaders($this->post('/lang/en')->assertNoContent());
});

test('redirect responses send baseline security headers', function () {
    assertBaselineSecurityHeaders($this->post(route('contact.store'), [])->assertRedirect());
});

test('api responses send baseline security headers', function () {
    assertBaselineSecurityHeaders($this->getJson('/api/projects')->assertOk());
});

test('security headers already set by the response are preserved', function () {
    Route::get('/__security-header-probe', fn () => response('ok')->withHeaders([
        'X-Content-Type-Options' => 'custom-nosniff',
        'Referrer-Policy' => 'no-referrer',
        'Permissions-Policy' => 'camera=(self)',
        'X-Frame-Options' => 'DENY',
    ]));

    $this->get('/__security-header-probe')
        ->assertOk()
        ->assertHeader('X-Content-Type-Options', 'custom-nosniff')
        ->assertHeader('Referrer-Policy', 'no-referrer')
        ->assertHeader('Permissions-Policy', 'camera=(self)')
        ->assertHeader('X-Frame-Options', 'DENY');
});
