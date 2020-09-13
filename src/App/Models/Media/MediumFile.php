<?php


namespace GeoSot\BaseAdmin\App\Models\Media;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediumFile extends BaseMediaModel
{

    public const TYPE = 'file';


    protected $frontEndConfigValues = [
        'admin' => [
//            'langDir' => 'helpModels/file',
//            'viewDir' => 'files',
//            'route' => 'files',
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

    public function fillData(
        $img,
        string $directoryName,
        string $disk,
        string $displayName = null,
        int $order = null,
        string $fileName = null
    ) {
        $collection = Str::snake($directoryName);
        $data = ['collection' => $collection];
        if ($img instanceof UploadedFile) {
            $data = $this->getDataFromUploadedFile($img, $disk, $collection, $displayName);

        } elseif (is_string($img)) {
            $data = $this->getDataFromString($img, $displayName);
        }

        return self::getFilledData($order, $data);

    }

}
