<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class AddValuesToConfigFiles extends BaseInstallCommand
{


    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:install:editConfigFiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds Configuration Settings to Config Files';


    /**
     * Execute the console command.
     *

     */
    public function handle()
    {

        $this->replaceInFile("'engine' => null,", $this->getDatabaseChanges(), config_path('database.php'));
        $this->replaceInFile("App\Models\Users\User::class", "App\Models\Users\User::class", config_path('auth.php'));

        $this->changeValuesInfFile($this->getLaratrustValues(), config_path('laratrust.php'));
        $this->changeValuesInfFile($this->getEnvEditorValues(), config_path('env-editor.php'));
        $this->changeValuesInfFile($this->getSluggableValues(), config_path('sluggable.php'));
        $this->changeValuesInfFile($this->getMediableValues(), config_path('mediable.php'));
        $this->changeValuesInfFile(["'fallback_locale' => null" => "'fallback_locale' => 'en'"], config_path('translatable.php'));
        $this->changeValuesInfFile($this->getlocalizationValues(), config_path('laravellocalization.php'));
        $this->changeValuesInfFile($this->getTranslationManagerValues(), config_path('translation-manager.php'));

        $this->tweakRoutesFile();
        $this->tweakConfigAppFile();


        $this->replaceInFile("/home", "/", app_path('Providers/RouteServiceProvider.php'));
//        if (!Str::contains(file_get_contents(base_path('routes/web.php')), "'/dashboard'")) {
//            (new Filesystem)->append(base_path('routes/web.php'), $this->livewireRouteDefinition());
//        }


    }

    /**
     * Get the route definition(s) that should be installed for Livewire.
     *
     * @return string
     */
    protected function livewireRouteDefinition()
    {
        return <<<'EOF'

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

EOF;
    }


    /**
     * @return string
     */
    protected function getDatabaseChanges()
    {
        return "'engine' => 'InnoDB',
                'modes'  => [
                ".self::tab()."//'ONLY_FULL_GROUP_BY', // Disable this to allow grouping by one column
                ".self::tab()."'STRICT_TRANS_TABLES',
                ".self::tab()."'NO_ZERO_IN_DATE',
                ".self::tab()."'NO_ZERO_DATE',
                ".self::tab()."'ERROR_FOR_DIVISION_BY_ZERO',
                ".self::tab()."'NO_AUTO_CREATE_USER',
                ".self::tab()."'NO_ENGINE_SUBSTITUTION'
                ],";


    }

    protected static function tab()
    {
        return chr(9);
    }

    private function getLaratrustValues(): array
    {
        $user = config('baseAdmin.config.models.user');
        $role = config('baseAdmin.config.models.role');
        $permission = config('baseAdmin.config.models.permission');
        return [
            "\App\Models\User::class" => "{$user}",
            "\App\Models\Role::class" => "{$role}",
            "\App\Models\Permission::class" => "{$permission}",
            "\App\Models\Team::class" => "App\Models\Users\UserTeam::class",
            "'enabled' => false" => "'enabled' => true",
            "'/home'" => "'/'",
        ];

    }

    protected function changeValuesInfFile(array $data, string $file)
    {

        if (!(new Filesystem)->exists($file)) {
            $this->error($file.' not exists');
            return;
        }

        foreach ($data as $oldVal => $newVal) {
            $this->replaceInFile($oldVal, $newVal, $file);
        }
    }

    private function getEnvEditorValues()
    {
        return [
            "'prefix' => 'env-editor'" => "'prefix' => 'admin/editor'",
            "'name' => 'env-editor'" => "'name' => 'admin.env-editor'",
            "['web']" => "['web', 'auth']",
            'env-editor::layout' => 'baseAdmin::admin.layout'
        ];
    }

    private function getSluggableValues()
    {
        return [
            "'includeTrashed' => false" => "'includeTrashed' => true"
        ];
    }

    /**
     * @return string[]
     */
    private function getMediableValues(): array
    {
        return [
            'Plank\Mediable\Media::class' => 'App\Models\Media\Medium::class',
            'ON_DUPLICATE_INCREMENT' => 'ON_DUPLICATE_UPDATE '
        ];
    }

    /**
     * @return string[]
     */
    private function getlocalizationValues(): array
    {
        return [
            "//'es'" => "'el'",
            "'hideDefaultLocaleInURL' => false" => "'hideDefaultLocaleInURL' => true"
        ];
    }

    private function getTranslationManagerValues()
    {
        return [
            "'prefix'     => 'translations'" => "'prefix' => 'admin/translations'",
            "'middleware' => 'auth'" => "'middleware' => ['web', 'auth']",
            "'delete_enabled' => true" => "'delete_enabled' => true",
            "'sort_keys'     => false" => "'sort_keys'     => true"
        ];
    }

    private function tweakRoutesFile(): void
    {
        $this->changeValuesInfFile([
            "
Route::get('/', function () {
    return view('welcome');
});
" => "Route::get('/', [App\Http\Controllers\Site\HomeController::class,'index'])->name('home');".self::newLine(2)
        ], base_path('routes/web.php'));


    }

    private function tweakConfigAppFile()
    {
        $this->replaceInFile("UTC", "Europe/Athens", config_path('app.php'));

        if (!Str::contains($appConfig = file_get_contents(config_path('app.php')), 'App\\Providers\\BaseAdminServiceProvider::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                "App\Providers\AppServiceProvider::class,",
                "App\\Providers\BaseAdminServiceProvider::class,".self::newLine()."        App\Providers\AppServiceProvider::class,",
                $appConfig
            ));
        }
    }

}
