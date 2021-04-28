<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Users;


use App\Models\Media\Medium;
use App\Models\Users\User;
use GeoSot\BaseAdmin\App\Helpers\Http\Controllers\Filter;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends BaseAdminController
{
    protected $_class = User::class;

    //OVERRIDES
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];


    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     *
     * @return Response
     */
    public function edit(User $user)
    {
        return $this->genericEdit($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  User  $user
     *
     * @return Response
     */
    public function update(Request $request, User $user)
    {
        return $this->genericUpdate($request, $user);
    }

    protected function listFields(): array
    {
        $neFields = [
            'listable' => ['full_name', 'is_enabled', 'roles.display_name', 'id'],
            'searchable' => ['first_name', 'last_name', 'is_enabled', 'id'],
            'sortable' => ['full_name', 'id'],
            'linkable' => ['full_name'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }

    protected function afterSave(Request &$request, $model)
    {
        /* @var User $model */
        $model->syncRequestMedia($request, true, Medium::REQUEST_FIELD_NAME__IMAGE);

        $model->syncRoles($request->get('roles', []));
    }

    protected function beforeUpdate(Request &$request, $model)
    {
        if (is_null($request->input('password', null))) {
            $request->request->remove('password');
            $request->request->remove('password_confirmation');
        }
    }

    protected function filters(): array
    {
        return [
            Filter::selectMulti('roles.name')
        ];
    }

}
