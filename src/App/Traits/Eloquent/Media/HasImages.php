<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;


use App\Models\Media\Medium;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;
use Plank\Mediable\Media;

trait HasImages
{
    use HasMedia;

    /**
     * @var HasMediaTraitHelper
     */
    private $hasImagesHelper;


    /**
     * Initiator
     */
    public function initializeHasImages()
    {
        return $this->hasImagesHelper = new HasMediaTraitHelper($this, [Medium::TYPE_IMAGE, Medium::TYPE_IMAGE_VECTOR]);
    }


    /**
     * @return HasMediaTraitHelper
     */
    public function getHasImagesHelper()
    {
        return $this->hasImagesHelper ?: $this->initializeHasImages();
    }

    /**
     * @return bool
     */
    public function hasImages()
    {
        return $this->getHasImagesHelper()->hasRelation();
    }

    /**
     * Relation of Images to parent model. Morph Many To Many relationship
     * Get all images related to the parent model.
     *
     * @return MorphToMany
     */
    public function images()
    {
        return $this->getHasImagesHelper()->relation();
    }

    /**
     * Replace the existing media collection for the specified tag(s).
     * @param  string|int|Media|Collection  $media
     * @param  string|string[]  $tags
     * @return void
     */
    public function syncImages($media, $tags = [])
    {
        $this->getHasImagesHelper()->syncMedia($media, $tags);
    }

    /**
     * Replace the existing media collection for the specified tag(s).
     * @param  string|int|Media|Collection  $media
     * @param  string|string[]  $tags
     * @return void
     */
    public function attachImages($media, $tags = [])
    {
        $this->getHasImagesHelper()->attachMedia($media, $tags);
    }


    /**
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     * @return void
     */
    public function syncRequestImages(Request $request, $keepFirstOnly = false, string $requestFieldName = 'images')
    {
        $this->getHasImagesHelper()->syncRequestMedia($request, $keepFirstOnly, $requestFieldName);
    }

    /**
     * @param  Request  $request
     *
     * @param  string  $requestFieldName
     * @return boolean
     */
    protected function removeRequestImages(Request $request, string $requestFieldName = 'images')
    {
        return $this->getHasImagesHelper()->removeRequestMedia($request, $requestFieldName);
    }

}
