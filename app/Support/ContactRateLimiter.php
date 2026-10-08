<?php

namespace App\Support;

use Illuminate\Support\Facades\RateLimiter;

/**
 * The contact form's rate limits: 5 submissions per minute per IP address and 3 per hour per
 * (normalized) email address. Used by the Livewire ContactForm and, for the same keys and numbers,
 * by the `contact` limiter that throttles POST /contact. Livewire's update requests never pass
 * through the route's throttle middleware, so the component has to enforce the limits itself.
 */
final class ContactRateLimiter
{
    /** Shown to the visitor when a limit is hit. */
    public const THROTTLED_MESSAGE = 'Terlalu banyak permintaan. Silakan coba lagi nanti.';

    public const IP_ATTEMPTS = 5;

    public const IP_DECAY_SECONDS = 60;

    public const EMAIL_ATTEMPTS = 3;

    public const EMAIL_DECAY_SECONDS = 3600;

    public static function ipKey(?string $ip): string
    {
        return 'contact-ip:'.$ip;
    }

    /**
     * Null when there is no usable email: a blank value must not share one limit bucket.
     */
    public static function emailKey(mixed $email): ?string
    {
        if (! is_string($email) || trim($email) === '') {
            return null;
        }

        return 'contact-email:'.hash('sha256', mb_strtolower(trim($email)));
    }

    public function tooManyAttempts(?string $ip, mixed $email): bool
    {
        if (RateLimiter::tooManyAttempts(self::ipKey($ip), self::IP_ATTEMPTS)) {
            return true;
        }

        $emailKey = self::emailKey($email);

        return $emailKey !== null && RateLimiter::tooManyAttempts($emailKey, self::EMAIL_ATTEMPTS);
    }

    public function hit(?string $ip, mixed $email): void
    {
        RateLimiter::hit(self::ipKey($ip), self::IP_DECAY_SECONDS);

        $emailKey = self::emailKey($email);

        if ($emailKey !== null) {
            RateLimiter::hit($emailKey, self::EMAIL_DECAY_SECONDS);
        }
    }
}
