<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;

use Illuminate\Console\Command;

abstract class BaseInstallCommand extends Command
{

    protected $hidden = true;



    /**
     * @return bool
     */
    protected function canExecute(): bool
    {
        if (app()->environment(['live', 'production'])) {
            $this->info("Install Scripts doesn't run on LIVE environments");
            return false;
        }
        return true;
    }


}
