<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\MediumImage;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

abstract class MediumBaseController extends BaseAdminController
{


    //OVERRIDES

    protected $allowedActionsOnIndex = ['edit', 'enable', 'disable', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];


    protected function listFields()
    {
        $neFields = [
            'listable' => ['title', 'thumb_html', 'enabled', 'id'],
            'searchable' => ['title', 'collection_name', 'file', 'id'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }

}
