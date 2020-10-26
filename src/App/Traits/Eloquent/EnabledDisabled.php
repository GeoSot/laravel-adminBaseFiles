<?php

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use Illuminate\Database\Eloquent\Builder;

trait EnabledDisabled
{
    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->hasAttribute('enabled') ? $this->enabled : true;
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function scopeEnabled(Builder $builder)
    {
        return $this->hasAttribute('enabled') ? $builder->where('enabled', true) : $builder;
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function scopeDisabled(Builder $builder)
    {
        return $this->hasAttribute('enabled') ? $builder->where('enabled', false) : $builder;
    }

    /**
     * @param $attr
     *
     * @return bool
     */
    public function hasAttribute($attr)
    {
        return array_key_exists($attr, $this->attributes) || $this->isFillable($attr);
    }
}
