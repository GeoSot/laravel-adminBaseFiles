<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use Illuminate\Support\Facades\Auth;

trait OwnedBy
{
    public static function bootOwnedBy()
    {
        static::creating(function ($model) {
            if (in_array('user_id', $model->getFillable()) and !empty(request()->all()) and !request()->has('user_id') and Auth::check()) {
                $model['user_id'] = Auth::user()->id;
            }
        });
    }

    public function owner()
    {
        return in_array('user_id', $this->getFillable()) ? $this->belongsTo(config('baseAdmin.config.models.user'), 'user_id') : null;
    }

    //Laratrust auth()->user()->owns($connection)
    public function ownerKey()
    {
        return optional($this->owner)->id;
    }
}
