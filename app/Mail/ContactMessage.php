<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactMessage extends Mailable implements ShouldQueue
{
    use Queueable;

    /**
     * Maximum delivery attempts before the job is marked as failed.
     */
    public int $tries = 3;

    /**
     * Seconds to wait between retries.
     *
     * @var array<int, int>
     */
    public array $backoff = [60, 300];

    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $senderEmail,
        public string $body,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesan Baru dari '.$this->headerSafe($this->firstName.' '.$this->lastName),
            replyTo: [new Address($this->senderEmail)],
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'mail.contact-message',
            with: [
                'fullName' => $this->firstName.' '.$this->lastName,
                'senderEmail' => $this->senderEmail,
                'body' => $this->body,
            ],
        );
    }

    /**
     * Log a delivery failure with minimal, non-personal context only.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('Contact message delivery failed.', [
            'exception' => $exception::class,
        ]);
    }

    private function headerSafe(string $value): string
    {
        return trim((string) preg_replace('/[\x00-\x1F\x7F]+/', ' ', $value));
    }
}
