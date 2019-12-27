<?php


namespace GeoSot\BaseAdmin\App\Models\MediaModels;


use Eloquent;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\ManagesFiles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

/**
 * GeoSot\BaseAdmin\App\Models\MedimaModels\BaseMediaModel
 *
 * @mixin Eloquent
 * */
abstract class BaseMediaModel extends BaseModel
{
    protected $table = 'media';
    use ManagesFiles, HasTranslations;
    private static $type = '';

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model['type'] = $model->type;
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
        'modified_by'
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
        return '<a data-id="'.$this->getKey().'" class="'.$class.'" href="'.$this->getFilePath().'" target="_blank" '.$downloadAttr.' title="'.$this->title.'">'.$this->full_name.'</a>';
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
        return '<img class="img-fluid" style="max-width:250px" src="'.$this->getThumbPath().'" />';
    }


    /**
     * @return string
     */
    public function getRelationKey()
    {
        return str_replace('model_', '', $this->getForeignKey());
    }
}
