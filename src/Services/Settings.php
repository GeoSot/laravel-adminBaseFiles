<?php
/**
 * Artified by GeoSv.
 * User: gsoti
 * Date: 20/8/2018
 * Time: 12:02 μμ
 */

namespace GeoSot\BaseAdmin\Services;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Class Settings
 */
class Settings
{
    /**
     * config
     *
     * @var array
     */

    protected $cacheIsEnabled;
    protected $cachedTime;
    protected $settingsModel;
    protected $cache;


    public function __construct()
    {
        $this->settingsModel = config('baseAdmin.config.models.setting');
        $this->cacheIsEnabled = config('baseAdmin.config.cacheSettings.enable', false);
        $this->cachedTime = Carbon::now()->addMinutes(config('baseAdmin.config.cacheSettings.time', 15));
    }

    /**
     * Gets Group Of Values SeparatedBy '.'
     *
     * @param string $groupKey
     *
     * @return Collection
     */
    public function getGroup(string $groupKey)
    {
        if ($this->cacheIsEnabled and $this->hasGroup($this->getSafeGroupKey($groupKey))) {
            return $this->getCache()->get($this->getSafeGroupKey($groupKey));
        }
        $rows = $this->getSettingsModelInstance()::where('group', $groupKey)->get()->pluck('value_parsed', 'slug');

        //No Results
        if ($rows->isEmpty()) {
            return null;
        }

        return $this->cacheKey($this->getSafeGroupKey($groupKey), $rows);

    }

    /**
     * Checks if setting exists.
     *
     * @param $key
     *
     * @return bool
     */
    public function hasGroup(string $key)
    {
        return $this->has($this->getSafeGroupKey($key));
    }


    //    /**
    //     * Store value into registry.
    //     *
    //     * @param string $key
    //     * @param mixed  $value
    //     * @param string $type
    //     *
    //     * @return boolean
    //     */
    //    public function set(string $key, $value = null, $type = null)
    //    {
    //        $setting = Setting::where('key', $key)->first();
    //        //Protect Developer's Mistake
    //        if (is_null($setting) and is_null($type)) {
    //            return false;
    //        }
    //        Setting::updateOrCreate([
    //            'key' => $key
    //        ], [
    //            'value' => $value,
    //            'type'  => is_null($type) ? $setting->type : $type,
    //        ]);
    //        $this->flushKey($key);
    //
    //        return true;
    //    }

    /**
     * Checks if setting exists.
     *
     * @param $key
     *
     * @return bool
     */
    public function has(string $key)
    {
        if ($this->cacheIsEnabled and $this->getCache()->has($key)) {
            return true;
        }
        $row = $this->get($key, null);

        return !is_null($row);
    }

    private function getCache()
    {
        return Cache::store('file');
    }

    /**
     * Gets a value.
     *
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if ($this->cacheIsEnabled and $this->getCache()->has($key)) {
            return $this->getCache()->get($key);
        }

        $row = $this->getSettingsModelInstance()::where('slug', $key)->first();

        $returnValue = Arr::get($row, 'value_parsed', $default);

        return $this->cacheKey($key, $returnValue);
    }

    protected function getSettingsModelInstance()
    {

        return $this->settingsModel;

    }

    /**
     * REFRESH THE Cache Key
     *
     * @param string $key
     * @param mixed $value
     *
     *
     * @return mixed
     */
    private function cacheKey($key, $value)
    {
        if ($this->cacheIsEnabled) {
            $this->getCache()->put($key, $value, $this->cachedTime);
        }

        return $value;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getSafeGroupKey(string $key)
    {
        return 'group.' . str_replace('group', '', $key);
    }

    public function cacheAll()
    {
        if ($this->cacheIsEnabled) {
            $this->getSettingsModelInstance()::all()->each(function ($item) {
                $this->cacheKey($item->slug, $item->parsed_value);
            });
        }
    }

    /**
     * Deletes the SETTING
     *
     * @param string $key
     *
     */
    public function deleteKey(string $key)
    {
        $this->flushKey($key);
        $this->getSettingsModelInstance()::where('slug', $key)->delete();
    }

    /**
     * REFRESH THE Cache Key
     *
     * @param string $key
     *
     */
    public function flushKey(string $key)
    {
        //if ($this->cacheIsEnabled) {
        $this->getCache()->forget($key);
        // }
    }
}
