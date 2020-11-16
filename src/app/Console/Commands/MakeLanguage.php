<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;


use GeoSot\BaseAdmin\App\Console\Commands\InstallScripts\GenericFileCreateCommand;
use Illuminate\Support\Str;

class MakeLanguage extends GenericFileCreateCommand
{
    /**
     * @inheritDoc
     */
    protected $signature = 'baseAdmin:makeLanguageFile  {name : The name of the Instance}';

    /**
     * @inheritDoc
     */
    protected $description = 'Create Language Files For A Model';

    /**
     * @inheritDoc
     */
    protected $type = 'Language File';


    /**
     * Get the view full path.
     *
     *
     * @return string
     */
    public function getFileWithPath()
    {
        return "resources/lang/en/{$this->getNameInput()}.php";
    }

    protected function buildParentReplacements($stub)
    {

        $name = ucfirst(class_basename($this->getNameInput()));
        $nameWithSpaces = trim(implode(' ', preg_split('/(?=[A-Z])/', $name)));

        $stub = str_replace('{{ menu }}', Str::plural($nameWithSpaces), $stub);
        $stub = str_replace('{{ single }}', $nameWithSpaces, $stub);
        $stub = str_replace('{{ plural }}', Str::plural($nameWithSpaces), $stub);

        return $stub;
    }

    /**
     * @inheritDoc
     */
    protected function getStub()
    {
        return __DIR__.'/../stubs/LanguageFile.stub';
    }

}
