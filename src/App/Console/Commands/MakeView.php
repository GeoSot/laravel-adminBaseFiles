<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;

use GeoSot\BaseAdmin\App\Console\Commands\InstallScripts\GenericFileCreateCommand;

class MakeView extends GenericFileCreateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:nameView {name : The name of the view file} {--textInside= : Text Inside Content Section.} {--layout= : Layout name.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make View Files Including Text';

    protected $type = 'View File';

    /**
     * Get the view full path.
     *
     * @return string
     */
    public function getFileWithPath()
    {
        $view = str_replace('.', '/', $this->getNameInput()).'.blade.php';

        return "resources/views/{$view}";
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../stubs/View.stub';
    }

    protected function buildParentReplacements($stub)
    {
        $textInside = $this->option('textInside') ?? $this->getNameInput();
        $layout = $this->option('layout') ?? 'site.layout';

        if (!is_null($textInside)) {
            $stub = str_replace('DummyText', $textInside, $stub);
        }

        $stub = str_replace('DummyLayout', $layout, $stub);

        return $stub;
    }
}
