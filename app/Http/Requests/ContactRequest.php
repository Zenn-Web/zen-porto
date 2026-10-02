<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Reject ASCII control characters (including CR/LF) in values that end up in mail headers.
     */
    private const NO_CONTROL_CHARACTERS = 'not_regex:/[\x00-\x1F\x7F]/';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255', self::NO_CONTROL_CHARACTERS],
            'last_name' => ['required', 'string', 'max:255', self::NO_CONTROL_CHARACTERS],
            'email' => ['required', 'email:rfc', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    /**
     * Invalid submissions return to the fixed contact section on the home page (never the Referer).
     */
    protected function getRedirectUrl(): string
    {
        return url('/').'#contact';
    }
}
