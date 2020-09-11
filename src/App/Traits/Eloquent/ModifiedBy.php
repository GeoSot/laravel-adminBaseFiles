<?php

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use Illuminate\Support\Facades\Auth;

trait ModifiedBy
{
    public static function bootModifiedBy()
    {
        static::saving(function ($model) {
            if (in_array('modified_by', $model->getFillable()) and !request()->has('user_id') and Auth::check()) {
                $model['modified_by'] = Auth::user()->id;
            }
        });
    }
}
