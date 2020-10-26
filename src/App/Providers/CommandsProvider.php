<?php

namespace GeoSot\BaseAdmin\App\Providers;

use GeoSot\BaseAdmin\App\Console\Commands\CreateAdminBaseFromFile;
use GeoSot\BaseAdmin\App\Console\Commands\InstallScripts\AddValuesToConfigFiles;
use GeoSot\BaseAdmin\App\Console\Commands\InstallScripts\InitializeEnv;
use GeoSot\BaseAdmin\App\Console\Commands\InstallScripts\MainCommand;
use GeoSot\BaseAdmin\App\Console\Commands\InstallScripts\PublishAssets;
use GeoSot\BaseAdmin\App\Console\Commands\InstallScripts\PublishInitialFiles;
use GeoSot\BaseAdmin\App\Console\Commands\MakeAdminController;
use GeoSot\BaseAdmin\App\Console\Commands\MakeAdminPermissions;
use GeoSot\BaseAdmin\App\Console\Commands\MakeLanguage;
use GeoSot\BaseAdmin\App\Console\Commands\MakeModel;
use GeoSot\BaseAdmin\App\Console\Commands\MakeView;
use Illuminate\Support\ServiceProvider;

class CommandsProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerConsoleCommands();
            $this->registerInstallCommands();
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        //
    }

    /**
     * Register Package Console Commands.
     */
    private function registerConsoleCommands()
    {
        $this->commands([
            CreateAdminBaseFromFile::class,
            MakeAdminController::class,
            MakeAdminPermissions::class,
            MakeLanguage::class,
            MakeModel::class,
            MakeView::class,
        ]);
    }

    private function registerInstallCommands()
    {
        $this->commands([
            MainCommand::class,
            InitializeEnv::class,
            PublishInitialFiles::class,
            PublishAssets::class,
            AddValuesToConfigFiles::class,
        ]);
    }
}
