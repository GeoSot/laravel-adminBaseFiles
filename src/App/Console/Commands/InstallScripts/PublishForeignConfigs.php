<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;


use GeoSot\BaseAdmin\Helpers\Paths;

class PublishForeignConfigs extends PublishInitialFiles
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:install:publishForeignConfigs';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes all foreign Configuration Files';


    protected function getStubDirectory()
    {
        return Paths::filesToPublishDir('config');
    }

    /**
     * Get the view full path.
     *
     *
     * @return string
     */
    public function getFileWithPath()
    {
        return "config\\{$this->fileName}";
    }

}
