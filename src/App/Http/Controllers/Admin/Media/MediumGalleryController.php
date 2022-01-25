<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\Medium;
use App\Models\Media\MediumGallery;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

class MediumGalleryController extends BaseAdminController
{
    protected $_class = MediumGallery::class;

    //OVERRIDES


    public function edit(MediumGallery $mediumGallery)
    {
        return $this->genericEdit($mediumGallery);
    }

    public function update(Request $request, MediumGallery $mediumGallery)
    {
        return $this->genericUpdate($request, $mediumGallery);
    }

    protected function afterSave(Request &$request, $model)
    {
        /* @var MediumGallery $model */
        $model->syncRequestMedia($request, false, Medium::REQUEST_FIELD_NAME__IMAGE);
    }



    protected function listFields(): array
    {
        return [
            'listable' => ['title', 'ownerModel', 'is_enabled', 'id'],
            'searchable' => ['title', 'slug', 'related_type', 'is_enabled', 'id'],
        ];
    }

}
