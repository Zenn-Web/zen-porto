<?php

test('user factory creates valid user', function () {
    $user = \App\Models\User::factory()->create();

    expect($user)->toBeInstanceOf(\App\Models\User::class);
    expect($user->name)->toBeString();
    expect($user->email)->toBeString();
    expect($user->email_verified_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('user can be created with specific attributes', function () {
    $user = \App\Models\User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    expect($user->name)->toEqual('Test User');
    expect($user->email)->toEqual('test@example.com');
});

test('database can store and retrieve users', function () {
    $user = \App\Models\User::factory()->create([
        'name' => 'Database User',
        'email' => 'db@example.com',
    ]);

    $retrieved = \App\Models\User::where('email', 'db@example.com')->first();
    expect($retrieved->name)->toEqual('Database User');
});
