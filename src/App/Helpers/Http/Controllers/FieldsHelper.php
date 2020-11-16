<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;



use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FieldsHelper
{
    protected $dbTables;

    /**
     * @var Collection
     */
    protected $fields;



    public function __construct(Model $relatedModel)
    {
        $this->fields = collect([]);


        $modelTable = $relatedModel->getTable();
        $this->dbTables = collect([$modelTable => Schema::getColumnListing($modelTable)]);
    }

    /**
     * @param  Field|FieldRelated  $field
     *
     * @return bool
     */

    private function fieldExists(Field $field): bool
    {
        return in_array($field->column, $this->dbTables->get($field->table));
    }


    /**
     * @param  Builder  $query
     * @param  string  $fieldName
     *
     * @return Field|FieldRelated
     */
    public function getField(Builder $query, string $fieldName)
    {
        if ($this->fields->has($fieldName)) {
            return $this->fields->get($fieldName);
        }

        $field = Str::contains($fieldName, '.')
            ? new FieldRelated($fieldName, $query)
            : new Field($fieldName, $query);


        if ($field->isRelated()) {
            $this->makeQueryJoins($query, $field->relationName);
            if (!$this->dbTables->has($field->table)) {
                $this->dbTables->put($field->table, Schema::getColumnListing($field->table));
            }
        }

        $field->exists = $this->fieldExists($field);
        $field->isAppended = $field->exists ? false : Arr::has($field->newInstance, $field->column);

        $this->fields->put($fieldName, $field);
        return $field;

    }

    /**
     * @param  Builder  $query
     * @param  FieldRelated  $field
     * @param  string  $sort
     *
     * @return Builder
     */
    public function sortByRelation(Builder $query, FieldRelated $field, string $sort)
    {
        if (!in_array($field->relationType, ['HasOne'])) {
            return $query;
        }
        $joins = $field->joins;
        $query->selectSub($joins->query->where("{$joins->parentTable}'.'{$joins->parentKey}", '=', DB::raw("{$field->table}.{$joins->foreignKey}"))
            ->select($field->column)->limit(1), 'pseudo-'.$field->column);

        return $query->orderBy('pseudo-'.$field->column, $sort);

    }


    /**
     * @param  Builder  $query
     * @param  string  $relation
     *
     * @return Builder
     */
    private function makeQueryJoins(Builder $query, string $relation)
    {
        if (!in_array($relation, array_keys($query->getEagerLoads()))) {
            $query->with([$relation]);
        }

        return $query;
    }

}
