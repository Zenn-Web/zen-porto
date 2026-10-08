{{--
    Minimal, unstyled markup for the ContactForm component. It is not placed on any page yet:
    copy, translations and styling are decided when the form is added to the contact section.
--}}
<div>
    @if ($status === 'sent')
        <p role="status">{{ \App\Services\ContactMessageService::SUCCESS_MESSAGE }}</p>
    @elseif ($status === 'throttled')
        <p role="alert">{{ \App\Support\ContactRateLimiter::THROTTLED_MESSAGE }}</p>
    @elseif ($status === 'failed')
        <p role="alert">{{ \App\Services\ContactMessageService::FAILURE_MESSAGE }}</p>
    @endif

    <form wire:submit="submit" novalidate>
        <div>
            <label for="contact_first_name">Nama depan</label>
            <input id="contact_first_name" type="text" wire:model="first_name" autocomplete="given-name" required>
            @error('first_name') <p role="alert">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="contact_last_name">Nama belakang</label>
            <input id="contact_last_name" type="text" wire:model="last_name" autocomplete="family-name" required>
            @error('last_name') <p role="alert">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="contact_email">Email</label>
            <input id="contact_email" type="email" wire:model="email" autocomplete="email" required>
            @error('email') <p role="alert">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="contact_message">Pesan</label>
            <textarea id="contact_message" wire:model="message" rows="5" required></textarea>
            @error('message') <p role="alert">{{ $message }}</p> @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="submit">Kirim</button>
        <span wire:loading wire:target="submit">Mengirim…</span>
    </form>
</div>
