<?php


namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin;


use GeoSot\BaseAdmin\App\Forms\Admin\BasicForm;
use GeoSot\BaseAdmin\App\Http\Controllers\BaseController;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\app\Traits\Controller\CachesRouteParameters;
use GeoSot\BaseAdmin\App\Traits\Controller\FieldsHelper;
use GeoSot\BaseAdmin\App\Traits\Controller\FiltersHelper;
use GeoSot\BaseAdmin\App\Traits\Controller\HasActionHooks;
use GeoSot\BaseAdmin\App\Traits\Controller\HasAllowedActions;
use GeoSot\BaseAdmin\App\Traits\Controller\HasFields;
use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Kris\LaravelFormBuilder\Form;

abstract class BaseAdminController extends BaseController
{
    use HasFields, FieldsHelper, FiltersHelper, CachesRouteParameters, HasActionHooks, HasAllowedActions;

    protected $_useGenericLang = true;
    protected $_genericViewsDir = 'admin.generic';
    protected $_genericLangDir = 'admin/generic';


    public function index(Request $request)
    {

        $redirectRoute = $this->checkForPreviousPageVersionWithFiltersAndReturnThem($request);
        if (!is_null($redirectRoute)) {
            return redirect($redirectRoute);
        }

        $extraOptions = collect([]);
        $query = $this->_class::select();
        $params = $this->makeParams($request);
        $this->beforeFilteringIndex($request, $params, $extraOptions);

        $this->filteringIndexModel($request, $params, $query);
        $this->applyExtraFiltersOnModel($params, $query);

        $this->afterFilteringIndex($request, $params, $query, $extraOptions);

        $records = $query->paginate($params->get('num_of_items'));

        $data = $this->variablesToView(collect($this->listFields()), 'index', ['records' => $records, 'params' => $params, 'extra_filters' => $this->getExtraFiltersData()]);

        return $this->sendProperResponse('index', $data);
    }

    /**
     * @param  Request  $request
     *
     * @return Collection
     */
    protected function makeParams(Request $request)
    {

        $params = collect([]);
        $params->put('num_of_items', $this->getNumberOfListingItems($request));
        $params->put('keyword', $request->input('keyword'));
        $params->put('status', $request->input('status'));

        if ($this->modelHasSoftDeletes()) {
            $params->put('trashed', $request->input('trashed', 0));
        }

        $orderBy = $request->input('order_by', $this->getOrderByOptions('column', 'created_at'));

        $orderBy = in_array($orderBy, $this->getSortableFields()) ? $orderBy : 'created_at';

        $params->put('order_by', $orderBy);
        $params->put('sort', $request->input('sort', $this->getOrderByOptions('sort', 'desc')));

        //merge Filters Params  from TRAIT
        $params = $params->put('extra_filters', $this->getExtraFiltersParams($request));

        return $params;
    }

    protected function filteringIndexModel(Request $request, Collection &$params, &$query)
    {
        if (!is_null($params->get('keyword'))) {
            $query->where(function ($query) use ($params) {
                foreach ($this->getSearchableFields() as $field) {
                    $fieldDt = $this->getFieldData($query, $field);
                    if ($fieldDt->get('exists')) {

                        if (!$fieldDt->has('relationName')) {
                            $query->orWhere($field, 'LIKE', '%'.$params->get('keyword').'%');
                        } else {
                            $query->orWhereHas($fieldDt->get('relationName'), function ($q) use ($fieldDt, $params) {
                                $q->where($fieldDt->get('column'), 'LIKE', '%'.$params->get('keyword').'%');
                            });
                        }
                    }
                }
            });
        }
        if ($params->get('status') == "-1") {
            $query->disabled();
        }
        if ($params->get('status') == "1") {
            $query->enabled();
        }
        if ($this->modelHasSoftDeletes()) {
            $params->put('trashed', $request->input('trashed', 0));
            if ($params->get('trashed') == 1) {
                $query->onlyTrashed();
            }
        }

        $fieldDt = $this->getFieldData($query, $params->get('order_by'));


        if ($fieldDt->has('relationName') and in_array($fieldDt->has('relationType'), ['HasOne'])) {
            $query = $this->sortByRelation($query, $fieldDt, $params->get('sort'));

            return;
        }

        if ($fieldDt->get('exists') and !$fieldDt->has('relationName')) {
            $query->orderBy($params->get('order_by'), $params->get('sort'));

            return;
        }


    }


