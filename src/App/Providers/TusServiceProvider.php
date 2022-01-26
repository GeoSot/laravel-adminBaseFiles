<?php

namespace GeoSot\BaseAdmin\App\Providers;


use Carbon\Carbon;
use GeoSot\BaseAdmin\App\Events\MediumUploaded;
use GeoSot\BaseAdmin\App\Models\Media\Medium;
use GeoSot\BaseAdmin\Helpers\Uploads;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Plank\Mediable\Facades\MediaUploader;
use TusPhp\Events\TusEvent;
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
                ->setApiPath(Uploads::getMediaUploadPath(false))
                ->setUploadDir($this->getUploadDir());// uploads dir changes per month
//                ->setCache(new FileStore(Storage::disk(config('mediable.default_disk', 'public'))->path('')));
            $this->registerEvents($server);
            return $server;
        });
    }

    protected function getUploadDir(): string
    {
        $dir = $this->getStoragePath($this->getUploadSubDir());
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true, true);
        }

        return $dir;
    }


    protected function registerEvents(Server $server): void
    {
        $server->event()->addListener(UploadComplete::NAME, function (TusEvent $event) {
            $file = $event->getFile();
            $meta = $file->details()['metadata'];
            $fileMeta = [
                'keywords' => Arr::get($meta, 'keywords'),
                'description' => Arr::get($meta, 'caption'),
                'title' => basename($file->getName() ?: $file->getFilePath()),
            ];
            $path = str_replace($this->getStoragePath($this->getUploadSubDir()), $this->getUploadSubDir(), $file->getFilePath());
            $medium = MediaUploader::beforeSave(function (Medium $media, $data) use ($fileMeta) {
                $media->fill($fileMeta);
            })->importPath('public', $path);
            MediumUploaded::dispatch($medium);
        });
    }

    /**
     * @return string
     */
    protected function getUploadSubDir(): string
    {
        return Carbon::now()->format("Y").DIRECTORY_SEPARATOR.Carbon::now()->format("m");
    }


    protected function getStoragePath(string $path = ''): string
    {
        return Storage::disk(Uploads::getDefaultDisk())->path($path);
    }
}
