<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 18/3/2019
 * Time: 7:30 μμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Controller;

use Illuminate\Http\Request;

trait CachesRouteParameters
{
    /**
     * In case of Cached Route it returns the FullLink and in opposite NULL.
     *
     * @param Request $request
     *
     * @return mixed
     */
    protected function checkForPreviousPageVersionWithFiltersAndReturnThem(Request $request)
    {
        $query = $request->getQueryString();
        $routeName = $request->route()->getName();
        $saveName = 'routesParameters.'.str_replace('.', '_', $routeName);

        if ($request->has('export')) {
            session()->forget($saveName);

            return null;
        }
        if (empty($query) and session()->has($saveName)) {
            return route($routeName, session()->pull($saveName));
        }
        if (!empty($query)) {
            session()->put($saveName, $query);
        }
        if ($request->has('clean-queries')) {
            session()->forget($saveName);

            return route($routeName);
        }

        return null;
    }
}
