<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;


use GeoSot\BaseAdmin\App\Models\Media\BaseMediaModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasMediaSubTrait
{

    /**
     * @param  string  $method
     * @return bool
     */
    private function hasMedia(string $method)
    {
        return (boolean) $this->{Str::plural($method)}()->count();
    }

    /**
     * Relation of Medium to parent model. Morph Many To Many relationship
     * Get all media related to the parent model.
     *
     * @param  string  $modelFQN
     * @return MorphMany
     */
    private function media(string $modelFQN)
    {
        $this->testSubClassOfBaseMediaModel($modelFQN);
        return $this->morphMany($modelFQN, 'model');
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    private function mediaEnabled(Builder $builder)
    {
        return $builder->where('enabled', true)->where('the_file_exists', true);
    }


    /**
     * Sync An medium to  model
     *
     * @param  string  $method
     * @param  null  $medium
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @param  string  $displayName  *
     * @param  integer  $order
     * @return BaseMediaModel|null
     */
    private function syncMedium(string $method, $medium = null, string $directoryName, string $disk, string $displayName = null, int $order = null)
    {
        $this->deleteAssociateMedia($method);
        return $this->{"add$method"}($medium, $directoryName, $disk, $displayName, $order);
    }

    private function deleteAssociateMedia(string $method)
    {
        $this->{Str::plural($method)}()->each(function (Model $model) {
            $model->delete();
        });
    }

    /**
     * .Add An medium to  model
     *
     * @param  string  $modelFQN
     * @param  mixed  $medium
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @param  string  $displayName  *
     * @param  integer  $order
     * @return MediumMedium
     */
    private function addMedium(string $modelFQN, $medium = null, string $directoryName, string $disk, string $displayName = null, int $order = null)
    {
        $this->testSubClassOfBaseMediaModel($modelFQN);

        /* @var BaseMediaModel $modelFQN */
        if (is_null($medium)) {
            return null;
        }

        $data = (new $modelFQN())->fillData($this, $medium, $directoryName, $disk, $displayName, $order);

        return $modelFQN::create($data);
    }


    /**
     * @param  string  $method
     * @param  Collection  $media
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @return Collection|null
     */
    private function syncMedia(string $method, Collection $media, string $directoryName, string $disk)
    {
        $this->deleteAssociateMedia($method);

        return $this->{'add'.Str::plural($method)}($media, $directoryName, $disk);
    }

    /**
     * @param  string  $method
     * @param  Collection  $media
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    private function addMedia(string $method, Collection $media, string $directoryName, string $disk)
    {
        if ($media->isEmpty()) {
            return null;
        }
        $collection = collect([]);
        foreach ($media as $index => $medium) {
            $collection->push($this->{"add$method"}($medium, $directoryName, $disk, null, $index * 10));
        }

        return $collection;
    }


    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     * @param  string  $method
     * @param  string  $modelFQN
     * @return BaseMediaModel|null
     */
    final private function syncRequestMedia(Request $request, $keepFirstOnly, string $requestFieldName, string $method, string $modelFQN)
    {
        $this->testSubClassOfBaseMediaModel($modelFQN);
        /* @var Model $thisModel */
        $thisModel = $this;
        $this->removeRequestMedia($request, $requestFieldName, $method, $modelFQN);

        $media = array_filter($request->file($requestFieldName, []));

        if (empty($media)) {
            return null;
        }

        if ($request->get("repeatable_$requestFieldName", !$keepFirstOnly)) {
            return $this->addMedia($method, $media, $thisModel->getTable(), "uploads");
        }


        $file = Arr::first($media, function ($value, $key) {
            return isset($value);
        });
        $allFields = array_merge($thisModel->getArrayableAppends(), $thisModel->getFillable());

        $extraText = Arr::first(array_intersect(['full_name', 'title', 'slug'], $allFields), null, '');
        $slug = empty($extraText) ? '' : '-'.Str::slug($this[$extraText]);
        $fileName = $thisModel->getKey().$slug;

        return $this->syncMedium($method, $file, $thisModel->getTable(), "uploads", $fileName);
    }

    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     * @param  string  $requestFieldName
     * @param  string  $method
     * @param  string  $modelFQN
     * @return boolean
     */
    final private function removeRequestMedia(Request $request, string $requestFieldName, string $method, string $modelFQN)
    {
        $this->testSubClassOfBaseMediaModel($modelFQN);
        /* @var BaseMediaModel $modelFQN */

        $removeIdsArray = array_filter($request->get("remove_$requestFieldName", []));
        $oldIds = array_filter($request->get("old_$requestFieldName", []));
        $deleteIdsArray = $this->{Str::plural($method)}()->whereNotIn('id', $oldIds)->pluck('id')->toArray();

        if (!empty($ids = array_merge($removeIdsArray, $deleteIdsArray))) {
            $modelFQN::deleteIds($ids);

            return true;
        }

        return false;
    }

    /**
     * @param  string  $modelFQN
     */
    private function testSubClassOfBaseMediaModel(string $modelFQN): void
    {
        if (!is_subclass_of($modelFQN, BaseMediaModel::class)) {
            throw new \InvalidArgumentException($modelFQN.' is not ChildClass of '.BaseMediaModel::class);
        }
    }


}
