<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;


use GeoSot\BaseAdmin\App\Console\Commands\InstallScripts\GenericFileCreateCommand;
use Illuminate\Support\Str;

class MakeLanguage extends GenericFileCreateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:makeLanguageFile  {name : The name of the Instance}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Language Files For A Model';

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

        $stub = str_replace('DummyMenu', Str::plural($nameWithSpaces), $stub);
        $stub = str_replace('DummySingle', $nameWithSpaces, $stub);
        $stub = str_replace('DummyPlural', Str::plural($nameWithSpaces), $stub);

        return $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/LanguageFile.stub';
    }

}
