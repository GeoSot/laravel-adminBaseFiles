<?php


namespace GeoSot\BaseAdmin\App\Models\MediaModels;


use Illuminate\Support\Facades\Storage;

class FileModel extends BaseMediaModel
{

    protected $table = 'media_files';


    protected $frontEndConfigValues = [
        'admin' => [
//            'langDir' => 'helpModels/file',
//            'viewDir' => 'files',
            'route' => 'files',
        ],
    ];


    public function getContent()
    {
        if ($this->disk == 'uploads') {
            $content = Storage::disk($this->disk)->get($this->file);
        }
        if ($this->disk == 'uri') {
            $content = $this->file;
        }

        return $content ?? '';
    }


}
