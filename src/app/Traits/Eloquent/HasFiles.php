<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use App\Models\MediaModels\FileModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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
        return $this->morphMany($this->getModel(), 'model');
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
     * @param  string  $fileName
     *
     * @return FileModel
     */
    public function syncFile($file = null, string $directoryName, string $disk = 'uploads', string $displayName = null, int $order = null, string $fileName = null)
    {
        $this->deleteAssociateFiles();

        return $this->addFile($file, $directoryName, $disk, $displayName, $order, $fileName);
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
     * @param  string  $fileName
     *
     * @return FileModel
     */
    public function addFile($file = null, string $directoryName, string $disk = 'uploads', string $displayName = null, int $order = null, string $fileName = null)
    {

        if (is_string($file) or $file instanceof UploadedFile) {

            $data = $this->fillFileData($file, $directoryName, $disk, $displayName, $order, $fileName);

            return $this->getModel()::create($data);
        }

        return null;
    }

    /**
     * @param $file
     * @param $directoryName
     * @param $disk
     * @param  null  $displayName
     * @param  null  $order
     * @param  string|null  $fileName
     * @return array
     */
    private function fillFileData($file, $directoryName, $disk, $displayName = null, $order = null, string $fileName = null)
    {
        $collection = Str::snake($directoryName);

        $sizeMb = $mimeType = $extension = '';
        if ($file instanceof UploadedFile) {
            $showName = $displayName ?? str_replace('.'.$file->getClientOriginalExtension(), '', $file->getClientOriginalName());
            $fileSaveName = $displayName ?: $file->hashName();
            $fileSaveName = ($fileName ?: $fileSaveName).'.'.$file->getClientOriginalExtension();
            $filePath = Storage::disk($disk)->putFileAs($collection, $file, $fileSaveName);
            $sizeMb = (float) number_format($file->getSize() / 1048576, 3);
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
        } else {
            $filePath = $file;
            $showName = is_null($displayName) ? $file : $displayName;
            $disk = "uri";
        }
        $attributes = [
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'collection_name' => $collection,
            'title' => $showName,
            'file' => $filePath,
            'disk' => $disk,
            'size_mb' => $sizeMb,
            'mime_type' => $mimeType,
            'extension' => $extension,
            'order' => $order
        ];

        return $attributes;
    }

    /**
     * Sync A File to model
     *
     * @param  mixed  $files
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @return  Collection|null
     */
    public function syncFiles($files, string $directoryName, string $disk = 'uploads')
    {
        $this->deleteAssociateFiles();

        return $this->addFiles($files, $directoryName, $disk);
    }

    /**
     * @param $files
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addFiles($files, string $directoryName, string $disk = 'uploads')
    {
        if (is_null($files) or empty($files)) {
            return null;
        }

        $collection = collect([]);
        foreach (collect($files) as $index => $file) {
            $collection->push($this->addFile($file, $directoryName, $disk, null, $index * 10));
        }

        return $collection;
    }


    /**
     * @return string
     */
    protected function getModel(): string
    {
        return config('baseAdmin.config.models.namespace').'MediaModels\\FileModel';
    }
}
