<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Определяем поддерживаемые языки
        $supportedLocales = config('localization.supported_locales');

        // Получаем язык из параметра ?lang или из сессии/предпочтений пользователя
        $locale = $request->query('lang', session('locale', Auth::user()->locale ?? config('app.locale')));

        // Устанавливаем локаль только если она поддерживается
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
