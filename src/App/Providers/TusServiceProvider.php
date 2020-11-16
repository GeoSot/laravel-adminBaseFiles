<?php

namespace GeoSot\BaseAdmin\App\Providers;


use Carbon\Carbon;
use GeoSot\BaseAdmin\App\Models\Media\Medium;
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
                ->setApiPath(route('admin.media.upload', [], false))
                ->setUploadDir($this->getUploadDir()); // uploads dir.
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


    protected function registerEvents(Server $server)
    {

        $server->event()->addListener(UploadComplete::NAME, function (TusEvent $event) {

            $file = $event->getFile();
            $meta = $file->details()['metadata'];
            $fileMeta = [
                'keywords' => Arr::get($meta, 'keywords'),
                'description' => Arr::get($meta, 'caption'),
                'title' => basename($file->getName() ?: $file->getFilePath())
            ];
            $path = str_replace($this->getStoragePath($this->getUploadSubDir()), $this->getUploadSubDir(), $file->getFilePath());
            MediaUploader::beforeSave(function (Medium $media, $data) use ($fileMeta) {
                $media->fill($fileMeta);
            })->importPath('public', $path);

        });
    }

    /**
     * @return string
     */
    protected function getUploadSubDir(): string
    {
        return Carbon::now()->format('Y'.DIRECTORY_SEPARATOR.'m');
    }


    /**
     * @param  string  $path
     * @return string|false
     */
    protected function getStoragePath(string $path = '')
    {
        return Storage::disk('public')->path($path);
    }


}
