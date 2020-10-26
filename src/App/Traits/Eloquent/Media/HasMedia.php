<?php

namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;

use App\Models\Media\Medium;
use GeoSot\BaseAdmin\Helpers\Alert;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Plank\Mediable\Media;
use Plank\Mediable\Mediable;
use Plank\Mediable\MediaUploaderFacade;

trait HasMedia
{
    use Mediable;

    /**
     * Relation of Images to parent model. Morph Many To Many relationship
     * Get all images related to the parent model.
     *
     * @return MorphToMany
     */
    public function images()
    {
        return $this->media()->whereIn('aggregate_type', [Medium::TYPE_IMAGE, Medium::TYPE_IMAGE_VECTOR]);
    }

    /**
     * Relation of Videos to parent model. Morph Many To Many relationship
     * Get all videos related to the parent model.
     *
     * @return MorphToMany
     */
    public function videos()
    {
        return $this->media()->whereIn('aggregate_type', [Medium::TYPE_VIDEO]);
    }

    /**
     * Save a Users Profile Picture.
     *
     * @param Request $request
     * @param bool    $keepFirstOnly
     * @param string  $requestFieldName *
     * @param array   $tags
     *
     * @return Media|Collection|null
     */
    final public function syncRequestMedia(Request $request, bool $keepFirstOnly = false, string $requestFieldName = Medium::REQUEST_FIELD_NAME__FILE, array $tags = [])
    {
        $supportedTypes = [
            Medium::REQUEST_FIELD_NAME__IMAGE => [Medium::TYPE_IMAGE, Medium::TYPE_IMAGE_VECTOR],
            Medium::REQUEST_FIELD_NAME__VIDEO => [Medium::TYPE_VIDEO],
        ];

        //Remove Old Attached Files
        $this->removeRequestMedia($request, $requestFieldName, $tags);

        $files = array_filter($request->file($requestFieldName, []));

        if (empty($files)) {
            return null;
        }

        // ---
        try {
            $mediaUploader = MediaUploaderFacade::toDirectory($this->getTable())
                ->setAllowedAggregateTypes(Arr::get($supportedTypes, $requestFieldName, []));

            //SAVE MANY
            if ($request->get("repeatable_$requestFieldName") && !$keepFirstOnly) {
                $mediaCollection = \Illuminate\Database\Eloquent\Collection::make($files)->map(function (UploadedFile $file) use ($mediaUploader) {
                    return $mediaUploader->fromSource($file)->upload();
                });

                $this->attachMedia($mediaCollection, $requestFieldName);

                return $mediaCollection;
            }

            //SAVE ONE
            $firstMedium = Arr::first($files, function ($value, $key) {
                return isset($value);
            });
            $medium = $mediaUploader->fromSource($firstMedium)->useFilename($this->getAPossibleMediaFilename())->upload();
            $this->syncMedia($medium, $requestFieldName);

            return $medium;
        } catch (\Exception $e) {
            if (isset($mediaCollection)) {
                Medium::destroy($mediaCollection);
            }
            if (isset($medium)) {
                Medium::destroy($medium);
            }
            $fqn = class_basename($this);

            $msg = __CLASS__." Media for Model {$fqn} {$this->getKey()}  was not saved";
            Alert::error($msg);
            Log::error($msg, ['msg' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * @param Request $request
     * @param string  $requestFieldName
     * @param array   $tags
     *
     * @return bool
     */
    final protected function removeRequestMedia(Request $request, string $requestFieldName = Medium::REQUEST_FIELD_NAME__FILE, array $tags = [])
    {
        $removeIdsArray = array_filter($request->get("remove_$requestFieldName", []));
        $oldIds = array_filter($request->get("old_$requestFieldName", []));
        $deleteIdsArray = $this->media()->whereNotIn('id', $oldIds)->pluck('id')->toArray();

        if (!empty($ids = array_merge($removeIdsArray, $deleteIdsArray))) {
            $this->detachMedia($ids, array_merge([$requestFieldName], $tags));

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getAPossibleMediaFilename(): string
    {
        $allFields = array_intersect(['first_name', 'last_name', 'title', 'slug'], $this->getFillable());

        $text = array_map(function ($field) {
            return optional($this)->{$field};
        }, $allFields);

        $parts = array_merge([$this->getKey()], $text, [now()->toDateTimeString()]);

        return Str::slug(implode('-', $parts));
    }
}
