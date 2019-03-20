<?php


namespace GeoSot\BaseAdmin\App\Models\HelpModels;


use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\ManagesFiles;
use Illuminate\Support\Facades\Storage;

abstract class BaseMediaModel extends BaseModel
{
    use ManagesFiles;

    protected $table = 'files';
    protected $fillable = [
        'model_type',
        'model_id',
        'title',
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
        'enabled'           => 'boolean',
        'the_file_exists'   => 'boolean',
        'custom_properties' => 'array',
        'order'             => 'integer'
    ];
    protected $appends = ['full_name', 'file_path'];


    public function getFilePath(string $typeOfImg = null)
    {

        if ($typeOfImg === 'thumb') {
            return $this->getThumbPath();
        }


        if ($this->disk == 'uploads') {
            return Storage::disk($this->disk)->url($this->file);
        }
        if ($this->disk == 'uri') {
            return $this->file;
        }


    }

    private function getThumbPath()
    {
        if (empty($this->thumb)) {
            return $this->getFilePath();
        }
        if ($this->disk == 'uploads') {
            return Storage::disk($this->disk)->url($this->thumb);
        }
        if ($this->disk == 'uri') {
            return $this->file;
        }
        return '';
    }


    public function getFullName()
    {
        return $this->getFullNameAttribute();
    }


    public function getFullNameAttribute()
    {
        return $this->title . '.' . $this->extension;
    }


    public function getFilePathAttribute()
    {
        return $this->getFilePath();
    }

}
