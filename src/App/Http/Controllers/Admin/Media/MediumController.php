<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\Medium;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use GeoSot\BaseAdmin\App\Jobs\CompressImage;
use Illuminate\Http\Request;

class MediumController extends BaseAdminController
{

    protected $_class = Medium::class;

    //OVERRIDES

    protected $allowedActionsOnIndex = ['edit', 'enable', 'disable', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];


    public function edit(Medium $medium)
    {
        return $this->genericEdit($medium);
    }

    public function update(Request $request, Medium $medium)
    {
        return $this->genericUpdate($request, $medium);
    }


    protected function listFields()
    {
        $neFields = [
            'listable' => ['title', 'thumb_html', 'enabled', 'id'],
            'searchable' => ['title', 'collection_name', 'file', 'id'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }
}
