<?php

use App\Livewire\ContactForm;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Livewire\Mechanisms\HandleRequests\HandleRequests;

// The visitor-facing text follows the session locale. The suite's default is English, but most tests
// below were written against the Indonesian text POST /contact has always shown, so they run as 'id'.
beforeEach(fn () => app()->setLocale('id'));

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
            ->and($html)->toMatch('/<label\b[^>]*\bfor="'.preg_quote($control[1], '/').'"/');
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

test('status messages follow the language of the page', function (string $locale, string $status, string $expected) {
    app()->setLocale($locale);
    Mail::fake();

    $form = filledContactForm();

    if ($status === 'failed') {
        Mail::shouldReceive('to')->andThrow(new RuntimeException('queue unavailable'));
        Log::shouldReceive('error')->once();
    }

    if ($status === 'throttled') {
        foreach (range(1, 5) as $i) {
            $form->set('email', "user{$i}@example.com")->call('submit');
        }
        $form->set('email', 'user6@example.com');
    }

    $form->call('submit')->assertSet('status', $status)->assertSee($expected);
})->with([
    'sent in English' => ['en', 'sent', 'Message sent successfully!'],
    'sent in Indonesian' => ['id', 'sent', 'Pesan berhasil dikirim!'],
    'failed in English' => ['en', 'failed', 'The message could not be sent. Please try again later.'],
    'failed in Indonesian' => ['id', 'failed', 'Pesan gagal dikirim. Silakan coba lagi nanti.'],
    'throttled in English' => ['en', 'throttled', 'Too many requests. Please try again later.'],
    'throttled in Indonesian' => ['id', 'throttled', 'Terlalu banyak permintaan. Silakan coba lagi nanti.'],
]);

test('field errors are short sentences in the language of the page', function (string $locale, string $field, string $value, string $expected) {
    app()->setLocale($locale);

    filledContactForm([$field => $value])
        ->call('submit')
        ->assertHasErrors([$field])
        ->assertSee($expected);
})->with([
    'required, English' => ['en', 'message', '', 'This field is required.'],
    'required, Indonesian' => ['id', 'message', '', 'Wajib diisi.'],
    'email, English' => ['en', 'email', 'john doe@example.com', 'Please enter a valid email address.'],
    'email, Indonesian' => ['id', 'email', 'john doe@example.com', 'Masukkan alamat email yang valid.'],
    'name too long, English' => ['en', 'first_name', str_repeat('a', 256), 'Please use at most 255 characters.'],
    'name too long, Indonesian' => ['id', 'first_name', str_repeat('a', 256), 'Maksimal 255 karakter.'],
    'message too long, English' => ['en', 'message', str_repeat('m', 5001), 'Please use at most 5000 characters.'],
    'control character, English' => ['en', 'last_name', "Do\x00e", 'This value contains characters that are not allowed.'],
    'control character, Indonesian' => ['id', 'last_name', "Do\x00e", 'Berisi karakter yang tidak diizinkan.'],
]);

test('labels carry both languages so the language button can switch them without a reload', function () {
    app()->setLocale('en');
    $html = Livewire::test(ContactForm::class)->html();

    foreach ([['First name', 'Nama depan'], ['Last name', 'Nama belakang'], ['Message', 'Pesan'], ['Send message', 'Kirim pesan']] as [$en, $id]) {
        expect($html)->toContain('data-i18n-en="'.$en.'"')->toContain('data-i18n-id="'.$id.'"');
    }
});

test('nothing inside the component uses the scroll-reveal classes that Livewire would undo', function () {
    // base.css hides these (opacity: 0) until JavaScript adds `.reveal-active`. Livewire restores the
    // class attribute from the server on every update, so a field using one would vanish after submit.
    $html = Livewire::test(ContactForm::class)->html();

    foreach (['form-group', 'animate-on-scroll', 'animate-text', 'animate-buttons', 'reveal-ready', 'text-reveal', 'btn-send-contact'] as $class) {
        expect($html)->not->toMatch('/class="[^"]*(?<![\w-])'.$class.'(?![\w-])/');
    }
});

test('the home page puts the form inside the contact card, after the contact buttons', function () {
    $html = $this->get('/')->assertOk()->assertSeeLivewire(ContactForm::class)->getContent();

    preg_match('#<section id="contact".*?</section>#s', $html, $section);

    expect($section)->not->toBeEmpty()
        ->and($section[0])->toContain('wire:id=')
        ->and(strrpos($section[0], 'contact-btn-classic'))->toBeLessThan(strpos($section[0], 'wire:id='));
});

test('the component follows the language the visitor chose after it was rendered', function () {
    // Livewire remembers the locale of the first render and restores it on every update, which
    // would override the language picked with the language button (stored in the session).
    app()->setLocale('en');
    $form = Livewire::test(ContactForm::class);

    session(['locale' => 'id']);

    $form->call('submit')
        ->assertSee('Wajib diisi.')
        ->assertDontSee('This field is required.');
});

test('it re-renders in the new language when the page announces a language change', function () {
    app()->setLocale('en');
    $form = Livewire::test(ContactForm::class)->call('submit')->assertSee('This field is required.');

    session(['locale' => 'id']);

    $form->dispatch('locale-changed')
        ->assertSee('Wajib diisi.')
        ->assertDontSee('This field is required.');
});


test('a field fixed before the language changed no longer shows an error afterwards', function () {
    app()->setLocale('en');
    $form = Livewire::test(ContactForm::class)->call('submit')->assertHasErrors(['first_name', 'last_name']);

    $form->set('first_name', 'John');
    session(['locale' => 'id']);

    $form->dispatch('locale-changed')
        ->assertHasNoErrors(['first_name'])
        ->assertHasErrors(['last_name'])
        ->assertSee('Wajib diisi.');
});
