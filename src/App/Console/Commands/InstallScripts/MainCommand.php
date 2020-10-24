<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;

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

        $this->deleteFiles();
        $this->makeFreshMigrationsForForeignPackages();
        $this->composer->dumpAutoloads();
        foreach ($this->scriptsAfterBasicScripts() as $key => $script) {
            $this->call(Arr::get($script, 0), Arr::get($script, 1, []));
        }

        $this->info('Platform ready! You can now login with your username and password at:');
        $adminRoute = config('app.url').'/'.config('baseAdmin.config.backEnd.baseRoute');
        $this->info($adminRoute);

        return true;

    }

    /**
     * @return array
     */
    protected function getInstallScripts(): array
    {

        return [
            'publishFiles' => ['baseAdmin:install:publishInitialFiles'],
            'iniyestializeEnv' => ['baseAdmin:install:initializeEnv'],
            'authorization' => ['vendor:publish', ['--provider' => 'Laravel\\Fortify\\FortifyServiceProvider', '--tag' => 'config']],
            'publishConf' => ['vendor:publish', ['--provider' => ServiceProvider::class, '--tag' => 'config']],
            'publishLaratrustConf' => ['vendor:publish', ['--tag' => 'laratrust']],
            'publishEnvConf' => ['vendor:publish', ['--provider' => 'GeoSot\EnvEditor\ServiceProvider', '--tag' => 'config']],
            'publishSluggableConf' => ['vendor:publish', ['--provider' => 'Cviebrock\EloquentSluggable\ServiceProvider', '--tag' => 'config']],
            'publishMediableConf' => ['vendor:publish', ['--provider' => 'Plank\Mediable\MediableServiceProvider', '--tag' => 'config']],
            'publishMediableMigrations' => ['vendor:publish', ['--provider' => 'Plank\Mediable\MediableServiceProvider', '--tag' => 'migrations']],
            'publishTranslatableConf' => ['vendor:publish', ['--provider' => 'Spatie\Translatable\TranslatableServiceProvider', '--tag' => 'config']],
            'publishLocalizationConf' => ['vendor:publish', ['--provider' => 'Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider', '--tag' => 'config']],
            'publishTranslationManagerConf' => ['vendor:publish', ['--provider' => 'Barryvdh\TranslationManager\ManagerServiceProvider', '--tag' => 'config']],
            'publishRevisionableConf' => ['vendor:publish', ['--provider' => 'Venturecraft\Revisionable\RevisionableServiceProvider']],
//            'publishChunkUploadConf' => ['vendor:publish', ['--provider' => 'Pion\Laravel\ChunkUpload\Providers\ChunkUploadServiceProvider']],


            /*   'publishTranslationMigrations' => [
                   'vendor:publish', [
                       '--provider' => 'Barryvdh\TranslationManager\ManagerServiceProvider',
                       '--tag' => 'migrations'
                   ]
               ],*/

            'editConfigFiles' => ['baseAdmin:install:editConfigFiles'],
            'clearConf' => ['config:clear'],
            'laratrustMigration' => ['laratrust:migration'],

        ];

    }


    private function makeFreshMigrationsForForeignPackages(): void
    {

        //Trick in order these files to be newer than the originals
        $files = [
            '_laratrust_add_fields.php',
            '_add_columns_on_media_table.php',
        ];
        $dbDir = __DIR__.'./../../../../Database/';

        foreach ($files as $file) {
            if ($results = glob($dbDir."Migrations/*{$file}")) {
                array_map(function ($filename) {
                    unlink($filename);
                }, $results);

            }
            $date = now()->addHour()->format('Y_m_d_His');
            copy($dbDir."MigrationsOnPackagesTables/{$file}", "{$dbDir}Migrations/{$date}{$file}");
        }


    }

    private function deleteFiles(): void
    {
        $files = [
            resource_path('views/welcome.blade.php'),
            app_path('Models/User.php'),
            app_path('Models/Role.php'),
            app_path('Models/Permission.php'),
            app_path('Models/Team.php')
        ];

        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }


    }

    private function scriptsAfterBasicScripts(): array
    {
        return [
            'makePassportKeys' => ['passport:keys'],
            'runMigration' => ['migrate'],
            'seedPackageData' => ['db:seed', ['--class' => DatabaseSeeder::class]],
            'installPassport' => ['passport:install'],//after migrate /https://laravel.com/docs/passport
            'symlink' => ['storage:link'],
//            'perms'=>['baseAdmin:makePermissionsForModel'],
        ];
    }


}
