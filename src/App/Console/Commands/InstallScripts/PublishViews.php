<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;


use GeoSot\BaseAdmin\Helpers\Paths;

class PublishViews extends PublishInitialFiles
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:install:publishViews';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes all Override View (blade) Files';

    /**
     * Get the view full path.
     *
     *
     * @return string
     */
    public function getFileWithPath()
    {
        return resource_path('views').DIRECTORY_SEPARATOR.$this->fileName;
    }

    protected function getStubDirectory()
    {
        return Paths::filesToPublishDir('resources'.DIRECTORY_SEPARATOR.'views');
    }

}
