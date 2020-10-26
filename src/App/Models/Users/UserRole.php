<?php

namespace GeoSot\BaseAdmin\App\Models\Users;

use GeoSot\BaseAdmin\App\Traits\Eloquent\HasAllowedToHandleCheck;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasFrontEndConfigs;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasRulesOnModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\IsExportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Models\LaratrustRole;

class UserRole extends LaratrustRole
{
    use SoftDeletes;
    use HasRulesOnModel;
    use HasFrontEndConfigs;
    use HasAllowedToHandleCheck;
    use HasFactory;
    use IsExportable;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'front_users_can_see',
        'front_users_can_choose',
        'is_protected',
    ];
    protected $casts = [
        'front_users_can_see'    => 'boolean',
        'front_users_can_choose' => 'boolean',
        'is_protected'           => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Validation RULES.
     *
     * @param array $merge
     *
     * @return array
     */
    protected function rules(array $merge = [])
    {
        return array_merge([
            'name'         => "required|unique:{$this->getTable()},name".$this->getIgnoreTextOnUpdate(),
            'display_name' => 'required',
        ], $merge);
    }

    public function scopeFrontUsersCanSee($builder)
    {
        return $builder->where('front_users_can_see', true);
    }

    public function scopeFrontUsersCanChoose($builder)
    {
        return $builder->where('front_users_can_choose', true);
    }
}
