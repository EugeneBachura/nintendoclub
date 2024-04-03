<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1); // Получаем первый сегмент URL

        if (!in_array($locale, ['ru', 'pl'])) {
            $locale = 'en'; // Если языка нет в списке, устанавливаем английский как язык по умолчанию
        }

        App::setLocale($locale); // Устанавливаем локаль приложения

        return $next($request); // Продолжаем обработку запроса
    }
}
