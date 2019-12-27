<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;


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
        return $this->getBaseStubDirectory().'config/';
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
