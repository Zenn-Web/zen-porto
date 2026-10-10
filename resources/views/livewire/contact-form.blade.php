{{--
    The contact form (Livewire ContactForm), rendered inside the contact card on the home page.

    Do NOT use the scroll-reveal classes here (form-group, animate-on-scroll, text-reveal, ...): they
    start at opacity 0 until JavaScript adds `.reveal-active`, and Livewire restores the class attribute
    from the server on every update, which would hide the fields again after a submit. The card around
    the component already reveals as a whole.

    Static strings carry data-i18n-id/-en so the language button swaps them without a reload. Strings that
    only exist after a server round trip (status line, field errors) are rendered in the session locale.
--}}
@php
    $t = fn (string $key) => [
        'id' => __("portfolio.{$key}", [], 'id'),
        'en' => __("portfolio.{$key}", [], 'en'),
    ];
@endphp
<div class="contact-form-classic">
    <div class="contact-status" role="status" aria-live="polite">
        @if ($status === 'sent')
            <p class="contact-status-message contact-status-message--success">{{ __('portfolio.contact_form_sent') }}</p>
        @elseif ($status === 'throttled')
            <p class="contact-status-message contact-status-message--error">{{ __('portfolio.contact_form_throttled') }}</p>
        @elseif ($status === 'failed')
            <p class="contact-status-message contact-status-message--error">{{ __('portfolio.contact_form_failed') }}</p>
        @endif
    </div>

    <form wire:submit="submit" novalidate>
        <div class="contact-field-row">
            <div class="contact-field">
                <label for="contact_first_name" data-i18n-id="{{ $t('contact_form_first_name')['id'] }}" data-i18n-en="{{ $t('contact_form_first_name')['en'] }}">{{ __('portfolio.contact_form_first_name') }}</label>
                <input id="contact_first_name" type="text" wire:model="first_name" class="contact-input" maxlength="255" autocomplete="given-name" required aria-invalid="{{ $errors->has('first_name') ? 'true' : 'false' }}" @error('first_name') aria-describedby="contact_first_name_error" @enderror>
                @error('first_name') <p id="contact_first_name_error" class="contact-error" role="alert">{{ $message }}</p> @enderror
            </div>

            <div class="contact-field">
                <label for="contact_last_name" data-i18n-id="{{ $t('contact_form_last_name')['id'] }}" data-i18n-en="{{ $t('contact_form_last_name')['en'] }}">{{ __('portfolio.contact_form_last_name') }}</label>
                <input id="contact_last_name" type="text" wire:model="last_name" class="contact-input" maxlength="255" autocomplete="family-name" required aria-invalid="{{ $errors->has('last_name') ? 'true' : 'false' }}" @error('last_name') aria-describedby="contact_last_name_error" @enderror>
                @error('last_name') <p id="contact_last_name_error" class="contact-error" role="alert">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="contact-field">
            <label for="contact_email" data-i18n-id="{{ $t('contact_form_email')['id'] }}" data-i18n-en="{{ $t('contact_form_email')['en'] }}">{{ __('portfolio.contact_form_email') }}</label>
            <input id="contact_email" type="email" wire:model="email" class="contact-input" maxlength="255" autocomplete="email" required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" @error('email') aria-describedby="contact_email_error" @enderror>
            @error('email') <p id="contact_email_error" class="contact-error" role="alert">{{ $message }}</p> @enderror
        </div>

        <div class="contact-field">
            <label for="contact_message" data-i18n-id="{{ $t('contact_form_message')['id'] }}" data-i18n-en="{{ $t('contact_form_message')['en'] }}">{{ __('portfolio.contact_form_message') }}</label>
            <textarea id="contact_message" wire:model="message" class="contact-input contact-textarea" rows="5" maxlength="5000" required aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}" @error('message') aria-describedby="contact_message_error" @enderror></textarea>
            @error('message') <p id="contact_message_error" class="contact-error" role="alert">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="contact-submit" wire:loading.attr="disabled" wire:target="submit">
            <span wire:loading.remove wire:target="submit" data-i18n-id="{{ $t('contact_form_submit')['id'] }}" data-i18n-en="{{ $t('contact_form_submit')['en'] }}">{{ __('portfolio.contact_form_submit') }}</span>
            <span wire:loading wire:target="submit" data-i18n-id="{{ $t('contact_form_sending')['id'] }}" data-i18n-en="{{ $t('contact_form_sending')['en'] }}">{{ __('portfolio.contact_form_sending') }}</span>
        </button>
    </form>
</div>
