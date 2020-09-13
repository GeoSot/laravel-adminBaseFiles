<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;


use GeoSot\BaseAdmin\App\Models\Media\BaseMediaModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HasMediaTraitHelper
{
    /**
     * @var  BaseMediaModel
     */
    private $modelFQN;

    /**
     * @var string
     */
    private $modelType;

    /**
     * @var Model
     */
    private $ownerModel;

    /**
     * HasMediaTraitHelper constructor.
     * @param  Model  $OwnerModel
     * @param  string  $modelType
     * @param  string  $modelFQN
     */
    public function __construct(Model $OwnerModel, string $modelType, string $modelFQN)
    {
        $this->modelType = $modelType;
        $this->modelFQN = $modelFQN;
        $this->ownerModel = $OwnerModel;
        $this->testSubClassOfBaseMediaModel($this->getModelFQN());

    }

    /**
     * @mixin BaseMediaModel
     */
    private function getOwnerModel()
    {
        return $this->ownerModel;
    }

    /**
     * @return  BaseMediaModel
     */
    private function getModelFQN()
    {
        return $this->modelFQN;
    }

    /**
     * @param  bool  $plural
     * @param  string  $prefix
     * @return string
     */
    public function getModelType(bool $plural = false, string $prefix = '')
    {
        return $prefix.($plural ? Str::plural($this->modelType) : $this->modelType);
    }

    /**
     * @return bool
     */
    public function hasMedia()
    {
        return $this->getOwnerModel()->{$this->getModelType(true)}()->isNotEmpty();
    }

    /**
     * Relation of Medium to parent model. Morph Many To Many relationship
     * Get all media related to the parent model.
     *
     * @return MorphToMany
     */
    public function media()
    {
//        return $this->getOwnerModel()->morphMany($this->morphToMany(), 'mediable');
        return $this->getOwnerModel()->morphToMany($this->getModelFQN(), 'mediable', 'mediables', 'media_id',
            'mediable_id', 'mediable_type');

    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function mediaEnabled(Builder $builder)
    {
        return $builder->where('enabled', true)->where('the_file_exists', true);
    }


    /**
     * Sync An medium to  model
     *
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @param  string  $displayName  *
     * @param  integer  $order
     * @return BaseMediaModel|null
     */
    public function syncMedium(
        string $directoryName,
        string $disk,
        string $displayName = null,
        int $order = null
    ) {
        $this->deleteAssociateMedia();
        return $this->getOwnerModel()->{$this->getModelType(false, 'add')}($directoryName, $disk, $displayName,
            $order);
    }

    public function deleteAssociateMedia()
    {
        $this->getOwnerModel()->{$this->getModelType(true)}()->detach();
        /*      $this->getOwnerModel()->{$this->getModelType(true)}()->each(function (Model $model) {
                  $model->delete();
              });*/
    }

    /**
     * .Add An medium to  model
     *
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @param  string  $displayName  *
     * @param  integer  $order
     * @return BaseMediaModel
     */
    public function addMedium(
        string $directoryName,
        string $disk,
        string $displayName = null,
        int $order = null
    ) {

        if (is_null($medium)) {
            return null;
        }
        /* @var BaseMediaModel $modelInstance */
        $modelInstance = app($this->getModelFQN());

        $data = $modelInstance->fillData($this->getOwnerModel(), $medium, $directoryName, $disk, $displayName, $order);

        return $this->getModelFQN()::create($data);

        $this->{$this->getModelType(true)}()->attach([$this->getOwnerModel()->getKey()]);
    }


    /**
     * @param  Collection  $media
     * @param  string  $directoryName
     * @param  string  $disk
     *
     * @return Collection|null
     */
    public function syncMedia(Collection $media, string $directoryName, string $disk)
    {
        $this->deleteAssociateMedia();

        return $this->{$this->getModelType(true, 'add')}($media, $directoryName, $disk);
    }

    /**
     * @param  Collection  $media
     * @param  string  $directoryName
     * @param  string  $disk
     * @return Collection|null
     */
    public function addMedia(Collection $media, string $directoryName, string $disk)
    {
        if ($media->isEmpty()) {
            return null;
        }
        $collection = collect([]);
        foreach ($media as $index => $medium) {
            $collection->push($this->{$this->getModelType(false, 'add')}($medium, $directoryName, $disk, null,
                $index * 10));
        }

        return $collection;
    }


    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     *
     * @return BaseMediaModel|null
     */
    final public function syncRequestMedia(Request $request, $keepFirstOnly, string $requestFieldName)
    {

        $this->removeRequestMedia($request, $requestFieldName);

        $media = array_filter($request->file($requestFieldName, []));

        if (empty($media)) {
            return null;
        }

        if ($request->get("repeatable_$requestFieldName", !$keepFirstOnly)) {
            return $this->addMedia($media, $this->getOwnerModel()->getTable(), "uploads");
        }

        $file = Arr::first($media, function ($value, $key) {
            return isset($value);
        });

        $allFields = array_intersect(['first_name', 'last_name', 'title', 'slug'],
            $this->getOwnerModel()->getFillable());

        $extraText = array_map(function ($field) {
            return optional($this->getOwnerModel())->{$field};
        }, $allFields);

        $slug = empty($extraText) ? '' : '-'.Str::slug(implode('-', $extraText));

        $fileName = $this->getOwnerModel()->getKey().$slug;

        return $this->syncMedium($file, $this->getOwnerModel()->getTable(), "uploads", $fileName);
    }

    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     * @param  string  $requestFieldName
     * @return boolean
     */
    final public function removeRequestMedia(Request $request, string $requestFieldName)
    {

        $removeIdsArray = array_filter($request->get("remove_$requestFieldName", []));
        $oldIds = array_filter($request->get("old_$requestFieldName", []));
        $deleteIdsArray = $this->getOwnerModel()->{$this->getModelType(true)}()->whereNotIn('id',
            $oldIds)->pluck('id')->toArray();

        if (!empty($ids = array_merge($removeIdsArray, $deleteIdsArray))) {
            $this->getModelFQN()::deleteIds($ids);

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
