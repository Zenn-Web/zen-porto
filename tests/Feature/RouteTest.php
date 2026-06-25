<?php

test('application routes are properly registered', function () {
    $routes = collect(Route::getRoutes()->getRoutes());

    $homeRoute = $routes->first(fn($r) => $r->uri() === '/' && $r->methods() === ['GET', 'HEAD']);
    expect($homeRoute)->not->toBeNull();

    $contactRoute = $routes->first(fn($r) => $r->uri() === 'contact' && in_array('POST', $r->methods()));
    expect($contactRoute)->not->toBeNull();
    expect($contactRoute->getName())->toEqual('contact.store');
});

test('contact route uses POST method only', function () {
    $routes = collect(Route::getRoutes()->getRoutes());
    $contactRoute = $routes->first(fn($r) => $r->uri() === 'contact');

    expect($contactRoute)->not->toBeNull();
    expect($contactRoute->methods())->toContain('POST');
    expect($contactRoute->methods())->not->toContain('GET');
});
