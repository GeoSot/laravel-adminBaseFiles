<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;


use Barryvdh\Debugbar\Facade;
use GeoSot\BaseAdmin\App\Traits\Eloquent\IsExportable;
use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Helper
{
    use FormBuilderTrait;

    /**
     * @var Model
     */
    private $model;

    public function __construct(Model $model = null)
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
    public function modelIsTranslatable(): bool
    {
        return $this->model->modelIsTranslatable();
    }

    /**
     * @return bool
     */
    public function modelHasSoftDeletes(): bool
    {
        return $this->usesTrait(SoftDeletes::class);
    }

    /**
     * @return bool
     */
    public function modelIsRevisionable(): bool
    {
        return $this->usesTrait(RevisionableTrait::class);
    }

    /**
     * @return bool
     */
    public function modelIsExportable(): bool
    {
        return $this->usesTrait(IsExportable::class);
    }


}
