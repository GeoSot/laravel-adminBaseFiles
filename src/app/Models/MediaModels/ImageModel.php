<?php

namespace GeoSot\BaseAdmin\App\Models\MediaModels;


class ImageModel extends BaseMediaModel
{
    protected $table = 'media_images';


    protected $frontEndConfigValues = [
        'admin' => [
//            'langDir' => 'helpModels/image',
//            'viewDir' => 'images',
            'route' => 'images',
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
    public function getImgHtml(string $typeOfImg = null, string $class = null, string $figureClass = null, string $onclickAction = null)
    {
        //TODO move on views
        $onclick = (is_null($onclickAction)) ? '' : 'onclick = "'.$onclickAction.'"';
        $html = '<figure class="'.$figureClass.'" data-originalimage="'.$this->getFilePath().'"data-originalid="'.$this->id.'" '.$onclick.' itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
        $html .= ' <img itemprop="image"src="'.$this->getFilePath($typeOfImg).'" alt="" class="img-responsive img-fluid"/>';
        $html .= '<meta itemprop="url" content="'.$this->getFilePath().'" /></figure>';
        return $html;
    }


}
