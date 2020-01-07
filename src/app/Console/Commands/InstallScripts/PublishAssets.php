<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;


use GeoSot\BaseAdmin\Helpers\Paths;

class PublishAssets extends PublishInitialFiles
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:publishAssets';
    protected $hidden = false;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes all admin Js & Css Files';

    /**
     * Get the view full path.
     *
     *
     * @return string
     */
    public function getFileWithPath()
    {
        return public_path(config('baseAdmin.config.backEnd.assetsPath')).$this->fileName;
    }

    protected function getStubDirectory()
    {
        return Paths::filesToPublishDir('assets');
    }


}
