<?php
/**
 * by GeoSv.
 * User: gsoti
 * Date: 16/7/2018
 * Time: 10:36 πμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use GeoSot\BaseAdmin\App\Helpers\Models\FrontEndConfigs;
use Illuminate\Database\Eloquent\Model;

trait HasFrontEndConfigs
{
    /**
     * @var FrontEndConfigs
     */
    public $frontConfigs;

    public function initializeHasFrontEndConfigs()
    {
        /** @var Model $this */
        $this->frontConfigs = new FrontEndConfigs($this, $this->frontEndConfigValues ?? []);
    }


}

