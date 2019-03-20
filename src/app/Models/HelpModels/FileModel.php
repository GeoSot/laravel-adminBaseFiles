<?php


namespace GeoSot\BaseAdmin\App\Models\HelpModels;


use GeoSot\BaseAdmin\App\Models\BaseModel;
use Illuminate\Support\Facades\Storage;

class FileModel extends BaseModel
{

    protected $table = 'files';

    protected $frontEndConfigValues = [
        'admin' => [
            'langDir' => 'files',
            'viewDir' => 'files',
            'route'   => 'files',
        ],
    ];

    /**
     * @param null $class
     * @param bool $download
     *
     * @return string
     */
    public function getAsLink($class = null, $download = false)
    {
        $downloadAttr = ($download) ? ' download="true" ' : '';
        $class = $class ?? 'btn btn-link';
        $class .= (app()->environment('local') and !file_exists($this->getFilePath())) ? ' disabled ' : '';

        return '<a data-id="' . $this->id . '" class="' . $class . '" href="' . $this->getFilePath() . '" target="_blank" ' . $downloadAttr . ' title="' . $this->title . '">' . $this->getFullName() . '</a>';
    }


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
