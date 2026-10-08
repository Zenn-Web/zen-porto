<?php

namespace App\Services;

use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Queues the contact message. Shared by POST /contact and the Livewire ContactForm, so the
 * recipient, mailable (encrypted, with bounded retries) and the logging policy exist once.
 */
class ContactMessageService
{
    /** Shown to the visitor after a message was queued. */
    public const SUCCESS_MESSAGE = 'Pesan berhasil dikirim!';

    /** Shown when queueing failed. Never include exception details here. */
    public const FAILURE_MESSAGE = 'Pesan gagal dikirim. Silakan coba lagi nanti.';

    /**
     * @param  array{first_name: string, last_name: string, email: string, message: string}  $validated
     *
     * @throws Throwable the original queue/delivery exception, after it was logged once
     */
    public function queue(array $validated): void
    {
        try {
            Mail::to(config('mail.contact_recipient'))->queue(new ContactMessage(
                $validated['first_name'],
                $validated['last_name'],
                $validated['email'],
                $validated['message'],
            ));
        } catch (Throwable $exception) {
            // Only the exception class is logged: queue driver errors can embed the payload.
            Log::error('Contact message could not be queued.', [
                'exception' => $exception::class,
            ]);

            throw $exception;
        }
    }
}
