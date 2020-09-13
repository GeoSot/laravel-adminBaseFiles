<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;


use App\Models\Media\MediumFile;
use GeoSot\BaseAdmin\App\Models\Media\BaseMediaModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


trait HasFiles
{
    /**
     * @var HasMediaTraitHelper
     */
    private $hasFilesHelper;

    /**
     * Listener
     */
    public static function bootHasFiles()
    {
        //Delete All Files from Model
        static::deleting(function ($model) {
            /* @var HasFiles $model */
            $model->getHasFilesHelper()->deleteAssociateMedia();
        });
    }

    /**
     * Initiator
     */
    public function initializeHasFiles()
    {
        $this->hasFilesHelper = new HasMediaTraitHelper($this, MediumFile::TYPE, MediumFile::class);
    }


    /**
     * @return HasMediaTraitHelper
     */
    public function getHasFilesHelper()
    {
        return $this->hasFilesHelper;
    }

    /**
     * @return bool
     */
    public function hasFiles()
    {
        return $this->getHasFilesHelper()->hasMedia();
    }

    /**
     * Relation of Files to parent model. Morph Many To Many relationship
     * Get all files related to the parent model.
     *
     * @return MorphMany
     */
    public function files()
    {
        return $this->getHasFilesHelper()->media();
    }

    /**
     * @return Builder
     */
    public function filesEnabled()
    {
        return $this->getHasFilesHelper()->mediaEnabled($this->files());
    }

    /**
     * Sync A File to model
     *
     * @param  mixed  $file
     * @param  string  $directoryName
     * @param  string  $displayName
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumFile|null
     */
    public function syncFile($file = null, string $directoryName, string $disk = 'uploads', string $displayName = null, int $order = null)
    {
        return $this->getHasFilesHelper()->syncMedium($file, $directoryName, $disk, $displayName, $order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed  $file
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return MediumFile|null
     */
    public function addFile($file = null, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK, string $displayName = null, int $order = null)
    {
        return $this->getHasFilesHelper()->addMedium($file, $directoryName, $disk, $displayName, $order);
    }


    /**
     * Sync A File to model
     *
     * @param  Collection  $files
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @return  Collection|null
     */
    public function syncFiles(Collection $files, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK)
    {
        return $this->getHasFilesHelper()->syncMedia($files, $directoryName, $disk);
    }

    /**
     * @param  Collection  $files
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addFiles(Collection $files, string $directoryName, string $disk = BaseMediaModel::DEFAULT_DISK)
    {
        return $this->getHasFilesHelper()->addMedia($files, $directoryName, $disk);
    }

    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     * @return MediumFile|null
     */
    public function syncRequestFiles(Request $request, $keepFirstOnly = false, string $requestFieldName = 'files')
    {
        return $this->getHasFilesHelper()->syncRequestMedia($request, $keepFirstOnly, $requestFieldName);
    }

    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     *
     * @param  string  $requestFieldName
     * @return boolean
     */
    protected function removeRequestFiles(Request $request, string $requestFieldName = 'files')
    {
        return $this->getHasFilesHelper()->removeRequestMedia($request, $requestFieldName);
    }

}
