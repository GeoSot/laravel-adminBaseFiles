<?php

namespace GeoSot\BaseAdmin\App\Models\Pages;

use Cviebrock\EloquentSluggable\Sluggable;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\Media\HasImages;
use Spatie\Translatable\HasTranslations;


class PageArea extends BaseModel
{
    use Sluggable, HasTranslations, HasImages;

    public $translatable = ['title', 'sub_title',];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id',
        'title',
        'sub_title',
        'css_class',
        'order',
        'notes',
        'background_color',
        'slug',
        'enabled',
        'user_id',
        'modified_by',

    ];

    protected $casts = [
        'enabled' => 'boolean',
        'order' => 'integer',
    ];


    protected function rules(array $merge = [])
    {
        return array_merge([
            'slug' => ['required', 'min:3', "unique:{$this->getTable()},slug".$this->getIgnoreTextOnUpdate(),],
        ], $merge, $this->rules);
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

    public function page()
    {
        return $this->belongsTo(\App\Models\Pages\Page::class);
    }


    public function blocks()
    {
        return $this->hasMany(\App\Models\Pages\PageBlock::class)->orderBy('order');
    }
}
