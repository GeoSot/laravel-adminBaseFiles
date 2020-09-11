<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Users;


use App\Models\Users\UserPermission;
use App\Models\Users\UserRole;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class UserPermissionController extends BaseAdminController
{

    protected $_class = UserPermission::class;

    //OVERRIDES
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];
    protected $allowedActionsOnIndex = ['create', 'edit'];


    public function index(Request $request)
    {
        $extraValues = collect([]);
        $params = $this->makeParams($request);
        $records = UserPermission::getAsGroups();
        $roles = UserRole::orderBy('id', 'ASC')->get()->load('permissions');

        $form = $this->plain([
            'url' => route($this->_modelRoute.'.change.status'),
            'method' => 'POST',
            'id' => 'permissionsForm',
        ]);

        return view($this->getView('index'),
            $this->variablesToView(collect($this->listFields()), 'index', ['records' => $records, 'params' => $params, 'roles' => $roles, 'form' => $form]));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  UserPermission  $userPermission
     *
     * @return Response
     */
    public function edit(UserPermission $userPermission)
    {
        return $this->genericEdit($userPermission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  UserPermission  $userPermission
     *
     * @return Response
     */
    public function update(Request $request, UserPermission $userPermission)
    {
        return $this->genericUpdate($request, $userPermission);
    }

    public function afterSave(Request &$request, $userPermission)
    {
        UserRole::whereName('god')->first()->attachPermission($userPermission);
    }

    public function changeStatus(Request $request)
    {

        foreach (UserRole::where('name', '<>', 'god')->get() as $role) {

            $perms = $request->get('role_permissions');
            $role->syncPermissions(Arr::get($perms, $role->getKey(), []));
        }
        return redirect()->back();

    }


}
