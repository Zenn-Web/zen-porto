<?php

namespace App\Support;

/**
 * Validation rules for the contact message, shared by the HTTP request (POST /contact) and the
 * Livewire ContactForm so both accept and reject exactly the same input.
 */
final class ContactRules
{
    /**
     * Reject ASCII control characters (including CR/LF) in values that end up in mail headers.
     */
    private const NO_CONTROL_CHARACTERS = 'not_regex:/[\x00-\x1F\x7F]/';

    /**
     * @return array<string, array<int, string>>
     */
    public static function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255', self::NO_CONTROL_CHARACTERS],
            'last_name' => ['required', 'string', 'max:255', self::NO_CONTROL_CHARACTERS],
            'email' => ['required', 'email:rfc', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
}
