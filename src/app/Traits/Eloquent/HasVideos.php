<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use App\Models\Media\MediumVideo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait HasVideos
{
    use HasMediaSubTrait;

    /**
     * Listener
     */
    public static function bootHasVideos()
    {
        //Delete All Files from Model
        static::deleting(function ($model) {
            /* @var HasVideos $model */
            $model->deleteAssociateMedia('video');
        });
    }


    /**
     * @return bool
     */
    public function hasVideos()
    {
        return $this->hasMedia($this->getVideoModelType());
    }

    /**
     * Relation of Files to parent model. Morph Many To Many relationship
     * Get all files related to the parent model.
     *
     * @return MorphMany
     */
    public function videos()
    {
        return $this->media($this->getVideoModelFQN());
    }

    /**
     * @return Builder
     */
    public function videosEnabled()
    {
        return $this->mediaEnabled($this->videos());
    }


    /**
     * Sync An video to  model
     *
     * @param  mixed  $file
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumVideo|null
     */
    public function syncVideo($file = null, string $directoryName, string $disk = "uploads", string $displayName = null, int $order = null)
    {
        return $this->syncMedium($this->getVideoModelType(), $file, $directoryName, $disk, $displayName, $order);
    }

    /**
     * .Add An video to  model
     *
     * @param  mixed  $file
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumVideo|null
     */
    public function addVideo($file = null, string $directoryName, string $disk = "uploads", string $displayName = null, int $order = null)
    {
        return $this->addMedium($this->getVideoModelFQN(), $file, $directoryName, $disk, $displayName, $order);
    }


    /**
     * @param  Collection  $videos
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @return Collection|null
     */
    public function syncVideos(Collection $videos, string $directoryName, string $disk = 'uploads')
    {
        return $this->syncMedia($this->getVideoModelType(), $videos, $directoryName, $disk);
    }

    /**
     * @param  Collection  $videos
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addVideos(Collection $videos, string $directoryName, string $disk = 'uploads')
    {
        return $this->addMedia($this->getVideoModelType(), $videos, $directoryName, $disk);
    }


    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     * @return MediumVideo|null
     */
    public function syncRequestVideos(Request $request, $keepFirstOnly = false, string $requestFieldName = 'videos')
    {
        return $this->syncRequestMedia($request, $keepFirstOnly, $requestFieldName, $this->getVideoModelType(), $this->getVideoModelFQN());
    }

    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     *
     * @param  string  $requestFieldName
     * @return boolean
     */
    protected function removeRequestVideos(Request $request, string $requestFieldName = 'videos')
    {
        return $this->removeRequestMedia($request, $requestFieldName, $this->getVideoModelType(), $this->getVideoModelFQN());
    }


    /**
     * @return string
     */
    private function getVideoModelFQN()
    {
        return MediumVideo::class;
    }

    /**
     * @return string
     */
    private function getVideoModelType()
    {
        return 'video';
    }


}
