<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Kris\LaravelFormBuilder\FormBuilderTrait;

abstract class BaseFrontController extends BaseController
{
    use FormBuilderTrait, AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    //
    protected $_genericLangDir = 'generic';
    protected $_class;
    protected $_hydratedModel;
    protected $_modelRoute;
    protected $_modelsLangDir;
    protected $_modelsViewsDir;


    public function __construct()
    {
        //
    }

    public function initializeModelValues()
    {
        //$modelConfigs          = $this->_hydratedModel->getFrontEndConfigPrefixed('site');
        if ($this->_class) {
            $this->_hydratedModel = new $this->_class();
            $modelConfigs = $this->_hydratedModel->getFrontEndConfigPrefixed('site');
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

    /**
     * @param string $formName
     * @param             $model
     * @param string|null $route
     * @param string|null $language
     * @param array $extraOptions
     * @return \Kris\LaravelFormBuilder\Form
     */
    protected function makeForm(string $formName, $model, string $route = null, string $language = null, array $extraOptions = [])
    {

        $options = [
            'method' => !$model->exists ? 'POST' : 'PATCH',
            'url' => is_null($route) ? route($model->getFrontEndConfigPrefixed('site', 'route') . '.' . (!$model->exists ? 'store' : 'update'), $model) : $route,
            'language_name' => 'baseAdmin::'.(is_null($language) ? $model->getFrontEndConfigPrefixed('site', 'langDir') : $language) . ".fields",
            'id' => 'mainForm',
            //'title' => '',
            'model' => $model,
        ];


        return $this->form('App\\Forms\\Site\\' . $formName . 'Form', $options, $extraOptions);
    }


    protected function getModelValidationMessages()
    {
        return $this->_hydratedModel->getErrorMessagesTranslated($this->_modelsLangDir . '.errorMessages.');
    }


    protected function getLang(string $langPath, $count = 1)
    {
        return trans_choice($this->_modelsLangDir . '.' . $langPath, $count, ['num' => $count]);
    }
}
