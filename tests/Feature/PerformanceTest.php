<?php

test('home page responds within acceptable time', function () {
    $start = microtime(true);

    $this->get('/');

    $duration = (microtime(true) - $start) * 1000;
    expect($duration)->toBeLessThan(2000);
});

test('contact form validation responds within acceptable time', function () {
    $start = microtime(true);

    $this->post(route('contact.store'), []);

    $duration = (microtime(true) - $start) * 1000;
    expect($duration)->toBeLessThan(2000);
});

test('multiple sequential requests maintain performance', function () {
    $times = [];

    for ($i = 0; $i < 10; $i++) {
        $start = microtime(true);
        $this->get('/');
        $times[] = (microtime(true) - $start) * 1000;
    }

    $average = array_sum($times) / count($times);
    expect($average)->toBeLessThan(1500);
});

test('contact form with valid data responds within acceptable time', function () {
    $start = microtime(true);

    $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'email'      => 'john@example.com',
        'message'    => 'Performance test message for contact form.',
    ]);

    $duration = (microtime(true) - $start) * 1000;
    expect($duration)->toBeLessThan(3000);
});
