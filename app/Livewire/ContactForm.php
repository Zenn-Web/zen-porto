<?php

namespace App\Livewire;

use App\Services\ContactMessageService;
use App\Support\ContactRateLimiter;
use App\Support\ContactRules;
use App\Support\SiteLocale;
use Livewire\Attributes\On;
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
        SiteLocale::applyFromSession();

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

    /**
     * Short validation messages in the language of the page (the rules stay in ContactRules).
     * `:max` is filled in by the validator.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'required' => __('portfolio.contact_error_required'),
            'email' => __('portfolio.contact_error_email'),
            'max' => __('portfolio.contact_error_max'),
            'string' => __('portfolio.contact_error_invalid'),
            'not_regex' => __('portfolio.contact_error_invalid'),
        ];
    }

    /**
     * Sent by the language button once the new language is stored in the session. Error messages are
     * stored as text when they are produced, so the fields that currently show an error are validated
     * again to get the same errors in the new language (a field fixed in the meantime just clears).
     */
    #[On('locale-changed')]
    public function refreshLocale(): void
    {
        SiteLocale::applyFromSession();

        $fields = array_keys($this->getErrorBag()->toArray());

        if ($fields === []) {
            return;
        }

        $this->validate(array_intersect_key(ContactRules::rules(), array_flip($fields)));
    }

    public function render()
    {
        SiteLocale::applyFromSession();

        return view('livewire.contact-form');
    }
}
