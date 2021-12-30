<?php


namespace GeoSot\BaseAdmin\App\Models\Pages;

use GeoSot\BaseAdmin\Helpers\Alert;
use GeoSot\BaseAdmin\Helpers\PageMeta;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;


class Page extends BasePage
{
    protected $with = ['pageAreas'];

    public const SESSION_PREVIEW_KEY = 'previewPage';
    use SoftDeletes;

    public $translatable = [
        'title',
        'sub_title',
        'meta_title',
        'meta_description',
        'keywords',
//        'meta_tags',
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
        'is_enabled',
        'user_id',
        'modified_by',
        'parent_id',
        'css',
        'javascript',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'meta_tags' => 'array',
    ];


    protected function rules(array $merge = [])
    {
        return array_merge([
            'title' => ['required', 'min:3'],
            'slug' => "min:3|unique:{$this->getTable()},slug".$this->getIgnoreTextOnUpdate(),
        ], $merge, $this->rules);
    }


    /*
    *
    * - - -  R E L A T I O N S H I P S  - - -
    *
    */
    public function getPreviewLink()
    {
        if (!Route::has('site.pages')) {
            return '';
        }
        session()->put(self::SESSION_PREVIEW_KEY, $this->getFullSlug());
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
        if (session()->get(Page::SESSION_PREVIEW_KEY) == $this->getFullSlug()) {
            $lang = 'baseAdmin::'.$this->frontConfigs->getLangDir('site').'.';
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
    public function pageAreas(): HasMany
    {
        return $this->hasMany(\App\Models\Pages\PageArea::class)->orderBy('order');
    }

    public function childrenPages(): HasMany
    {
        return $this->hasMany(\App\Models\Pages\Page::class, 'parent_id')->orderBy('order');
    }

    public function parentPage(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Pages\Page::class, 'parent_id');
    }

//*********  M E T H O D S  ***************
    protected function getViewTemplate(): string
    {
        return 'baseAdmin::site.blockLayouts.genericPage';
    }


    public function getFullSlug(): string
    {
        $parentSlug = $this->parentPage ? "$this->parentPage->slug/" : '';
        return $parentSlug.$this->slug;
    }


    public function getMeta(): PageMeta
    {
        $meta = PageMeta::make()
            ->setTitle($this->meta_title ?: $this->title)
            ->setDescription($this->meta_description)
            ->setImage('')
            ->addKeywords($this->keywords)
            ->setUrl(URL::to($this->getFullSlug()));

        $meta->addExtraMetaTags('robots', 'index,follow');
        foreach ($this->meta_tags as $me) {
            $meta->addExtraMetaTags($me['key'], $me['val']);
        }
        return $meta;
    }
}
