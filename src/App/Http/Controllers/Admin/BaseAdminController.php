<?php


namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin;


use Exception;
use GeoSot\BaseAdmin\App\Helpers\Http\Controllers\{FieldsHelper, Filter, FiltersHelper};
use GeoSot\BaseAdmin\App\Http\Controllers\BaseController;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Controller\{CachesRouteParameters, HasActionHooks, HasAllowedActions, HasFields};
use GeoSot\BaseAdmin\App\Traits\Eloquent\IsExportable;
use GeoSot\BaseAdmin\Helpers\Alert;
use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request, Response,};
use Illuminate\Support\{Arr, Collection, Facades\Route, Str};
use Kris\LaravelFormBuilder\Form;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class BaseAdminController extends BaseController
{
    use HasFields, CachesRouteParameters, HasActionHooks, HasAllowedActions;

    protected $_useGenericLang = true;
    protected $_genericViewsDir = 'admin.generic';
    protected $_genericLangDir = 'admin/generic';

    /**
     * @var FieldsHelper
     */
    protected $fieldsHelper;

    /**
     * @var FiltersHelper
     */
    protected $filtersHelper;

    public function __construct()
    {
        parent::__construct();

    }


    /**
     * @param  Request  $request
     * @return JsonResponse|RedirectResponse|Response|StreamedResponse
     * @throws Exception
     */
    public function index(Request $request)
    {

      /*  $redirectRoute = $this->checkForPreviousPageVersionWithFiltersAndReturnThem($request);
        if (!is_null($redirectRoute)) {
            return redirect()->to($redirectRoute);
        }*/

        $this->fieldsHelper = new FieldsHelper($this->_hydratedModel);
        $this->filtersHelper = new FiltersHelper($this->_hydratedModel, $this->fieldsHelper, $this->filters());

        $extraOptions = collect([]);
        $query = $this->_class::select();
        $params = $this->makeParams($request);

        $this->beforeFilteringIndex($request, $params, $extraOptions);

        $this->filteringIndexModel($request, $params, $query);

        $this->filtersHelper->applyExtraFiltersOnModel($query);

        $this->afterFilteringIndex($request, $params, $query, $extraOptions);

        if ($request->input('export') === 'csv' && $this->helper->modelIsExportable()) {
            /** @var IsExportable $model */
            $model = $this->_hydratedModel;
            return $model->exportToCsv($query->get());
        }

        /** @var LengthAwarePaginator  $records */
        $records = $query->paginate($params->get('num_of_items'));
        $records->appends($params->toArray());

        $data = $this->variablesToView(collect($this->listFields()), 'index', [
            'records' => $records,
            'params' => $params,
            FiltersHelper::EXTRA_FILTERS_KEY => $this->filtersHelper->getAvailableData()
        ]);

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
        $params->put('num_of_items', $this->helper->getNumberOfListingItems($request));
        $params->put('keyword', $request->input('keyword'));
        $params->put('status', $request->input('status'));

        if ($this->helper->modelHasSoftDeletes()) {
            $params->put('trashed', $request->input('trashed', 0));
        }

        $orderBy = $request->input('order_by', $this->getOrderByOptions('column', 'created_at'));

        $orderBy = in_array($orderBy, $this->getSortableFields()) ? $orderBy : 'created_at';

        $params->put('order_by', $orderBy);
        $params->put('sort', $request->input('sort', $this->getOrderByOptions('sort', 'desc')));

        //merge Filters Params
        $params = $params->put('extra_filters', $this->filtersHelper->parseParams($request));

        return $params;
    }

    protected function filteringIndexModel(Request $request, Collection &$params, &$query)
    {
        if (!is_null($params->get('keyword'))) {
            $query->where(function ($query) use ($params) {
                foreach ($this->getSearchableFields() as $field) {
                    $field = $this->fieldsHelper->getField($query, $field);
                    if ($field->exists) {

                        if (!$field->isRelated()) {
                            $query->orWhere($field->column, 'LIKE', '%'.$params->get('keyword').'%');
                        } else {
                            $query->orWhereHas($field->relationName, function ($q) use ($field, $params) {
                                $q->where($field->column, 'LIKE', '%'.$params->get('keyword').'%');
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
        if ($this->helper->modelHasSoftDeletes()) {
            $params->put('trashed', $request->input('trashed', 0));
            if ($params->get('trashed') == 1) {
                $query->onlyTrashed();
            }
        }

        $field = $this->fieldsHelper->getField($query, $params->get('order_by'));


        if ($field->isRelated()) {
            $query = $this->fieldsHelper->sortByRelation($query, $field, $params->get('sort'));

            return;
        } elseif ($field->exists) {
            $query->orderBy($params->get('order_by'), $params->get('sort'));

            return;
        }

    }

    protected function getView(string $finalView)
    {
        return $this->chooseProperViewFile($finalView).'.'.$finalView;
    }

    protected function variablesToView(Collection $extraValues = null, $action = 'index', $merge = [])
    {

        $vals = array_merge([
            'action' => $action,
            'packagePrefix' => Base::addPackagePrefix(),
            'baseRoute' => $this->_modelRoute,
            'baseView' => $this->chooseProperViewFile($action),
            'baseLang' => Base::addPackagePrefix($this->usingLangDir()),
            'modelView' => $this->getView($action),
            'modelLang' => $this->chooseProperLangFile(),
            'modelClass' => $this->_class,
            'modelClassShort' => class_basename($this->_class),
            'modelPermissions' => $this->_hydratedModel->modelPermissionsFromDB(),
            'extraValues' => $extraValues,
            'options' => collect([
                'fillable' => $this->_hydratedModel->getFillable(),
                'modelHasSoftDeletes' => $this->helper->modelHasSoftDeletes(),
                'modelIsExportable' => $this->helper->modelIsExportable(),
                'modelIsTranslatable' => $this->helper->modelIsTranslatable(),
                'modelIsRevisionable' => $this->helper->modelIsRevisionable(),
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
            $collection->put(Base::addPackagePrefix($this->usingLangDir()).'.menu.'.$action, '');
        }

        return $collection;
    }

    public function create(Collection $extraValues = null)
    {
        if (!$this->isAllowedAction('create')) {
            Alert::error($this->getLang('create.deny'))->typeToast();
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
            Alert::error($this->getLang('create.deny'))->typeToast();
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
            Alert::success($message, $title)->typeToast();
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
        if (!($this->isAllowedAction('index', ['delete']) || $this->isAllowedAction('edit', ['delete']))) {
            abort(403, $this->getLang('delete.deny'));
        }
        $ids = $request->input('ids', []);
        $results = $this->_class::whereIn('id', $ids)->each(function ($model) use ($request) {
            $this->beforeDelete($request, $model);
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
            Alert::error($this->getLang('edit.deny'))->typeToast();
            return redirect()->back();
        }
        if (!$model->allowedToHandle()) {
            Alert::error($this->getLang('handle.deny'))->typeToast();
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
            Alert::error("{$action}.errorMsg", $this->getLang("{$action}.errorTitle"))->typeToast();

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
        Alert::success($msg['msg'], $msg['title'])->typeToast();

        $afterSaveVal = $request->input('after_save');

        if (in_array($afterSaveVal, ['back', 'save_and_close']) and $request->has('after_save_redirect_to')) {
            return redirect()->to($request->input('after_save_redirect_to'));
        }

        $route = $record->frontConfigs->getRouteDir('admin');

        if ($afterSaveVal == 'back') {
            return redirect()->route("{$route}.index");
        }
        if ($afterSaveVal == 'new') {
            return redirect()->route("{$route}.create");
        }

        return redirect()->route("{$route}.edit", $record);
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
        return trans_choice(Base::addPackagePrefix($this->usingLangDir()).'.messages.crud.'.$langPath, $count,
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
        $this->helper->debugMsg("Try Find Lang: {$langFile}");
        if (trans()->has($langFile)) {
            $this->helper->infoMsg("Return Lang: {$langFile}");
            return $this->_modelsLangDir.$string;
        }
        /**
         * Or in LangPath/vendor/package/locale/etc
         * Example: resources/lang/vendor/baseAdmin/en/admin/pages/page.php
         * $file = app()->langPath().DIRECTORY_SEPARATOR.app()->getLocale().DIRECTORY_SEPARATOR.$this->_modelsLangDir.'.php';
         * */
        $langFile = $this->_modelsLangDir.$string;
        $this->helper->infoMsg("Return Lang: {$langFile}");
        return Base::addPackagePrefix($langFile);

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
        $this->helper->debugMsg("Try Find View: {$view}");
        if (view()->exists($view)) {
            $this->helper->infoMsg("Return View: {$view}");
            return $modelView;
        }
        $view = Base::addPackagePrefix($view);
        $this->helper->debugMsg("Try Find View: {$view}");
        if (view()->exists($view)) {
            $this->helper->infoMsg("Return View: {$view}");
            return Base::addPackagePrefix($modelView);
        }


        $view = Base::addPackagePrefix($this->_genericViewsDir);

        $this->helper->infoMsg("Return View: {$view}");
        return $view;
    }


    /**
     * @param  string  $action
     * @param  array  $data
     * @return JsonResponse|Response
     */
    protected function sendProperResponse(string $action = 'index', array $data = [])
    {

        if (request()->wantsJson()) {
            return response()->json(request()->has('only_data') ? $data['viewVals'] : view($this->getView($action), $data)->render());
        }
        return response()->view($this->getView($action), $data);
    }


    /**
     * @return array|Filter[]
     */
    protected function filters()
    {

        /*   return [
               Filter::boolean('propertyToSearch'),
               Filter::hasValue('propertyToSearchIfNullOrNot'),
               Filter::select('propertyToSearch')->values(
                   User::enabled()->whereRoleIs('supporter')->get()->sortBy('full_name')->pluck('full_name', 'id')->toArray()
               ),
               Filter::selectMulti('related.propertyToSearch')->relatedKey('slug'),
               Filter::dateTime('propertyToSearch'),
               Filter::dateRange('propertyToSearch'),
           ];*/

        return [];
    }

    /**
     * @return string
     */
    protected function usingLangDir(): string
    {
        return $this->_useGenericLang ? $this->_genericLangDir : $this->_modelsLangDir;
    }


}
