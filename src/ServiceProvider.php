<?php

namespace GeoSot\BaseAdmin;


use GeoSot\BaseAdmin\App\Providers\BaseAdminRouteServiceProvider;
use GeoSot\BaseAdmin\App\Providers\CommandsProvider;
use GeoSot\BaseAdmin\App\Providers\CustomValidationServiceProvider;
use GeoSot\BaseAdmin\App\Providers\FortifyViewsServiceProvider;
use GeoSot\BaseAdmin\App\Providers\ImageManipulatorServiceProvider;
use GeoSot\BaseAdmin\App\Providers\TusServiceProvider;
use GeoSot\BaseAdmin\Helpers\Alert;
use GeoSot\BaseAdmin\Helpers\Paths;
use GeoSot\BaseAdmin\Services\Settings;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;


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
    protected const PACKAGE = 'baseAdmin';


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
        view()->share('packageVariables', static::getPackageVariables());
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
        $this->mergeConfigFrom($configDir.'main.php', static::PACKAGE.'.main');
        $this->mergeConfigFrom($configDir.'config.php', static::PACKAGE.'.config');
    }

    /**
     * Register Package Resources
     */
    private function loadResources()
    {
        //  $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
        $this->loadMigrationsFrom(Paths::srcDir('Database/migrations'));
        $this->loadViewsFrom(Paths::rootDir('resources/views'), static::PACKAGE);
        $this->loadTranslationsFrom(Paths::rootDir('resources/lang'), static::PACKAGE);
    }

    /**
     * Make Resources Available to Publish
     */
    private function publishResources()
    {
        $this->publishes([
            __DIR__."/../config" => config_path(static::PACKAGE),
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


        $this->publishes([
            __DIR__.'/../assets' => public_path("vendor/{$this->package}"),
        ], 'baseAdmin-assets');


        Blade::component("{$this->package}::_subBlades._components.modal", 'baseAdmin-modal');

//                $this->publishes([
//                    __DIR__.'/../filesToPublish' => base_path(),
//                ], 'baseAdmin-mainFiles');

    }


    /**
     * @return Collection
     */
    public static function getPackageVariables(): Collection
    {
        return collect([
            'package' => static::PACKAGE,
            'nameSpace' => static::PACKAGE.'::',
            'adminLayout' => config(static::PACKAGE.".config.backEnd.layout"),
            'siteLayout' => config(static::PACKAGE.".config.site.layout"),
            'blades' => static::PACKAGE."::",
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
            FortifyViewsServiceProvider::class,
            TusServiceProvider::class,
            ImageManipulatorServiceProvider::class
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
