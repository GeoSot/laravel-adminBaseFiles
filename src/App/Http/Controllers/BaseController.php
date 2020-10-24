<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers;


use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasFrontEndConfigs;
use GeoSot\BaseAdmin\App\Traits\Eloquent\IsExportable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Spatie\Translatable\HasTranslations;
use Venturecraft\Revisionable\RevisionableTrait;

abstract class BaseController extends Controller
{
    use FormBuilderTrait, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //
    protected $_genericLangDir = 'generic';
    /* @var BaseModel $_class */
    protected $_class;
    /* @var BaseModel $_hydratedModel */
    protected $_hydratedModel;
    protected $_modelRoute;
    protected $_modelsLangDir;
    protected $_modelsViewsDir;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->initializeModelValues();
    }

    public function initializeModelValues()
    {


        if (is_subclass_of($this->_class, Model::class) and Arr::has(class_uses_recursive($this->_class), HasFrontEndConfigs::class)) {
            $this->_hydratedModel = new $this->_class();
            $modelConfigs = $this->_hydratedModel->getFrontEndConfigPrefixed((is_subclass_of($this, BaseAdminController::class)) ? 'admin' : 'site');
            $this->_modelRoute = $modelConfigs->get('route');
            $this->_modelsLangDir = $modelConfigs->get('langDir');
            $this->_modelsViewsDir = $modelConfigs->get('viewDir');
        }


    }

    protected function variablesToView(Collection $extraValues = null, $action = 'index', $merge = [])
    {

        $vals = array_merge([
            'action' => $action,
            'baseLang' => $this->_genericLangDir,
            'modelLang' => $this->_modelsLangDir,
            'modelRoute' => $this->_modelRoute,
            'modelView' => $this->_modelsViewsDir,
            'modelClass' => $this->_class,
            'extraValues' => $extraValues,

        ], $merge);

        return ['viewVals' => collect($vals)];
    }


    protected function getModelValidationMessages()
    {
        return $this->_hydratedModel->getErrorMessagesTranslated($this->_modelsLangDir.'.errorMessages.');
    }


    protected function getLang(string $langPath, $count = 1)
    {
        return trans_choice($this->_modelsLangDir.'.'.$langPath, $count, ['num' => $count]);
    }

    protected function getBaseAdminLang(string $langPath, $count = 1)
    {
        return trans_choice($this->addPackagePrefix($this->_modelsLangDir).'.'.$langPath, $count, ['num' => $count]);
    }

    /**
     * @return bool
     */
    protected function modelIsTranslatable()
    {
        return $this->usesTrait(HasTranslations::class);
    }

    /**
     * @return bool
     */
    protected function modelHasSoftDeletes()
    {
        return $this->usesTrait(SoftDeletes::class);
    }

    /**
     * @return bool
     */
    protected function modelIsRevisionable()
    {
        return $this->usesTrait(RevisionableTrait::class);
    }

    /**
     * @return bool
     */
    protected function modelIsExportable()
    {
        return $this->usesTrait(IsExportable::class);
    }

    /**
     * @param  string  $trait
     * @return bool
     */
    protected function usesTrait(string $trait)
    {
        return in_array($trait, class_uses_recursive($this->_hydratedModel));
    }


    /**
     * @param  string  $string
     * @return string
     */
    protected function addPackagePrefix(string $string = ''): string
    {
        return 'baseAdmin::'.$string;
    }

    /**
     * @param  string  $string
     */
    protected function debugMsg(string $string): void
    {
        if (!class_exists(\Barryvdh\Debugbar\Facade::class)) {
            return;
        }
        \Barryvdh\Debugbar\Facade::debug($string);
    }
}
