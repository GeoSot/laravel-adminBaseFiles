<?php

namespace GeoSot\BaseAdmin\App\Traits\Controller;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait HasActionHooks
{
    protected function beforeFilteringIndex(Request &$request, Collection &$params, Collection &$extraOptions)
    {
        //
    }

    protected function afterFilteringIndex(Request &$request, Collection &$params, Builder &$query, &$extraOptions)
    {
        //
    }

    protected function beforeStore(Request &$request)
    {
        //
    }

    protected function afterStore(Request &$request, $record)
    {
        //
    }

    protected function beforeUpdate(Request &$request, $model)
    {
        //
    }

    protected function afterUpdate(Request &$request, $model)
    {
        //
    }

    protected function afterSave(Request &$request, $model)
    {
        //
    }
}
