<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 29/1/2019
 * Time: 1:39 πμ
 */

namespace GeoSot\BaseAdmin\App\Providers;

use App\Models\Pages\Page;
use GeoSot\BaseAdmin\App\Http\Middleware\MinifyHtml;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\Middleware as Mcamara;


class RouteServiceProvider extends ServiceProvider
{


    protected $router;
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';


    /**
     * Middlewares to Register
     *
     * @var array
     */
    protected $middlewareToAdd = [
        'minifyHtml' => MinifyHtml::class,
        'localize' => Mcamara\LaravelLocalizationRoutes::class,
        'localizationRedirect' => Mcamara\LaravelLocalizationRedirectFilter::class,
        'localeSessionRedirect' => Mcamara\LocaleSessionRedirect::class,
        'localeCookieRedirect' => Mcamara\LocaleCookieRedirect::class,
        'localeViewPath' => Mcamara\LaravelLocalizationViewPath::class
    ];

    public function boot()
    {
        parent::boot();

        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }

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

        $router->namespace($this->namespace)->group(function () {
            $this->loadApiRoutes();
        });

        $router->namespace($this->namespace)->prefix(LaravelLocalization::setLocale())
            ->middleware(['web', 'localeSessionRedirect', 'localizationRedirect', 'localize'])->group(function () {
                $this->getRouter()->prefix(config('baseAdmin.config.backEnd.baseRoute'))->namespace('Admin')->as('admin.')->middleware(['auth'])->group(function () {
                    $this->loadBackendRoutes();
                });
                Route::mixin(new \Laravel\Ui\AuthRouteMethods());
                Route::auth(config('baseAdmin.config.authActions'));
                $this->getRouter()->prefix(config('baseAdmin.config.frontEnd.baseRoute'))->namespace('Site')->group(function () {
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

    private function loadApiRoutes()
    {
        //        $api = $this->getApiRoute();
        //
        //        if ($api && file_exists($api)) {
        //            $router->group([
        //                'namespace'  => 'Api',
        //                'prefix'     =>  '/api',
        //                'middleware' =>[],
        //            ], function (Router $router) use ($api) {
        //                require $api;
        //            });
        //        }
    }

    /**
     */
    private function loadBackendRoutes()
    {
        Route::impersonate();
        $this->getRouter()->get('log', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs.index');
        $this->getRouter()->get('', 'DashboardController@index')->name('dashboard')->middleware(['permission:admin.*']);
        $this->getRouter()->get('admin.or-site', 'DashboardController@choosePage')->name('choosePage');


        //QUEUES
        $this->getRouter()->prefix('queues')->name('queues.')->namespace('Queues')->group(function () {
            $this->getRouter()->get('', "QueueController@index")->name('index')->middleware(['permission:admin.index-job']);
            $this->getRouter()->patch('retry/{id}', "QueueController@retry")->name('retry')->middleware(['permission:admin.retry-job']);
            $this->getRouter()->patch('flush/{id}', "QueueController@flush")->name('flush')->middleware(['permission:admin.flush-job']);
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
    protected function makeCrudRoutes($name, $plural = null, $parentRoute = '', $side = 'admin', string $controller = null)
    {
        $permissionPrefix = 'permission:'.strtolower($side);
        $controller = $controller ?? ucfirst($parentRoute).ucfirst($name).'Controller';
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

            $this->getRouter()->prefix($parentPlural)->as($parentPlural.'.')->namespace(ucfirst($parentPlural))->group(function () use ($parentRoute, $node, $parentPlural) {

                if (!Arr::has($node, 'menus') or in_array($parentRoute, $node['menus'])) {
                    $this->makeCrudRoutes($parentRoute, $parentPlural, '', 'admin');
                }

                foreach (Arr::get($node, 'menus', []) as $name) {
                    if ($name == $parentRoute) {
                        continue;
                    }
                    $this->getRouter()->prefix(Str::plural($name))->as(Str::plural($name).'.')->group(function () use ($parentRoute, $name) {
                        $this->makeCrudRoutes($name, null, $parentRoute, 'admin');
                    });

                }
            });
        }
    }

    private function loadFrontendRoutes()
    {
        $this->getRouter()->get('/', 'HomeController@index')->name('home');
        $this->getRouter()->as('site.')->group(function () {
            $this->getRouter()->post('contact-us', 'HomeController@contactUs')->name('contactUs.store');
            $this->getRouter()->middleware(['auth'])->group(function () {
                $this->getRouter()->as('users.')->prefix('profile')->group(function () {
                    $this->getRouter()->get('', "UserProfileController@edit")->name('edit');
                    $this->getRouter()->patch('', "UserProfileController@update")->name('update');
                });
            });

            $this->getRouter()->get('{page}', function ($page) {
                $pg = Page::whereSlug($page);
                if ($pg->exists()) {
                    return App::call('App\Http\Controllers\Site\GenericPageController@show', ['page' => $pg->first()]);
                }
                return abort(404);
            })->name('pages.index');

        });

    }
}
