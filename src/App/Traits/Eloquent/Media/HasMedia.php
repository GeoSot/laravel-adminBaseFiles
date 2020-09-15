<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;


use Illuminate\Http\Request;
use Plank\Mediable\Mediable;


trait HasMedia
{
    use Mediable;

    /**
     * @var HasMediaTraitHelper
     */
    private $hasMediaHelper;


    /**
     * Initiator
     */
    public function initializeHasFiles()
    {
        $this->hasMediaHelper = new HasMediaTraitHelper($this);
    }


    /**
     * @return HasMediaTraitHelper
     */
    public function getHasMediaHelper()
    {
        return $this->hasMediaHelper;
    }


    /**
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     * @return void
     */
    final public function syncRequestMedia(Request $request, $keepFirstOnly = false, string $requestFieldName = 'files')
    {
        $this->getHasMediaHelper()->syncRequestMedia($request, $keepFirstOnly, $requestFieldName);
    }

    /**
     * @param  Request  $request
     *
     * @param  string  $requestFieldName
     * @return boolean
     */
    final protected function removeRequestMedia(Request $request, string $requestFieldName = 'files')
    {
        return $this->getHasMediaHelper()->removeRequestMedia($request, $requestFieldName);
    }
}
