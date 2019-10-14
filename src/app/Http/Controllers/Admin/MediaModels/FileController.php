<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\MediaModels;


use App\Models\MediaModels\FileModel;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

class FileController extends BaseAdminController
{

    protected $_class = FileModel::class;
    //OVERRIDES

    protected $allowedActionsOnIndex = ['edit', 'enable', 'disable', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose'];


    public function edit(FileModel $fileModel)
    {
        return $this->genericEdit($fileModel);
    }

    public function update(Request $request, FileModel $fileModel)
    {
        return $this->genericUpdate($request, $fileModel);
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
