<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;

class MakeAdminController extends ControllerMakeCommand
{
    /**
     * @inheritDoc
     */
    protected $name = 'baseAdmin:makeAdminController';

    /**
     * @inheritDoc
     */
    protected $description = 'Create a new AdminController, if it does not exist ( Extends BaseAdminController )';


    /**
     * @inheritDoc
     */
    protected function getStub()
    {
        return __DIR__.'/../stubs/AdminControllerWithModel.stub';
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return parent::getDefaultNamespace($rootNamespace).'\Admin';
    }

}
