<?php

namespace GeoSot\BaseAdmin\Helpers;

use Illuminate\Support\Arr;
use Plank\Mediable\Media;

class Uploads
{


    public static function getUppyOptions($allowedFileTypes): array
    {
        return [
            'endpoint' => static::getMediaUploadPath(),
            'restrictions' => [
                'maxFileSize' => 100000000,
                'maxNumberOfFiles' => 10,
                'allowedFileTypes' => static::parseAllowedTypes($allowedFileTypes),
            ],
        ];
    }

    public static function getDefaultDisk(): string
    {
        return config('mediable.default_disk', 'public');
    }

    public static function getMediaUploadPath(bool $absolute = true): string
    {
        return route('admin.media.upload', [], $absolute);
    }

    /**
     * @param  string|array<string>  $allowedFileTypes
     * @return string[]
     */
    private static function parseAllowedTypes($allowedFileTypes): array
    {
        $accepted = collect(Arr::wrap($allowedFileTypes))->map(function ($value) {
            switch ($value) {
                case Media::TYPE_IMAGE:
                    return 'image/*';
                case null:
                default:
                    return '*/*';
            }
        });

        return $accepted->toArray();
    }
}
