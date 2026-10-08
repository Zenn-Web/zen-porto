<?php

namespace App\Livewire;

use App\Services\ContactMessageService;
use App\Support\ContactRateLimiter;
use App\Support\ContactRules;
use Livewire\Component;
use Throwable;

/**
 * The contact form as the project's single Livewire island. The page around it stays Blade.
 */
class ContactForm extends Component
{
    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $message = '';

    /** Outcome of the last submission: 'sent', 'throttled' or 'failed', or null. */
    public ?string $status = null;

    public function submit(ContactMessageService $messages, ContactRateLimiter $limiter): void
    {
        $this->status = null;

        // The identity comes from the server-side request, never from a field the client controls.
        $ip = request()->ip();

        if ($limiter->tooManyAttempts($ip, $this->email)) {
            $this->status = 'throttled';

            return;
        }

        // Counted before validation, like the throttle middleware on POST /contact.
        $limiter->hit($ip, $this->email);

        $validated = $this->validate(ContactRules::rules());

        try {
            $messages->queue($validated);
        } catch (Throwable) {
            // The service already logged it (exception class only). Keep what was typed so the
            // visitor can retry, and never surface the exception itself.
            $this->status = 'failed';

            return;
        }

        $this->reset(['first_name', 'last_name', 'email', 'message']);
        $this->status = 'sent';
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
