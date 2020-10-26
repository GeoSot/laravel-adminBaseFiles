<?php

namespace GeoSot\BaseAdmin\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

/**
 * Class Settings.
 *
 * @method static mixed|null  getGroup(string $groupKey)
 * @method static bool hasGroup(string $key)
 *                                                              //method static mixed set(string $key, $value, $type, Model $relatedModel = null)
 * @method static bool has(string $key)e, string $level = null)
 * @method static mixed get(string $key, $default = null)
 * @method static bool cacheAll()
 * @method static bool deleteKey(string $key)
 * @method static bool flushKey(string $key)
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return 'settings';
    }
}
