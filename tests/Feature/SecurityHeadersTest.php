<?php

use App\Models\Project;

function assertBaselineSecurityHeaders(Illuminate\Testing\TestResponse $response): void
{
    $response
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

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

test('error and redirect responses send baseline security headers', function () {
    assertBaselineSecurityHeaders($this->get('/project/does-not-exist')->assertNotFound());
    assertBaselineSecurityHeaders($this->post('/lang/en'));
});

test('api responses send baseline security headers', function () {
    assertBaselineSecurityHeaders($this->getJson('/api/projects')->assertOk());
});
