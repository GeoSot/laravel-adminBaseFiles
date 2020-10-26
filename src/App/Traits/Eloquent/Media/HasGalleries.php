<?php

namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;

use App\Models\Media\MediumGallery;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasGalleries
{
    public function hasGalleries()
    {
        return $this->galleries()->count() > 0;
    }

    /**
     * Relation of Gallery to parent model. Morph Many To Many relationship
     * Get all galleries related to the parent model.
     *
     * @return MorphMany
     */
    public function galleries()
    {
        return $this->morphMany(MediumGallery::class, 'related');
    }
}
