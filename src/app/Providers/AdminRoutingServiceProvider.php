<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 29/1/2019
 * Time: 1:39 πμ
 */

namespace GeoSot\BaseAdmin\App\Providers;

use GeoSot\BaseAdmin\App\Http\Middleware\MinifyHtml;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class AdminRoutingServiceProvider extends ServiceProvider
{
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
        'minifyHtml'            => MinifyHtml::class,
        'role'                  => \Laratrust\Middleware\LaratrustRole::class,
        'permission'            => \Laratrust\Middleware\LaratrustPermission::class,
        'ability'               => \Laratrust\Middleware\LaratrustAbility::class,
        'localize'              => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        'localizationRedirect'  => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
        'localeViewPath'        => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class
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
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {

        $this->registerMiddlewareGroups($router);


        //        $router->group(['namespace' => $this->namespace], function (Router $router) {
        //            $this->loadApiRoutes($router);
        //        });
        //

        $router->namespace($this->namespace)->prefix(LaravelLocalization::setLocale())
            ->middleware(['web', 'localeSessionRedirect', 'localizationRedirect', 'localize'])->group(function (Router $router) {

                $this->loadBackendRoutes($router);
                $router->auth([/*'register' => false, 'reset' => false,*/
                               'verify' => true,]);
                $this->loadFrontendRoutes($router);
            });
    }

    /**
     * @param Router $router
     */
    protected function registerMiddlewareGroups(Router $router)
    {
        foreach ($this->middlewareToAdd as $name => $class) {
            $router->aliasMiddleware($name, $class);
        }
    }

    /**
     * @param Router $router
     */
    private function loadBackendRoutes(Router $router)
    {


        $router->prefix('admin')->middleware(['auth'])->namespace('Admin')->as('admin.')->group(function () use ($router) {
            Route::impersonate();
            $router->get('', 'DashboardController@index')->name('dashboard')->middleware(['permission:admin.*']);
            $router->get('admin.or-site', 'DashboardController@choosePage')->name('choosePage');
            $router->delete('files/delete', "Files\FilesController@delete")->name('files.delete')->middleware(['permission:admin.delete-file']);

            $adminRoutes = config('baseAdmin.main.routes');
            foreach ($adminRoutes as $parentRoute => $node) {
                $parentPlural = Arr::get($node, 'plural', Str::plural($parentRoute));

                $router->prefix($parentPlural)->as($parentPlural . '.')->namespace(ucfirst($parentPlural))->group(function () use ($parentRoute, $node, $parentPlural, $router) {

                    $this->makeCrudRoutes($router, $parentRoute, $parentPlural, '', 'admin');

                    foreach (Arr::get($node, 'menus', []) as $name) {
                        if ($name == $parentRoute) {
                            continue;
                        }
                        $router->prefix(Str::plural($name))->as(Str::plural($name) . '.')->group(function () use ($parentRoute, $name, $router) {
                            $this->makeCrudRoutes($router, $name, null, $parentRoute, 'admin');
                        });

                    }
                });
            }
        });

    }

    /**
     * @param Router $router
     * @param        $name
     * @param null   $plural
     * @param string $parentRoute
     * @param string $side
     */
    protected function makeCrudRoutes(Router $router, $name, $plural = null, $parentRoute = '', $side = 'admin')
    {
        $permissionPrefix = 'permission:' . strtolower($side);
        $controller = ucfirst($parentRoute) . ucfirst($name) . 'Controller';
        $model = ($parentRoute) ? $parentRoute . ucfirst($name) : $name;
        $router->get('', "{$controller}@index")->name('index')->middleware([$permissionPrefix . '.index-' . $model]);;

        $router->get('create', "{$controller}@create")->name('create')->middleware([$permissionPrefix . '.create-' . $model]);
        $router->post('', "{$controller}@store")->name('store')->middleware([$permissionPrefix . '.create-' . $model]);
        $router->get("edit/{{$model}}", "{$controller}@edit")->name('edit')->middleware([$permissionPrefix . '.edit-' . $model]);
        $router->patch("{{$model}}", "{$controller}@update")->name('update')->middleware([$permissionPrefix . '.update-' . $model]);
        $router->delete('delete', "{$controller}@delete")->name('delete')->middleware([$permissionPrefix . '.delete-' . $model]);
        $router->post('restore', "{$controller}@restore")->name('restore')->middleware([$permissionPrefix . '.restore-' . $model]);
        $router->delete('forceDelete', "{$controller}@forceDelete")->name('force.delete')->middleware([$permissionPrefix . '.forceDelete-' . $model]);
        $router->post('changeStatus', "{$controller}@changeStatus")->name('change.status')->middleware([$permissionPrefix . '.update-' . $model]);

    }

    /**
     * @param Router $router
     */
    private function loadFrontendRoutes(Router $router)
    {
        $router->get('/', 'Site\HomeController@index')->name('home');
        $router->namespace('Site')->as('site.')->group(function () use ($router) {
            $router->middleware(['auth'])->group(function () use ($router) {
                $router->as('users.')->prefix('profile')->group(function () use ($router) {
                    $router->get('', "UserProfileController@edit")->name('edit');
                    $router->patch('', "UserProfileController@update")->name('update');
                });
            });
        });
//        $router->namespace('Site')->as('site.')->group(function () use ($router) {
//
//        });
    }

    /**
     * @param Router $router
     */
    private function loadApiRoutes(Router $router)
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

}
