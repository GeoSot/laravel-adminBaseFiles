<?php

namespace GeoSot\BaseAdmin\App\Models\Users;

use GeoSot\BaseAdmin\App\Traits\Eloquent\HasAllowedToHandleCheck;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasFrontEndConfigs;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasRulesOnModel;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use Laratrust\Models\LaratrustPermission;

class UserPermission extends LaratrustPermission
{
    use HasRulesOnModel, HasFrontEndConfigs, HasAllowedToHandleCheck;
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permission_group_id'
    ];

    public static function getAsGroups($modelName = null)
    {

        $query = parent::orderBy('name', 'ASC');
        /* @var Builder $query */
        if (!is_null($modelName)) {
            $query = $query->where('name', 'like', '%-'.$modelName);
        }

        $permissions = $query->get()->groupBy([
            function ($item) {
                return Str::before($item->name, '.');
            },
            function ($item1, $key) {
                $newKey = Str::after($item1->name, '-');

                return $newKey;
            }
        ])->sortKeys()->map(function ($value, $key) {
            return $value->sortKeys();
        });

        return $permissions;
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Validation RULES
     *
     * @param  array  $merge
     *
     * @return array
     */
    protected function rules(array $merge = [])
    {
        return array_merge([
            'name' => "required|unique:{$this->getTable()},name".$this->getIgnoreTextOnUpdate(),
            "display_name" => "required"
        ], $merge);
    }
}
