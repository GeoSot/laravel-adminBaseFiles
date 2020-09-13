<?php

namespace GeoSot\BaseAdmin\App\Models\Media;


use Illuminate\Http\UploadedFile;

class MediumVideo extends BaseMediaModel
{
    public const TYPE = 'video';


    protected $frontEndConfigValues = [
        'admin' => [
//            'langDir' => 'helpModels/image',
//            'viewDir' => 'images',
//            'route' => 'videos',
        ],
    ];

    /**
     * Get the URL Path of a File on Server.
     *
     * @param  string  $typeOfImg
     * @param  string  $class
     * @param  string  $figureClass
     * @param  string  $onclickAction
     *
     * @return string
     */
    public function getImgHtml(
        string $typeOfImg = null,
        string $class = null,
        string $figureClass = null,
        string $onclickAction = null
    ) {
        //TODO move on views
        $onclick = (is_null($onclickAction)) ? '' : 'onclick = "'.$onclickAction.'"';
        $html = '<figure class="'.$figureClass.'" data-originalimage="'.$this->getFilePath().'" data-originalid="'.$this->getKey().'" '.$onclick.' itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
        $html .= ' <img itemprop="image"src="'.$this->getFilePath($typeOfImg).'" alt="" class="img-responsive img-fluid"/>';
        $html .= '<meta itemprop="url" content="'.$this->getFilePath().'" /></figure>';
        return $html;
    }

    /**
     * @inheritDoc
     */
    public function fillData(
        $img,
        string $directoryName,
        string $disk,
        string $displayName = null,
        int $order = null,
        string $fileName = null
    ) {
        $collection = preg_replace('/[^a-zA-Z0-9]/', '', $directoryName);

        $data = ['collection' => $collection];
        if ($img instanceof UploadedFile) {
            $data = $this->getDataFromUploadedFile($img, $disk, $collection, $displayName);
        } elseif (is_string($img)) {
            $data = $this->getDataFromString($img, $displayName);
        }

        return self::getFilledData($order, $data);

    }

}
