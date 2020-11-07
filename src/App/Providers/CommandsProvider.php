<?php

namespace GeoSot\BaseAdmin\App\Providers;


use GeoSot\BaseAdmin\App\Console\Commands\{CreateAdminBaseFromFile,
    InstallScripts\AddValuesToConfigFiles,
    InstallScripts\InitializeEnv,
    InstallScripts\MainCommand,
    InstallScripts\PublishAssets,
    InstallScripts\PublishInitialFiles,
    MakeAdminController,
    MakeAdminPermissions,
    MakeLanguage
};
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
     * Register Package Console Commands
     */
    private function registerConsoleCommands()
    {
        $this->commands([
            CreateAdminBaseFromFile::class,
            MakeAdminController::class,
            MakeAdminPermissions::class,
            MakeLanguage::class,
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
