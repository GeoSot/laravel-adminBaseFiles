<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\MediaModels;


use App\Models\MediaModels\ImageModel;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

class ImageController extends BaseAdminController
{

    protected $_class = ImageModel::class;
    //OVERRIDES

    protected $allowedActionsOnIndex = ['edit', 'enable', 'disable', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];


    public function edit(ImageModel $imageModel)
    {
        return $this->genericEdit($imageModel);
    }

    public function update(Request $request, ImageModel $imageModel)
    {
        return $this->genericUpdate($request, $imageModel);
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
