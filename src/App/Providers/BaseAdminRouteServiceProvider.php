<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 29/1/2019
 * Time: 1:39 πμ
 */

namespace GeoSot\BaseAdmin\App\Providers;

use App\Models\Pages\Page;
use GeoSot\BaseAdmin\App\Http\Middleware\ForceHttpsProtocol;
use GeoSot\BaseAdmin\App\Http\Middleware\MinifyHtml;
use GeoSot\BaseAdmin\App\Http\Middleware\RedirectIfAuthenticatedAtIntended;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\Middleware as Mcamara;


class BaseAdminRouteServiceProvider extends ServiceProvider
{
    protected const APP_NS = 'App\Http\Controllers';
    protected const BASE_NS = 'GeoSot\BaseAdmin\App\Http\Controllers';

    protected $router;
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = '';


    /**
     * Middlewares to Register
     *
     * @var array
     */
    protected $middlewareToAdd = [
        'forceHttps' => ForceHttpsProtocol::class,
        'minifyHtml' => MinifyHtml::class,
        'redirectAtIntended' => RedirectIfAuthenticatedAtIntended::class,
        'localize' => Mcamara\LaravelLocalizationRoutes::class,
        'localizationRedirect' => Mcamara\LaravelLocalizationRedirectFilter::class,
        'localeSessionRedirect' => Mcamara\LocaleSessionRedirect::class,
        'localeCookieRedirect' => Mcamara\LocaleCookieRedirect::class,
        'localeViewPath' => Mcamara\LaravelLocalizationViewPath::class
    ];


    public function boot()
    {
        $this->registerMiddlewareGroups();
    }

    /**
     * Define the routes for the application.
     *
     * @param  Router  $router
     *
     * @return void
     */
    public function map()
    {
        Route::namespace($this->namespace)->prefix(LaravelLocalization::setLocale())
            ->middleware(['web', 'localeSessionRedirect', 'localizationRedirect', 'localize'])->group(function () {
                Route::prefix(config('baseAdmin.config.backEnd.routePrefix'))->as('admin.')->middleware([
                    'auth', 'verified'
                ])->group(function () {
                    $this->loadBackendRoutes();
                });

                $file = base_path('routes/admin.php');
                if (file_exists($file)) {
                    Route::prefix(config('baseAdmin.config.backEnd.routePrefix'))->as('admin.')->middleware([
                        'auth', 'verified'
                    ])->group($file);
                }

                Route::prefix(config('baseAdmin.config.frontEnd.routePrefix'))->group(function () {
                    $this->loadFrontendRoutes();
                });
            });
    }


    /**
     * register Middleware
     */
    protected function registerMiddlewareGroups()
    {
        foreach ($this->middlewareToAdd as $name => $class) {
            Route::aliasMiddleware($name, $class);
        }
    }

    /**
     * @return   Router  $router
     */
    protected function getRouter()
    {
        return resolve(Router::class);
    }