    protected function getNumberOfListingItems(Request $request)
    {
        $keyName = 'num_of_items';
        $sessionNumOfItems = session($keyName, \GeoSot\BaseAdmin\Helpers\Base::settings('admin.generic.paginationDefaultNumOfItems', 100));
        $numOfItems = $request->input($keyName, $sessionNumOfItems);
        if ($numOfItems != $sessionNumOfItems) {
            session([$keyName => $numOfItems]);
        }

        return $numOfItems;
    }


    protected function getView(string $finalView)
    {
        return $this->chooseProperViewFile($finalView).'.'.$finalView;
    }

    protected function variablesToView(Collection $extraValues = null, $action = 'index', $merge = [])
    {

        $vals = array_merge([
            'action' => $action,
            'packagePrefix' => $this->addPackagePrefix(),
            'baseRoute' => $this->_modelRoute,
            'baseView' => $this->chooseProperViewFile($action),
            'baseLang' => $this->addPackagePrefix($this->_useGenericLang ? $this->_genericLangDir : $this->_modelsLangDir),
            'modelView' => $this->getView($action),
            'modelLang' => $this->chooseProperLangFile(),
            'modelClass' => $this->_class,
            'modelClassShort' => class_basename($this->_class),
            'modelPermissions' => $this->_hydratedModel->modelPermissionsFromDB(),
            'extraValues' => $extraValues,
            'options' => collect([
                'fillable' => $this->_hydratedModel->getFillable(),
                'modelHasSoftDeletes' => $this->modelHasSoftDeletes(),
                'modelIsTranslatable' => $this->modelIsTranslatable(),
                'modelTraits' => Arr::sortRecursive(array_flip(array_map(function ($i) {
                    return class_basename($i);
                }, class_uses_recursive($this->_class)))),
                'indexActions' => $this->allowedActionsOnIndex,
                'createActions' => $this->allowedActionsOnCreate,
                'editActions' => $this->allowedActionsOnEdit,
            ]),
            'breadCrumb' => $this->makeBreadCrumb($action),
        ], $merge);

        return ['viewVals' => collect($vals)];
    }

    protected function makeBreadCrumb($action = 'index')
    {
        $collection = collect([]);
        $routeSplit = explode('.', $this->_modelRoute);

        //In Case the model Has Parent put parent into breadcrumb
        if (count($routeSplit) > 2) {
            $baseLangRoute = Str::before($this->chooseProperLangFile(), lcfirst(class_basename($this->_class)));

            $parentsRoute = "$routeSplit[0].$routeSplit[1].index";

            $collection->put($baseLangRoute.Str::singular(explode('/', $baseLangRoute)[1]).'.general.menuTitle', Route::has($parentsRoute) ? route($parentsRoute) : '');

        }

        $collection->put($this->chooseProperLangFile('.general.menuTitle'), route("{$this->_modelRoute}.index"));

        if ($action !== 'index') {
            $collection->put($this->addPackagePrefix($this->_useGenericLang ? $this->_genericLangDir : $this->_modelsLangDir).'.menu.'.$action, '');
        }

        return $collection;
    }

    public function create(Collection $extraValues = null)
    {
        if (!$this->isAllowedAction('create')) {
            flashToastr($this->getLang('create.deny'), null, 'danger');

            return redirect()->back();
        }

        if (is_null($extraValues)) {
            $extraValues = collect([]);
        }
        $extraValues->put('form', $this->makeForm());

        $data = $this->variablesToView($extraValues, 'create');

        return $this->sendProperResponse('form', $data);
    }

    public function store(Request $request)
    {

        if (!$this->isAllowedAction('create')) {
            flashToastr($this->getLang('create.deny'), null, 'danger');

            return redirect()->back();
        }
        $this->beforeStore($request);
        $this->validate($request, $this->_hydratedModel->getRules(), $this->getModelValidationMessages());

        $model = $this->_class::create($request->all());
        $this->afterStore($request, $model);
        $this->afterSave($request, $model);

        return $this->checksAndNotificationsAfterSave($model, $request, 'store');
    }


