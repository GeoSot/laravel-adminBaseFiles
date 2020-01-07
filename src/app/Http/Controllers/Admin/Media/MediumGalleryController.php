<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\MediumGallery;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

class MediumGalleryController extends BaseAdminController
{

    protected $_class = MediumGallery::class;
    //OVERRIDES

//    protected $allowedActionsOnIndex = ['edit', 'enable', 'disable', 'delete', 'forceDelete', 'restore'];
//    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];


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
            'listable' => ['title', 'thumb_html', 'enabled', 'id'],
            'searchable' => ['translations.title', 'collection_name', 'file', 'enabled', 'id'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }

}
