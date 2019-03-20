<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 10/3/2018
 * Time: 4:10 μμ
 */


if (!function_exists('minutesToHuman')) {
    function minutesToHuman($arg)
    {
        if (is_null($arg)) {
            return '';
        }
        $isNegative = ($arg < 0);
        $hours = intdiv(abs($arg), 60);
        $hoursFormatted = (strlen($hours) < 2) ? substr('00' . $hours, -2) : $hours;

        $minutes = substr('00' . (fmod(abs($arg), 60)), -2);

        return ($isNegative ? '-' : '') . $hoursFormatted . ':' . $minutes;
    }

}


if (!function_exists('getCachedRouteAsLink')) {
    function getCachedRouteAsLink($routeName)
    {
        if (is_null($routeName)) {
            return '#';
        }
        $saveName = 'routesParameters.' . str_replace('.', '_', $routeName);


        return session()->has($saveName) ? route($routeName, session()->get($saveName)) : route($routeName);
    }

}


if (!function_exists('settings')) {
    function settings( $key = null, $default = null)
    {
        $settings = app('settings');
        if (is_null($key)) {
            return $settings;
        }

        return $settings->get($key, $default);

    }

}

if (!function_exists('baseAdmin_asset')) {
    function baseAdmin_asset(string $path, $secure = null)
    {
        return asset(config('baseAdmin.config.assets.path') . '/' . $path . config('baseAdmin.config.assets.version'), $secure);
    }
}

