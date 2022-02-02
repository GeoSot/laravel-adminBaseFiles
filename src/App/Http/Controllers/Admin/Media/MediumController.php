<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Media;


use App\Models\Media\Medium;
use GeoSot\BaseAdmin\App\Helpers\Http\Controllers\Filter;
use GeoSot\BaseAdmin\App\Helpers\Http\Controllers\ListField;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use GeoSot\BaseAdmin\Helpers\Alert;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use TusPhp\Tus\Server;

class MediumController extends BaseAdminController
{

    protected $_class = Medium::class;

    //OVERRIDES

    protected $allowedActionsOnIndex = ['edit', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'delete'];

    protected function afterFilteringIndex(Request &$request, Collection &$params, Builder &$query, &$extraOptions)
    {
        if (in_array(Medium::TYPE_ALL, $request->input('extra_filters.aggregate_type', []))) {
            $query->orWhereNotNull('aggregate_type');
        }
    }

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
            'listable' => ['title', 'thumb_html', ListField::make('size', fn(Medium $m, Collection $viewVars) => $m->readableSize()), 'created_at', 'id'],
            'sortable' => ['title', 'size', 'created_at', 'id'],
            'searchable' => ['filename', 'extension'],
            'linkable' => ['title', 'thumb_html'],
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


    protected function filters(): array
    {
        return [
            Filter::selectMulti('aggregate_type'),
            Filter::selectMulti('directory'),
            Filter::selectMulti('extension'),
            Filter::selectMulti('mime_type'),
        ];
    }

}
