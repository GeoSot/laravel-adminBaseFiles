<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\MediumVideo;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

class MediumVideoController extends BaseAdminController
{

    protected $_class = MediumVideo::class;
    //OVERRIDES

    protected $allowedActionsOnIndex = ['edit', 'enable', 'disable', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose'];


    public function edit(MediumVideo $mediumVideo)
    {
        return $this->genericEdit($mediumVideo);
    }

    public function update(Request $request, MediumVideo $mediumVideo)
    {
        return $this->genericUpdate($request, $mediumVideo);
    }

    protected function listFields()
    {
        $neFields = [
            'listable' => ['title', 'enabled', 'id'],
            'searchable' => ['translations.title', 'collection_name', 'file', 'enabled', 'id'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }
}
