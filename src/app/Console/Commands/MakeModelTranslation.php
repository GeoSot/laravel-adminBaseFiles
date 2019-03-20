<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;


use Illuminate\Foundation\Console\ModelMakeCommand;


class MakeModelTranslation extends ModelMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'baseAdmin:makeModelTranslation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create A new Translation for Model  if it does not exist ( Extends BaseModel )';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'TranslationModel';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/ModelTranslatable.stub';
    }


}
