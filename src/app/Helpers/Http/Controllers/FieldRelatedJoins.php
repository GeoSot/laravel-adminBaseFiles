<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class FieldRelatedJoins
{
    /**
     * @var Builder
     */
    public $query;
    /**
     * @var string
     */
    public $queryAsString;
    /**
     * @var string
     */
    public $parentTable;
    /**
     * @var string
     */
    public $parentKeyFull;
    /**
     * @var string
     */
    public $parentKey;
    /**
     * @var string
     */
    public $foreignKey;
    /**
     * @var string
     */
    public $foreignKeyFull;

    public function __construct(Relation $relation, string $parentTable)
    {

        $this->query = $relation->getQuery();

        $this->queryAsString = $relation->getQuery()->toSql();

        $this->parentTable = $parentTable;

        $this->parentKeyFull = $relation->getQualifiedParentKeyName();

        $this->parentKey = $relation->getParent()->getKeyName();

        $this->foreignKey = method_exists($relation, 'getForeignKeyName') ? $relation->getForeignKeyName() : '';

        $this->foreignKeyFull = method_exists($relation, 'getExistenceCompareKey') ? $relation->getExistenceCompareKey() : '';

    }
}
