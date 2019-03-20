<?php

namespace GeoSot\BaseAdmin\App\Console\Commands\InstallScripts;


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
    protected $description = 'Publishes all Files from stubsToPublishOnInstall Directory';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'File';
    protected $fileName = '';

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $allStubFiles = $this->files->allFiles($this->getStubDirectory());
        foreach ($allStubFiles as $stubFile) {
            $this->fileName = $stubFile->getRelativePathname();
            //  $this->type = $stubFile->getBasename('.stub');
            $path = $this->getFileWithPath();
            $stub = $this->buildParentReplacements($this->getStubContent());
            $this->populateStub($path, $stub);
        }


    }


    protected function getStubDirectory()
    {
        return __DIR__ . '/../../stubsToPublishOnInstall';
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
        return "app\\{$file}.php";
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->getStubDirectory() . '/' . $this->fileName;
    }
}
