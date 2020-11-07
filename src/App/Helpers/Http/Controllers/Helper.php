<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;


use Barryvdh\Debugbar\Facade;
use GeoSot\BaseAdmin\App\Forms\Admin\BasicForm;
use GeoSot\BaseAdmin\App\Traits\Eloquent\IsExportable;
use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Spatie\Translatable\HasTranslations;
use Venturecraft\Revisionable\RevisionableTrait;

class Helper
{
    use FormBuilderTrait;

    /**
     * @var Model
     */
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getNumberOfListingItems(Request $request)
    {
        $keyName = 'num_of_items';
        $sessionNumOfItems = session($keyName, Base::settings('admin.generic.paginationDefaultNumOfItems', 100));
        $numOfItems = $request->input($keyName, $sessionNumOfItems);
        if ($numOfItems != $sessionNumOfItems) {
            session([$keyName => $numOfItems]);
        }

        return $numOfItems;
    }



    /**
     * @param  string  $trait
     * @return bool
     */
    public function usesTrait(string $trait): bool
    {
        return in_array($trait, class_uses_recursive($this->model));
    }


    /**
     * @param  string  $string
     */
    public function debugMsg(string $string): void
    {
        if (!class_exists(Facade::class)) {
            return;
        }
        Facade::debug($string);
    }

    /**
     * @param  string  $string
     */
    public function infoMsg(string $string): void
    {
        if (!class_exists(Facade::class)) {
            return;
        }
        Facade::info($string);
    }

    /**
     * @return bool
     */
    public function modelIsTranslatable()
    {
        return $this->usesTrait(HasTranslations::class);
    }

    /**
     * @return bool
     */
    public function modelHasSoftDeletes()
    {
        return $this->usesTrait(SoftDeletes::class);
    }

    /**
     * @return bool
     */
    public function modelIsRevisionable()
    {
        return $this->usesTrait(RevisionableTrait::class);
    }

    /**
     * @return bool
     */
    public function modelIsExportable()
    {
        return $this->usesTrait(IsExportable::class);
    }


}
