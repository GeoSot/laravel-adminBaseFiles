<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;

use Barryvdh\TranslationManager\ManagerServiceProvider;
use GeoSot\BaseAdmin\Database\Seeds\DatabaseSeeder;
use GeoSot\BaseAdmin\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Composer;

class MainCommand extends BaseInstallCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'baseAdmin:install';
    protected $hidden = false;
    protected $composer;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install The package. Runs The required scripts in order o get the BaseAdmin Ready for use';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Package';

    /**
     * Create a new migration install command instance.
     *
     * @param  Composer  $composer
     *
     * @return void
     */
    public function __construct(Composer $composer)
    {
        parent::__construct();
        $this->composer = $composer;
    }

    /**
     * Execute the actions
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->canExecute()) {
            return false;
        }

        $this->info('Welcome!', 'Starting the installation process...', 'comment');

        foreach ($this->getInstallScripts() as $key => $script) {
            $this->call(Arr::get($script, 0), Arr::get($script, 1, []));
        }
        $this->composer->dumpAutoloads();

        $this->info('Platform ready!You can now login with your username and password at / backend');
        return true;

    }

    /**
     * @return array
     */
    protected function getInstallScripts(): array
    {
        return [
            'publishFiles' => ['baseAdmin:install:publishInitialFiles'],
            'initializeEnv' => ['baseAdmin:install:initializeEnv'],
            'authorization' => ['ui', ['type' => 'bootstrap', '--auth']],
            'publishConf' => ['vendor:publish', ['--provider' => ServiceProvider::class, '--tag' => 'config']],
            'publishForeignConfigs' => ['baseAdmin:install:publishForeignConfigs'],
//            'publishPackageMigrations'  => [
//                'vendor:publish', [
//                    '--provider' => 'GeoSot\BaseAdmin\ServiceProvider',
//                    '--tag'      => 'migrations'
//                ]
//            ],
            /*           'publishEnvEditorConfig'    => [
                           'vendor:publish', [
                               '--provider' => 'GeoSot\EnvEditor\ServiceProvider',
                               '--tag' => 'config'
                           ]
                       ],
                       'publishLocalizationConfig' => [
                           'vendor:publish', [
                               '--provider' => 'Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider',
                               '--tag' => 'config'
                           ]
                       ],*/
            'publishTranslationMigrations' => [
                'vendor:publish', [
                    '--provider' => ManagerServiceProvider::class,
                    '--tag' => 'migrations'
                ]
            ],
            'makePassportKeys' => ['passport:keys'],
            'editConfigFiles' => ['baseAdmin:install:editConfigFiles'],
            'publishViews' => ['baseAdmin:install:publishViews'],
            'publishAssets' => ['baseAdmin:publishAssets'],
            'runMigration' => ['migrate',],
            'seedPackageData' => ['db:seed', ['--class' => DatabaseSeeder::class]],
            'installPassport' => ['passport:install'],//after migrate /https://laravel.com/docs/passport
            'symlink' => ['storage:link'],
//            'perms'=>['baseAdmin:makePermissionsForModel'],
        ];

    }


}
