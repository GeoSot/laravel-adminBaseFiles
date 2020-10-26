<?php
/**
 * by GeoSv.
 * User: gsoti
 * Date: 16/7/2018
 * Time: 1:42 μμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait HasAllowedToHandleCheck
{
    public function allowedToHandle(string $arg = null)
    {
        return true;
    }

    public function allowedToHandleRelation(string $arg = null)
    {
        return true;
    }

    /**
     * @param bool $onlyNames
     *
     * @return Collection
     */
    public function modelPermissionsFromDB(bool $onlyNames = true)
    {
        $modelName = lcfirst(class_basename(static::class)); //get_called_class()
        $permissions = config('baseAdmin.config.models.permission')::getAsGroups($modelName)->map(function ($group) use ($onlyNames, $modelName) {
            if (!$onlyNames) {
                return $group->flatten();
            }

            return $group->flatten()->mapWithKeys(function ($item) use ($modelName) {
                preg_match("/\.(.*?)\-$modelName/", $item['name'], $matches);

                return [Arr::get($matches, 1) => $item['name']];
            });
        });

        return $permissions;
    }
}
