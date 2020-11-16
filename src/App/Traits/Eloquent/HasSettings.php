<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;



use App\Models\Setting;

trait HasSettings
{

    public static function bootHasSettings()
    {
        // Delete associated images if they exist.
        static::deleting(function ($model) {
            $model->settings->delete();
        });
    }


    public function hasSettings()
    {
        return (boolean)$this->settings()->count();
    }

    /**
     * Relation of Files to parent model. Morph Many To Many relationship
     * Get all files related to the parent model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function settings()
    {
        return $this->morphMany(Setting::class, 'model');
    }


    public function getSetting(string $arg, string $searchField = 'slug', $default = null)
    {
        $setting = $this->settings()->where($searchField, $arg)->first();

        return (!$setting) ? $default : $setting->value_parsed;
    }


}
