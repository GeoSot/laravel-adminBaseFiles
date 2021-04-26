<?php

namespace GeoSot\BaseAdmin\App\Models\Pages;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\belongsTo;


class PageBlock extends BasePage
{

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
        'is_enabled',
        'user_id',
        'modified_by',

    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'has_multiple_images' => 'boolean',
        'starts_at' => 'datetime:d/m/Y',
        'expires_at' => 'datetime:d/m/Y',
        'order' => 'integer',
    ];


    protected function rules(array $merge = [])
    {
        $rules = ['slug' => ['required', 'min:3', "unique:{$this->getTable()},slug".$this->getIgnoreTextOnUpdate(),]];
        if (!is_null($this->getKey())) {
            $rules = array_merge($rules, ['layout' => ['required']]);
        }
        return array_merge($rules, $merge, $this->rules);
    }


    public function scopeActive(Builder $builder)
    {
        return $builder->where('expires_at', '>', Carbon::now())->where('starts_at', '<', Carbon::now());
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

    public function getViewTemplate(): string
    {
        return 'baseAdmin::site.blockLayouts._pageBlock';
    }

    public function hasOneImage()
    {
        return !$this->has_multiple_images;
    }

}
