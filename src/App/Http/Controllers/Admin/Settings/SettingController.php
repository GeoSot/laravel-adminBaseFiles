<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Admin\Settings;


use App\Models\Media\Medium;
use App\Models\Setting;
use Carbon\Carbon;
use GeoSot\BaseAdmin\App\Helpers\Http\Controllers\Filter;
use GeoSot\BaseAdmin\App\Http\Controllers\Admin\BaseAdminController;
use GeoSot\BaseAdmin\Services\SettingsChoices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SettingController extends BaseAdminController
{
    protected $_class = Setting::class;

    //OVERRIDES
    protected $allowedActionsOnIndex = ['create', 'edit', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnCreate = ['save',];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew', 'makeNewCopy'];


    public function afterFilteringIndex(Request &$request, Collection &$params, Builder &$query, &$extraOptions)
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
            ],
        ]);

        return $extraValues;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Setting  $setting
     *
     * @return Response
     */
    public function edit(Setting $setting)
    {
        $extraValues = $this->getListValues();

        return $this->genericEdit($setting, $extraValues);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Setting  $setting
     *
     * @return Response
     */
    public function update(Request $request, Setting $setting)
    {
        return $this->genericUpdate($request, $setting);
    }

    /**
     * @param  Request  $request
     * @param  Setting  $model
     */
    protected function beforeUpdate(Request &$request, $model)
    {
        $type = $request->input('type');
        if ($type === SettingsChoices::DATE_RANGE) {
            $value = Carbon::parse($request->input('value.from'))->daysUntil($request->input('value.to'));
            $request['value'] = $value->toIso8601String();
        }

        if (in_array($type, [Medium::TYPE_IMAGE, Medium::class])) {
            $result = $model->syncRequestMedia($request, true, 'value_dummy');
            $request->merge(['value' => optional($result)->getKey()]);
        }

    }


    protected function listFields(): array //Can be omitted
    {
        return [
            'linkable' => ['slug'],
            'listable' => ['slug', 'value', 'type_to_human', 'ownerModel.title', 'id'],
            'searchable' => ['slug'],
            'sortable' => ['key'],
            'orderBy' => ['column' => 'key', 'sort' => 'desc'],
        ];
    }

    protected function filters(): array //Can be omitted
    {
        return [
            Filter::select('group'),
            Filter::selectMulti('sub_group'),
        ];
    }


}
