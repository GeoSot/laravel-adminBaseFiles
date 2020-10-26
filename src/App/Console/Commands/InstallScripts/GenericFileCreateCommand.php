<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

abstract class GenericFileCreateCommand extends GeneratorCommand
{
    /**
     * Get the view full path.
     *
     * @return string
     */
    abstract protected function getFileWithPath();

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
        $this->addOption('--force', null, InputOption::VALUE_NONE, 'Publish the files, even if already exists');
    }

    /**
     * @param $stub
     *
     * @return mixed
     */
    protected function buildParentReplacements($stub)
    {
        return $stub;
    }

    /**
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $stub = $this->buildParentReplacements($this->getStubContent());

        $this->populateStub($this->getFileWithPath(), $stub);
    }

    /**
     * @throws FileNotFoundException
     *
     * @return string
     */
    protected function getStubContent()
    {
        return $this->files->get($this->getStub());
    }

    /**
     * @param string $path
     * @param string $stub
     *
     * @return bool|null
     */
    protected function populateStub(string $path, string $stub)
    {
        if ($this->alreadyExists($path) and !$this->option('force')) {
            $this->warn("{$this->type} {$path} already exists!");

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $stub);

        $this->info("{$this->type} {$path} was created");
    }

    /**
     * Determine if the class already exists.
     *
     * @param string $rawName
     *
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->exists($this->getFileWithPath($rawName));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Publish the files, even if already exists'],
        ];
    }
}
