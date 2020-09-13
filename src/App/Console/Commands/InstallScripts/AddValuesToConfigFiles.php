<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class AddValuesToConfigFiles extends BaseInstallCommand
{

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

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
     * Create a new controller creator command instance.
     *
     * @param  Filesystem  $files
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *

     */
    public function handle()
    {

        foreach ($this->getChanges() as $fileName => $changes) {
            foreach ($changes as $key => $value) {
                $this->table(['File', 'Key', 'Value'], [[$fileName, $key, $value]]);
                $this->addValueToFile($fileName, $key, $value);
            }
        }

    }


    protected function getConfigDirectory($arg = '')
    {
        return config_path($arg);
    }


    /**
     * Get the stub file for the generator.
     *
     * @return array
     */
    protected function getChanges()
    {
        return [
            'database'  => [
                'mysql' => "[
                        'engine' => 'InnoDB',
                        'modes'  => [
                            //'ONLY_FULL_GROUP_BY', // Disable this to allow grouping by one column
                            'STRICT_TRANS_TABLES',
                            'NO_ZERO_IN_DATE',
                            'NO_ZERO_DATE',
                            'ERROR_FOR_DIVISION_BY_ZERO',
                            'NO_AUTO_CREATE_USER',
                            'NO_ENGINE_SUBSTITUTION'
                        ],
                    ]"
            ],

        ];
    }


    /**
     *

     */
    protected function addValueToFile(string $filesName, string $keyToChange, $valueToChange): void
    {
        return;
        $path = $this->getConfigDirectory($filesName . '.php');
        if (!$this->files->exists($path)) {
            $this->error("Didn't Find " . $path);
            return;
        }
        $existingValue = config(str_replace('\\', '.', $filesName) . '.' . $keyToChange);

        $newValue = is_array($existingValue) ? array_merge($existingValue, Arr::wrap($valueToChange)) : $valueToChange;
        //  dd($existingValue);
        //TODO
        dd(var_export($existingValue) . ',');

// create the array as a php text string
        //   $text = "<?php\n\nreturn " . var_export($myarray, true) . ";";
        $contents = $this->files->get($path);

        $contents .= str_replace('%host%', $host, $contents);

        $this->files->put($path, $contents);
    }
}
