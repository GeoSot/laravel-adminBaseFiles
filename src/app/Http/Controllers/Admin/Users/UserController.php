<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Users;


use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Users\User;
use Illuminate\Http\Request;

class UserController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->_class = User::class;
        $this->initializeModelValues();

        //OVERRIDES
        $this->_useGenericViewForm = false;
        $this->_useBasicForm = false;
        $this->allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return $this->genericEdit($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        return $this->genericUpdate($request, $user);
    }

    protected function listFields()
    {
        $neFields = [
            'listable' => ['full_name', 'enabled', 'roles.display_name', 'id'],
            'searchable' => ['first_name', 'last_name', 'enabled', 'id'],
            'sortable' => ['full_name', 'id'],
            'linkable' => ['full_name'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }

    protected function afterSave(Request &$request, $model)
    {
        $model->syncPictures($request);

        $model->syncRoles($request->get('roles', []));
    }

    protected function beforeUpdate(Request &$request, $model)
    {
        if (is_null($request->input('password', null))) {
            $request->request->remove('password');
            $request->request->remove('password_confirmation');
        }
    }

    protected function filters()
    {
        return [
            'roles.name' => ['type' => 'multiSelect'],
        ];
    }

}
