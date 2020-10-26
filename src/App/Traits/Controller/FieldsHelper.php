<?php

namespace GeoSot\BaseAdmin\App\Traits\Controller;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait FieldsHelper
{
    protected $dbData = null;

    /**
     * @param Builder    $query
     * @param Collection $fieldData
     *
     * @return bool
     */
    private function fieldExists(Builder $query, Collection $fieldData)
    {
        $table = $this->dbData->get('modelTable') ?? $this->dbData->put('modelTable', Schema::getColumnListing($query->getModel()->getTable()))->get('modelTable');

        if (in_array($fieldData->get('column'), $table) and !$fieldData->has('relationName')) {
            return true;
        }
        if ($fieldData->has('relationName')) {
            $relTable = $fieldData->get('table');
            $tableColumns = $this->dbData->get('tables')->get($relTable) ?? $this->dbData->get('tables')->put($relTable, Schema::getColumnListing($relTable))->get($relTable);
            if (in_array($fieldData->get('column'), $tableColumns)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Builder    $query
     * @param Collection $fieldData
     *
     * @return bool
     */
    private function isAppended(Builder $query, Collection $fieldData)
    {
        $model = $query->getModel();

        if ($fieldData->has('relationName')) {
            $className = $fieldData->get('fullClass');
            $model = new $className();
        }

        return Arr::has($model, $fieldData->get('column'));
    }

    /**
     * @param Builder $query
     * @param string  $field
     *
     * @return Collection
     */
    private function getFieldData(Builder $query, string $field)
    {
        if (is_null($this->dbData)) {
            $this->dbData = collect(['tables' => collect([])]);
        }
        $fieldData = explode('.', $field);
        $col = collect(['column' => last($fieldData)]);

        if (isset($fieldData[1])) {
            $relation = $query->getRelation(head($fieldData));
            $col->put('table', $relation->getRelated()->getTable());
            $col->put('fullClass', $relation->getRelated()->getMorphClass());
            $col->put('shortClass', class_basename($relation->getRelated()->getMorphClass()));
            $col->put('relationName', head($fieldData));
            $col->put('relationType', class_basename($relation));
            $col->put('joins', collect([
                'query'          => $relation->getQuery(),
                'queryAsString'  => $relation->getQuery()->toSql(),
                'parentTable'    => $this->_hydratedModel->getTable(),
                'parentKeyFull'  => $relation->getQualifiedParentKeyName(),
                'parentKey'      => $relation->getParent()->getKeyName(),
                'foreignKey'     => method_exists($relation, 'getForeignKeyName') ? $relation->getForeignKeyName() : '',
                'foreignKeyFull' => method_exists($relation, 'getExistenceCompareKey') ? $relation->getExistenceCompareKey() : '',
            ]));

            $this->makeQueryJoins($query, head($fieldData));
        } else {
            $col->put('table', $query->getQuery()->from);
        }

        $col->put('columnFullName', $col->get('table').'.'.$col->get('column'));
        $fieldExists = $this->fieldExists($query, $col);
        $col->put('exists', $fieldExists);
        $col->put('isAppended', $fieldExists ? false : $this->isAppended($query, $col));

        return $col;
    }

    /**
     * @param Builder    $query
     * @param Collection $fieldData
     * @param            $sort
     *
     * @return Builder
     */
    private function sortByRelation(Builder $query, Collection $fieldData, $sort)
    {
        $joins = $fieldData->get('joins');
        $query->selectSub($joins['query']->where($joins['parentTable'].'.'.$joins['parentKey'], '=', DB::raw($fieldData->get('table').'.'.$joins['foreignKey']))
            ->select($fieldData->get('column'))->limit(1), 'pseudo-'.$fieldData->get('column'));

        return $query->orderBy('pseudo-'.$fieldData->get('column'), $sort);
    }

    /**
     * @param Builder $query
     * @param string  $relation
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
