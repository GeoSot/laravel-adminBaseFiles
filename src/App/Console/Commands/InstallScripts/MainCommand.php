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

        $this->info('Welcome!');
        $this->info('Starting the installation process...', 'comment');

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
            'initializeEnv' => ['baseAdmin:install:initializeEnv'],
            'fortifyConfig' => $this->publishCmd('Laravel\\Fortify\\FortifyServiceProvider', 'fortify-config'),
            'fortifyStubs' => $this->publishCmd('Laravel\\Fortify\\FortifyServiceProvider', 'fortify-support'),
            'publishConf' => $this->publishCmd(ServiceProvider::class, 'config'),
            'publishLaratrustConf' => $this->publishCmd(null, 'laratrust'),
            'publishEnvConf' => $this->publishCmd('GeoSot\EnvEditor\ServiceProvider', 'config'),
            'publishSluggableConf' => $this->publishCmd('Cviebrock\EloquentSluggable\ServiceProvider', 'config'),
            'publishMediableConf' => $this->publishCmd('Plank\Mediable\MediableServiceProvider', 'config'),
            'publishMediableMigrations' => $this->publishCmd('Plank\Mediable\MediableServiceProvider', 'migrations'),
            'publishTranslatableConf' => $this->publishCmd('Spatie\Translatable\TranslatableServiceProvider', 'config'),
            'publishLocalizationConf' => $this->publishCmd('Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider', 'config'),
            'publishTranslationManagerConf' => $this->publishCmd('Barryvdh\TranslationManager\ManagerServiceProvider', 'config'),
            'publishRevisionableConf' => $this->publishCmd('Venturecraft\Revisionable\RevisionableServiceProvider'),
//            'publishChunkUploadConf' => ['vendor:publish', ['--provider' => 'Pion\Laravel\ChunkUpload\Providers\ChunkUploadServiceProvider']],


            'publishTranslationMigrations' => $this->publishCmd('Barryvdh\TranslationManager\ManagerServiceProvider', 'migrations'),

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
                /*   array_map(function ($filename) {
                       unlink($filename);
                   }, $results);*/
                continue;
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
            'publishAssets' => ['baseAdmin:publishAssets'],
//            'perms'=>['baseAdmin:makePermissionsForModel'],
        ];
    }

    /**
     * @param  string|null  $provider
     * @param  string|null  $tag
     * @return array
     */
    protected function publishCmd(string $provider = null, string $tag = null): array
    {
        $options = [];
        if ($provider) {
            $options  ['--provider'] = $provider;
        }
        if ($tag) {
            $options   ['--tag'] = $tag;
        }

        return ['vendor:publish', $options];
    }


}
