<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;


use Illuminate\Contracts\Filesystem\FileNotFoundException;

class PublishInitialFiles extends GenericFileCreateCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:install:publishInitialFiles';
    protected $hidden = true;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes all Files to app Directory';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'File';

    protected $fileName = '';

    /**
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $allStubFiles = $this->files->allFiles($this->getStubDirectory());
        foreach ($allStubFiles as $stubFile) {
            $this->fileName = $stubFile->getRelativePathname();
            $stub = $this->buildParentReplacements($this->getStubContent());
            $this->populateStub($this->getFileWithPath(), $stub);
        }
    }

    protected function getStubDirectory()
    {
        return $this->getBaseStubDirectory().'app/';
    }

    protected function getBaseStubDirectory()
    {
        return __DIR__.'/../../../../../filesToPublish/';
    }


    /**
     * Get the view full path.
     *
     *
     * @return string
     */
    public function getFileWithPath()
    {
        $file = str_replace('.stub', '', $this->fileName);
        return base_path("app/{$file}.php");
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->getStubDirectory().DIRECTORY_SEPARATOR.$this->fileName;
    }
}
