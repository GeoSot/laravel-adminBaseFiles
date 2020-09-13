<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;


use App\Models\Media\MediumImage;
use GeoSot\BaseAdmin\App\Jobs\CompressImage;
use GeoSot\BaseAdmin\App\Models\Media\BaseMediaModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait HasImages
{

    /**
     * @var HasMediaTraitHelper
     */
    private $hasImagesHelper;

    /**
     * Listener
     */
    public static function bootHasImages()
    {
        //Delete All Images from Model
        static::deleting(function ($model) {
            /* @var HasImages $model */
            $model->getHasImagesHelper()->deleteAssociateMedia();
        });
    }

    /**
     * Initiator
     */
    public function initializeHasImages()
    {
        $this->hasImagesHelper = new HasMediaTraitHelper($this, MediumImage::TYPE, MediumImage::class);
    }


    /**
     * @return HasMediaTraitHelper
     */
    public function getHasImagesHelper()
    {
        return $this->hasImagesHelper;
    }

    /**
     * @return bool
     */
    public function hasImages()
    {
        return $this->getHasImagesHelper()->hasMedia();
    }

    /**
     * Relation of Images to parent model. Morph Many To Many relationship
     * Get all images related to the parent model.
     *
     * @return MorphMany
     */
    public function images()
    {
        return $this->getHasImagesHelper()->media();
    }

    /**
     * @return Builder
     */
    public function imagesEnabled()
    {
        return $this->getHasImagesHelper()->mediaEnabled($this->images());
    }

    /**
     * Sync A Image to model
     *
     * @param  mixed  $image
     * @param  string  $directoryName
     * @param  string  $displayName
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumImage|null
     */
    public function syncImage($image = null, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK, string $displayName = null, int $order = null)
    {
        return $this->getHasImagesHelper()->syncMedium($image, $directoryName, $disk, $displayName, $order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed  $image
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumImage|null
     */
    public function addImage($image = null, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK, string $displayName = null, int $order = null)
    {
        $img = $this->getHasImagesHelper()->addMedium($image, $directoryName, $disk, $displayName, $order);

        if ($img instanceof MediumImage) {
            CompressImage::dispatch($img);
        }

        return $img;
    }


    /**
     * Sync A Image to model
     *
     * @param  Collection  $images
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @return  Collection|null
     */
    public function syncImages(Collection $images, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK)
    {
        return $this->getHasImagesHelper()->syncMedia($images, $directoryName, $disk);
    }

    /**
     * @param  Collection  $images
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addImages(Collection $images, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK)
    {
        return $this->getHasImagesHelper()->addMedia($images, $directoryName, $disk);
    }

    /**
     * Save a Users Proimage Picture
     *
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     * @return MediumImage|null
     */
    public function syncRequestImages(Request $request, $keepFirstOnly = false, string $requestFieldName = 'images')
    {
        return $this->getHasImagesHelper()->syncRequestMedia($request, $keepFirstOnly, $requestFieldName);
    }

    /**
     * Save a Users Proimage Picture
     *
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
