<?php


namespace GeoSot\BaseAdmin\App\Models\Media;


use Eloquent;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\ManagesFiles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

/**
 * GeoSot\BaseAdmin\App\Models\Media\Models\BaseMediaModel
 *
 * @mixin Eloquent
 * */
abstract class BaseMediaModel extends BaseModel
{
    protected $table = 'media';
    use ManagesFiles, HasTranslations;
    protected static $type = '';

    public static function boot()
    {
        parent::boot();
        static::saving(function (BaseMediaModel $model) {
            $model['type'] = static::$type;
        });
        static::addGlobalScope('type', function (Builder $query) {
            return $query->where('type', '=', static::$type);
        });
    }


    public $translatable = [
        'title',
        'description',
        'alt_attribute',
        'keywords'
    ];

    protected $fillable = [
        'title',
        'description',
        'alt_attribute',
        'keywords',
        'model_type',
        'model_id',
        'collection_name',
        'file',
        'disk',
        'the_file_exists',
        'thumb',
        'size_mb',
        'mime_type',
        'extension',
        'order',
        'custom_properties',
        'enabled',
        'modified_by',
        'type'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'the_file_exists' => 'boolean',
        'custom_properties' => 'array',
        'order' => 'integer'
    ];
    protected $appends = ['full_name', 'file_path', 'thumb_html'];

    /**
     * @param  null  $class
     * @param  bool  $download
     *
     * @return string
     */
    public function getAsLink($class = null, $download = false)
    {
        $downloadAttr = ($download) ? ' download="true" ' : '';
        $class = $class ?? 'btn btn-link';
        return '<a data-id="'.$this->getKey().'" class="js-lazy '.$class.'" href="'.$this->getFilePath().'" target="_blank" '.$downloadAttr.' title="'.$this->title.'">'.$this->full_name.'</a>';
    }

    /**
     * @param  string|null  $typeOfImg
     * @return string
     */
    public function getFilePath(string $typeOfImg = null)
    {
        if ($typeOfImg === 'thumb') {
            return $this->getThumbPath();
        }
        if ($this->disk == 'uploads') {
            return Storage::disk($this->disk)->url($this->file);
        }
        return $this->file;
    }

    /**
     * @return string
     */
    private function getThumbPath()
    {
        if ($this->disk == 'uploads') {
            return Storage::disk($this->disk)->url($this->thumb ?? $this->file);
        }
        return $this->file;
    }

    /**
     * @return string
     */
    public static function getDummyImageUrl()
    {
        return baseAdmin_assets('images/dummy-image.png');
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return ($this->title ?? str_replace('Model', '', class_basename($this))).'.'.$this->extension;
    }


    /**
     * @return string
     */
    public function getFilePathAttribute()
    {
        return $this->getFilePath();
    }

    /**
     * @return string
     */
    public function getThumbHtmlAttribute()
    {
        return $this->getThumb();
    }

    /**
     * @param  string  $width
     * @return string
     */
    public function getThumb(string $width = '')
    {
        return '<img class="js-lazy img-fluid" style="max-width:100%; max-height:100px; width:'.$width.';" src="'.static::getDummyImageUrl().'" data-src="'.$this->getThumbPath().'" />';
    }


    /**
     * @return string
     */
    public function getRelationKey()
    {
        return str_replace('model_', '', $this->getForeignKey());
    }

    /**
     * @param  Model  $model
     * @param $file
     * @param  string  $directoryName
     * @param  string  $disk
     * @param  string  $displayName
     * @param  int  $order
     * @return array
     */
    abstract public function fillData(Model $model, $file, string $directoryName, string $disk, string $displayName = null, int $order = null);

    protected function getDataFromUploadedFile(UploadedFile $file, string $disk, string $collection, string $displayName = null)
    {
        $fileSaveName = Str::slug($displayName ?: $file->hashName()).'.'.$file->getClientOriginalExtension();

        return [
            'title' => $displayName ?? str_replace('.'.$file->getClientOriginalExtension(), '', $file->getClientOriginalName()),
            'file' => Storage::disk($disk)->putFileAs($collection, $file, $fileSaveName),
            'disk' => $disk,
            'size_mb' => (float) number_format($file->getSize() / 1048576, 3),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ];
    }

    /**
     * @param  string  $file
     * @param  string|null  $displayName
     * @param  string  $disk
     * @return array
     */
    protected function getDataFromString(string $file, string $displayName = null, string $disk = 'uri')
    {
        return [
            'title' => $displayName ?: $file,
            'file' => $file,
            'disk' => $disk,
        ];
    }


    final protected function getFilledData(Model $model, float $order = null, array $args = [])
    {

        $data = [
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'collection_name' => '',
            'title' => '',
            'file' => '',
            'disk' => '',
            'size_mb' => null,
            'mime_type' => '',
            'extension' => '',
            'order' => $order,
        ];

        foreach ($args as $key => $val) {
            $data[$key] = $val;
        }
        return $data;
    }
}
