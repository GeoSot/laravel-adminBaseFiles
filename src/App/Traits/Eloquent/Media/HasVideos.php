<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;


use App\Models\Media\Medium;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;
use Plank\Mediable\Media;

trait HasVideos
{
    use HasMedia;

    /**
     * @var HasMediaTraitHelper
     */
    private $hasVideosHelper;


    /**
     * Initiator
     */
    public function initializeHasVideos()
    {
        return $this->hasVideosHelper = new HasMediaTraitHelper($this, [Medium::TYPE_VIDEO]);
    }


    /**
     * @return HasMediaTraitHelper
     */
    public function getHasVideosHelper()
    {
        return $this->hasVideosHelper ?: $this->initializeHasVideos();
    }

    /**
     * @return bool
     */
    public function hasVideos()
    {
        return $this->getHasVideosHelper()->hasRelation();
    }

    /**
     * Relation of Videos to parent model. Morph Many To Many relationship
     * Get all videos related to the parent model.
     *
     * @return MorphToMany
     */
    public function videos()
    {
        return $this->getHasVideosHelper()->relation();
    }


    /**
     * Replace the existing media collection for the specified tag(s).
     * @param  string|int|Media|Collection  $media
     * @param  string|string[]  $tags
     * @return void
     */
    public function syncVideos($media, $tags = [])
    {
        $this->getHasVideosHelper()->syncMedia($media, $tags);
    }

    /**
     * Replace the existing media collection for the specified tag(s).
     * @param  string|int|Media|Collection  $media
     * @param  string|string[]  $tags
     * @return void
     */
    public function attachVideos($media, $tags = [])
    {
        $this->getHasVideosHelper()->attachMedia($media, $tags);
    }

    /**
     *
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     * @return void
     */
    public function syncRequestVideos(Request $request, $keepFirstOnly = false, string $requestFieldName = 'videos')
    {
        return $this->getHasVideosHelper()->syncRequestMedia($request, $keepFirstOnly, $requestFieldName);
    }

    /**
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
