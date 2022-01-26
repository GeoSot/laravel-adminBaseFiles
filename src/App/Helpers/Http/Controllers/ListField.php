<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ListField
{


    /**
     * @var callable|null
     */
    protected $callback;
    private string $property;

    public function __construct(string $property, callable $callback = null)
    {
        $this->property = $property;
        $this->callback = $callback;
    }


    public static function make($arg): self
    {
        if (is_string($arg)) {
            return static::new($arg);
        }
        if ($arg instanceof ListField) {
            return $arg;
        }
        throw new \Exception("ListField couldn't be parsed");
    }

    public static function new(string $property, callable $callback = null): self
    {
        return new self($property, $callback);
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function parseValue(Model $model, Collection $viewVals): ?string
    {
        $parser = new ListFieldParser($this->property, $model, $viewVals);
        return is_callable($this->callback) ? $parser->getValue($this->callback) : $parser->getValue();
    }
}
