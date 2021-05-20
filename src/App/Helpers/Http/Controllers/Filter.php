<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;


use Illuminate\Support\Arr;

class Filter
{

    public const SELECT = 'select';
    public const MULTI_SELECT = 'multiSelect';
    public const DATE_TIME = 'dateTime';
    public const DATE_RANGE = 'dateRange';
    public const BOOLEAN = 'boolean';
    public const HAS_VALUE = 'hasValue';
    /**
     * @var array
     */
    private $values = [];
    /**
     * @var string
     */
    private $property;

    /**
     * if we use a  property of a relation  to our model, we can define the column we are intending to search
     * @var string
     */
    private $relatedKey = 'id';
    /**
     * @var string
     */
    private $type;

    public function __construct(string $property, string $type)
    {
        $this->property = $property;
        $this->type = $type;
    }


    public static function select(string $property): self
    {
        return new self($property, static::SELECT);
    }

    public static function selectMulti(string $property): self
    {
        return new self($property, static::MULTI_SELECT);
    }

    public static function dateRange(string $property): self
    {
        return new self($property, static::DATE_RANGE);
    }

    public static function dateTime(string $property): self
    {
        return new self($property, static::DATE_TIME);
    }

    public static function boolean(string $property): self
    {
        return new self($property, static::BOOLEAN);
    }

    public static function hasValue(string $property): self
    {
        return new self($property, static::HAS_VALUE);
    }


    /**
     * Give predefined Values
     * @param  array  $values
     * @return Filter
     */
    public function values(array $values): Filter
    {
        $this->values = $values;
        return $this;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function hasValues(): bool
    {
        return !empty($this->values);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param  mixed  ...$types
     * @return boolean
     */
    public function isType(...$types): bool
    {
        return in_array($this->type, Arr::flatten($types));
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->property;
    }


    /**
     * foreign Column name that is used as a key in our Entity (usually :id)
     * @return string
     */
    public function getRelatedKey(): string
    {
        return $this->relatedKey ;
    }

    /**
     * @param  string  $relatedKey
     * @return Filter
     */
    public function relatedKey(string $relatedKey): self
    {
        $this->relatedKey = $relatedKey;
        return $this;
    }


}
