<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;

use Illuminate\Console\GeneratorCommand;
abstract class GenericFileCreateCommand extends GeneratorCommand
{

    /**
     * Get the view full path.

     * @return string
     */
    abstract protected function getFileWithPath();

    /**
     * @param $stub
     * @return mixed
     */
    protected function buildParentReplacements($stub)
    {
        return $stub;
    }


    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $path = $this->getFileWithPath();
        $stub = $this->buildParentReplacements($this->getStubContent());
        $this->populateStub($path, $stub);
    }


    /**
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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
        if ($this->alreadyExists($path)) {
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
     * @param  string $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->exists($this->getFileWithPath($rawName));
    }


}
