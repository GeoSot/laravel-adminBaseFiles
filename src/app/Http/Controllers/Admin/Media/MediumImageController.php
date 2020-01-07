<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\MediumImage;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

class MediumImageController extends BaseAdminController
{

    protected $_class = MediumImage::class;
    //OVERRIDES

    protected $allowedActionsOnIndex = ['edit', 'enable', 'disable', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];


    public function edit(MediumImage $mediumImage)
    {
        return $this->genericEdit($mediumImage);
    }

    public function update(Request $request, MediumImage $mediumImage)
    {
        return $this->genericUpdate($request, $mediumImage);
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
