<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 10/3/2018
 * Time: 4:10 μμ
 */

namespace GeoSot\BaseAdmin\Helpers;

use GeoSot\BaseAdmin\Services\Settings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Base
{
    /**
     * @param string $path
     *
     * @return string
     */
    public static function adminAssets(string $path)
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

    /**
     * @param $arg
     *
     * @return string
     */
    public static function minutesToHuman($arg)
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

    /**
     * @param $routeName
     *
     * @return string
     */
    public static function getCachedRouteAsLink($routeName)
    {
        if (is_null($routeName) or !Route::has($routeName)) {
            return '#';
        }
        $saveName = 'routesParameters.'.str_replace('.', '_', $routeName);

        return session()->has($saveName) ? route($routeName, session()->get($saveName)) : route($routeName);
    }

    /**
     * Translate the given message with a fallback string if none exists.
     *
     * @param string      $key
     * @param array       $replace
     * @param string|null $locale
     *
     * @return string
     */
    public static function transWithFallback(string $key, array $replace = [], string $locale = null)
    {
        $translation = __($key, $replace, $locale);

        return ($key === $translation) ? __("baseAdmin::{$key}", $replace, $locale) : $translation;
    }

    public static function settings($key = null, $default = null)
    {
        /** @var Settings $settings */
        $settings = app('settings');
        if (is_null($key)) {
            return $settings;
        }

        return $settings->get($key, $default);
    }
}