    protected function jsonResponse($results, $action, Request $request)
    {
        $flag = $results == 0 ? 'error' : 'success';
        $title = $this->getLang($action.'.'.$flag.'Title');
        $message = $this->getLang($action.'.'.$flag.'Msg', $results);
        if ($flag == 'success' and !$request->input('onlyJson', false)) {
            flashToastr($message, $title, 'success');
        }

        return response()->json([
            'flag' => $flag,
            'count' => $results,
            'title' => $title,
            'message' => $message,
            'model' => $this->_class,
            'success' => ($flag == 'success')
        ]);
    }

    public function changeStatus(Request $request)
    {
        if (!$this->isAllowedAction('index', ['enable', 'disable'])) {
            abort(403, $this->getLang('changeStatus.deny'));
        }
        $ids = $request->input('ids', []);
        $status = $request->input('status');
        $results = $this->_class::whereIn('id', $ids)->update(['enabled' => $status ? 1 : 0]);

        return $this->jsonResponse($results, 'changeStatus', $request);
    }


    public function delete(Request $request)
    {
        if (!$this->isAllowedAction('index', ['delete'])) {
            abort(403, $this->getLang('delete.deny'));
        }
        $ids = $request->input('ids', []);
        $results = $this->_class::whereIn('id', $ids)->each(function ($model) {
            $model->delete();
        });

        return $this->jsonResponse($results, 'delete', $request);
    }

    public function restore(Request $request)
    {
        if (!$this->isAllowedAction('index', ['restore'])) {
            abort(403, $this->getLang('restore.deny'));
        }
        $ids = $request->input('ids', []);
        $results = $this->_class::whereIn('id', $ids)->onlyTrashed()->restore();

        return $this->jsonResponse($results, 'restore', $request);
    }

    public function forceDelete(Request $request)
    {
        if (!$this->isAllowedAction('index', ['forceDelete'])) {
            abort(403, $this->getLang('forceDelete.deny'));
        }
        $ids = $request->input('ids', []);
        $results = $this->_class::whereIn('id', $ids)->onlyTrashed()->forceDelete();

        return $this->jsonResponse($results, 'forceDelete', $request);
    }

    protected function genericEdit(Model $model, Collection $extraValues = null)
    {

        if (is_null($extraValues)) {
            $extraValues = collect([]);
        }
        $form = $this->makeForm($model);
        $extraValues->put('form', $form);
        if (request()->wantsJson()) {
            $newForm = clone $form;
            $extraValues->put('formRendered', $newForm->renderForm(['id' => 'edit'.class_basename($this->_class).'Form']));
        }

        $data = $this->variablesToView($extraValues, 'edit', ['record' => $model]);

        return $this->sendProperResponse('form', $data);
    }

    protected function genericUpdate(Request $request, $model)
    {
        /* @var BaseModel $model */
        /* poio apotelesmatiko validation
         * $form = $this->makeForm($model);
        $form->redirectIfNotValid();*/
        if ($request->input('after_save') == 'makeCopy') {
            if ($model->slug == $request->input('slug')) {
                $request->replace($request->except(['slug']));
            }
            return $this->store($request);
        }
        if (!$this->isAllowedAction('edit')) {
            flashToastr($this->getLang('edit.deny'), null, 'danger');

            return redirect()->back();
        }
        if (!$model->allowedToHandle()) {
            flashToastr($this->getLang('handle.deny'), null, 'danger');

            return redirect()->back();
        }

        $this->beforeUpdate($request, $model);
        $this->validate($request, $model->getRules(), $this->getModelValidationMessages());
        $model->update($request->all());


        $this->afterUpdate($request, $model);
        $this->afterSave($request, $model);

        return $this->checksAndNotificationsAfterSave($model, $request, 'update');
    }

    protected function checksAndNotificationsAfterSave($record, Request $request, $action)
    {
        if (is_null($record)) {
            flashToastr($this->getLang("{$action}.errorMsg"), $this->getLang("{$action}.errorTitle"), 'danger');

            return back();
        }
        $msg = [
            'type' => 'success',
            'msg' => $this->getLang("{$action}.successMsg"),
            'title' => $this->getLang("{$action}.successTitle")
        ];

        if ($request->wantsJson()) {
            return response()->json(['record' => $record, 'msg' => $msg]);
        }
        flashToastr($msg['msg'], $msg['title'], $msg['type']);

        $afterSaveVal = $request->input('after_save');

        if (in_array($afterSaveVal, ['back', 'save_and_close']) and $request->has('after_save_redirect_to')) {
            return redirect()->to($request->input('after_save_redirect_to'));
        }
        if ($afterSaveVal == 'back') {
            return redirect()->route("{$this->_modelRoute}.index");
        }
        if ($afterSaveVal == 'new') {
            return redirect()->route("{$this->_modelRoute}.create");
        }

        return redirect()->route("{$this->_modelRoute}.edit", $record);
    }

