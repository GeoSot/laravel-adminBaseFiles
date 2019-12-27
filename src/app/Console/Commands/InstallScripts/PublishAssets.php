<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;


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
        return public_path(config('baseAdmin.config.backEnd.assetsPath')).DIRECTORY_SEPARATOR.$this->fileName;
    }

    protected function getStubDirectory()
    {
        return $this->getBaseStubDirectory().'assets\\';
    }

}
