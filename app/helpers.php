<?php

use Illuminate\Support\Facades\App;

if (!function_exists('localized_url')) {
    function localized_url($name, $parameters = [], $absolute = true)
    {
        $url = route($name, $parameters, $absolute);
        $query = http_build_query(['lang' => App::getLocale()]);
        return $url . '?' . $query;
    }
}
