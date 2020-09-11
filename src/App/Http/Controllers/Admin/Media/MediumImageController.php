<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\MediumImage;
use Illuminate\Http\Request;

class MediumImageController extends MediumBaseController
{

    protected $_class = MediumImage::class;


    public function edit(MediumImage $mediumImage)
    {
        return $this->genericEdit($mediumImage);
    }

    public function update(Request $request, MediumImage $mediumImage)
    {
        return $this->genericUpdate($request, $mediumImage);
    }

}