    /**
     */
    private function loadBackendRoutes()
    {


        Route::any('/uploads/{any?}', static::getController('Media\MediumController').'@upload')->where('any',
            '.*')->name('media.upload');
        Route::post('restore/{revision}', static::getController('RestoreController').'@restoreHistory')->name('restore');
        Route::post('restore/clear/{revision}',
            static::getController('RestoreController').'@clearHistory')->name('restore.clear');

        Route::impersonate();
        Route::get('log', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs.index');
        Route::get('',
            static::getController('DashboardController').'@index')->name('dashboard')->middleware(['permission:admin.*']);
        Route::get('admin.or-site',
            static::getController('DashboardController').'@choosePage')->name('choosePage');


        //QUEUES
        Route::prefix('queues')->name('queues.')->group(function () {
            Route::get('',
                static::getController('Queues\QueueController').'@index')->name('index')->middleware(['permission:admin.index-job']);
            Route::patch('retry/{id}',
                static::getController('Queues\QueueController').'@retry')->name('retry')->middleware(['permission:admin.retry-job']);
            Route::patch('flush/{id}',
                static::getController('Queues\QueueController').'@flush')->name('flush')->middleware(['permission:admin.flush-job']);
        });


        //ALL
        $this->registerRoutesFromConfigurationFile();


    }

    /**
     * @param  string  $name
     * @param  string  $namespace
     * @param  string  $parentRoute
     * @param  string|null  $plural
     */
    protected function makeCrudRoutes(string $name, string $namespace, string $parentRoute = '', string $plural = null)
    {

        $permissionPrefix = 'permission:admin.';
        $controller = $namespace.'\\'.ucfirst($parentRoute).ucfirst($name).'Controller';

        $controller = static::getController($controller);

        $model = ($parentRoute) ? $parentRoute.ucfirst($name) : $name;


        Route::get('',
            "{$controller}@index")->name('index')->middleware([$permissionPrefix.'index-'.$model]);

        Route::get('create',
            "{$controller}@create")->name('create')->middleware([$permissionPrefix.'create-'.$model]);
        Route::post('',
            "{$controller}@store")->name('store')->middleware([$permissionPrefix.'create-'.$model]);
        Route::get("edit/{{$model}}",
            "{$controller}@edit")->name('edit')->middleware([$permissionPrefix.'edit-'.$model]);
        Route::patch("{{$model}}",
            "{$controller}@update")->name('update')->middleware([$permissionPrefix.'update-'.$model]);
        Route::delete('delete',
            "{$controller}@delete")->name('delete')->middleware([$permissionPrefix.'delete-'.$model]);
        Route::post('restore',
            "{$controller}@restore")->name('restore')->middleware([$permissionPrefix.'restore-'.$model]);
        Route::delete('forceDelete',
            "{$controller}@forceDelete")->name('force.delete')->middleware([$permissionPrefix.'forceDelete-'.$model]);
        Route::post('changeStatus',
            "{$controller}@changeStatus")->name('change.status')->middleware([$permissionPrefix.'update-'.$model]);

    }

    /**
     */
    protected function registerRoutesFromConfigurationFile()
    {
        $adminRoutes = config('baseAdmin.main.routes');
        foreach ($adminRoutes as $parentRoute => $node) {
            $parentPlural = Arr::get($node, 'plural', Str::plural($parentRoute));

            Route::prefix($parentPlural)->as($parentPlural.'.')->group(function () use (
                $parentRoute,
                $node,
                $parentPlural
            ) {

                $nameSpace = ucfirst($parentPlural);
                if ( ! Arr::has($node, 'menus') or in_array($parentRoute, $node['menus'])) {
                    $this->makeCrudRoutes($parentRoute, $nameSpace, '', $parentPlural);
                }

                foreach (Arr::get($node, 'menus', []) as $name) {
                    if ($name == $parentRoute) {
                        continue;
                    }
                    Route::prefix(Str::plural($name))->as(Str::plural($name).'.')->group(function () use (
                        $parentRoute,
                        $name,
                        $nameSpace
                    ) {
                        $this->makeCrudRoutes($name, $nameSpace, $parentRoute, null);
                    });

                }
            });
        }
    }

    private function loadFrontendRoutes()
    {

        Route::as('site.')->group(function () {
            Route::get('/', static::getController('HomeController', 'Site').'@index')->name('home');
            Route::post('contact-us',
                static::getController('HomeController', 'Site').'@contactUs')->name('contactUs.store');
            Route::middleware(['auth', 'verified'])->group(function () {
                Route::as('users.')->prefix('profile')->group(function () {
                    Route::get('',
                        static::getController('UserProfileController', 'Site').'@edit')->name('edit');
                    Route::patch('',
                        static::getController('UserProfileController', 'Site').'@update')->name('update');
                });
            });
        });
    }

    public static function dynamicPages()
    {
        Route::get('{page?}', function ($page) {
            if ($page) {
                $pg = Page::where('slug', $page)->first();
                if ($pg) {
                    return App::call(static::getController('GenericPageController', 'Site'), '@show', ['page' => $pg]);
                }
            }
            return abort(404);
        })->name('site.pages');
    }

    /**
     * @param  string  $controller
     * @param  string  $side
     * @return string
     */
    protected static function getController(string $controller, string $side = 'Admin'): string
    {
        $appController = self::APP_NS.'\\'.$side.'\\'.$controller;
        return class_exists($appController) ? $appController : self::BASE_NS.'\\'.$side.'\\'.$controller;

    }
}
