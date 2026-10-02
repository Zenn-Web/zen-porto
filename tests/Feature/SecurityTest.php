<?php

use Illuminate\Support\Facades\Mail;

test('contact form prevents xss via script injection', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => '<script>alert("xss")</script>',
        'last_name' => '<img src=x onerror=alert(1)>',
        'email' => 'test@example.com',
        'message' => '<script>document.cookie</script>',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
});

test('home page escapes html in output', function () {
    $response = $this->get('/');
    $content = $response->getContent();

    expect($content)->not->toContain('<?php');
});

test('application does not expose sensitive environment data', function () {
    $response = $this->get('/');
    $content = $response->getContent();

    expect($content)->not->toContain('APP_KEY');
    expect($content)->not->toContain('DB_PASSWORD');
    expect($content)->not->toContain('MAIL_PASSWORD');
});

test('contact form validates oversized input to prevent dos', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => str_repeat('A', 5000),
        'last_name' => str_repeat('B', 5000),
        'email' => 'test@example.com',
        'message' => str_repeat('C', 100000),
    ]);

    // first_name and last_name have max:255 validation, message does not
    $response->assertSessionHasErrors(['first_name', 'last_name']);
});

test('contact form rejects duplicate submissions gracefully', function () {
    Mail::fake();

    $response1 = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'Test message.',
    ]);
    $response1->assertRedirect();

    $response2 = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'Test message.',
    ]);
    $response2->assertRedirect();
});

test('application does not leak route information via unexpected methods', function () {
    $response = $this->put('/contact');
    expect(in_array($response->getStatusCode(), [405, 404]))->toBeTrue();

    $response = $this->patch('/contact');
    expect(in_array($response->getStatusCode(), [405, 404]))->toBeTrue();

    $response = $this->delete('/contact');
    expect(in_array($response->getStatusCode(), [405, 404]))->toBeTrue();
});

function maliciousProject(): App\Models\Project
{
    return App\Models\Project::create([
        'title' => '<script>alert(1)</script>',
        'title_en' => "<script>alert('en')</script>",
        'slug' => 'xss-probe',
        'category' => '<img src=x onerror=alert(1)>',
        'category_en' => '"><svg onload=alert(2)>',
        'description' => '<b onmouseover=alert(3)>desc</b>',
        'description_en' => '<b onmouseover=alert(33)>desc en</b>',
        'flow_description' => "Step one <iframe src=javascript:alert(4)>\nStep two",
        'flow_description_en' => "Step EN <img src=x onerror=alert(44)>\nStep EN two",
        'year' => '2026',
        'tech_stack' => ['Laravel'],
    ]);
}

test('home page escapes untrusted project fields in text and data attributes', function (string $locale) {
    maliciousProject();

    $content = $this->withSession(['locale' => $locale])->get('/')->assertOk()->getContent();

    // No executable payload survives anywhere in the document.
    expect($content)
        ->not->toContain('<script>alert(1)</script>')
        ->not->toContain("<script>alert('en')</script>")
        ->not->toContain('<img src=x onerror=alert(1)>')
        ->not->toContain('<svg onload=alert(2)>')
        ->not->toContain('<b onmouseover=');

    // Title, category and description attributes hold escaped text only.
    expect($content)
        ->toContain('data-i18n-id="&lt;script&gt;alert(1)&lt;/script&gt;"')
        ->toContain('data-i18n-en="&lt;script&gt;alert(&#039;en&#039;)&lt;/script&gt;"')
        ->toContain('data-i18n-id="&lt;img src=x onerror=alert(1)&gt;"')
        ->toContain('data-i18n-en="&quot;&gt;&lt;svg onload=alert(2)&gt;"')
        ->toContain('data-i18n-id="&lt;b onmouseover=alert(3)&gt;desc&lt;/b&gt;"')
        ->toContain('data-i18n-en="&lt;b onmouseover=alert(33)&gt;desc en&lt;/b&gt;"');
})->with(['id', 'en']);

test('project detail page escapes untrusted project fields in text and data attributes', function (string $locale) {
    maliciousProject();

    $content = $this->withSession(['locale' => $locale])->get('/project/xss-probe')->assertOk()->getContent();

    expect($content)
        ->not->toContain('<script>alert(1)</script>')
        ->not->toContain("<script>alert('en')</script>")
        ->not->toContain('<iframe src=javascript:alert(4)>')
        ->not->toContain('<img src=x onerror=alert(44)>')
        ->not->toContain('<b onmouseover=');

    expect($content)
        ->toContain('data-i18n-id="&lt;script&gt;alert(1)&lt;/script&gt;"')
        ->toContain('data-i18n-en="&lt;script&gt;alert(&#039;en&#039;)&lt;/script&gt;"')
        ->toContain('data-i18n-id="&lt;b onmouseover=alert(3)&gt;desc&lt;/b&gt;"')
        // Multi-line flow description stays plain text (no <br> markup in attributes).
        ->toContain("data-i18n-id=\"Step one &lt;iframe src=javascript:alert(4)&gt;\nStep two\"")
        ->toContain("data-i18n-en=\"Step EN &lt;img src=x onerror=alert(44)&gt;\nStep EN two\"");
})->with(['id', 'en']);

test('trusted static translation markup is still rendered on the home page', function () {
    $content = $this->withSession(['locale' => 'en'])->get('/')->assertOk()->getContent();

    expect($content)->toContain('<strong>Front-End Developer</strong>');
});

test('language switcher never parses data attributes as html', function () {
    $script = file_get_contents(resource_path('js/alpine-init.js'));

    expect($script)
        ->not->toContain('innerHTML')
        ->not->toContain('outerHTML')
        ->not->toContain('insertAdjacentHTML')
        ->toContain('textContent');
});
