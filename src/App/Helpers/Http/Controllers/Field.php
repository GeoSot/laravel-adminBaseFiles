<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;


use GeoSot\BaseAdmin\App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Field
{
    /**
     * @var string
     */
    public $column;
    /**
     * @var string
     */
    public $columnFullName;
    /**
     * @var string
     */
    public $table;
    /**
     * @var string
     */
    public $fullClass;
    /**
     * @var string
     */
    public $shortClass;

    /** Dummy Instance
     * @var BaseModel
     */
    public $newInstance;
    /**
     * @var bool
     */
    public $exists = false;
    /**
     * @var string
     */
    public $isAppended = false;
    /**
     * @var Builder
     */
    protected $query;


    /**
     * Field constructor.
     * @param  string  $column
     * @param  Builder  $query
     */
    public function __construct(string $column, Builder $query)
    {
        $this->column = $column;
        $this->query = $query;
        $this->table = $this->getInitialTable();
        $this->columnFullName = $this->getColumnFullName();

        $this->fillModelThings($query->getModel());
    }

    protected function getColumnFullName(): string
    {
        return "{$this->table}.{$this->column}";
    }

    /**
     * @return string
     */
    protected function getInitialTable(): string
    {
        return $this->query->getQuery()->from;
    }


    /**
     * @param  Model  $model
     */
    protected function fillModelThings(Model $model): void
    {
        $this->newInstance = $model;
        $this->fullClass = $this->newInstance->getMorphClass();
        $this->shortClass = class_basename($this->fullClass);

    }
}
