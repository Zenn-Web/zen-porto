<?php

use App\Mail\ContactMessage;
use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

function validContactPayload(array $overrides = []): array
{
    return array_merge([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'This is a test message.',
    ], $overrides);
}

test('contact form stores and redirects with success message', function () {
    Mail::fake();

    $response = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'This is a test message.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Pesan berhasil dikirim!');
});

test('contact form validates required fields', function () {
    $response = $this->post(route('contact.store'), []);

    $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'message']);
});

test('contact form validates first_name is required', function () {
    $response = $this->post(route('contact.store'), [
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'Test',
    ]);

    $response->assertSessionHasErrors(['first_name']);
});

test('contact form validates last_name is required', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'email' => 'john@example.com',
        'message' => 'Test',
    ]);

    $response->assertSessionHasErrors(['last_name']);
});

test('contact form validates email is required', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'message' => 'Test',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('contact form validates message is required', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
    ]);

    $response->assertSessionHasErrors(['message']);
});

test('contact form validates email format', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'not-an-email',
        'message' => 'Test message.',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('contact form validates max string length', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => str_repeat('a', 256),
        'last_name' => str_repeat('b', 256),
        'email' => 'john@example.com',
        'message' => 'Valid message.',
    ]);

    $response->assertSessionHasErrors(['first_name', 'last_name']);
});

test('contact form rejects non-POST methods', function () {
    $response = $this->get('/contact');

    $response->assertStatus(405);
});

test('contact form does not send email when validation fails', function () {
    // Validation fails before reaching mail send logic
    $response = $this->post(route('contact.store'), [
        'first_name' => '',
        'last_name' => '',
        'email' => 'invalid',
        'message' => '',
    ]);

    $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'message']);
});

test('contact form rejects messages longer than 5000 characters', function () {
    Mail::fake();

    $this->post(route('contact.store'), validContactPayload(['message' => str_repeat('m', 5001)]))
        ->assertSessionHasErrors(['message']);

    $this->post(route('contact.store'), validContactPayload(['message' => str_repeat('m', 5000)]))
        ->assertSessionHasNoErrors();

    Mail::assertQueued(ContactMessage::class, 1);
});

test('contact form rejects email addresses longer than 255 characters', function () {
    Mail::fake();

    $this->post(route('contact.store'), validContactPayload(['email' => str_repeat('a', 244).'@example.com']))
        ->assertSessionHasErrors(['email']);

    Mail::assertNothingQueued();
    Mail::assertNothingSent();
});

test('contact form rejects non rfc email addresses', function () {
    Mail::fake();

    $this->post(route('contact.store'), validContactPayload(['email' => 'john doe@example.com']))
        ->assertSessionHasErrors(['email']);

    Mail::assertNothingQueued();
});

test('contact form never queues or sends mail for invalid input', function () {
    Mail::fake();
    Queue::fake();

    $this->post(route('contact.store'), [
        'first_name' => '',
        'last_name' => '',
        'email' => 'invalid',
        'message' => str_repeat('x', 5001),
    ])->assertSessionHasErrors(['first_name', 'last_name', 'email', 'message']);

    Mail::assertNothingQueued();
    Mail::assertNothingSent();
    Queue::assertNothingPushed();
});

test('contact form queues the contact mailable instead of sending it in the request', function () {
    Mail::fake();
    config(['mail.contact_recipient' => 'owner@example.test']);

    $this->from('/')
        ->post(route('contact.store'), validContactPayload())
        ->assertRedirect(url('/').'#contact')
        ->assertSessionHas('success', 'Pesan berhasil dikirim!');

    Mail::assertNothingSent();
    Mail::assertQueued(ContactMessage::class, function (ContactMessage $mail) {
        return $mail->hasTo('owner@example.test')
            && $mail->hasReplyTo('john@example.com')
            && $mail->hasSubject('Pesan Baru dari John Doe');
    });
});

test('contact form pushes delivery onto the queue', function () {
    Queue::fake();
    config(['mail.contact_recipient' => 'owner@example.test']);

    $this->post(route('contact.store'), validContactPayload())
        ->assertSessionHas('success', 'Pesan berhasil dikirim!');

    Queue::assertPushed(SendQueuedMailable::class, function (SendQueuedMailable $job) {
        return $job->mailable instanceof ContactMessage
            && $job->tries === 3;
    });
});

test('contact form does not report success when the message cannot be queued', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('queue unavailable'));

    $this->from('/')
        ->post(route('contact.store'), validContactPayload())
        ->assertRedirect(url('/').'#contact')
        ->assertSessionMissing('success')
        ->assertSessionHasErrors(['contact']);
});

test('contact message keeps the plain text body, subject and reply-to', function () {
    $mail = new ContactMessage('John', 'Doe', 'john@example.com', "Line one\nLine <b>two</b> & more");

    $mail->assertHasSubject('Pesan Baru dari John Doe');
    $mail->assertHasReplyTo('john@example.com');
    $mail->assertSeeInText('Nama: John Doe');
    $mail->assertSeeInText('Email: john@example.com');
    $mail->assertSeeInText("Pesan:\nLine one\nLine <b>two</b> & more", false);
});

test('contact message has bounded retries and backoff', function () {
    $mail = new ContactMessage('John', 'Doe', 'john@example.com', 'Hi');

    expect($mail)->toBeInstanceOf(Illuminate\Contracts\Queue\ShouldQueue::class)
        ->and($mail->tries)->toBe(3)
        ->and($mail->backoff)->toBe([60, 300]);
});

test('contact message failure is logged without personal data or message body', function () {
    Log::shouldReceive('error')
        ->once()
        ->withArgs(function (string $message, array $context) {
            $logged = $message.json_encode($context);

            return ! str_contains($logged, 'john@example.com')
                && ! str_contains($logged, 'secret body')
                && ! str_contains($logged, 'smtp said john');
        });

    (new ContactMessage('John', 'Doe', 'john@example.com', 'secret body'))
        ->failed(new RuntimeException('smtp said john@example.com'));
});

test('contact form is throttled per ip address with a generic message', function () {
    Mail::fake();

    for ($i = 1; $i <= 5; $i++) {
        $this->from('/')
            ->post(route('contact.store'), validContactPayload(['email' => "user{$i}@example.com"]))
            ->assertSessionHas('success');
    }

    $response = $this->from('/')
        ->post(route('contact.store'), validContactPayload(['email' => 'user6@example.com']));

    $response->assertRedirect(url('/').'#contact')
        ->assertSessionMissing('success')
        ->assertSessionHasErrors(['contact' => 'Terlalu banyak permintaan. Silakan coba lagi nanti.']);

    Mail::assertQueued(ContactMessage::class, 5);
});

test('contact form is throttled per normalized email across ip addresses', function () {
    Mail::fake();

    $emails = ['john@example.com', 'JOHN@example.com', 'John@Example.COM'];

    foreach ($emails as $i => $email) {
        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.'.($i + 1)])
            ->from('/')
            ->post(route('contact.store'), validContactPayload(['email' => $email]))
            ->assertSessionHas('success');
    }

    $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.99'])
        ->from('/')
        ->post(route('contact.store'), validContactPayload(['email' => 'john@EXAMPLE.com']))
        ->assertSessionMissing('success')
        ->assertSessionHasErrors(['contact']);

    Mail::assertQueued(ContactMessage::class, 3);
});
