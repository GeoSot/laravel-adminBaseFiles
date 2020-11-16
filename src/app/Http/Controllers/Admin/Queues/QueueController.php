<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Queues;

use GeoSot\BaseAdmin\App\Http\Controllers\BaseController;
use GeoSot\BaseAdmin\Helpers\Alert;
use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QueueController extends BaseController
{
    public function index()
    {
        $records = collect([
            'jobs' => collect([
                'records' => $this->getPendingJobs(),
                'listable' => ['id', 'name', 'queue', 'attempts', 'reserved_at', 'available_at', 'created_at', 'actions'],
            ]),
            'failed_jobs' => collect([
                'records' => $this->getFailedJobs(),
                'listable' => ['id', 'name', 'connection', 'queue', 'failed_at', 'actions'],
            ]),
        ]);

        $viewVals = collect([
            'records' => $records,
            'modelLang' => Base::addPackagePrefix('admin/queues/queue'),
            'modelRoute' => 'admin.queues',
        ]);

        return view(Base::addPackagePrefix('admin.queues.index'), compact('viewVals'));
    }

    public function retry(Request $request, $id)
    {
        Artisan::call('queue:retry', ['id' => $id]);

        return $this->flashNotification(Artisan::output());
    }

    public function flashNotification($output)
    {
        $safeString = str_replace('"', '', str_replace('\r\n', '<br>', json_encode($output)));
        Alert::info($safeString)->typeToast();

        return redirect()->route('admin.queues.index');
    }

    public function flush(Request $request, $id)
    {
        if ($id == 'all') {
            Artisan::call('queue:flush');
        } else {
            Artisan::call('queue:forget', ['id' => $id]);
        }

        return $this->flashNotification(Artisan::output());
    }

    /**
     * @return Collection
     */
    protected function getPendingJobs(): Collection
    {
        $table = config('queue.connections.database.table');
        return Schema::hasTable($table) ? DB::table($table)->latest()->get() : collect();
    }

    /**
     * @return Collection
     */
    protected function getFailedJobs(): Collection
    {
        $table = config('queue.failed.table');
        return Schema::hasTable($table) ? DB::table($table)->latest()->get() : collect();
    }

}
