<?php

namespace App\Support;

use Illuminate\Support\Facades\App;

/**
 * The site's language: the visitor's choice lives in the session (POST /lang/{locale}) and falls back
 * to the configured default. Used by the SetLocale middleware and by Livewire components, because
 * Livewire restores the locale of a component's FIRST render on every update and would otherwise
 * ignore a language chosen afterwards.
 */
final class SiteLocale
{
    public const SUPPORTED = ['id', 'en'];

    public static function applyFromSession(): void
    {
        $locale = session('locale', config('app.locale'));

        if (in_array($locale, self::SUPPORTED, true)) {
            App::setLocale($locale);
        }
    }
}
