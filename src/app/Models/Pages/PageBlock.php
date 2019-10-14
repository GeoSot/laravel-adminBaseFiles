<?php

namespace GeoSot\BaseAdmin\App\Models\Pages;

use Cviebrock\EloquentSluggable\Sluggable;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasImages;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Spatie\Translatable\HasTranslations;


class PageBlock extends BaseModel
{
    use Sluggable, HasTranslations, HasImages;

    public $translatable = ['title', 'sub_title', 'notes'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'sub_title', 'notes',
        'page_area_id',
        'css_class',
        'layout',
        'background_color',
        'has_multiple_images',
        'order',
        'slug',
        'enabled',
        'user_id',
        'modified_by',

    ];

    protected $casts = [
        'enabled' => 'boolean',
        'has_multiple_images' => 'boolean',
        'starts_at' => 'datetime:d/m/Y',
        'expires_at' => 'datetime:d/m/Y',
        'order' => 'integer',
    ];


    function rules(array $merge = [])
    {
        $rules = ['slug' => ['required', 'min:3', "unique:{$this->getTable()},slug".$this->getIgnoreTextOnUpdate(),]];
        if (!is_null($this->getKey())) {
            $rules = array_merge($rules, ['layout' => ['required']]);
        }
        return array_merge($rules, $merge, $this->rules);
    }


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return ['slug' => ['source' => 'en.title']];
    }



    /*
    *
    * - - -  R E L A T I O N S H I P S  - - -
    *
    */

    /**
     * Get the contracts that owns.
     *
     * @return belongsTo
     */
    public function pageArea()
    {
        return $this->belongsTo(\App\Models\Pages\PageArea::class);
    }


    //*********  M E T H O D S  ***************

    public function hasOneImage()
    {
        return !$this->has_multiple_images;
    }

}
