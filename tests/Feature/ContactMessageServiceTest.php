<?php

use App\Mail\ContactMessage;
use App\Services\ContactMessageService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

function validatedContact(array $overrides = []): array
{
    return array_merge([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'Hello there',
    ], $overrides);
}

test('queue sends the contact mailable to the configured recipient with the sender as reply-to', function () {
    Mail::fake();
    config(['mail.contact_recipient' => 'owner@example.test']);

    (new ContactMessageService)->queue(validatedContact());

    Mail::assertNothingSent();
    Mail::assertQueued(ContactMessage::class, 1);
    Mail::assertQueued(ContactMessage::class, function (ContactMessage $mail) {
        return $mail->hasTo('owner@example.test')
            && $mail->hasReplyTo('john@example.com')
            && $mail->hasSubject('Pesan Baru dari John Doe')
            && $mail->body === 'Hello there';
    });
});

test('queue logs a failure once with only the exception class, then rethrows it', function () {
    $secret = 'john@example.com';

    Mail::shouldReceive('to')->andThrow(new RuntimeException("queue unavailable for {$secret}: Hello there"));
    Log::shouldReceive('error')
        ->once()
        ->with('Contact message could not be queued.', ['exception' => RuntimeException::class]);

    expect(fn () => (new ContactMessageService)->queue(validatedContact()))
        ->toThrow(RuntimeException::class, "queue unavailable for {$secret}: Hello there");
});
