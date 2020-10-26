<?php

namespace GeoSot\BaseAdmin\App\Models\Pages;

use Cviebrock\EloquentSluggable\Sluggable;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\Media\HasMedia;
use GeoSot\BaseAdmin\Helpers\Alert;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Spatie\Translatable\HasTranslations;

class Page extends BaseModel
{
    public const SESSION_PREVIEW_KEY = 'previewPage';
    use Sluggable;
    use HasTranslations;
    use SoftDeletes;
    use HasMedia;

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
            'slug'  => "min:3|unique:{$this->getTable()},slug".$this->getIgnoreTextOnUpdate(),
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
    public function getPreviewLink()
    {
        session()->put(self::SESSION_PREVIEW_KEY, $this->slug);

        return URL::signedRoute('site.pages', ['page' => $this->getRouteKey()], now()->addMinutes(45));
    }

    public function userCanSee()
    {
        if (!request()->hasValidSignature()) {
            session()->forget(Page::SESSION_PREVIEW_KEY);
        }
        if ($this->isEnabled()) {
            return true;
        }
        if (session()->get(Page::SESSION_PREVIEW_KEY) == $this->slug) {
            $lang = 'baseAdmin::'.$this->getFrontEndConfigPrefixed('site', 'langDir').'.';
            Alert::info(__("{$lang}general.isPreview.msg"), __("{$lang}general.isPreview.title"));

            return true;
        }

        return false;
    }

    /**
     * Get the contracts that owns.
     *
     * @return HasMany
     */
    public function pageAreas()
    {
        return $this->hasMany(\App\Models\Pages\PageArea::class)->orderBy('order');
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
