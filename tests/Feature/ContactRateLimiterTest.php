<?php

use App\Support\ContactRateLimiter;

test('a visitor who has not submitted is not limited', function () {
    expect((new ContactRateLimiter)->tooManyAttempts('10.0.0.1', 'john@example.com'))->toBeFalse();
});

test('an ip address is limited after five submissions in a minute, not before', function () {
    $limiter = new ContactRateLimiter;

    foreach (range(1, 4) as $i) {
        $limiter->hit('10.0.0.1', "user{$i}@example.com");
    }
    expect($limiter->tooManyAttempts('10.0.0.1', 'fresh@example.com'))->toBeFalse();

    $limiter->hit('10.0.0.1', 'user5@example.com');

    expect($limiter->tooManyAttempts('10.0.0.1', 'fresh@example.com'))->toBeTrue()
        ->and($limiter->tooManyAttempts('10.0.0.2', 'fresh@example.com'))->toBeFalse();
});

test('an email is limited after three submissions in an hour whatever its case or padding, from any ip', function () {
    $limiter = new ContactRateLimiter;

    $limiter->hit('10.0.0.1', 'john@example.com');
    $limiter->hit('10.0.0.2', 'JOHN@example.com');
    expect($limiter->tooManyAttempts('10.0.0.9', 'john@example.com'))->toBeFalse();

    $limiter->hit('10.0.0.3', '  John@Example.COM ');

    expect($limiter->tooManyAttempts('10.0.0.9', 'john@EXAMPLE.com'))->toBeTrue()
        ->and($limiter->tooManyAttempts('10.0.0.9', 'someone.else@example.com'))->toBeFalse();
});

test('a blank or missing email is never email-limited', function (mixed $blank) {
    $limiter = new ContactRateLimiter;

    foreach (range(1, 10) as $i) {
        $limiter->hit("10.0.1.{$i}", $blank);
    }

    expect($limiter->tooManyAttempts('10.0.2.1', $blank))->toBeFalse();
})->with([
    'empty string' => [''],
    'whitespace only' => ['   '],
    'null' => [null],
    'not a string' => [['john@example.com']],
]);

test('the ip limit lasts one minute while the email limit lasts one hour', function () {
    $limiter = new ContactRateLimiter;

    foreach (range(1, 5) as $i) {
        $limiter->hit('10.0.0.1', 'same@example.com');
    }
    expect($limiter->tooManyAttempts('10.0.0.1', 'new@example.com'))->toBeTrue()
        ->and($limiter->tooManyAttempts('10.0.0.2', 'same@example.com'))->toBeTrue();

    $this->travel(61)->seconds();

    expect($limiter->tooManyAttempts('10.0.0.1', 'new@example.com'))->toBeFalse()
        ->and($limiter->tooManyAttempts('10.0.0.2', 'same@example.com'))->toBeTrue();

    $this->travel(3600)->seconds();

    expect($limiter->tooManyAttempts('10.0.0.2', 'same@example.com'))->toBeFalse();
});
