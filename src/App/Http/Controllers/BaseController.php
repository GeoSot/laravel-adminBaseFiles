<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers;


use GeoSot\BaseAdmin\App\Forms\Admin\BasicForm;
use GeoSot\BaseAdmin\App\Helpers\Http\Controllers\Helper;
use GeoSot\BaseAdmin\App\Helpers\Models\FrontEndConfigs;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasFrontEndConfigs;
use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilderTrait;

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
     * @var Helper
     */
    protected $helper;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {

        $this->initializeModelValues();
        $this->helper = new Helper($this->_hydratedModel);
        $this->helper->infoMsg('using: '.static::class);
    }

    public function initializeModelValues()
    {


        if (is_subclass_of($this->_class, Model::class) and Arr::has(class_uses_recursive($this->_class), HasFrontEndConfigs::class)) {
            $this->_hydratedModel = new $this->_class();
            $side = is_subclass_of($this, BaseAdminController::class) ? FrontEndConfigs::ADMIN : FrontEndConfigs::SITE;
            $this->_modelRoute = $this->_hydratedModel->frontConfigs->getRouteDir($side);
            $this->_modelsLangDir = $this->_hydratedModel->frontConfigs->getLangDir($side);
            $this->_modelsViewsDir = $this->_hydratedModel->frontConfigs->getViewDir($side);
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
     * Searches For Proper Form
     * Searches model Directory, Package Model Directory and FallBacks to Default
     * @param  array  $options
     * @param  string  $side
     * @return Form
     */
    public function chooseProperForm(array $options, string $side = 'Admin'): Form
    {
        $parentDir = (Str::replaceFirst('\\', '',
            str_replace('App\Models', '', Str::replaceLast('\\'.class_basename($this->_class), '', $this->_class))));
        $parenName = (empty($parentDir) ? '' : $parentDir.'\\');
        $formName = "App\\Forms\\{$side}\\".$parenName.class_basename($this->_class).'Form';
        $this->helper->debugMsg("Try Find Form: {$formName}");
        if (class_exists($formName)) {
            return $this->form($formName, $options);
        }
        $formName = 'GeoSot\\BaseAdmin\\'.$formName;
        $this->helper->debugMsg("Try Find Form: {$formName}");
        if (class_exists($formName)) {
            return $this->form($formName, $options);
        }
        $formName = BasicForm::class;
        $this->helper->infoMsg("Return Form: {$formName}");
        return $this->form($formName, $options);
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
        return trans_choice(Base::addPackagePrefix($this->_modelsLangDir).'.'.$langPath, $count, ['num' => $count]);
    }


}
