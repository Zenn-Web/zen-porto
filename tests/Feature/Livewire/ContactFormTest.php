<?php

use App\Livewire\ContactForm;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Livewire\Mechanisms\HandleRequests\HandleRequests;

function filledContactForm(array $overrides = [])
{
    $fields = array_merge([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'This is a test message.',
    ], $overrides);

    $component = Livewire::test(ContactForm::class);

    foreach ($fields as $name => $value) {
        $component->set($name, $value);
    }

    return $component;
}

/**
 * Submits the form through the real Livewire update endpoint (an actual HTTP request), because
 * Livewire::test() always uses one fixed client address and cannot set the request headers.
 * Returns the component's resulting `status`.
 */
function submitOverHttp(string $email, string $remoteAddr, array $headers = []): ?string
{
    $component = Livewire::test(ContactForm::class);
    $state = new ReflectionProperty($component, 'lastState');
    $snapshot = $state->getValue($component)->getSnapshot();

    $response = test()
        ->withServerVariables(['REMOTE_ADDR' => $remoteAddr])
        ->withHeaders(['X-Livewire' => 'true'] + $headers)
        ->postJson(app(HandleRequests::class)->getUpdateUri(), ['components' => [[
            'snapshot' => json_encode($snapshot),
            'updates' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => $email, 'message' => 'Hello'],
            'calls' => [['path' => '', 'method' => 'submit', 'params' => []]],
        ]]])
        ->assertOk();

    return json_decode($response->json('components.0.snapshot'), true)['data']['status'];
}

test('an email is limited to three submissions an hour across ip addresses, whatever its case or padding', function () {
    Mail::fake();

    expect(submitOverHttp('john@example.com', '10.0.0.1'))->toBe('sent')
        ->and(submitOverHttp('JOHN@example.com', '10.0.0.2'))->toBe('sent')
        ->and(submitOverHttp('john@example.com', '10.0.0.3'))->toBe('sent')
        ->and(submitOverHttp('john@EXAMPLE.com', '10.0.0.99'))->toBe('throttled')
        ->and(submitOverHttp('someone.else@example.com', '10.0.0.99'))->toBe('sent');

    Mail::assertQueued(ContactMessage::class, 4);
});

test('a spoofed X-Forwarded-For header cannot be used to dodge the ip limit', function () {
    Mail::fake();

    // The application trusts no proxy, so only the connection's own address counts.
    $statuses = array_map(
        fn (int $i) => submitOverHttp("user{$i}@example.com", '10.7.7.7', ['X-Forwarded-For' => "203.0.113.{$i}"]),
        range(1, 6),
    );

    expect($statuses)->toBe(['sent', 'sent', 'sent', 'sent', 'sent', 'throttled']);
    Mail::assertQueued(ContactMessage::class, 5);
});

test('invalid input is rejected per field, nothing is queued and what was typed is kept', function (string $field, string $value) {
    Mail::fake();

    filledContactForm([$field => $value])
        ->call('submit')
        ->assertHasErrors([$field])
        ->assertSet('status', null)
        ->assertSet($field, $value)
        ->assertDontSee('Pesan berhasil dikirim!');

    Mail::assertNothingQueued();
    Mail::assertNothingSent();
})->with([
    'line break in first name (header injection)' => ['first_name', "John\r\nBcc: victim@example.com"],
    'last name over 255 characters' => ['last_name', str_repeat('b', 256)],
    'email that is not RFC valid' => ['email', 'john doe@example.com'],
    'message over 5000 characters' => ['message', str_repeat('m', 5001)],
    'empty message' => ['message', ''],
]);

