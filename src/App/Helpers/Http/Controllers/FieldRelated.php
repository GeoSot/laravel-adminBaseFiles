<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class FieldRelated extends Field
{
    /**
     * @var string
     */
    public $relationName;
    /**
     * @var Relation
     */
    public $relation;

    /**
     * @var string
     */
    public $relationType;
    /**
     * @var FieldRelatedJoins
     */
    public $joins;

    public function __construct(string $fieldName, Builder $query)
    {
        $fieldData = explode('.', $fieldName);
        $this->relationName = head($fieldData);

        parent::__construct(last($fieldData), $query);


        $relation = $this->query->getRelation($this->relationName);
        $related = $relation->getRelated();

        $this->fillModelThings($related);

        $this->relation = $relation;
        $this->relationType = class_basename($relation);
        $this->table = $related->getTable();

        $this->joins = new FieldRelatedJoins($relation, $this->getInitialTable());

        $this->columnFullName = $this->getColumnFullName();
    }

    public function isRelated(): bool
    {
        return true;
    }
}
