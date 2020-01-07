<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use App\Models\Media\MediumFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;


trait HasFiles
{

    /**
     * Listener
     */
    public static function bootHasFile()
    {
        //Delete All Files from Model
        static::deleting(function (HasFiles $model) {
            $model->deleteAssociateFiles();
        });
    }

    public function hasFiles()
    {
        return (boolean) $this->files()->count();
    }

    /**
     * Relation of Files to parent model. Morph Many To Many relationship
     * Get all files related to the parent model.
     *
     * @return MorphMany
     */
    public function files()
    {
        return $this->morphMany(MediumFile::class, 'model');
    }

    public function filesEnabled()
    {
        return $this->files()->where('enabled', true)->where('the_file_exists', true);
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
     * @return MediumFile
     */
    public function syncFile($file = null, string $directoryName, string $disk = 'uploads', string $displayName = null, int $order = null)
    {
        $this->deleteAssociateFiles();

        return $this->addFile($file, $directoryName, $disk, $displayName, $order);
    }

    private function deleteAssociateFiles()
    {
        $this->files()->each(function (Model $model) {
            $model->delete();
        });
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
     * @return MediumFile
     */
    public function addFile($file = null, string $directoryName, string $disk = 'uploads', string $displayName = null, int $order = null)
    {

        if (is_string($file) or $file instanceof UploadedFile) {

            $data = (new MediumFile())->fillData($this, $file, $directoryName, $disk, $displayName, $order);

            return MediumFile::create($data);
        }

        return null;
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
        $this->deleteAssociateFiles();

        return $this->addFiles($files, $directoryName, $disk);
    }

    /**
     * @param  Collection  $files
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addFiles(Collection $files, string $directoryName, string $disk = 'uploads')
    {
        if ($files->isEmpty()) {
            return null;
        }
        $collection = collect([]);
        foreach ($files as $index => $file) {
            $collection->push($this->addFile($file, $directoryName, $disk, null, $index * 10));
        }

        return $collection;
    }


}
