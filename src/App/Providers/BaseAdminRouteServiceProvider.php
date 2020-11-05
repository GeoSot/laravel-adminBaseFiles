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


    /**
     * Define the routes for the application.
     *
     * @param  Router  $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $this->router = $router;
        $this->registerMiddlewareGroups();

        $router->namespace($this->namespace)->prefix(LaravelLocalization::setLocale())
            ->middleware(['web', 'localeSessionRedirect', 'localizationRedirect', 'localize'])->group(function () {
                $this->getRouter()->prefix(config('baseAdmin.config.backEnd.baseRoute'))->as('admin.')->middleware(['auth'])->group(function () {
                    $this->loadBackendRoutes();
                });

                $this->getRouter()->prefix(config('baseAdmin.config.frontEnd.baseRoute'))->group(function () {
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
            $this->getRouter()->aliasMiddleware($name, $class);
        }
    }

    /**
     * @return   Router  $router
     */
    protected function getRouter()
    {
        return $this->router;
    }


    /**
     */
    private function loadBackendRoutes()
    {


        Route::any('/tus/{any?}', $this->getContoller('Media\MediumController').'@tusUpload')->where('any', '.*')->name('media.tusUpload');
        Route::post('restore/{revision}', $this->getContoller('RestoreController').'@restoreHistory')->name('restore');
        Route::post('restore/clear/{revision}', $this->getContoller('RestoreController').'@clearHistory')->name('restore.clear');

        Route::impersonate();
        $this->getRouter()->get('log', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs.index');
        $this->getRouter()->get('', $this->getContoller('DashboardController').'@index')->name('dashboard')->middleware(['permission:admin.*']);
        $this->getRouter()->get('admin.or-site', $this->getContoller('DashboardController').'@choosePage')->name('choosePage');


        //QUEUES
        $this->getRouter()->prefix('queues')->name('queues.')->group(function () {
            $this->getRouter()->get('', $this->getContoller('Queues\QueueController').'@index')->name('index')->middleware(['permission:admin.index-job']);
            $this->getRouter()->patch('retry/{id}', $this->getContoller('Queues\QueueController').'@retry')->name('retry')->middleware(['permission:admin.retry-job']);
            $this->getRouter()->patch('flush/{id}', $this->getContoller('Queues\QueueController').'@flush')->name('flush')->middleware(['permission:admin.flush-job']);
        });


        //ALL
        $this->registerRoutesFromConfigurationFile();


    }

    /**
     * @param        $name
     * @param  null  $plural
     * @param  string  $parentRoute
     * @param  string  $side
     * @param  string|null  $controller
     */
    protected function makeCrudRoutes($name, $plural = null, $parentRoute = '', string $namespace)
    {
        $side = 'admin';
        $permissionPrefix = 'permission:'.strtolower($side);
        $controller = $namespace.'\\'.ucfirst($parentRoute).ucfirst($name).'Controller';

        $controller = $this->getContoller($controller);

        $model = ($parentRoute) ? $parentRoute.ucfirst($name) : $name;


        $this->getRouter()->get('',
            "{$controller}@index")->name('index')->middleware([$permissionPrefix.'.index-'.$model]);

        $this->getRouter()->get('create',
            "{$controller}@create")->name('create')->middleware([$permissionPrefix.'.create-'.$model]);
        $this->getRouter()->post('',
            "{$controller}@store")->name('store')->middleware([$permissionPrefix.'.create-'.$model]);
        $this->getRouter()->get("edit/{{$model}}",
            "{$controller}@edit")->name('edit')->middleware([$permissionPrefix.'.edit-'.$model]);
        $this->getRouter()->patch("{{$model}}",
            "{$controller}@update")->name('update')->middleware([$permissionPrefix.'.update-'.$model]);
        $this->getRouter()->delete('delete',
            "{$controller}@delete")->name('delete')->middleware([$permissionPrefix.'.delete-'.$model]);
        $this->getRouter()->post('restore',
            "{$controller}@restore")->name('restore')->middleware([$permissionPrefix.'.restore-'.$model]);
        $this->getRouter()->delete('forceDelete',
            "{$controller}@forceDelete")->name('force.delete')->middleware([$permissionPrefix.'.forceDelete-'.$model]);
        $this->getRouter()->post('changeStatus',
            "{$controller}@changeStatus")->name('change.status')->middleware([$permissionPrefix.'.update-'.$model]);

    }

    /**
     */
    protected function registerRoutesFromConfigurationFile()
    {
        $adminRoutes = config('baseAdmin.main.routes');
        foreach ($adminRoutes as $parentRoute => $node) {
            $parentPlural = Arr::get($node, 'plural', Str::plural($parentRoute));

            $this->getRouter()->prefix($parentPlural)->as($parentPlural.'.')->group(function () use ($parentRoute, $node, $parentPlural) {

                $nameSpace = ucfirst($parentPlural);
                if (!Arr::has($node, 'menus') or in_array($parentRoute, $node['menus'])) {
                    $this->makeCrudRoutes($parentRoute, $parentPlural, '', $nameSpace);
                }

                foreach (Arr::get($node, 'menus', []) as $name) {
                    if ($name == $parentRoute) {
                        continue;
                    }
                    $this->getRouter()->prefix(Str::plural($name))->as(Str::plural($name).'.')->group(function () use ($parentRoute, $name, $nameSpace) {
                        $this->makeCrudRoutes($name, null, $parentRoute, $nameSpace);
                    });

                }
            });
        }
    }

    private function loadFrontendRoutes()
    {

        $this->getRouter()->as('site.')->group(function () {
            $this->getRouter()->post('contact-us', $this->getContoller('HomeController', 'Site').'@contactUs')->name('contactUs.store');
            $this->getRouter()->middleware(['auth'])->group(function () {
                $this->getRouter()->as('users.')->prefix('profile')->group(function () {
                    $this->getRouter()->get('', $this->getContoller('UserProfileController', 'Site').'@edit')->name('edit');
                    $this->getRouter()->patch('', $this->getContoller('UserProfileController', 'Site').'@update')->name('update');
                });
            });

        });

    }

    public static function dynamicPages()
    {
        Route::get('{page}', function ($page) {
            $pg = Page::where('slug', $page)->first();
            if ($pg) {
                return App::call($this->getContoller('GenericPageController', 'Site'), '@show', ['page' => $pg]);
            }
            return abort(404);
        })->name('site.pages');
    }

    /**
     * @param  string  $controller
     * @return string
     */
    protected function getContoller(string $controller, string $side = 'Admin'): string
    {
        $appController = self::APP_NS.DIRECTORY_SEPARATOR.$side.'\\'.$controller;
        return class_exists($appController) ? $appController : self::BASE_NS.DIRECTORY_SEPARATOR.$side.'\\'.$controller;

    }
}
