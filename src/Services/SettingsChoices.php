<?php
/**
 * by GeoSv.
 * User: gsoti
 * Date: 20/8/2018
 * Time: 12:02 μμ
 */

namespace GeoSot\BaseAdmin\Services;


use App\Models\Media\Medium;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Plank\Mediable\Media;

/**
 * Class Settings
 */
class SettingsChoices
{

    public const STRING = 'string';
    public const BOOLEAN = 'boolean';
    public const TEXTAREA = 'textarea';
    public const NUMBER = 'number';
    public const TIME_TO_MINUTES = 'timeToMinutes';
    public const DATETIME = 'dateTime';
    public const DATE_RANGE = 'dateRange';
    public const COLLECTION_STRING = 'collectionString';
    public const COLLECTION_NUMBER = 'collectionNumber';
    public const MEDIUM = Medium::class;
    public const IMAGE = Media::TYPE_IMAGE;


    /**
     * @return array<string, string>
     */
    public static function getSettingTypes(): array
    {
        return [
            static::STRING => 'String',
            static::TEXTAREA => 'Textarea',
            static::BOOLEAN => 'Boolean',
            static::NUMBER => 'Number',
            static::TIME_TO_MINUTES => 'Time To Minutes',
            static::DATETIME => 'DateTime',
            static::DATE_RANGE => 'Date Range',
            static::COLLECTION_STRING => 'Collection of Strings',
            static::COLLECTION_NUMBER => 'Collection of Numbers',
            static::MEDIUM => 'Media',
            static::IMAGE => 'Image',
        ];
    }


    public static function parseValue(string $type, $value)
    {
        if (!$value || empty($value)) {
            return null;
        }

        if (self::typeIs($type, ['number', 'timeToMinutes'])) {
            return (float) $value;
        }

        if (self::typeIs($type, [SettingsChoices::COLLECTION_STRING, SettingsChoices::COLLECTION_NUMBER])) {
            return array_map(fn($it) => self::typeIs($type, SettingsChoices::COLLECTION_NUMBER) ? (float) $it : $it, json_decode($value, true));
        }

        if (self::typeIs($type, self::DATETIME)) {
            return Carbon::parse($value);
        }

        if (self::typeIs($type, self::DATE_RANGE)) {
            return CarbonPeriod::createFromIso($value);
        }

        if (self::typeIs($type, self::BOOLEAN)) {
            return (bool) $value;
        }

        if (self::typeIs($type, Medium::class)) {
            /* @var Medium $type */
            return $type::find($value);
        }

        return $value;

    }

    protected static function typeIs(string $type, $possibleTypes): bool
    {
        return in_array($type, Arr::wrap($possibleTypes));
    }
}
