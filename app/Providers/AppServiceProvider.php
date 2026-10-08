<?php

namespace App\Providers;

use App\Support\ContactRateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    private function configureRateLimiting(): void
    {
        // Keys, numbers and message come from ContactRateLimiter, which the Livewire ContactForm
        // also uses (its requests do not pass through this route middleware).
        RateLimiter::for('contact', function (Request $request) {
            $throttled = fn (Request $request, array $headers) => redirect(url('/').'#contact')
                ->withInput()
                ->withErrors(['contact' => ContactRateLimiter::THROTTLED_MESSAGE])
                ->withHeaders($headers);

            $limits = [
                (new Limit(
                    ContactRateLimiter::ipKey($request->ip()),
                    ContactRateLimiter::IP_ATTEMPTS,
                    ContactRateLimiter::IP_DECAY_SECONDS,
                ))->response($throttled),
            ];

            $emailKey = ContactRateLimiter::emailKey($request->input('email'));

            if ($emailKey !== null) {
                $limits[] = (new Limit(
                    $emailKey,
                    ContactRateLimiter::EMAIL_ATTEMPTS,
                    ContactRateLimiter::EMAIL_DECAY_SECONDS,
                ))->response($throttled);
            }

            return $limits;
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by('api-ip:'.$request->ip());
        });
    }
}
