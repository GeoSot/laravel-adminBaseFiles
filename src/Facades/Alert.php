<?php

declare(strict_types=1);

namespace GeoSot\BaseAdmin\Facades;

use GeoSot\BaseAdmin\Helpers\Alert as AlertClass;
use GeoSot\BaseAdmin\Helpers\Color;
use Illuminate\Support\Facades\Facade;


/**
 * Class Alert.
 *
 * @method static AlertClass info(string $message, string $title = '')
 * @method static AlertClass success(string $message, string $title = '')
 * @method static AlertClass error(string $message, string $title = '')
 * @method static AlertClass warning(string $message, string $title = '')
 * @method static AlertClass message(string $message, string $title = '', string $level = Color::INFO, string $type = '')
 * @method static AlertClass typeInline()
 * @method static AlertClass typeToast()
 * @method static AlertClass typeSwal()
 */
class Alert extends Facade
{
    /**
     * Initiate a mock expectation on the facade.
     *
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return 'alert';
    }
}
