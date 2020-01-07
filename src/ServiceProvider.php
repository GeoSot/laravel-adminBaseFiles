<?php

namespace GeoSot\BaseAdmin;


use Carbon\Carbon;
use GeoSot\BaseAdmin\App\Providers\CommandsProvider;
use GeoSot\BaseAdmin\App\Providers\CustomValidationServiceProvider;
use GeoSot\BaseAdmin\App\Providers\RouteServiceProvider;
use GeoSot\BaseAdmin\App\Providers\SidebarServiceProvider;
use GeoSot\BaseAdmin\Helpers\Paths;
use GeoSot\BaseAdmin\Services\Settings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;


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
        $this->setSystemDefaults();

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
        $this->loadMigrationsFrom(Paths::srcDir('database/migrations'));
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
            __DIR__.'/database/migrations/' => database_path('migrations')
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
            RouteServiceProvider::class,
            CommandsProvider::class,
//            ModuleServiceProvider::class,
            CommandsProvider::class,
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

}
