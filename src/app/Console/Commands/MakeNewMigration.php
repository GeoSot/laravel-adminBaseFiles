<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeNewMigration extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:makeNewMigration {name : The name of the Migration} {--create= : The name of the migration.}{--translatable=false : Whether the model has translated values.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create A new Migration File With Default Values';

    protected $migrationString = 'create_++_table';
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

    public function handle()
    {


        $name = trim($this->input->getArgument('name'));

        $table = Str::snake($this->input->getOption('create') ?: $name);

        $finalName = str_replace('++', $table, $this->migrationString);


        $this->writeMigration($finalName, $table, false);

        if ($this->isTranslatableModel()) {
            $this->writeMigration($finalName, $table, true);
        }

    }

    protected function writeMigration($name, $table, $isTranslation)
    {
        if ($this->checkIfMigrationExist($name, $table)) {
            $this->error("Migration {$this->getClassName($name)} class already exists.");
            return;
        }
        $file = $this->getMigrationPath() . '\\' . $this->getDatePrefix() . '_' . $name($isTranslation ? '_translations' : '') . '.php';

        $res = file_put_contents($file, $this->populateStub($table, $this->getClassName($name), $isTranslation));
        $this->info("Created Migration: {$file}");
        $this->composer->dumpAutoloads();
    }

    /**
     * @param $name
     * @param $table
     * @return bool
     */
    protected function checkIfMigrationExist($name, $table)
    {
        if (class_exists($className = $this->getClassName($name))) {
            return true;
        }

        $filesInFolder = File::allFiles($this->getMigrationPath());
        foreach ($filesInFolder as $path) {
            $exists = Str::contains($path->getBasename(), $name);
            if ($exists) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the class name of a migration name.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function getClassName($name)
    {
        return Str::studly($name);
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return database_path(config('database.migrations'));
    }

    /**
     * Get the date prefix for the migration.
     *
     * @return string
     */
    protected function getDatePrefix()
    {
        return date('Y_m_d_His');
    }

    /**
     * @param string $table
     * @param string $class
     * @param bool $isTranslation
     * @return mixed|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function populateStub($table = 'DummyTable', $class = "DummyClass", $isTranslation = false)
    {
        $stub = File::get($this->getStubPath($isTranslation));
        $stub = str_replace('DummyTable', $table, $stub);
        $stub = str_replace('DummyClass', $class, $stub);
        $stub = str_replace('DummyForeignKey', Str::singular($table) . '_id', $stub);

        return $stub;
    }

    /**
     * @param $isForTranslation
     * @return string
     */
    public function getStubPath($isForTranslation)
    {
        $stubName = $isForTranslation ? 'MigrationMigration' : 'Migration';
        return __DIR__ . "/../stubs/$stubName.stub";
    }

    /**
     * @return bool
     */
    public function isTranslatableModel()
    {
        return filter_var($this->option('translatable'), FILTER_VALIDATE_BOOLEAN);
    }
}
