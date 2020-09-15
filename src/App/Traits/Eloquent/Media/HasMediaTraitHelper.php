<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;


use GeoSot\BaseAdmin\App\Models\Media\Medium;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Plank\Mediable\Media;
use Plank\Mediable\Mediable;
use Plank\Mediable\MediaUploaderFacade;

class HasMediaTraitHelper
{

    /**
     * @var string
     */
    private $modelType;

    /**
     * @var Model|Mediable
     */
    private $ownerModel;
    private $tags=[];
    /**
     * @var []
     */
    private $supportedTypes;

    /**
     * HasMediaTraitHelper constructor.
     * @param  Model  $OwnerModel
     * @param  array  $types
     */
    public function __construct(Model $OwnerModel, array $types=[])
    {
        $this->ownerModel = $OwnerModel;
        $this->supportedTypes = $types;
    }

    /**
     * @mixin Model
     */
    private function getOwnerModel()
    {
        return $this->ownerModel;
    }


    public function getTags($extraTags = [])
    {
        return array_merge($this->tags, Arr::wrap($extraTags));
    }


    /**
     * @return bool
     */
    public function hasRelation()
    {
        return $this->relation()->count() > 0;
    }

    /**
     * Relation of Medium to parent model. Morph Many To Many relationship
     * Get all media related to the parent model.
     *
     * @return MorphToMany
     */
    public function relation()
    {
        return $this->getOwnerModel()->media()->whereIn('aggregate_type', $this->supportedTypes);

    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeMediaEnabled(Builder $builder)
    {
        return $builder/*->where('enabled', true)*/ ->where('the_file_exists', true);
    }


    public function deleteAssociateMedia()
    {
        $this->relation()->detachMediaTags($this->getTags());
    }


    /**
     * Replace the existing media collection for the specified tag(s).
     * @param  string|int|Media|\Illuminate\Database\Eloquent\Collection  $media
     * @param  string|string[]  $tags
     * @return void
     */
    public function syncMedia($media, $tags = [])
    {
        return $this->getOwnerModel()->syncMedia($media, $this->getTags($tags));
    }

    /**
     * Attach a media entity to the model with one or more tags.
     * @param  string|int|Media|\Illuminate\Database\Eloquent\Collection  $media  Either a string or numeric id, an array of ids, an instance of `Media` or an instance of `Collection`
     * @param  string|string[]  $tags  One or more tags to define the relation
     * @return void
     */
    public function attachMedia($media, $tags = [])
    {
        $this->getOwnerModel()->attachMedia($media, $this->getTags($tags));
    }


    /**
     * Save a Users Profile Picture
     *
     * @param  Request  $request
     * @param  bool  $keepFirstOnly
     * @param  string  $requestFieldName
     *
     * @return void
     */
    final public function syncRequestMedia(Request $request, bool $keepFirstOnly, string $requestFieldName)
    {

        $this->removeRequestMedia($request, $requestFieldName);

        $media = array_filter($request->file($requestFieldName, []));

        if (empty($media)) {
            return null;
        }

        if ($request->get("repeatable_$requestFieldName") && !$keepFirstOnly) {
            $this->attachMedia($media, $requestFieldName);
            return;
        }

        $firstMedium = Arr::first($media, function ($value, $key) {
            return isset($value);
        });


        $mediaUploader = MediaUploaderFacade::fromSource($firstMedium)->toDirectory($this->getOwnerModel()->getTable())->setAllowedAggregateTypes($this->supportedTypes);
        $fileName = $this->getAPossibleFilename();

        $fileName ? $mediaUploader->useFilename($fileName) : $mediaUploader->useHashForFilename();


        $this->syncMedia($mediaUploader->upload(), $requestFieldName);
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
        $deleteIdsArray = $this->getOwnerModel()->media()->whereNotIn('id', $oldIds)->pluck('id')->toArray();


        if (!empty($ids = array_merge($removeIdsArray, $deleteIdsArray))) {
            $this->getOwnerModel()->detachMedia($ids, $requestFieldName);
            return true;
        }

        return false;
    }


    /**
     * @return string
     */
    public function getAPossibleFilename(): string
    {
        $allFields = array_intersect(['first_name', 'last_name', 'title', 'slug'], $this->getOwnerModel()->getFillable());

        $extraText = array_map(function ($field) {
            return optional($this->getOwnerModel())->{$field};
        }, $allFields);

        $slug = empty($extraText) ? '' : '-'.Str::slug(implode('-', $extraText));

        return $this->getOwnerModel()->getKey().$slug;

    }


}
