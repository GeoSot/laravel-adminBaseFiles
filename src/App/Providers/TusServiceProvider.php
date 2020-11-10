<?php

namespace GeoSot\BaseAdmin\App\Providers;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Plank\Mediable\Facades\MediaUploader;
use TusPhp\Events\UploadComplete;
use TusPhp\Tus\Server;

class TusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('tus-server', function ($app) {
            $server = new Server('file');
            $server
                ->setApiPath(route('admin.media.tusUpload', [], false))
                ->setUploadDir($this->getUploadDir()); // uploads dir.
            $this->registerEvents($server);
            return $server;
        });
    }

    protected function getUploadDir(): string
    {

        $dir = storage_path('app/public'.DIRECTORY_SEPARATOR.$this->getUploadSubDir());

        if (!File::exists($dir)) {

            File::makeDirectory($dir, 0755, true, true);
        }


        return $dir;
    }


    protected function registerEvents(Server $server)
    {

        $server->event()->addListener(UploadComplete::NAME, function (\TusPhp\Events\TusEvent $event) {

            $file = $event->getFile();
            $meta = $file->details()['metadata'];
            $filename = pathinfo($file->getFilePath(), PATHINFO_FILENAME);
            $extension = pathinfo($file->getFilePath(), PATHINFO_EXTENSION);
            $fileMeta = [
                'keywords' => Arr::get($meta, 'keywords'),
                'description' => Arr::get($meta, 'caption'),
                'title' => basename(Arr::get($meta, 'name', $filename))
            ];
            MediaUploader::importPath('public', $file->getFilePath(), $fileMeta);

        });
    }

    /**
     * @return string
     */
    protected function getUploadSubDir(): string
    {
        return Carbon::now()->format('m_Y');
    }


}
