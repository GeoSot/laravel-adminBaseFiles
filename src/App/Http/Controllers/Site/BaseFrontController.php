<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use GeoSot\BaseAdmin\App\Forms\BaseForm;
use GeoSot\BaseAdmin\App\Http\Controllers\BaseController;
use GeoSot\BaseAdmin\Helpers\Alert;
use GeoSot\BaseAdmin\Helpers\Base;
use Kris\LaravelFormBuilder\Form;

abstract class BaseFrontController extends BaseController
{


    /**
     * @param string $formName
     * @param             $model
     * @param string|null $route
     * @param string|null $language
     * @param array $extraOptions
     * @return BaseForm|Form
     */
    protected function makeForm(string $formName, $model, string $route = null, string $language = null, array $extraOptions = [])
    {

        $options = [
            'method' => $model->exists ? 'PATCH' : 'POST',
            'url' => $route ?? route($this->_modelRoute . '.' . ($model->exists ? 'update' : 'store'), $model),
            'language_name' => $this->chooseProperLangFile($language ?? $this->_modelsLangDir) . ".fields",
            'model' => $model,
        ];

        return $this->form($formName, $options, $extraOptions);
    }


    /**
     * @param string $string
     * @return string
     */
    protected function chooseProperLangFile(string $file = ''): string
    {
        $tryGetFile = function ($langFile, $locale) {
            $baseLangPath = resource_path("lang/{$locale}/{$langFile}");
            $this->helper->debugMsg("Try Find Lang on: {$baseLangPath}");

            if (file_exists("{$baseLangPath}.php")) {
                $this->helper->infoMsg("Return Lang : {$baseLangPath}");
                return $langFile;
            }
            return null;
        };
        $locale = config('app.locale');
        if ($result = $tryGetFile($file, $locale)) {
            return $result;
        }

        $fallBackLocale = config('app.fallback_locale');
        if ($fallBackLocale !== $locale && $result = $tryGetFile($file, $fallBackLocale)) {
            return $result;
        }


        $file = Base::addPackagePrefix($file);
        $this->helper->infoMsg("Return Lang: {$file}");
        return $file;
    }


}
