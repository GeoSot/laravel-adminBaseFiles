<?php

namespace GeoSot\BaseAdmin;


use Carbon\Carbon;
use GeoSot\BaseAdmin\App\Providers\AdminRoutingServiceProvider;
use GeoSot\BaseAdmin\App\Providers\CommandsProvider;
use GeoSot\BaseAdmin\App\Providers\CustomValidationServiceProvider;
use GeoSot\BaseAdmin\Services\Settings;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Vendor name.
     *
     * @var string
     */
    protected $vendor = 'geo-sv';
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
        $this->setSystemDefaults();

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //  $this->loadHelpers();
        $this->registerProviders();
        $this->mergeConfigFrom(__DIR__ . "/../config/main.php", $this->package . '.main');
        $this->mergeConfigFrom(__DIR__ . "/../config/config.php", $this->package . '.config');
    }

    /**
     * Register Package Resources
     */
    private function loadResources()
    {
        //  $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', $this->package);
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', $this->package);
    }

    /**
     * Make Resources Available to Publish
     */
    private function publishResources()
    {

        $this->publishes([
            __DIR__ . "/../config" => config_path($this->package)
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views/' => resource_path("views"),//resource_path("views/vendor/{$this->vendor}/{$this->package}"),
        ], 'views');

        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'migrations');
        $this->publishes([
            __DIR__ . '/../resources/lang/' => resource_path("lang"),
            //            __DIR__ . '/../resources/lang/' => resource_path("lang/{$this->vendor}"),
        ], 'translations');
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    private function getPackageVariables()
    {
        return collect([
            'package'     => $this->package,
            'nameSpace'   => $this->package . '::',
            'adminLayout' => config($this->package . ".config.backEnd.layout"),
            'siteLayout'  => config($this->package . ".config.site.layout"),
            'blades'      => $this->package . "::",
        ]);
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $this->app->register(CustomValidationServiceProvider::class);
        $this->app->register(AdminRoutingServiceProvider::class);
        $this->app->register(CommandsProvider::class);
    }

    protected function registerServices(): void
    {
        $this->app->singleton(Settings::class, function ($app) {
            return new Settings();
        });
        $this->app->alias(Settings::class, 'settings');
    }

    /**
     *
     */
    protected function setSystemDefaults(): void
    {
        Schema::defaultStringLength(191);

        Carbon::setLocale(config('app.locale'));
//        Carbon::serializeUsing(function ($carbon) {
//            return $carbon->format('d/m/y H:i:s');
//        });
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__ . '/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }
}
