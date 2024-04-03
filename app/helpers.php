<?php

if (!function_exists('localizedRoute')) {
    function localizedRoute($route, $parameters = [])
    {
        $locale = app()->getLocale();
        $defaultLocale = 'en';

        if ($locale == $defaultLocale) {
            return route($route, $parameters);
        }
        return route($route . '.locale', array_merge(['locale' => $locale], $parameters));
    }
}
