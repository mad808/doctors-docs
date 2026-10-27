<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['tk', 'ru', 'en'];
        $defaultLocale = config('app.locale', 'tk');

        $locale = $request->session()->get('locale', $request->cookie('locale', $defaultLocale));

        if (!in_array($locale, $supportedLocales)) {
            $locale = $defaultLocale;
        }

        App::setLocale($locale);

        return $next($request);
    }
}