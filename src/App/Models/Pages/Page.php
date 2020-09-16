<?php


namespace GeoSot\BaseAdmin\App\Models\Pages;

use Cviebrock\EloquentSluggable\Sluggable;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\Media\HasMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;


class Page extends BaseModel
{
    use Sluggable, HasTranslations, SoftDeletes, HasMedia;

    public $translatable = [
        'title',
        'sub_title',
        'meta_title',
        'meta_description',
        'keywords',
        'meta_tags',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'sub_title',
        'meta_title',
        'meta_description',
        'keywords',
        'meta_tags',
        'notes',
        'slug',
        'enabled',
        'user_id',
        'modified_by',
        'parent_id',
        'css',
        'javascript',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];


    protected function rules(array $merge = [])
    {
        return array_merge([
            'title' => ['required', 'min:3'],
            'slug' => "min:3|unique:{$this->getTable()},slug".$this->getIgnoreTextOnUpdate(),
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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /*
    *
    * - - -  R E L A T I O N S H I P S  - - -
    *
    */

    /**
     * Get the contracts that owns.
     *
     * @return HasMany
     */
    public function pageAreas()
    {
        return $this->hasMany(\App\Models\Pages\PageArea::class);
    }

    public function childrenPages()
    {
        return $this->hasMany(\App\Models\Pages\Page::class, 'parent_id')->orderBy('order');
    }

    public function parentPage()
    {
        return $this->belongsTo(\App\Models\Pages\Page::class, 'parent_id');
    }

    //*********  M E T H O D S  ***************
}
