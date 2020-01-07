<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 10/3/2018
 * Time: 4:10 μμ
 */


use GeoSot\BaseAdmin\Helpers\Paths;
use GeoSot\BaseAdmin\Services\Settings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\HtmlString;

if (!function_exists('minutesToHuman')) {
    /**
     * @param $arg
     * @return string
     */
    function minutesToHuman($arg)
    {
        if (is_null($arg)) {
            return '';
        }
        $isNegative = ($arg < 0);
        $hours = intdiv(abs($arg), 60);
        $hoursFormatted = (strlen($hours) < 2) ? substr('00'.$hours, -2) : $hours;

        $minutes = substr('00'.(fmod(abs($arg), 60)), -2);

        return ($isNegative ? '-' : '').$hoursFormatted.':'.$minutes;
    }

}


if (!function_exists('getCachedRouteAsLink')) {
    /**
     * @param $routeName
     * @return string
     */
    function getCachedRouteAsLink($routeName)
    {
        if (is_null($routeName) or !Route::has($routeName)) {
            return '#';
        }
        $saveName = 'routesParameters.'.str_replace('.', '_', $routeName);


        return session()->has($saveName) ? route($routeName, session()->get($saveName)) : route($routeName);
    }

}


if (!function_exists('settings')) {
    /**
     * @param  null  $key
     * @param  null  $default
     * @return Settings|Application|mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    function settings($key = null, $default = null)
    {
        $settings = app('settings');
        if (is_null($key)) {
            return $settings;
        }

        return $settings->get($key, $default);

    }

}

if (!function_exists('baseAdmin_assets')) {
    /**
     * @param  string  $path
     * @return string
     */
    function baseAdmin_assets(string $path)
    {
        if (Str::endsWith($path, '/')) {
            $path = Str::replaceLast('/', '', $path);
        }
        $assetsPath = str_replace(DIRECTORY_SEPARATOR, '/', config('baseAdmin.config.backEnd.assetsPath'));

        $fullPath = $assetsPath.$path;

        $manifest = Paths::rootDir().'mix-manifest.json';
        if (file_exists($manifest)) {
            $manifestData = json_decode(file_get_contents($manifest), true);
            $dummyPath = '/filesToPublish/assets/';

            if (isset($manifestData[$dummyPath.$path])) {
                return str_replace($dummyPath, "/$assetsPath", $manifestData[$dummyPath.$path]);
            }

        }

        return "/$fullPath";
    }
}

if (!function_exists('trans_with_fallback')) {
    /**
     * Translate the given message with a fallback string if none exists.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string  $locale
     * @return string
     */
    function trans_with_fallback($key, $replace = [], $locale = null)
    {
        $translation = __($key, $replace, $locale);
        return ($key === $translation) ? __("baseAdmin::{$key}", $replace, $locale) : $translation;
    }
}
