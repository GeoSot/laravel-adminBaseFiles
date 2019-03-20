<?php


namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use GeoSot\BaseAdmin\app\Traits\Controller\CachesRouteParameters;
use GeoSot\BaseAdmin\App\Traits\Controller\FieldsHelper;
use GeoSot\BaseAdmin\App\Traits\Controller\FiltersHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\FormBuilderTrait;

abstract class BaseAdminController extends Controller
{
    use FormBuilderTrait, FieldsHelper, FiltersHelper,CachesRouteParameters;

    protected $baseRoute;
    protected $_genericLangDir = 'generic';
    protected $_modelsLangDir;
    protected $_useGenericLang = true;
    protected $_genericViewsDir = 'generic';
    protected $_modelsViewsDir;
    protected $_useGenericViewIndex = true;
    protected $_useGenericViewForm = true;
    protected $_class;
    protected $_hydratedModel;
    protected $_useBasicForm = true;


    protected $allowedActionsOnIndex = ['create', 'edit', 'enable', 'disable', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnCreate = ['save', 'saveAndClose', 'saveAndNew'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew', 'makeNewCopy'];


    /**
     * BaseAdminController constructor.
     */
      public function __construct(){}

    public function initializeModelValues()
    {
        $this->_hydratedModel = new $this->_class();
        $modelConfigs = $this->_hydratedModel->getFrontEndConfig('admin');
        $this->baseRoute = $modelConfigs->get('route');
        $this->_modelsLangDir = $modelConfigs->get('langDir');
        $this->_modelsViewsDir = $modelConfigs->get('viewDir');

    }

    public function index(Request $request)
    {
//        $this->__call('checkForPreviousPageVersionWithFiltersAndReturnThem',['e']);
//        $redirectRoute =  $this->checkForPreviousPageVersionWithFiltersAndReturnThem($request);
//        if (!is_null($redirectRoute)) {
//            return redirect($redirectRoute);
//        }


        $extraOptions = collect([]);
        $query = $this->_class::select();
        $params = $this->makeParams($request);
        $this->beforeFilteringIndex($request, $params, $extraOptions);

        $this->filteringIndexModel($request, $params, $query);
        $this->applyExtraFiltersOnModel($request, $params, $query);

        $this->afterFilteringIndex($request, $params, $query, $extraOptions);

        $records = $query->paginate($params->get('num_of_items'));

        $data = $this->variablesToView(collect($this->listFields()), 'index', ['records' => $records, 'params' => $params, 'extra_filters' => $this->getExtraFiltersData()]);

        if (request()->wantsJson()) {
            return response()->json($data);
        }
        return view($this->getView('index'), $data);
    }

    /**
     * @param Request    $request
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
                            $query->orWhere($field, 'LIKE', '%' . $params->get('keyword') . '%');
                        } else {
                            $query->orWhereHas($fieldDt->get('relationName'), function ($q) use ($fieldDt, $params) {
                                $q->where($fieldDt->get('column'), 'LIKE', '%' . $params->get('keyword') . '%');
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
        $sessionNumOfItems = session($keyName, settings('admin.generic.paginationDefaultNumOfItems', 100));
        $numOfItems = $request->input($keyName, $sessionNumOfItems);
        if ($numOfItems != $sessionNumOfItems) {
            session([$keyName => $numOfItems]);
        }

        return $numOfItems;
    }

    private function getSearchableFields()
    {
        return Arr::get($this->listFields(), 'searchable', []);
    }

    protected function listFields()
    {
        return [
            'listable'   => ['title', 'enabled', 'id'],
            'searchable' => ['title', 'enabled',],
            'sortable'   => ['title', 'enabled', 'id'],
            'linkable'   => ['title'],
            'orderBy'    => ['column' => 'created_at', 'sort' => 'desc'],
        ];
    }

    private function getSortableFields()
    {
        return Arr::get($this->listFields(), 'sortable', []);
    }

    /**
     * @param string|null $arg
     * @param string|null $default
     *
     * @return mixed
     */
    private function getOrderByOptions(string $arg = null, string $default = null)
    {
        $options = Arr::get($this->listFields(), 'orderBy', []);

        return is_null($arg) ? $options : Arr::get($options, $arg, $default);
    }

    protected function getView(string $finalView)
    {
        return 'baseAdmin::admin.' . $this->getViewBase($finalView) . '.' . $finalView;
    }

    protected function variablesToView(Collection $extraValues, $action = 'index', $merge = [])
    {

        $vals = array_merge([
            'action'           => $action,
            'baseRoute'        => 'admin.' . $this->baseRoute,
            'baseView'         => 'admin.' . $this->getViewBase($action),
            'baseLang'         => 'admin/' . ($this->_useGenericLang ? $this->_genericLangDir : $this->_modelsLangDir),
            'modelView'        => 'admin.' . $this->_genericViewsDir,
            'modelLang'        => 'admin/' . $this->_modelsLangDir,
            'modelClass'       => $this->_class,
            'modelClassShort'  => class_basename($this->_class),
            'modelPermissions' => $this->_hydratedModel->modelPermissionsFromDB(),
            'extraValues'      => $extraValues,
            'options'          => collect([
                'fillable'            => $this->_hydratedModel->getFillable(),
                'modelHasSoftDeletes' => $this->modelHasSoftDeletes(),
                'modelTraits'         => array_flip(array_map(function ($i) {
                    return class_basename($i);
                }, class_uses_recursive($this->_class))),
                'indexActions'        => $this->allowedActionsOnIndex,
                'createActions'       => $this->allowedActionsOnCreate,
                'editActions'         => $this->allowedActionsOnEdit,
            ]),
            'breadCrumb'       => $this->makeBreadCrumb($action),
        ], $merge);

        return ['viewVals' => collect($vals)];
    }

    protected function makeBreadCrumb($action = 'index')
    {
        $collection = collect([]);
        //$collection = collect(['generic.menu.dashboard' => route('admin.dashboard')]);

        $routeSplit = explode('.', $this->baseRoute);
        $langSplit = explode('/', $this->_modelsLangDir);

        $collection->put($langSplit[0] . '/' . Str::singular($langSplit[0]) . '.general.menuTitle', route("admin.{$routeSplit[0]}.index"));

        if (count($routeSplit) > 1) {
            $collection->put($this->_modelsLangDir . '.general.menuTitle', route("admin.{$this->baseRoute}.index"));
        }
        if ($action != 'index') {
            $collection->put(($this->_useGenericLang ? $this->_genericLangDir : $this->_modelsLangDir) . '.menu.' . $action, '');//route("admin.{$this->baseRoute}.{$view}")
        }

        return $collection;
    }

    public function create(Collection $extraValues = null)
    {
        if (!$this->allowedActions('create', ['save', 'saveAndNew', 'SaveAndClose'])) {
            flashToastr($this->getLang('create.deny'), null, 'danger');

            return redirect()->back();
        }
        $form = $this->makeForm();
        if (is_null($extraValues)) {
            $extraValues = collect([]);
        }
        $extraValues->put('form', $form);

        $data = $this->variablesToView($extraValues, 'create');
        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return view($this->getView('form'), $data);
    }

    public function store(Request $request)
    {
        if (!$this->allowedActions('create', ['save', 'saveAndNew', 'SaveAndClose'])) {
            flashToastr($this->getLang('create.deny'), null, 'danger');

            return redirect()->back();
        }
        $this->beforeStore($request);
        $this->validate($request, $this->_hydratedModel->rules(), $this->getModelValidationMessages());

        $model = $this->_class::create($request->all());
        $this->afterStore($request, $model);
        $this->afterSave($request, $model);

        return $this->checksAndNotificationsAfterSave($model, $request, 'store');
    }


    protected function jsonResponse($results, $action, Request $request)
    {
        $flag = $results == 0 ? 'error' : 'success';
        $title = $this->getLang($action . '.' . $flag . 'Title');
        $message = $this->getLang($action . '.' . $flag . 'Msg', $results);
        if ($flag == 'success' and !$request->input('onlyJson', false)) {
            flashToastr($message, $title, 'success');
        }

        return response()->json([
            'flag'    => $flag,
            'count'   => $results,
            'title'   => $title,
            'message' => $message,
            'model'   => $this->_class,
            'success' => ($flag == 'success')
        ]);
    }

    public function changeStatus(Request $request)
    {
        if (!$this->allowedActions('index', ['enable', 'disable'])) {
            abort(403, $this->getLang('changeStatus.deny'));
        }
        $ids = $request->input('ids', []);
        $status = $request->input('status');
        $results = $this->_class::whereIn('id', $ids)->update(['enabled' => $status ? 1 : 0]);

        return $this->jsonResponse($results, 'changeStatus', $request);
    }


    public function delete(Request $request)
    {
        if (!$this->allowedActions('index', ['delete'])) {
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
        if (!$this->allowedActions('index', ['restore'])) {
            abort(403, $this->getLang('restore.deny'));
        }
        $ids = $request->input('ids', []);
        $results = $this->_class::whereIn('id', $ids)->onlyTrashed()->restore();

        return $this->jsonResponse($results, 'restore', $request);
    }

    public function forceDelete(Request $request)
    {
        if (!$this->allowedActions('index', ['forceDelete'])) {
            abort(403, $this->getLang('forceDelete.deny'));
        }
        $ids = $request->input('ids', []);
        $results = $this->_class::whereIn('id', $ids)->onlyTrashed()->forceDelete();

        return $this->jsonResponse($results, 'forceDelete', $request);
    }

    protected function genericEdit(Model $model, Collection $extraValues = null)
    {
        $form = $this->makeForm($model);
        if (is_null($extraValues)) {
            $extraValues = collect([]);
        }
        $extraValues->put('form', $form);
        if (request()->wantsJson()) {
            $newForm = clone  $form;
            $extraValues->put('formRendered', $newForm->renderForm(['id' => 'edit' . class_basename($this->_class) . 'Form']));
        }

        $data = $this->variablesToView($extraValues, 'edit', ['record' => $model]);

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return view($this->getView('form'), $data);
    }

    protected function genericUpdate(Request $request, $model)
    {
        /* poio apotelesmatiko validation
         * $form = $this->makeForm($model);
        $form->redirectIfNotValid();*/
        if ($request->input('after_save') == 'makeCopy') {
            $request->replace($request->except(['slug']));

            return $this->store($request);
        }
        if (!$this->allowedActions('edit', ['save', 'saveAndClose', 'saveAndNew', 'makeNewCopy'])) {
            flashToastr($this->getLang('edit.deny'), null, 'danger');

            return redirect()->back();
        }
        if (!$model->allowedToHandle()) {
            flashToastr($this->getLang('handle.deny'), null, 'danger');

            return redirect()->back();
        }
        $this->beforeUpdate($request, $model);
        $this->validate($request, $model->rules(), $this->getModelValidationMessages());
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
            'type'  => 'success',
            'msg'   => $this->getLang("{$action}.successMsg"),
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
            return redirect()->route("admin.{$this->baseRoute}.index");
        }
        if ($afterSaveVal == 'new') {
            return redirect()->route("admin.{$this->baseRoute}.create");
        }

        return redirect()->route("admin.{$this->baseRoute}.edit", $record);
    }

    protected function makeForm($model = null)
    {
        $options = [
            'method'        => is_null($model) ? 'POST' : 'PATCH',
            'url'           => route('admin.' . $this->baseRoute . '.' . (is_null($model) ? 'store' : 'update'), $model),
            'language_name' => "baseAdmin::admin/{$this->_modelsLangDir}.fields",
            'id'            => 'mainForm',
            'model'         => is_null($model) ? $this->_hydratedModel : $model,
        ];

        $parentDir = (Str::replaceFirst('\\', '', str_replace('App\Models', '', Str::replaceLast('\\' . class_basename($this->_class), '', $this->_class))));
        $parenName = (empty($parentDir) ? '' : $parentDir . '\\');
        $formName = $this->_useBasicForm ? 'Basic' : $parenName . class_basename($this->_class);

        return $this->form('App\\Forms\\Admin\\' . $formName . 'Form', $options, [//            'modelInstance' => is_null($model) ? $this->_hydratedModel : $model
        ]);
    }

    protected function getModelValidationMessages()
    {
        return $this->_hydratedModel->getErrorMessagesTranslated('admin/' . $this->_modelsLangDir . '.errorMessages.');
    }


    protected function getLang(string $langPath, $count = 1)
    {
        return trans_choice('baseAdmin::admin/' . ($this->_useGenericLang ? $this->_genericLangDir : $this->_modelsLangDir) . '.messages.crud.' . $langPath, $count, ['num' => $count]);
    }

    public function allowedActions($method = null, $actions = [])
    {
        foreach ($actions as $action) {
            if (in_array($action, $this->{"allowedActionsOn" . ucfirst($method)})) {
                return true;
            }
        }

        return false;

    }

    /**
     * @return bool
     */
    protected function modelHasSoftDeletes()
    {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->_hydratedModel));
    }

    /**
     * @param $action
     *
     * @return string
     */
    protected function getViewBase($action): string
    {

        if ($this->_useGenericViewIndex and $action == 'index') {
            return $this->_genericViewsDir;
        }
        if ($this->_useGenericViewForm and $action != 'index') {
            return $this->_genericViewsDir;
        }

        return $this->_modelsViewsDir;
    }

    private function getListableFields()
    {
        return Arr::get($this->listFields(), 'listable', []);
    }


    protected function beforeFilteringIndex(Request &$request, Collection &$params, Collection &$extraOptions)
    {
        //
    }

    protected function afterFilteringIndex(Request &$request, Collection &$params, &$query, &$extraOptions)
    {
        //
    }

    protected function beforeStore(Request &$request)
    {
        //
    }

    protected function afterStore(Request &$request, $record)
    {
        //
    }

    protected function beforeUpdate(Request &$request, $model)
    {
        //
    }

    protected function afterUpdate(Request &$request, $model)
    {
        //
    }

    protected function afterSave(Request &$request, $model)
    {
        //
    }


}
