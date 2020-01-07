<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use GeoSot\BaseAdmin\App\Models\Media\MediumVideo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

trait HasVideos
{

    /**
     * Listener
     */
    public static function bootHasVideos()
    {
        //Delete All Files from Album
        static::deleting(function (HasVideos $model) {
            $model->deleteAssociateVideos();
        });
    }

    public function hasVideos()
    {
        return (boolean) $this->videos()->count();
    }

    /**
     * Relation of Video to parent model. Morph Many To Many relationship
     * Get all videos related to the parent model.
     *
     * @return MorphMany
     */
    public function videos()
    {
        return $this->morphMany(MediumVideo::class, 'model');
    }

    public function videosEnabled()
    {
        return $this->videos()->where('enabled', true)->where('the_file_exists', true);
    }


    /**
     * Sync An video to  model
     *
     * @param  mixed  $video
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumVideo
     */
    public function syncVideo($video = null, string $directoryName, string $disk = "uploads", string $displayName = null, int $order = null)
    {
        $this->deleteAssociateVideos();
        return $this->addVideo($video, $directoryName, $disk, $displayName, $order);
    }

    private function deleteAssociateVideos()
    {
        $this->videos()->each(function (Model $model) {
            $model->delete();
        });
    }

    /**
     * .Add An video to  model
     *
     * @param  mixed  $video
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumVideo
     */
    public function addVideo($video = null, string $directoryName, string $disk = "uploads", string $displayName = null, int $order = null)
    {
        if (is_null($video)) {
            return null;
        }

        $data = (new MediumVideo())->fillData($video, $directoryName, $disk, $displayName, $order);

        return MediumVideo::create($data);
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
        $this->deleteAssociateVideos();

        return $this->addVideos($videos, $directoryName, $disk);
    }

    /**
     * @param  Collection  $videos
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addVideos(Collection $videos, string $directoryName, string $disk = 'uploads')
    {
        if ($videos->isEmpty()) {
            return null;
        }
        $collection = collect([]);
        foreach ($videos as $index => $video) {
            $collection->push($this->addVideo($video, $directoryName, $disk, null, $index * 10));
        }

        return $collection;
    }


}
