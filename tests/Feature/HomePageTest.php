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
