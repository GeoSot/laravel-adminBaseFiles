<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Settings;



use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SettingController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->_class = Setting::class;
        $this->initializeModelValues();


        //OVERRIDES
        $this->_useBasicForm = false;
        $this->_useGenericViewForm = false;
        $this->_useGenericViewIndex = false;

        $this->allowedActionsOnIndex = ['create', 'edit'];
        $this->allowedActionsOnCreate = ['save',];
        $this->allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew', 'makeNewCopy'];

    }

    public function afterFilteringIndex(Request &$request, Collection &$params, &$query, &$extraOptions)
    {
        if (empty($request->query())) {
            $query->whereRaw('1 = 0');
        }
    }

    public function create(Collection $extraValues = null)
    {
        $extraValues = $this->getListValues();

        return parent::create($extraValues);
    }

    /**
     * @return Collection
     */
    private function getListValues()
    {
        $settings = Setting::enabled()->get();
        $extraValues = collect([
            'settingsExistingValues' => [
                'keys' => $settings->whereNotIn('key', [''])->pluck('key')->unique()->sort(),
                'groups' => $settings->whereNotIn('group', [''])->pluck('group')->unique()->sort(),
                'subGroups' => $settings->whereNotIn('sub_group', [''])->pluck('sub_group')->unique()->sort(),
            ]
        ]);

        return $extraValues;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Setting $setting
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        $extraValues = $this->getListValues();

        return $this->genericEdit($setting, $extraValues);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Setting $setting
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        return $this->genericUpdate($request, $setting);
    }

    protected function listFields()//Can be omitted
    {
        $neFields = [
            'linkable' => ['key'],
            'listable' => ['key', 'value', 'type', 'ownerModel.title', 'id'],
            'searchable' => [],
            'sortable' => ['key'],
            'orderBy' => ['column' => 'key', 'sort' => 'desc'],
        ];

        return array_merge(parent::listFields(), $neFields);
    }

    protected function filters()//Can be omitted
    {
        return [
            'group' => ['type' => 'select'],
            'sub_group' => ['type' => 'multiSelect'],
        ];
    }


}
