<?php

namespace GeoSot\BaseAdmin\App\Providers;


use GeoSot\BaseAdmin\App\Console\Commands\{CreateAdminBaseFromFile,
    InstallScripts\AddValuesToConfigFiles,
    InstallScripts\InitializeEnv,
    InstallScripts\MainCommand,
    InstallScripts\PublishAssets,
    InstallScripts\PublishForeignConfigs,
    InstallScripts\PublishInitialFiles,
    InstallScripts\PublishViews,
    MakeAdminController,
    MakeAdminPermissions,
    MakeLanguage,
    MakeModel,
    MakeNewMigration,
    MakeView,
    RefreshDbMigrationsAndSeeds
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
            MakeModel::class,
            MakeNewMigration::class,
            MakeView::class,
            RefreshDbMigrationsAndSeeds::class,
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
            PublishForeignConfigs::class,
            PublishViews::class,
        ]);
    }

}
