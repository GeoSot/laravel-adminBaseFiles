<?php

namespace GeoSot\BaseAdmin\App\Models\Pages;


class PageArea extends BasePage
{
    protected $with=['blocks'];

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
        'is_enabled',
        'user_id',
        'modified_by',
    ];
    protected $appends = ['background_image'];

    protected $casts = [
        'is_enabled' => 'boolean',
        'order' => 'integer',
    ];


    protected function rules(array $merge = [])
    {
        return array_merge([
            'slug' => ['required', 'min:3', "unique:{$this->getTable()},slug".$this->getIgnoreTextOnUpdate(),],
        ], $merge, $this->rules);
    }


    public function getBackgroundImageAttribute()
    {
        return $this->images()->first();
    }


    /*
    *
    * - - -  R E L A T I O N S H I P S  - - -
    *
    */

    protected function getViewTemplate(): string
    {
        return 'baseAdmin::site.blockLayouts._pageArea';
    }

    public function page()
    {
        return $this->belongsTo(\App\Models\Pages\Page::class);
    }


    public function blocks()
    {
        return $this->hasMany(\App\Models\Pages\PageBlock::class)->orderBy('order');
    }
}
