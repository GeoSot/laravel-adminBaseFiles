<?php


namespace GeoSot\BaseAdmin\App\Models\Media;


use GeoSot\BaseAdmin\App\Traits\Eloquent\HasAllowedToHandleCheck;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasFrontEndConfigs;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasRulesOnModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasTranslations;
use GeoSot\BaseAdmin\App\Traits\Eloquent\ModifiedBy;
use GeoSot\BaseAdmin\App\Traits\Eloquent\OwnedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Plank\Mediable\Media;

/**
 *
 * @mixin Eloquent
 * */
class Medium extends Media
{
    protected $with = ['variants'];
    use HasTranslations, OwnedBy, ModifiedBy, HasRulesOnModel, HasFrontEndConfigs, HasAllowedToHandleCheck;

    const REQUEST_FIELD_NAME__IMAGE = 'images';
    const REQUEST_FIELD_NAME__VIDEO = 'videos';
    const REQUEST_FIELD_NAME__FILE = 'files';
    public const VARIANT_NAME_THUMB = 'thumb';

    public $translatable = [
        'title',
        'description',
        'alt_attribute',
        'keywords',
    ];

    protected $fillable = [
        'title',
        'notes',
        'alt_attribute',
        'keywords',


        'the_file_exists',
        'custom_properties',
        'user_id',
        'modified_by',
    ];

    protected $casts = [
        'the_file_exists' => 'boolean',
        'custom_properties' => 'array',
        'size' => 'int',
    ];
    protected $appends = ['url', 'thumb_html'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('original', function (Builder $builder) {
            $builder->whereNull('original_media_id');
        });
        static::deleting(function (Medium $medium) {
            $medium->getAllVariants()->each(function (Media $variant) {
                $variant->delete();
            });
        });
    }


    public function scopeEnabled(Builder $builder)
    {
        return $builder->where('the_file_exists', true);
    }


    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    public function getThumb(): Medium
    {
        return $this->findVariant(static::VARIANT_NAME_THUMB) ?: $this;
    }


    /**
     * @return string
     */
    public function getThumbUrl(): string
    {
        return $this->getThumb()->getUrl();
    }

    /**
     * @return string
     */
    public static function getDummyImageUrl()
    {
        return "https://dummyimage.com/600x400/737480/fff.png&text=your+image+is+loading...";
    }

    /**
     * @return string
     */
    public function getThumbHtmlAttribute()
    {
        return $this->getThumbHtml();
    }


    public function getThumbHtml(string $width = ''): string
    {
        return '<img class="lazyload img-fluid" style="max-width:100%; max-height:100px; width:'.$width.';" src="'.static::getDummyImageUrl().'" data-src="'.$this->getThumbUrl().'" />';
    }


    public function getHtml(array $args = []): string
    {
        $data = [
            'class',
            'wrapperClass',
            'onclickAction',
        ];

        $options = collect($data)->mapWithKeys(function ($key) use ($args) {
            return [$key => Arr::get($args, $key, '')];
        });

        $model = $this;

        switch ($this->aggregate_type) {
            case Medium::TYPE_VIDEO:
                return view('baseAdmin::_subBlades.media.video', compact('class', 'options', 'model'))->render();
            case Medium::TYPE_IMAGE :
            case Medium::TYPE_IMAGE_VECTOR :
                return view('baseAdmin::_subBlades.media.image', compact('options', 'model'))->render();
            case 2:
                echo "i equals 2";
                break;
            default:
                return $model->getUrl();
        }
    }


    public function getLinkHtml(string $class = null, bool $download = false): string
    {
        $downloadAttr = ($download) ? ' download="true" ' : '';
        $class = $class ?? 'btn btn-link';
        $title = $this->title ?: $this->basename;
        return '<a data-id="'.$this->getKey().'" class="'.$class.'" href="'.$this->getUrl().'" target="_blank" '.$downloadAttr.' title="'.$title.'">'.$title.'</a>';
    }


    /**
     * @inerhitDoc
     */
    public function variants(): HasMany
    {
        return $this->hasMany(get_class($this), 'original_media_id')->withoutGlobalScope('original');
    }

}
