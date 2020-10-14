<?php


namespace GeoSot\BaseAdmin\App\Models;

use Eloquent;
use GeoSot\BaseAdmin\App\Traits\Eloquent\EnabledDisabled;
use GeoSot\BaseAdmin\App\Traits\Eloquent\Encryptable;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasAllowedToHandleCheck;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasFrontEndConfigs;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasRulesOnModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\IsExportable;
use GeoSot\BaseAdmin\App\Traits\Eloquent\ModifiedBy;
use GeoSot\BaseAdmin\App\Traits\Eloquent\OwnedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * GeoSot\BaseAdmin\App\Models\BaseModel
 *
 * @mixin Eloquent
 * */
abstract class BaseModel extends Model
{
    use EnabledDisabled, OwnedBy, ModifiedBy, HasRulesOnModel, HasFrontEndConfigs, HasAllowedToHandleCheck, Encryptable, HasFactory,IsExportable;
}
