<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Users;


use App\Models\Users\UserPermission;
use App\Models\Users\UserRole;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use GeoSot\BaseAdmin\Helpers\Alert;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class UserRoleController extends BaseAdminController
{
    protected $_class = UserRole::class;
    //OVERRIDES
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];


    public function create(Collection $extraValues = null)
    {
        $extraValues = collect(['permissionsGrouped' => UserPermission::getAsGroups()]);

        return parent::create($extraValues);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  UserRole  $userRole
     *
     * @return Response
     */
    public function edit(UserRole $userRole)
    {
        $userRole->load('users');
        $extraValues = collect([
            'permissionsGrouped' => UserPermission::getAsGroups()
        ]);

        return $this->genericEdit($userRole, $extraValues);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  UserRole  $userRole
     *
     * @return Response
     */
    public function update(Request $request, UserRole $userRole)
    {
        return $this->genericUpdate($request, $userRole);
    }

    public function afterSave(Request &$request, $userRole)
    {

        if ($userRole->name !== 'god') {
            $userRole->syncPermissions(array_filter($request->get('permissions')));
        }
    }

    public function delete(Request $request)
    {

        $protectedRoles = $this->_class::where('is_protected', true)->pluck('id')->toArray();

        $originalSentIds = $request->get('ids');
        $request['ids'] = array_diff($originalSentIds, $protectedRoles);


        if (count($request['ids']) and count($originalSentIds) > count($request['ids'])) {
            Alert::warning(__('admin/'.$this->_modelsLangDir.'.some_roles_where_not_deleted_cause_they_are_protected'))->typeToast();
        }

        return parent::delete($request);
    }

    protected function listFields(): array //Can be omitted
    {
        $neFields = [
            'listable' => ['display_name', 'name', 'id'],
            'searchable' => ['name', 'display_name', 'id'],
            'sortable' => ['display_name', 'id'],
            'linkable' => ['display_name'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }

}
