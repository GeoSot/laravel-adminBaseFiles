<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
    protected $signature = 'baseAdmin:makeNewMigration {name : The name of the Migration} {--create= : The name of the migration.}';

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
     * @param  Composer  $composer
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


        $this->writeMigration($table, false);

    }

    protected function writeMigration($table)
    {
        $name = $this->getTableNAme($table);

        if ($this->checkIfMigrationExist($name)) {
            $this->error("Migration {$this->getClassName($name)} class already exists.");
            return;
        }
        $file = $this->getMigrationPath().'\\'.$this->getDatePrefix().'_'.$name.'.php';


        $res = file_put_contents($file, $this->populateStub($table, $this->getClassName($name)));
        $this->info("Created Migration: {$file}");
        $this->composer->dumpAutoloads();
    }

    /**
     * @param $name
     *
     * @return bool
     */
    protected function checkIfMigrationExist($name)
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
     * @param  string  $name
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
     * @param  string  $table
     * @param  string  $class
     *
     * @return mixed|string
     * @throws FileNotFoundException
     */
    protected function populateStub($table = 'DummyTable', $class = "DummyClass")
    {
        $stub = File::get($this->getStubPath());
        $stub = str_replace('DummyTable', $table, $stub);
        $stub = str_replace('DummyClass', $class, $stub);
        $stub = str_replace('DummyForeignKey', Str::singular($table).'_id', $stub);

        return $stub;
    }

    /**
     *
     * @return string
     */
    public function getStubPath()
    {
        return __DIR__."/../stubs/Migration.stub";
    }


    /**
     * @param $table
     *
     * @return string
     */
    protected function getTableNAme($table)
    {
        return str_replace('++', $table, $this->migrationString);
    }
}
