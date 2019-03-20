<?php

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use Illuminate\Database\Eloquent\Builder;

trait EnabledDisabled
{
    public function isEnabled()
    {
        return (bool)in_array('enabled', $this->getFillable()) ? $this->enabled : true;
    }

    public function scopeEnabled(Builder $builder)
    {
        return in_array('enabled', $this->getFillable()) ? $builder->where('enabled', true) : $builder;
    }

    public function scopeDisabled(Builder $builder)
    {
        return in_array('enabled', $this->getFillable()) ? $builder->where('enabled', false) : $builder;
    }
}
