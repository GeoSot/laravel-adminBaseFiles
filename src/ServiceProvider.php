<?php

namespace GeoSot\BaseAdmin;


use GeoSot\BaseAdmin\App\Providers\BaseAdminRouteServiceProvider;
use GeoSot\BaseAdmin\App\Providers\CommandsProvider;
use GeoSot\BaseAdmin\App\Providers\CustomValidationServiceProvider;
use GeoSot\BaseAdmin\App\Providers\FortifyViewsServiceProvider;
use GeoSot\BaseAdmin\Helpers\Alert;
use GeoSot\BaseAdmin\Helpers\Paths;
use GeoSot\BaseAdmin\Services\Settings;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Vendor name.
     *
     * @var string
     */
    protected $vendor = 'geo-sot';
    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'baseAdmin';


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadResources();
        $this->publishResources();
        $this->registerServices();
        view()->share('packageVariables', $this->getPackageVariables());

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->registerProviders();
        $configDir = Paths::rootDir('config');
        $this->mergeConfigFrom($configDir.'main.php', $this->package.'.main');
        $this->mergeConfigFrom($configDir.'config.php', $this->package.'.config');

    }

    /**
     * Register Package Resources
     */
    private function loadResources()
    {
        //  $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
        $this->loadMigrationsFrom(Paths::srcDir('Database/Migrations'));
        $this->loadViewsFrom(Paths::rootDir('resources/views'), $this->package);
        $this->loadTranslationsFrom(Paths::rootDir('resources/lang'), $this->package);
    }

    /**
     * Make Resources Available to Publish
     */
    private function publishResources()
    {

        $this->publishes([
            __DIR__."/../config" => config_path($this->package)
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/views/' => resource_path("views/vendor/{$this->vendor}/{$this->package}"),//resource_path("views/vendor/{$this->vendor}/{$this->package}"),
        ], 'views');


        $this->publishes([
            __DIR__.'/Database/Migrations/' => database_path('migrations'),
        ], 'migrations');
        $this->publishes([
//            __DIR__ . '/../resources/lang/' => resource_path("lang"),
            __DIR__.'/../resources/lang/' => resource_path("lang/vendor/{$this->package}"),
        ], 'translations');
    }


    /**
     * @return Collection
     */
    private function getPackageVariables()
    {
        return collect([
            'package' => $this->package,
            'nameSpace' => $this->package.'::',
            'adminLayout' => config($this->package.".config.backEnd.layout"),
            'siteLayout' => config($this->package.".config.site.layout"),
            'blades' => $this->package."::",
        ]);
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $providers = [
            CustomValidationServiceProvider::class,
            BaseAdminRouteServiceProvider::class,
            CommandsProvider::class,
            FortifyViewsServiceProvider::class
//            ModuleServiceProvider::class,
        ];

        array_map(function ($provider) {
            $this->app->register($provider);
        }, $providers);

    }

    /**
     * Register Services.
     */
    protected function registerServices()
    {
        $this->app->singleton('settings', function ($app) {
            return new Settings();
        });
        $this->app->bind('alert', function (Container $app) {
            return new Alert($app->make('session'));
        });

        $this->app->alias('Settings', Facades\Settings::class);

    }

}
