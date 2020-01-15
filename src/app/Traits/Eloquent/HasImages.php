<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use App\Models\Media\MediumImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait HasImages
{
    use HasMediaSubTrait;

    /**
     * Listener
     */
    public static function bootHasImages()
    {
        //Delete All Files from Model
        static::deleting(function ($model) {
            /* @var HasImages $model */
            $model->deleteAssociateMedia('image');
        });
    }


    /**
     * @return bool
     */
    public function hasImages()
    {
        return $this->hasMedia($this->getImageModelType());
    }

    /**
     * Relation of Files to parent model. Morph Many To Many relationship
     * Get all files related to the parent model.
     *
     * @return MorphMany
     */
    public function images()
    {
        return $this->media($this->getImageModelFQN());
    }

    /**
     * @return Builder
     */
    public function imagesEnabled()
    {
        return $this->mediaEnabled($this->images());
    }


    /**
     * Sync An image to  model
     *
     * @param  mixed  $file
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumImage|null
     */
    public function syncImage($file = null, string $directoryName, string $disk = "uploads", string $displayName = null, int $order = null)
    {
        return $this->syncMedium($this->getImageModelType(), $file, $directoryName, $disk, $displayName, $order);
    }

    /**
     * .Add An image to  model
     *
     * @param  mixed  $file
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumImage|null
     */
    public function addImage($file = null, string $directoryName, string $disk = "uploads", string $displayName = null, int $order = null)
    {
        return $this->addMedium($this->getImageModelFQN(), $file, $directoryName, $disk, $displayName, $order);
    }


    /**
     * @param  Collection  $images
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @return Collection|null
     */
    public function syncImages(Collection $images, string $directoryName, string $disk = 'uploads')
    {
        return $this->syncMedia($this->getImageModelType(), $images, $directoryName, $disk);
    }

    /**
     * @param  Collection  $images
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addImages(Collection $images, string $directoryName, string $disk = 'uploads')
    {
        return $this->addMedia($this->getImageModelType(), $images, $directoryName, $disk);
    }


    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     * @return MediumImage|null
     */
    public function syncRequestImages(Request $request, $keepFirstOnly = false, string $requestFieldName = 'images')
    {
        return $this->syncRequestMedia($request, $keepFirstOnly, $requestFieldName, $this->getImageModelType(), $this->getImageModelFQN());
    }

    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     *
     * @param  string  $requestFieldName
     * @return boolean
     */
    protected function removeRequestImages(Request $request, string $requestFieldName = 'images')
    {
        return $this->removeRequestMedia($request, $requestFieldName, $this->getImageModelType(), $this->getImageModelFQN());
    }


    /**
     * @return string
     */
    private function getImageModelFQN()
    {
        return MediumImage::class;
    }

    /**
     * @return string
     */
    private function getImageModelType()
    {
        return 'image';

    }
}
