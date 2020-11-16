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

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile(string $search, string $replace, string $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }


}
