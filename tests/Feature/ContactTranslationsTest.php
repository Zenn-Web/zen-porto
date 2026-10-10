<?php

use App\Services\ContactMessageService;
use App\Support\ContactRateLimiter;

const CONTACT_FORM_KEYS = [
    'contact_form_title',
    'contact_form_first_name',
    'contact_form_last_name',
    'contact_form_email',
    'contact_form_message',
    'contact_form_submit',
    'contact_form_sending',
    'contact_form_sent',
    'contact_form_failed',
    'contact_form_throttled',
    'contact_error_required',
    'contact_error_email',
    'contact_error_max',
    'contact_error_invalid',
];

test('every contact form string exists in both languages (a missing key would show the raw key name)', function (string $locale) {
    foreach (CONTACT_FORM_KEYS as $key) {
        expect(__("portfolio.{$key}", [], $locale))
            ->not->toBe("portfolio.{$key}", "{$key} is missing for locale {$locale}")
            ->not->toBeEmpty();
    }
})->with(['id', 'en']);

test('the two languages really differ where the text is translated', function () {
    foreach (['contact_form_submit', 'contact_form_sent', 'contact_error_required'] as $key) {
        expect(__("portfolio.{$key}", [], 'id'))->not->toBe(__("portfolio.{$key}", [], 'en'));
    }
});

test('the Indonesian status messages equal what POST /contact flashes, so the two paths cannot drift', function () {
    expect(__('portfolio.contact_form_sent', [], 'id'))->toBe(ContactMessageService::SUCCESS_MESSAGE)
        ->and(__('portfolio.contact_form_failed', [], 'id'))->toBe(ContactMessageService::FAILURE_MESSAGE)
        ->and(__('portfolio.contact_form_throttled', [], 'id'))->toBe(ContactRateLimiter::THROTTLED_MESSAGE);
});

test('the max-length message carries its :max placeholder in both languages', function (string $locale) {
    expect(__('portfolio.contact_error_max', ['max' => 255], $locale))->toContain('255')
        ->and(__('portfolio.contact_error_max', [], $locale))->toContain(':max');
})->with(['id', 'en']);
