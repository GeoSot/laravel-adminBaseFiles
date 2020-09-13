<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;


use App\Models\Media\MediumVideo;
use GeoSot\BaseAdmin\App\Models\Media\BaseMediaModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait HasVideos
{
    /**
     * @var HasMediaTraitHelper
     */
    private $hasVideosHelper;

    /**
     * Listener
     */
    public static function bootHasVideos()
    {
        //Delete All Videos from Model
        static::deleting(function ($model) {
            /* @var HasVideos $model */
            $model->getHasVideosHelper()->deleteAssociateMedia();
        });
    }

    /**
     * Initiator
     */
    public function initializeHasVideos()
    {
        $this->hasVideosHelper = new HasMediaTraitHelper($this, MediumVideo::TYPE, MediumVideo::class);
    }


    /**
     * @return HasMediaTraitHelper
     */
    public function getHasVideosHelper()
    {
        return $this->hasVideosHelper;
    }

    /**
     * @return bool
     */
    public function hasVideos()
    {
        return $this->getHasVideosHelper()->hasMedia();
    }

    /**
     * Relation of Videos to parent model. Morph Many To Many relationship
     * Get all videos related to the parent model.
     *
     * @return MorphMany
     */
    public function videos()
    {
        return $this->getHasVideosHelper()->media();
    }

    /**
     * @return Builder
     */
    public function videosEnabled()
    {
        return $this->getHasVideosHelper()->mediaEnabled($this->videos());
    }

    /**
     * Sync A Video to model
     *
     * @param  mixed  $video
     * @param  string  $directoryName
     * @param  string  $displayName
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumVideo|null
     */
    public function syncVideo($video = null, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK, string $displayName = null, int $order = null)
    {
        return $this->getHasVideosHelper()->syncMedium($video, $directoryName, $disk, $displayName, $order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed  $video
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumVideo|null
     */
    public function addVideo($video = null, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK, string $displayName = null, int $order = null)
    {
        return $this->getHasVideosHelper()->addMedium($video, $directoryName, $disk, $displayName, $order);
    }


    /**
     * Sync A Video to model
     *
     * @param  Collection  $videos
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @return  Collection|null
     */
    public function syncVideos(Collection $videos, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK)
    {
        return $this->getHasVideosHelper()->syncMedia($videos, $directoryName, $disk);
    }

    /**
     * @param  Collection  $videos
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addVideos(Collection $videos, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK)
    {
        return $this->getHasVideosHelper()->addMedia($videos, $directoryName, $disk);
    }

    /**
     * Save a Users Provideo Picture
     *
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     * @return MediumVideo|null
     */
    public function syncRequestVideos(Request $request, $keepFirstOnly = false, string $requestFieldName = 'videos')
    {
        return $this->getHasVideosHelper()->syncRequestMedia($request, $keepFirstOnly, $requestFieldName);
    }

    /**
     * Save a Users Provideo Picture
     *
     * @param  Request  $request
     *
     * @param  string  $requestFieldName
     * @return boolean
     */
    protected function removeRequestVideos(Request $request, string $requestFieldName = 'videos')
    {
        return $this->getHasVideosHelper()->removeRequestMedia($request, $requestFieldName);
    }

}
