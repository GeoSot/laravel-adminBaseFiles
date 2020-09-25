<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;

use Barryvdh\TranslationManager\ManagerServiceProvider;
use GeoSot\BaseAdmin\Database\Seeders\DatabaseSeeder;
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
        unlink( app_path('Models/User.php'));
        unlink( app_path('Models/Role.php'));
        unlink( app_path('Models/Permission.php'));
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
//            'initializeEnv' => ['baseAdmin:install:initializeEnv'],
            'authorization' => ['ui', ['type' => 'bootstrap', '--auth']],

            'publishConf' => ['vendor:publish', ['--provider' => ServiceProvider::class, '--tag' => 'config']],
            'publishLaratrustConf' => ['vendor:publish', ['--tag' => 'laratrust']],
            'publishEnvConf' => ['vendor:publish', ['--provider' => 'GeoSot\EnvEditor\ServiceProvider','--tag' => 'config']],
            'publishSluggableConf' => ['vendor:publish', ['--provider' => 'Cviebrock\EloquentSluggable\ServiceProvider','--tag' => 'config']],
            'publishMediableConf' => ['vendor:publish', ['--provider' => 'Plank\Mediable\MediableServiceProvider','--tag' => 'config']],
            'publishTranslatableConf' => ['vendor:publish', ['--provider' => 'Spatie\Translatable\TranslatableServiceProvider','--tag' => 'config']],
            'publishLocalizationConf' => ['vendor:publish', ['--provider' => 'Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider','--tag' => 'config']],
            'publishTranslationManagerConf' => ['vendor:publish', ['--provider' => 'Barryvdh\TranslationManager\ManagerServiceProvider','--tag' => 'config']],


//            'publishPackageMigrations'  => [
//                'vendor:publish', [
//                    '--provider' => 'GeoSot\BaseAdmin\ServiceProvider',
//                    '--tag'      => 'migrations'
//                ]
//            ],

         /*   'publishTranslationMigrations' => [
                'vendor:publish', [
                    '--provider' => 'Barryvdh\TranslationManager\ManagerServiceProvider',
                    '--tag' => 'migrations'
                ]
            ],*/

            'editConfigFiles' => ['baseAdmin:install:editConfigFiles'],
            'makePassportKeys' => ['passport:keys'],
            'laratrust:setup' => ['laratrust:setup',['--force' => true]],
            'runMigration' => ['migrate'],
            'seedPackageData' => ['db:seed', ['--class' => DatabaseSeeder::class]],
            'installPassport' => ['passport:install'],//after migrate /https://laravel.com/docs/passport
            'symlink' => ['storage:link'],
//            'perms'=>['baseAdmin:makePermissionsForModel'],
        ];

    }


}
