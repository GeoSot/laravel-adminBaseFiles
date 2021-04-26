<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


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


    protected function listFields()
    {
        $neFields = [
            'listable' => ['title', 'ownerModel', 'is_enabled', 'id'],
            'searchable' => ['title', 'slug', 'related_type', 'is_enabled', 'id'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }

}
