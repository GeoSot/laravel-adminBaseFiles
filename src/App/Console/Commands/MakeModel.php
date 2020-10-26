<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeModel extends ModelMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'baseAdmin:makeModel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create A new Model model if it does not exist ( Extends BaseModel )';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../stubs/Model.stub';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $extendedOptions = [
            ['viewBase', null, InputOption::VALUE_NONE, 'viewBaser.'],
            ['langBase', null, InputOption::VALUE_NONE, 'langBase.'],
            ['routeBase', null, InputOption::VALUE_NONE, 'routeBase.'],
        ];

        return array_merge(parent::getOptions(), $extendedOptions);
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $replacePatterns = [
            'routeBase' => '_BASE_ROUTE_',
            'langBase'  => '_MODEL_LANG_DIR_',
            'viewBase'  => '_MODEL_VIEWS_DIR_',
        ];

        $replace = [];

        foreach ($replacePatterns as $option => $pattern) {
            if ($text = $this->option($option)) {
                $replace = array_merge(['$pattern' => $text], $replace);
            }
        }

        //		if ($this->option('model')) {
        //		$stub = $this->buildModelReplacements($stub);
        //	}
        //		$stub = $this->replaceClass($stub, $name);
        //		$stub = str_replace('DummyNamespace', $this->getNamespace($name), $stub);
        //		$stub = str_replace('DummyRootNamespace', $this->rootNamespace(), $stub);

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }
}
