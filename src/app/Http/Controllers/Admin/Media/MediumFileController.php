<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\MediumFile;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

class MediumFileController extends BaseAdminController
{

    protected $_class = MediumFile::class;
    //OVERRIDES

    protected $allowedActionsOnIndex = ['edit', 'enable', 'disable', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose'];


    public function edit(MediumFile $mediumFile)
    {
        return $this->genericEdit($mediumFile);
    }

    public function update(Request $request, MediumFile $mediumFile)
    {
        return $this->genericUpdate($request, $mediumFile);
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
