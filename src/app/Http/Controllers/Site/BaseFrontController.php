<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers;


use Kris\LaravelFormBuilder\Form;

abstract class BaseFrontController extends BaseController
{


    /**
     * @param  string  $formName
     * @param             $model
     * @param  string|null  $route
     * @param  string|null  $language
     * @param  array  $extraOptions
     * @return Form
     */
    protected function makeForm(string $formName, $model, string $route = null, string $language = null, array $extraOptions = [])
    {

        $options = [
            'method' => $model->exists ? 'PATCH' : 'POST',
            'url' => $route ?? route($this->_modelRoute.'.'.($model->exists ? 'update' : 'store'), $model),
            'language_name' => $this->addPackagePrefix($language ?? $this->_modelsLangDir).".fields",
            'id' => 'mainForm',
            'model' => $model,
        ];


        return $this->form('App\\Forms\\Site\\'.$formName.'Form', $options, $extraOptions);
    }

}