test('the sixth submission within a minute from one ip is throttled, queues nothing and keeps the input', function () {
    Mail::fake();

    // Livewire's update requests do not pass through the route's throttle middleware,
    // so the component itself has to refuse the sixth one.
    $form = Livewire::test(ContactForm::class);

    foreach (range(1, 5) as $i) {
        $form->set('first_name', 'John')->set('last_name', 'Doe')
            ->set('email', "user{$i}@example.com")->set('message', "Message {$i}")
            ->call('submit')
            ->assertSet('status', 'sent');
    }

    $form->set('first_name', 'John')->set('last_name', 'Doe')
        ->set('email', 'user6@example.com')->set('message', 'Please keep this text.')
        ->call('submit')
        ->assertSet('status', 'throttled')
        ->assertSet('message', 'Please keep this text.')
        ->assertSee('Terlalu banyak permintaan. Silakan coba lagi nanti.')
        ->assertDontSee('Pesan berhasil dikirim!');

    Mail::assertQueued(ContactMessage::class, 5);
});

test('invalid submissions also count towards the ip limit, like on the POST route', function () {
    Mail::fake();

    $form = Livewire::test(ContactForm::class);

    // Different invalid emails, so only the ip limit (not the per-email limit) can trip.
    foreach (range(1, 5) as $i) {
        $form->set('email', "not-an-email-{$i}")->call('submit')->assertHasErrors(['email']);
    }

    $form->set('first_name', 'John')->set('last_name', 'Doe')
        ->set('email', 'john@example.com')->set('message', 'Hello')
        ->call('submit')
        ->assertSet('status', 'throttled');

    Mail::assertNothingQueued();
});

test('when the message cannot be queued it is not reported as sent, nothing leaks and the input is kept', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('queue unavailable for john@example.com'));
    Log::shouldReceive('error')
        ->once()
        ->with('Contact message could not be queued.', ['exception' => RuntimeException::class]);

    filledContactForm()
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('status', 'failed')
        ->assertSet('first_name', 'John')
        ->assertSet('message', 'This is a test message.')
        ->assertSee('Pesan gagal dikirim. Silakan coba lagi nanti.')
        ->assertDontSee('Pesan berhasil dikirim!')
        ->assertDontSee('queue unavailable')
        ->assertDontSee('RuntimeException');
});

test('the queued payload from the component does not hold personal data in plain text', function () {
    config([
        'queue.default' => 'database',
        'mail.contact_recipient' => 'owner@example.test',
    ]);

    filledContactForm([
        'first_name' => 'Zebulon',
        'email' => 'zebulon.private@example.com',
        'message' => 'very secret body text',
    ])->call('submit')->assertSet('status', 'sent');

    $payloads = DB::table('jobs')->pluck('payload');

    expect($payloads)->toHaveCount(1);
    expect($payloads->first())
        ->not->toContain('very secret body text')
        ->not->toContain('zebulon.private@example.com')
        ->not->toContain('Zebulon');
});

test('every field is reachable through a label that points at it', function () {
    $html = Livewire::test(ContactForm::class)->html();

    foreach (['first_name', 'last_name', 'email', 'message'] as $field) {
        preg_match('/<(?:input|textarea)\b[^>]*\bid="([^"]+)"[^>]*wire:model="'.$field.'"/', $html, $control);

        expect($control)->not->toBeEmpty("no control is bound to {$field}")
            ->and($html)->toContain('<label for="'.$control[1].'">');
    }
});

test('a valid submission queues the message, clears the form and confirms it', function () {
    Mail::fake();
    config(['mail.contact_recipient' => 'owner@example.test']);

    filledContactForm()
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('status', 'sent')
        ->assertSet('first_name', '')
        ->assertSet('last_name', '')
        ->assertSet('email', '')
        ->assertSet('message', '')
        ->assertSee('Pesan berhasil dikirim!');

    Mail::assertNothingSent();
    Mail::assertQueued(ContactMessage::class, 1);
    Mail::assertQueued(ContactMessage::class, function (ContactMessage $mail) {
        return $mail->hasTo('owner@example.test')
            && $mail->hasReplyTo('john@example.com')
            && $mail->hasSubject('Pesan Baru dari John Doe')
            && $mail->body === 'This is a test message.';
    });
});
