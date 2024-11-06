<?php

use Illuminate\Support\Facades\App;

if (!function_exists('localized_url')) {
    function localized_url($name, $parameters = [], $absolute = true)
    {
        $locale = App::getLocale();
        $parameters['lang'] = $locale;
        return route($name, $parameters, $absolute);
    }
}