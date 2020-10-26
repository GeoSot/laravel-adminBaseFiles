<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Users;

use App\Models\Users\UserTeam;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserTeamController extends BaseAdminController
{
    protected $_class = UserTeam::class;
    //OVERRIDES
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew'];

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserTeam $userTeam
     *
     * @return Response
     */
    public function edit(UserTeam $userTeam)
    {
        return $this->genericEdit($userTeam);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param UserTeam $userTeam
     *
     * @return Response
     */
    public function update(Request $request, UserTeam $userTeam)
    {
        return $this->genericUpdate($request, $userTeam);
    }

    protected function listFields()//Can be omitted
    {
        $neFields = [
            'listable'   => ['display_name', 'name', 'id'],
            'searchable' => ['name', 'display_name', 'id'],
            'sortable'   => ['display_name', 'id'],
            'linkable'   => ['display_name'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }
}
