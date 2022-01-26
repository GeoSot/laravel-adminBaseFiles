<?php

namespace GeoSot\BaseAdmin\Helpers;

use Illuminate\Support\Arr;

class Uploads
{


    public static function getUppyOptions($allowedFileTypes = '*/*'): array
    {
        return [
            'endpoint' => static::getMediaUploadPath(),
            'restrictions' => [
                'maxFileSize' => 100000000,
                'maxNumberOfFiles' => 10,
                'allowedFileTypes' => Arr::wrap($allowedFileTypes),
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
}
