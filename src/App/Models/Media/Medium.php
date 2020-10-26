<?php


namespace GeoSot\BaseAdmin\App\Models\Media;


use GeoSot\BaseAdmin\App\Jobs\CompressImage;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasAllowedToHandleCheck;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasFrontEndConfigs;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasRulesOnModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\ModifiedBy;
use GeoSot\BaseAdmin\App\Traits\Eloquent\OwnedBy;
use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Plank\Mediable\Media;
use Spatie\Translatable\HasTranslations;

/**
 *
 * @mixin Eloquent
 * */
class Medium extends Media
{
    use HasTranslations, OwnedBy, ModifiedBy, HasRulesOnModel, HasFrontEndConfigs, HasAllowedToHandleCheck;

    const REQUEST_FIELD_NAME__IMAGE = 'images';
    const REQUEST_FIELD_NAME__VIDEO = 'videos';
    const REQUEST_FIELD_NAME__FILE = 'files';

    public $translatable = [
        'title',
        'description',
        'alt_attribute',
        'keywords'
    ];

    protected $fillable = [
        'title',
        'notes',
        'alt_attribute',
        'keywords',


        'the_file_exists',
        'thumb',
        'custom_properties',
        'modified_by',
    ];

    protected $casts = [
        'the_file_exists' => 'boolean',
        'custom_properties' => 'array',
        'size' => 'int',
    ];
    protected $appends = [ 'url', 'thumb_html'];

    public static function boot()
    {
        parent::boot();
        static::created(function (Medium $model) {
//            dispatch(new CompressImage($model));
        });
    }


    public function scopeEnabled(Builder $builder)
    {
        return $builder/*->where('enabled', true)*/ ->where('the_file_exists', true);
    }


    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->getUrl();
    }


    /**
     * @return string
     */
    private function getThumbUrl()
    {
        if ($this->disk == 'public' && $this->thumb) {
            return Storage::disk($this->disk)->url($this->thumb);
        }
        return $this->getUrl();
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

    /**
     * @param  string  $width
     * @return string
     */
    public function getThumbHtml(string $width = '')
    {
        return '<img class="lazyload img-fluid" style="max-width:100%; max-height:100px; width:'.$width.';" src="'.static::getDummyImageUrl().'" data-src="'.$this->getThumbUrl().'" />';
    }


    /**
     *
     * @param  array  $args
     * @return string
     *
     */
    public function getHtml($args = [])
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

    /**
     * @param  null  $class
     * @param  bool  $download
     *
     * @return string
     */
    public function getLinkHtml($class = null, $download = false)
    {
        $downloadAttr = ($download) ? ' download="true" ' : '';
        $class = $class ?? 'btn btn-link';
        $title = $this->title ?: $this->basename;
        return '<a data-id="'.$this->getKey().'" class="'.$class.'" href="'.$this->getUrl().'" target="_blank" '.$downloadAttr.' title="'.$title.'">'.$title.'</a>';
    }


}
