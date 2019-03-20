<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Queues;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{
    public function index()
    {
        $records = collect([
            'jobs'        => collect([
                'records'  => $this->getPendingJobs(),
                'listable' => ['id', 'name', 'queue', 'attempts', 'reserved_at', 'available_at', 'created_at', 'actions'],
            ]),
            'failed_jobs' => collect([
                'records'  => $this->getFailedJobs(),
                'listable' => ['id', 'name', 'connection', 'queue', 'failed_at', 'actions'],
            ]),
        ]);

        $viewVals = collect([
            'records'    => $records,
            'modelLang'  => 'admin/queues/queue',
            'modelRoute' => 'admin.queues',
        ]);

        return view('admin.queues.index', compact('viewVals'));
    }

    public function retry(Request $request, $id)
    {
        Artisan::call('queue:retry', ['id' => $id]);

        return $this->flashNotification(Artisan::output());
    }

    public function flashNotification($output)
    {
        $safeString = str_replace('"', '', str_replace('\r\n', '<br>', json_encode($output)));
        flashToastr($safeString);

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
     * @return \Illuminate\Support\Collection
     */
    protected function getPendingJobs(): \Illuminate\Support\Collection
    {
        return DB::table(config('queue.connections.database.table'))->latest()->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getFailedJobs(): \Illuminate\Support\Collection
    {
        return DB::table(config('queue.failed.table'))->get();
    }

}
