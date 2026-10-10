<?php

namespace App\Http\Middleware;

use App\Support\SiteLocale;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Set the application locale from session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        SiteLocale::applyFromSession();

        return $next($request);
    }
}
