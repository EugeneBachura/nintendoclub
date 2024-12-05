<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware for setting application locale based on user preference or request parameter.
 */
class SetLocale
{
    public function handle($request, Closure $next)
    {
        $supportedLocales = config('localization.supported_locales');
        $locale = $request->query('lang', session('locale', Auth::user()->locale ?? config('app.locale')));

        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
            session(['locale' => $locale]);

            if (Auth::check() && Auth::user()->locale !== $locale) {
                Auth::user()->update(['locale' => $locale]);
            }
        }

        return $next($request);
    }
}
