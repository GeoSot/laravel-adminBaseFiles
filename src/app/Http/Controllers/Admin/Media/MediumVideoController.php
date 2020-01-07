<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\MediumVideo;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

class MediumVideoController extends MediumBaseController
{

    protected $_class = MediumVideo::class;


    public function edit(MediumVideo $mediumVideo)
    {
        return $this->genericEdit($mediumVideo);
    }

    public function update(Request $request, MediumVideo $mediumVideo)
    {
        return $this->genericUpdate($request, $mediumVideo);
    }

}
