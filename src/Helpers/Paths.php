<?php

namespace GeoSot\BaseAdmin\Helpers;


class Paths
{


    /**
     * Return Directory path
     * @param  string  $path
     * @return string
     */
    public static function srcDir(string $path = '')
    {
        $path = self::sanitizePath($path);

        return realpath(dirname(__DIR__).$path).DIRECTORY_SEPARATOR;
    }

    /**
     * Return Directory path
     * @param  string  $path
     * @return string
     */
    public static function rootDir(string $path = '')
    {
        return self::srcDir("..".self::sanitizePath($path));
    }

    /**
     * Return Directory path
     * @param  string  $path
     * @return string
     */
    public static function filesToPublishDir(string $path = '')
    {
        return self::rootDir('filesToPublish'.self::sanitizePath($path));
    }

    /**
     * Return Resources path
     * @param  string  $path
     * @return string
     */
    public static function resourcesDir(string $path = '')
    {
        return self::rootDir('resources'.self::sanitizePath($path));
    }

    /**
     * @param  string  $path
     * @return string
     */
    protected static function sanitizePath(string $path): string
    {
        return DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
    }

}
