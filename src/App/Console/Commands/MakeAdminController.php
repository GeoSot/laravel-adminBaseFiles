<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;

class MakeAdminController extends ControllerMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'baseAdmin:makeAdminController';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create A new AdminController  if it does not exist ( Extends BaseAdminController )';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/AdminControllerWithModel.stub';
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers\Admin';
    }

}
