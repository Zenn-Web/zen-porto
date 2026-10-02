<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds baseline security headers that are compatible with the current
 * frontend (inline theme script, Google Fonts, Vite assets/dev server).
 *
 * A Content-Security-Policy is intentionally not sent yet; see the CSP
 * source inventory in the security-hardening plan before adding one.
 */
class SecurityHeaders
{
    /**
     * Browser features the portfolio never uses.
     *
     * @var list<string>
     */
    private const DISABLED_FEATURES = [
        'accelerometer',
        'camera',
        'geolocation',
        'gyroscope',
        'magnetometer',
        'microphone',
        'payment',
        'usb',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set(
            'Permissions-Policy',
            implode(', ', array_map(fn (string $feature) => "{$feature}=()", self::DISABLED_FEATURES)),
        );

        return $response;
    }
}
