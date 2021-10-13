<?php

namespace GeoSot\BaseAdmin\App\Models\Users;

use Cviebrock\EloquentSluggable\Sluggable;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasAllowedToHandleCheck;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasFrontEndConfigs;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasRulesOnModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\IsExportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laratrust\Models\LaratrustTeam;

class UserTeam extends LaratrustTeam
{
    use HasRulesOnModel, HasFrontEndConfigs, HasAllowedToHandleCheck, Sluggable, IsExportable, HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];


//    public function getRouteKeyName()
//    {
//        return 'name';
//    }

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
            "display_name" => "required",
        ], $merge);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return ['name' => ['source' => 'display_name']];
    }


}
