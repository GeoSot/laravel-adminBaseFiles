<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use App\Models\Media\MediumFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


trait HasFiles
{
    use HasMediaSubTrait;

    /**
     * Listener
     */
    public static function bootHasFiles()
    {
        //Delete All Files from Model
        static::deleting(function ($model) {
            /* @var HasFiles $model */
            $model->deleteAssociateMedia('file');
        });
    }

    /**
     * @return bool
     */
    public function hasFiles()
    {
        return $this->hasMedia($this->getFileModelType());
    }

    /**
     * Relation of Files to parent model. Morph Many To Many relationship
     * Get all files related to the parent model.
     *
     * @return MorphMany
     */
    public function files()
    {
        return $this->media($this->getFileModelFQN());
    }

    /**
     * @return Builder
     */
    public function filesEnabled()
    {
        return $this->mediaEnabled($this->files());
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
        return $this->syncMedium($this->getFileModelType(), $file, $directoryName, $disk, $displayName, $order);
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
    public function addFile($file = null, string $directoryName, string $disk = 'uploads', string $displayName = null, int $order = null)
    {
        return $this->addMedium($this->getFileModelFQN(), $file, $directoryName, $disk, $displayName, $order);
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
    public function syncFiles(Collection $files, string $directoryName, string $disk = 'uploads')
    {
        return $this->syncMedia($this->getFileModelType(), $files, $directoryName, $disk);
    }

    /**
     * @param  Collection  $files
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addFiles(Collection $files, string $directoryName, string $disk = 'uploads')
    {
        return $this->addMedia($this->getFileModelType(), $files, $directoryName, $disk);
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
        return $this->syncRequestMedia($request, $keepFirstOnly, $requestFieldName, $this->getFileModelType(), $this->getFileModelFQN());
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
        return $this->removeRequestMedia($request, $requestFieldName, $this->getFileModelType(), $this->getFileModelFQN());
    }

    /**
     * @return string
     */
    private function getFileModelFQN()
    {
        return MediumFile::class;
    }

    /**
     * @return string
     */
    private function getFileModelType()
    {
        return 'file';
    }

}
