<?php
/**
 * by GeoSv.
 * User: gsoti
 * Date: 20/8/2018
 * Time: 12:02 μμ
 */

namespace GeoSot\BaseAdmin\Services;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class Settings.
 */
class Settings
{
    /**
     * @var bool
     */
    protected $cacheIsEnabled;
    protected $cachedTime;
    /**
     * @var string|Setting
     */
    protected $settingsModel;

    public function __construct()
    {
        $this->settingsModel = Setting::class;
        $this->cacheIsEnabled = config('baseAdmin.config.cacheSettings.enable', false);
        $this->cachedTime = Carbon::now()->addMinutes(config('baseAdmin.config.cacheSettings.time', 15));
    }

    /**
     * @param string $groupKey
     *
     * @throws InvalidArgumentException
     *
     * @return mixed|null
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
     * @param string $key
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function hasGroup(string $key)
    {
        return $this->has($this->getSafeGroupKey($key));
    }

    /**
     * Store value into registry.
     *
     * @param string     $key
     * @param mixed      $value
     * @param string     $type
     * @param Model|null $relatedModel
     *
     * @return void
     */
    public function set(string $key, $value, $type, Model $relatedModel = null)
    {
//        $setting=$this->get()
//        $validator = Validator::make($request->all(), [
//            'email' => 'required|email|unique:users',
//            'name' => 'required|string|max:50',
//            'password' => 'required'
//        ]);
//
//        if ($validator->fails()) {
//        }
//        $setting = Setting::where('key', $key)->first();
//        //Protect Developer's Mistake
//
//        Setting::updateOrCreate([
//            'key' => $key
//        ], [
//            'value' => $value,
//            'type' => is_null($type) ? $setting->type : $type,
//        ]);
//        $this->flushKey($key);
//
//        return true;
    }

    /**
     * @param string $key
     *
     * @throws InvalidArgumentException
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

    /**
     * @return Repository
     */
    private function getCache()
    {
        return Cache::store('file');
    }

    /**
     * @param string $key
     * @param null   $default
     *
     * @throws InvalidArgumentException
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

    /**
     * @return Model
     */
    protected function getSettingsModelInstance()
    {
        return $this->settingsModel;
    }

    /**
     * REFRESH THE Cache Key.
     *
     * @param string $key
     * @param mixed  $value *
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
        return 'group.'.str_replace('group', '', $key);
    }

    /**
     * @return bool
     */
    public function cacheAll()
    {
        if ($this->cacheIsEnabled) {
            $this->getSettingsModelInstance()::all()->each(function ($item) {
                $this->cacheKey($item->slug, $item->parsed_value);
            });

            return true;
        }

        return false;
    }

    /**
     * Deletes the SETTING.
     *
     * @param string $key
     *
     * @return bool
     */
    public function deleteKey(string $key)
    {
        $this->flushKey($key);

        return $this->getSettingsModelInstance()::where('slug', $key)->delete();
    }

    /**
     * REFRESH THE Cache Key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function flushKey(string $key)
    {
        return $this->getCache()->forget($key);
    }
}
