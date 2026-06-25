<?php

uses(Tests\TestCase::class);

test('user model has correct fillable attributes', function () {
    $user = new \App\Models\User();

    expect($user->getFillable())->toEqual([
        'name',
        'email',
        'password',
    ]);
});

test('user model has correct hidden attributes', function () {
    $user = new \App\Models\User();

    expect($user->getHidden())->toEqual([
        'password',
        'remember_token',
    ]);
});

test('user model has correct cast types', function () {
    $user = new \App\Models\User();
    $casts = $user->getCasts();

    expect($casts)->toHaveKey('email_verified_at', 'datetime');
    expect($casts)->toHaveKey('password', 'hashed');
});

test('user model uses expected traits', function () {
    $traits = class_uses(\App\Models\User::class);

    expect($traits)->toContain('Illuminate\Database\Eloquent\Factories\HasFactory');
    expect($traits)->toContain('Illuminate\Notifications\Notifiable');
});

test('user model is an authenticatable', function () {
    $user = new \App\Models\User();

    expect($user)->toBeInstanceOf('Illuminate\Foundation\Auth\User');
});
