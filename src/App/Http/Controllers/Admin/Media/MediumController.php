<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\Medium;
use GeoSot\BaseAdmin\App\Helpers\Http\Controllers\ListField;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use GeoSot\BaseAdmin\Helpers\Alert;
use Illuminate\Http\Request;
use TusPhp\Tus\Server;

class MediumController extends BaseAdminController
{

    protected $_class = Medium::class;

    //OVERRIDES

    protected $allowedActionsOnIndex = ['edit', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose','delete'];


    public function edit(Medium $medium)
    {
        return $this->genericEdit($medium);
    }

    public function update(Request $request, Medium $medium)
    {
        return $this->genericUpdate($request, $medium);
    }


    protected function listFields(): array
    {
        return [
            'listable' => ['title', 'thumb_html', 'size', 'created_at', 'id'],
            'sortable' => ['title', 'size', 'created_at', 'id'],
            'searchable' => ['title', 'the_file_exists', 'directory', 'filename', 'extension', 'aggregate_type', 'description', 'keywords', 'id'],
        ];
    }


    public function upload(Request $request)
    {
        if (!auth()->user()->hasPermission(['admin.create-medium'])) {
            $message = $this->getLang('store.deny');
            $title = $this->getLang('store.errorTitle');
            if ($request->expectsJson()) {
                return response()->json(['message' => $message, 'title' => $title], 403);
            }
            Alert::warning($message, $title);
            return abort(403, $message);
        }
        /** @var Server $server */
        $server = app('tus-server');
        $response = $server->serve();

        $response->send();
        exit(0);
    }


}
