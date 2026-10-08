<?php

use App\Support\ContactRules;
use Illuminate\Support\Facades\Validator;

function contactErrors(array $overrides = []): array
{
    $payload = array_merge([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'This is a test message.',
    ], $overrides);

    return Validator::make($payload, ContactRules::rules())->errors()->keys();
}

test('a complete valid contact payload passes the shared rules', function () {
    expect(contactErrors())->toBe([]);
});

test('each contact field is required', function () {
    $errors = Validator::make([], ContactRules::rules())->errors()->keys();

    expect($errors)->toBe(['first_name', 'last_name', 'email', 'message']);
});

test('shared rules reject the value for the field', function (string $field, string $value) {
    expect(contactErrors([$field => $value]))->toBe([$field]);
})->with([
    'line break in first name (header injection)' => ['first_name', "John\r\nBcc: victim@example.com"],
    'control character in last name' => ['last_name', "Do\x00e"],
    'first name over 255 characters' => ['first_name', str_repeat('a', 256)],
    'email with a space (not RFC)' => ['email', 'john doe@example.com'],
    'email without a domain' => ['email', 'john@'],
    'email over 255 characters' => ['email', str_repeat('a', 244).'@example.com'],
    'message over 5000 characters' => ['message', str_repeat('m', 5001)],
]);

test('shared rules accept the boundary values', function (string $field, string $value) {
    expect(contactErrors([$field => $value]))->toBe([]);
})->with([
    'first name of exactly 255 characters' => ['first_name', str_repeat('a', 255)],
    'message of exactly 5000 characters' => ['message', str_repeat('m', 5000)],
    'multi-line message (newlines allowed in the body)' => ['message', "Line one\nLine two"],
]);
