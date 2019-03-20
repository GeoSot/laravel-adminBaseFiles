<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Users;


use App\Models\Users\UserPermission;
use App\Models\Users\UserRole;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;

class UserPermissionController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->_class = UserPermission::class;
        $this->initializeModelValues();

        //OVERRIDES
        $this->allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];
        $this->allowedActionsOnIndex = ['create', 'edit'];
        $this->_useGenericViewIndex = false;
        $this->_useBasicForm = false;
    }


    public function index(Request $request)
    {
        $extraValues = collect([]);
        $params = $this->makeParams($request, $extraValues);
        $records = UserPermission::getAsGroups();
        $roles = UserRole::orderBy('id', 'ASC')->get()->load('permissions');

        $form = $this->plain([
            'url' => route('admin.' . $this->baseRoute . '.change.status'),
            'method' => 'POST',
            'id' => 'permissionsForm',
        ]);

        return view($this->getView('index'),
            $this->variablesToView(collect($this->listFields()), 'index', ['records' => $records, 'params' => $params, 'roles' => $roles, 'form' => $form]));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  UserPermission $userPermission
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPermission $userPermission)
    {
        return $this->genericEdit($userPermission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  UserPermission $userPermission
     *
     * @return \Illuminate\Http\Response
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


        foreach (UserRole::all() as $role) {
            if ($role->name == 'god') {// Avoid Change GOdD Permissions
                continue;
            }
            $perms = $request->get('role_permissions');
            if (isset($perms[$role->id])) {
                $role->syncPermissions($perms[$role->id]);
            } else {
                $role->syncPermissions([]);
            }
        }

        return redirect()->back();

    }


}
