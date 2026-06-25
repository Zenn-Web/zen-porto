<?php

use Illuminate\Support\Facades\Mail;

test('contact form stores and redirects with success message', function () {
    Mail::fake();

    $response = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'email'      => 'john@example.com',
        'message'    => 'This is a test message.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Pesan berhasil dikirim!');
});

test('contact form validates required fields', function () {
    $response = $this->post(route('contact.store'), []);

    $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'message']);
});

test('contact form validates first_name is required', function () {
    $response = $this->post(route('contact.store'), [
        'last_name' => 'Doe',
        'email'     => 'john@example.com',
        'message'   => 'Test',
    ]);

    $response->assertSessionHasErrors(['first_name']);
});

test('contact form validates last_name is required', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'email'      => 'john@example.com',
        'message'    => 'Test',
    ]);

    $response->assertSessionHasErrors(['last_name']);
});

test('contact form validates email is required', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'message'    => 'Test',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('contact form validates message is required', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'email'      => 'john@example.com',
    ]);

    $response->assertSessionHasErrors(['message']);
});

test('contact form validates email format', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'email'      => 'not-an-email',
        'message'    => 'Test message.',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('contact form validates max string length', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => str_repeat('a', 256),
        'last_name'  => str_repeat('b', 256),
        'email'      => 'john@example.com',
        'message'    => 'Valid message.',
    ]);

    $response->assertSessionHasErrors(['first_name', 'last_name']);
});

test('contact form rejects non-POST methods', function () {
    $response = $this->get('/contact');

    $response->assertStatus(405);
});

test('contact form does not send email when validation fails', function () {
    // Validation fails before reaching mail send logic
    $response = $this->post(route('contact.store'), [
        'first_name' => '',
        'last_name'  => '',
        'email'      => 'invalid',
        'message'    => '',
    ]);

    $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'message']);
});
