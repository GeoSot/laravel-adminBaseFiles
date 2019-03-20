<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use App\Models\HelpModels\FileModel;
use Illuminate\Http\UploadedFile;
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
        static::deleting(function ($model) {
            $model->deleteAssociateFiles();
        });
    }

    public function hasFiles()
    {
        return (boolean)$this->files()->count();
    }

    /**
     * Relation of Files to parent model. Morph Many To Many relationship
     * Get all files related to the parent model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
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
     * @param  mixed   $file
     * @param  string  $directoryName
     * @param  string  $displayName
     * @param  integer $order
     * @param  string  $disk
     * @param  string  $fileName
     *
     * @return FileModel
     */
    public function syncFile($file, string $directoryName, string $disk = 'uploads', string $displayName = null, int $order = null, string $fileName = null)
    {
        $this->deleteAssociateFiles();

        return $this->addFile($file, $directoryName, $disk, $displayName, $order, $fileName);
    }

    private function deleteAssociateFiles()
    {
        $this->files()->each(function ($model) {
            $model->delete();
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed   $file
     * @param  string  $directoryName
     * @param  string  $displayName *
     * @param  integer $order
     * @param  string  $disk
     * @param  string  $fileName
     *
     * @return FileModel
     */
    public function addFile($file, string $directoryName, string $disk = 'uploads', string $displayName = null, int $order = null, string $fileName = null)
    {

        if (is_string($file) or $file instanceof UploadedFile) {

            $data = $this->fillFileData($file, $directoryName, $disk, $displayName, $order, $fileName);

            return $this->getModel()::create($data);
        }

        return null;
    }

    private function fillFileData($file, $directoryName, $disk, $displayName = null, $order = null, string $fileName = null)
    {
        $collection = Str::snake($directoryName);

        $sizeMb = $mimeType = $extension = '';
        if ($file instanceof UploadedFile) {
            $showName     = is_null($displayName) ? str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) : $displayName;
            $fileSaveName = is_null($displayName) ? $file->hashName() : $displayName;
            $fileSaveName = (is_null($fileName) ? $fileSaveName : $fileName) . '.' . $file->getClientOriginalExtension();
            $filePath     = Storage::disk($disk)->putFileAs($collection, $file, $fileSaveName);
            $sizeMb       = (float)number_format($file->getSize() / 1048576, 3);
            $mimeType     = $file->getMimeType();
            $extension    = $file->getClientOriginalExtension();
        } else {
            $filePath = $file;
            $showName = is_null($displayName) ? $file : $displayName;
            $disk     = "uri";
        }
        $attributes = [
            'model_type'      => get_class($this),
            'model_id'        => $this->id,
            'collection_name' => $collection,
            'title'           => $showName,
            'file'            => $filePath,
            'disk'            => $disk,
            'size_mb'         => $sizeMb,
            'mime_type'       => $mimeType,
            'extension'       => $extension,
            'order'           => $order
        ];

        return $attributes;
    }

    /**
     * Sync A File to model
     *
     * @param  mixed  $files
     * @param  string $directoryName
     * @param  string $disk
     *
     * @return FileModel
     */
    public function syncFiles($files, string $directoryName, string $disk = 'uploads')
    {
        $this->deleteAssociateFiles();

        return $this->addFiles($files, $directoryName, $disk);
    }

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

    //    /**
    //     * Save a Users Profile Picture
    //     *
    //     * @param Request $request
    //     * @param bool    $keepFirstOnly
    //     * @param string    $subFieldName
    //     *
    //     * @return mixed
    //     */
    //    public function syncFilesFromRequest(Request $request, $keepFirstOnly = false, string $subFieldName='')
    //    {
    //        $this->removeFilesFromRequest($request);
    //
    //        $files = array_filter($request->file('files', []));
    //
    //        if (empty($files)) {
    //            return false;
    //        }
    //
    //
    //        if ($request->get('repeatable_files', !$keepFirstOnly)) {
    //            return $this->addFiles($files, $this->getTable(), "uploads");
    //        }
    //
    //
    //        $file      = array_first($files, function ($value, $key) {
    //            return isset($value);
    //        });
    //        $allFields = array_merge($this->getArrayableAppends(), $this->getFillable());
    //        $extraText = array_intersect(['full_name', 'title', 'slug'], $allFields)[0] ?? '';
    //        $slug      = empty($extraText) ? '' : '-' . str_slug($this[$extraText]);
    //
    //        $fileName = $this->id . $slug;
    //
    //        return $this->syncFile($file, $this->getTable(), "uploads", $fileName);
    //
    //
    //    }
    //
    //    /**
    //     * Save a Users Profile Picture
    //     *
    //     * @param Request $request
    //     * @param string $subFieldName
    //     *
    //     * @return boolean
    //     */
    //    protected function removeFilesFromRequest(Request $request,  string $subFieldName='')
    //    {
    //
    //        $removeIdsArray = array_filter($request->get('remove_files', []));
    //        $oldIds         = array_filter($request->get('old_files', []));
    //        $deleteIdsArray = $this->files->whereNotIn('id', $oldIds)->pluck('id')->toArray();
    //
    //        if (!empty($ids = array_merge($removeIdsArray, $deleteIdsArray))) {
    //            FileModel::deleteIds($ids);
    //
    //            return true;
    //        }
    //
    //        return false;
    //    }

    /**
     * @return string
     */
    protected function getModel(): string
    {
        return config('baseAdmin.config.models.namespace') . 'HelpModels\\FileModel';
    }
}
