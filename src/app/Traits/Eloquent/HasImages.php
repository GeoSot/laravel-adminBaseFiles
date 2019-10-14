<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use GeoSot\BaseAdmin\App\Models\MediaModels\ImageModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasImages
{

    /**
     * Listener
     */
    public static function bootHasImages()
    {
        //Delete All Files from Album
        static::deleting(function (HasImages $model) {
            $model->deleteAssociateImages();
        });
    }

    public function hasImages()
    {
        return (boolean) $this->images()->count();
    }

    /**
     * Relation of Image to parent model. Morph Many To Many relationship
     * Get all images related to the parent model.
     *
     * @return MorphMany
     */
    public function images()
    {
        return $this->morphMany($this->getModel(), 'model');
    }

    public function imagesEnabled()
    {
        return $this->images()->where('enabled', true)->where('the_file_exists', true);
    }



    /**
     * Sync An image to  model
     *
     * @param  mixed  $img
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return ImageModel
     */
    public function syncImage($img = null, string $directoryName, string $disk = "uploads", string $displayName = null, int $order = null)
    {
        $this->deleteAssociateImages();

        return $this->addImage($img, $directoryName, $disk, $displayName, $order);
    }

    private function deleteAssociateImages()
    {
        $this->images()->each(function ($model) {
            $model->delete();
        });
    }
    /**
     * .Add An image to  model
     *
     * @param  mixed  $img
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return ImageModel
     */
    public function addImage($img = null, string $directoryName, string $disk = "uploads", string $displayName = null, int $order = null)
    {
        if (is_null($img)) {
            return null;
        }
        $data = $this->fillData($img, $directoryName, $disk, $displayName, $order);

        return ImageModel::create($data);
    }

    /**
     * .Sync An image to  model
     *
     * @param  mixed  $img
     * @param  string  $directoryName
     * @param  string  $displayName  *
     * @param  integer  $order
     * @param  string  $disk
     *
     * @return array
     */
    private function fillData($img, string $directoryName, string $disk, string $displayName = null, int $order = null)
    {
        $collection = preg_replace('/[^a-zA-Z0-9]/', '', $directoryName);
        $sizeMb = $mimeType = $extension = null;
        if ($img instanceof UploadedFile) {
            $filePath = ($displayName)
                ? Storage::disk($disk)->putFileAs($collection, $img, $displayName.'.'.$img->getClientOriginalExtension())
                : $filePath = Storage::disk($disk)->putFile($collection, $img);

            $fileName = $displayName ?: str_replace('.'.$img->getClientOriginalExtension(), '', $img->getClientOriginalName());
            $sizeMb = (float) number_format($img->getSize() / 1048576, 2);
            $mimeType = $img->getMimeType();
            $extension = $img->getClientOriginalExtension();
        } else {
            $filePath = $img;
            $fileName = $displayName ?: $img;
            $disk = "uri";
        }
        return [
            'model_type' => get_class($this),
            'model_id' => $this->getKey(),
            'collection_name' => $collection,
            'title' => $fileName,
            'file' => $filePath,
            'disk' => $disk,
            'size_mb' => $sizeMb,
            'mime_type' => $mimeType,
            'extension' => $extension,
            'order' => $order,
        ];

    }

    /**
     * @param  null  $images
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @return Collection|null
     */
    public function syncImages($images = null, string $directoryName, string $disk = 'uploads')
    {
        $this->deleteAssociateImages();

        return $this->addImages($images, $directoryName, $disk);
    }

    public function addImages($images, string $directoryName, string $disk = 'uploads')
    {
        if (is_null($images) or empty($images)) {
            return null;
        }
        $collection = collect([]);
        foreach (Arr::wrap($images) as $index => $image) {
            $collection->push($this->addImage($image, $directoryName, $disk, null, $index * 10));
        }

        return $collection;
    }


    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     *
     * @return mixed
     */
    public function syncPictures(Request $request, $keepFirstOnly = false)
    {
        $this->removePictures($request);

        $images = array_filter($request->file('images', []));

        if (empty($images)) {
            return false;
        }

        if ($request->get('repeatable_images', !$keepFirstOnly)) {
            return $this->addImages($images, $this->getTable(), "uploads");
        }


        $file = Arr::first($images, function ($value, $key) {
            return isset($value);
        });
        $allFields = array_merge($this->getArrayableAppends(), $this->getFillable());
        $extraText = array_intersect(['full_name', 'title', 'slug'], $allFields)[0] ?? '';
        $slug = empty($extraText) ? '' : '-'.Str::slug($this[$extraText]);

        $fileName = $this->getKey().$slug;

        return $this->syncImage($file, $this->getTable(), "uploads", $fileName);


    }

    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     *
     * @return boolean
     */
    protected function removePictures(Request $request)
    {
        $removeIdsArray = array_filter($request->get('remove_images', []));
        $oldIds = array_filter($request->get('old_images', []));
        $deleteIdsArray = $this->images->whereNotIn('id', $oldIds)->pluck('id')->toArray();

        if (!empty($ids = array_merge($removeIdsArray, $deleteIdsArray))) {
            $this->getModel()::deleteIds($ids);

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getModel(): string
    {
        return config('baseAdmin.config.models.namespace').'MediaModels\\ImageModel';
    }

}
