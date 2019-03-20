<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Files;


use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use App\Models\HelpModels\FileModel;
use Illuminate\Http\Request;

class FilesController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->_class = FileModel::class;
        $this->initializeModelValues();

        //OVERRIDES
        $this->allowedActionsOnIndex = ['delete'];
        $this->allowedActionsOnCreate = [];
        $this->allowedActionsOnEdit = [];
    }

    public function index(Request $request)
    {
        return abort(403, 'not implemented method');
    }
    //
    //    public function create(Collection $extraValues = null)
    //    {
    //        return abort(403, 'not implemented method');
    //    }
    //
    //    public function edit(FileModel $fileModel)
    //    {
    //        return abort(403, 'not implemented method');
    //    }
    //
    //    public function store(Request $request)
    //    {
    //        return abort(403, 'not implemented method');
    //    }
    //
    //    public function update(Request $request, FileModel $fileModel)
    //    {
    //        return abort(403, 'not implemented method');
    //    }


}
