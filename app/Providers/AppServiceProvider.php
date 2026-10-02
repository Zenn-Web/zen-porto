<?php

namespace App\Providers;

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
        RateLimiter::for('contact', function (Request $request) {
            $throttled = fn (Request $request, array $headers) => redirect(url('/').'#contact')
                ->withInput()
                ->withErrors(['contact' => 'Terlalu banyak permintaan. Silakan coba lagi nanti.'])
                ->withHeaders($headers);

            $limits = [
                Limit::perMinute(5)->by('contact-ip:'.$request->ip())->response($throttled),
            ];

            $email = $request->input('email');

            if (is_string($email) && trim($email) !== '') {
                $limits[] = Limit::perHour(3)
                    ->by('contact-email:'.hash('sha256', mb_strtolower(trim($email))))
                    ->response($throttled);
            }

            return $limits;
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by('api-ip:'.$request->ip());
        });
    }
}
