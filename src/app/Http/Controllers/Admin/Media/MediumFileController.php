<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\MediumFile;
use Illuminate\Http\Request;

class MediumFileController extends MediumBaseController
{

    protected $_class = MediumFile::class;


    public function edit(MediumFile $mediumFile)
    {
        return $this->genericEdit($mediumFile);
    }

    public function update(Request $request, MediumFile $mediumFile)
    {
        return $this->genericUpdate($request, $mediumFile);
    }

}