    /**
     * @param  null  $model
     * @return Form
     */
    protected function makeForm($model = null)
    {

        $options = [
            'method' => $model ? 'PATCH' : 'POST',
            'url' => route($this->_modelRoute.'.'.($model ? 'update' : 'store'), $model),
            'language_name' => $this->chooseProperLangFile('.fields'),
            'id' => 'mainForm',
            'model' => $model ?? $this->_hydratedModel,
        ];

        return $this->chooseProperForm($options);
    }


    protected function getLang(string $langPath, $count = 1)
    {
        return trans_choice($this->addPackagePrefix($this->_useGenericLang ? $this->_genericLangDir : $this->_modelsLangDir).'.messages.crud.'.$langPath, $count,
            ['num' => $count]);
    }

    /**
     * @param  string  $string
     * @return string
     */
    protected function chooseProperLangFile(string $string = ''): string
    {
        //Translation File Can Be In LangPath
        $langFile = $this->_modelsLangDir.'.general.menuTitle';
        $this->debugMsg("Try Find Lang: {$langFile}");
        if (trans()->has($langFile, [])) {
            return $this->_modelsLangDir.$string;
        }
        /**
         * Or in LangPath/vendor/package/locale/etc
         * Example: resources/lang/vendor/baseAdmin/en/admin/pages/page.php
         * $file = app()->langPath().DIRECTORY_SEPARATOR.app()->getLocale().DIRECTORY_SEPARATOR.$this->_modelsLangDir.'.php';
         * */
        $langFile = $this->_modelsLangDir.$string;
        $this->debugMsg("Return Lang: {$langFile}");
        return $this->addPackagePrefix($langFile);

    }

    /**
     * Searches For Proper View File
     * Searches model Directory, Package Model Directory andFallBacks to Default
     * @param $action
     *
     * @return string
     */
    protected function chooseProperViewFile($action): string
    {

        $suffix = '.'.($action == 'index' ? 'index' : 'form');

        $modelView = $this->_modelsViewsDir;
        $view = $modelView.$suffix;
        $this->debugMsg("Try Find View: {$view}");
        if (view()->exists($view)) {
            return $modelView;
        }
        $view = $this->addPackagePrefix($modelView.$suffix);
        $this->debugMsg("Try Find View: {$view}");
        if (view()->exists($view)) {
            return $this->addPackagePrefix($modelView);
        }

        $view = $this->addPackagePrefix($this->_genericViewsDir);
        $this->debugMsg("Return View: {$view}");
        return $view;
    }


    /**
     * @param  string  $action
     * @param  array  $data
     * @return Factory|JsonResponse|View
     */
    protected function sendProperResponse(string $action = 'index', array $data = [])
    {
        if (request()->wantsJson()) {
            return response()->json($data);
        }
        return view($this->getView($action), $data);
    }

    /**
     * Searches For Proper Form
     * Searches model Directory, Package Model Directory and FallBacks to Default
     * @param  array  $options
     * @return Form
     */
    protected function chooseProperForm(array $options): Form
    {
        $parentDir = (Str::replaceFirst('\\', '', str_replace('App\Models', '', Str::replaceLast('\\'.class_basename($this->_class), '', $this->_class))));
        $parenName = (empty($parentDir) ? '' : $parentDir.'\\');
        $formName = 'App\\Forms\\Admin\\'.$parenName.class_basename($this->_class).'Form';
        $this->debugMsg("Try Find Form: {$formName}");
        if (class_exists($formName)) {
            return $this->form($formName, $options);
        }
        $formName = 'GeoSot\\BaseAdmin\\'.$formName;
        $this->debugMsg("Try Find Form: {$formName}");
        if (class_exists($formName)) {
            return $this->form($formName, $options);
        }
        $formName = BasicForm::class;
        $this->debugMsg("Return Form: {$formName}");
        return $this->form($formName, $options);
    }

}
