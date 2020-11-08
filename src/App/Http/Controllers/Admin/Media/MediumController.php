<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\Medium;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

class MediumController extends BaseAdminController
{

    protected $_class = Medium::class;

    //OVERRIDES

    protected $allowedActionsOnIndex = ['edit', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose'];


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
            'listable' => ['title', 'thumb_html', 'size', 'created_at', 'id'],
            'sortable' => ['title', 'size', 'created_at', 'id'],
            'searchable' => ['title', 'the_file_exists', 'directory', 'filename', 'extension', 'aggregate_type', 'description', 'keywords', 'id'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }


    public function tusUpload(Request $request)
    {
        dd($request);
        $response = app('tus-server')->serve();

        return $response->send();

    }


}
