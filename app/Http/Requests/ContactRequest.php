<?php

namespace App\Http\Requests;

use App\Support\ContactRules;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return ContactRules::rules();
    }

    /**
     * Invalid submissions return to the fixed contact section on the home page (never the Referer).
     */
    protected function getRedirectUrl(): string
    {
        return url('/').'#contact';
    }
}
