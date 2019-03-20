<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class CreateAdminBaseFromFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:autoCreateAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automated Models And AdminControllers Based On baseAdmin Config File';

    protected $composer;

    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Support\Composer $composer
     *
     * @return void
     */
    public function __construct(Composer $composer)
    {
        parent::__construct();


        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $adminRoutes = config('baseAdmin.main.routes');
        foreach ($adminRoutes as $parentRoute => $node) {

            if (Arr::get($node, 'makeFiles', true) === false) {
                continue;
            }

            $parentPlural = Arr::get($node, 'plural', Str::plural($parentRoute));

            $this->makeFiles($parentRoute, $parentPlural, '', isset($node['menus']), $this->existsOnTranslatableArray($parentPlural, $node));

            foreach (Arr::get($node, 'menus', []) as $name) {
                if ($name == $parentRoute) {
                    continue;
                }
                $this->makeFiles($name, $parentPlural, $parentRoute, true, $this->existsOnTranslatableArray($name, $node));
            }

        }
        $this->info('New Models And AdminControllers Are READY :), You Are ready To Fly Baby ');
        $this->composer->dumpAutoloads();
    }

    protected function makeFiles($name, $plural, $parentRoute = '', $hasSubMenus = true, $isTranslatable = false)
    {
        $controllerName = ucfirst($parentRoute) . ucfirst($name) . 'Controller';
        $model = ucfirst($parentRoute) . ucfirst($name);
        $modelNameSpace = 'App\Models\\' . (($plural) ? ucfirst($plural) . '\\' : '');
        $fullModelClass = $modelNameSpace . $model;
        $viewBaseDir = lcfirst(Str::plural(empty($parentRoute) ? $model : $parentRoute));
        if ($hasSubMenus) {
            $viewBaseDir .= '/' . lcfirst(Str::plural($model));
        }
        $viewBase = str_replace('/', '.', $viewBaseDir);
        $langBase = lcfirst($plural) . '/' . lcfirst($model);
        $routeBase = ((!empty($parentRoute)) ? Str::plural($parentRoute) . '.' : '') . Str::plural($name);


        $this->info('');
        $this->info($model . '  ---  Making Files');
        $this->info('------------------------------------------');

        if ($this->confirm("Make  {$model} Files?")) {

            if ($this->confirm("Make {$model} Model?")) {
                $this->makeModel($fullModelClass, $viewBase, $langBase, $routeBase, $isTranslatable);
            }
            if ($this->confirm("Make Admin Permissions for {$model} Model?")) {
                $this->makePermissions($model);
            }
            if ($this->confirm("Make {$controllerName} Controller?")) {
                $this->makeController($plural, $controllerName, $fullModelClass);
            }
            if ($this->confirm("Make {$model} Migration?")) {
                $this->makeMigration($name, $parentRoute, $model);
            }
            if ($this->confirm("Make {$model} Factory?")) {
                $this->makeFactory($model, $fullModelClass);
            }
            if ($this->confirm("Make {$langBase} LanguageFile?")) {
                $this->makeLanguage($langBase);
            }
            if ($this->confirm("Make {$model} ViewFile?")) {
                $this->makeView($parentRoute, $model, $viewBaseDir, 'index');
            }
        }
        $this->info('------------------------------------------');
        $this->info('');
    }

    /**
     * @param        $fullModelClass
     * @param string $viewBase
     * @param string $langBase
     * @param string $routeBase
     * @param bool   $isTranslatable
     */
    protected function makeModel($fullModelClass, string $viewBase, string $langBase, string $routeBase, bool $isTranslatable)
    {
        //Model
        $this->call('baseAdmin:makeModel', [
            'name'           => $fullModelClass,
            '--viewBase'     => $viewBase,
            '--langBase'     => $langBase,
            '--routeBase'    => $routeBase,
            '--translatable' => $isTranslatable
        ]);
    }

    /**
     * @param $model
     */
    protected function makePermissions($model)
    {
        //Permissions
        $this->call('baseAdmin:makePermissionsForModel', [
            'name' => $model
        ]);
    }

    /**
     * @param $plural
     * @param $controllerName
     * @param $fullModelClass
     */
    protected function makeController($plural, $controllerName, $fullModelClass)
    {
        //Controller
        $this->call('baseAdmin:makeAdminController', [
            'name'    => ucfirst($plural) . '\\' . $controllerName,
            '--model' => $fullModelClass,
        ]);
    }

    /**
     * @param $name
     * @param $parentRoute
     * @param $model
     */
    protected function makeMigration($name, $parentRoute, $model)
    {
        //Migration
        $this->call('baseAdmin:makeNewMigration', [
            'name'     => Str::plural($model),
            '--create' => (($parentRoute) ? $parentRoute . '_' : '') . Str::plural($name)
        ]);
    }

    /**
     * @param $model
     * @param $fullModelClass
     */
    protected function makeFactory($model, $fullModelClass)
    {
        //Factory
        $this->call('make:factory', [
            'name'    => $model . 'Factory',
            '--model' => $fullModelClass
        ]);
    }

    /**
     * @param $langBase
     */
    protected function makeLanguage($langBase)
    {
        //Language
        $this->call('baseAdmin:makeLanguageFile', [
            'name' => 'admin/' . $langBase
        ]);
    }

    /**
     * @param $parentRoute
     * @param $model
     * @param $viewBase
     * @param $viewName
     *
     */
    protected function makeView($parentRoute, $model, $viewBase, $viewName)
    {
        //View
        $this->call('baseAdmin:nameView', [
            'name'         => 'admin/' . $viewBase . '/' . $viewName,
            '--textInside' => $parentRoute . '   ' . $model,
            '--layout'     => 'admin.layout'
        ]);
    }

    /**
     * @param $name
     * @param $node
     *
     * @return  bool
     */
    protected function existsOnTranslatableArray($name, $node)
    {
        return in_array($name, Arr::get($node, 'translatable', []));
    }


}
