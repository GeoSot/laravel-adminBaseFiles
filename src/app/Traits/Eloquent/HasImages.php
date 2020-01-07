<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use App\Models\Media\MediumImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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
        return $this->morphMany(MediumImage::class, 'model');
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
     * @return MediumImage
     */
    public function syncImage($img = null, string $directoryName, string $disk = "uploads", string $displayName = null, int $order = null)
    {
        $this->deleteAssociateImages();
        return $this->addImage($img, $directoryName, $disk, $displayName, $order);
    }

    private function deleteAssociateImages()
    {
        $this->images()->each(function (Model $model) {
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
     * @return MediumImage
     */
    public function addImage($img = null, string $directoryName, string $disk = "uploads", string $displayName = null, int $order = null)
    {
        if (is_null($img)) {
            return null;
        }

        $data = (new MediumImage())->fillData($this, $img, $directoryName, $disk, $displayName, $order);

        return MediumImage::create($data);
    }


    /**
     * @param  Collection  $images
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @return Collection|null
     */
    public function syncImages(Collection $images, string $directoryName, string $disk = 'uploads')
    {
        $this->deleteAssociateImages();

        return $this->addImages($images, $directoryName, $disk);
    }

    /**
     * @param  Collection  $images
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addImages(Collection $images, string $directoryName, string $disk = 'uploads')
    {
        if ($images->isEmpty()) {
            return null;
        }
        $collection = collect([]);
        foreach ($images as $index => $image) {
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
        $deleteIdsArray = $this->images()->whereNotIn('id', $oldIds)->pluck('id')->toArray();

        if (!empty($ids = array_merge($removeIdsArray, $deleteIdsArray))) {
            MediumImage::deleteIds($ids);

            return true;
        }

        return false;
    }


}
