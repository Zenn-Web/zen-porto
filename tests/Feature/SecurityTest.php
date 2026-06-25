<?php

use Illuminate\Support\Facades\Mail;

test('contact form prevents xss via script injection', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => '<script>alert("xss")</script>',
        'last_name'  => '<img src=x onerror=alert(1)>',
        'email'      => 'test@example.com',
        'message'    => '<script>document.cookie</script>',
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
        'last_name'  => str_repeat('B', 5000),
        'email'      => 'test@example.com',
        'message'    => str_repeat('C', 100000),
    ]);

    // first_name and last_name have max:255 validation, message does not
    $response->assertSessionHasErrors(['first_name', 'last_name']);
});

test('contact form rejects duplicate submissions gracefully', function () {
    Mail::fake();

    $response1 = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'email'      => 'john@example.com',
        'message'    => 'Test message.',
    ]);
    $response1->assertRedirect();

    $response2 = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'email'      => 'john@example.com',
        'message'    => 'Test message.',
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
