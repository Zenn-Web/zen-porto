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
 * source inventory under "Known gaps" in README.md before adding one.
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

        $defaults = [
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => implode(', ', array_map(fn (string $feature) => "{$feature}=()", self::DISABLED_FEATURES)),
            // Nothing embeds the portfolio; only same-origin framing is allowed.
            'X-Frame-Options' => 'SAMEORIGIN',
        ];

        // Never override a header a route or controller set deliberately.
        foreach ($defaults as $name => $value) {
            if (! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }
}
