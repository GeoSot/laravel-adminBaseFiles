<?php

namespace GeoSot\BaseAdmin\App\Traits\Controller;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait FiltersHelper
{
    protected function filters()
    {

        //           return [
        //            'group'      => ['type' => 'select'],
        //            'sub_group'   => ['type' => 'multiSelect'],
        //            'created_at' => ['type' => 'dateRange'],
        //            'is_bool' => ['type' => 'boolean'],
        //            'has_value' => ['type' => 'hasValueInside'],
        //            'created_At' => ['type' => 'dateRange'],
        //        ];
        //
        return [];
    }

    /**
     * @return Collection
     */
    private function getExtraFiltersData()
    {
        $filters = $this->filters();
        $query = (new $this->_class())->newQuery(); ///newModelQuery

        foreach ($filters as $key => $filterVals) {
            if (in_array($filterVals['type'], ['select', 'multiSelect'])) {
                $filters[$key]['values'] = $this->getValuesForSelectTypeFilters($this->getFieldData($query, $key), $key);
            }
        }

        return collect($filters);
    }

    /**
     * @param $fieldDt
     * @param $key
     *
     * @return array|mixed
     */
    private function getValuesForSelectTypeFilters(Collection $fieldDt, string $key)
    {
        if (Arr::has($this->filters()[$key], 'values')) {
            return Arr::get($this->filters()[$key], 'values');
        }
        if ($fieldDt->get('exists') and !$fieldDt->has('relationName')) {
            return $this->_hydratedModel->newQuery()->groupBy($key)->pluck($key, $key)->toArray(); //newModelQuery
        }
        if ($fieldDt->has('relationName')) {
            return $fieldDt->get('fullClass')::all()->pluck($fieldDt->get('column'), Arr::get($this->filters()[$key], 'relation_key', 'id'))->unique();
        }

        return [];
    }

    /**
     * @param Request $request
     *
     * @return Collection
     */
    private function getExtraFiltersParams(Request $request)
    {
        $params = collect([]);
        $requestFilters = $request->input('extra_filters', []);

        foreach ($this->filters() as $key => $filterVals) {
            $value = Arr::get($requestFilters, $key);
            if ($filterVals['type'] == 'dateTime' and !is_null($value)) {
                $value = strtotime($value) ? Carbon::parse($value) : null;
            }
            if ($filterVals['type'] == 'dateRange' and !is_null($value)) {
                $value = array_map(function ($el) {
                    return strtotime($el) ? Carbon::parse($el) : null;
                }, $value);
            }
            $params->put($key, $value);
        }

        return $params;
    }

    /**
     * @param Collection $params
     * @param            $query
     */
    private function applyExtraFiltersOnModel(Collection $params, &$query)
    {
        $filteredParams = $params->get('extra_filters')->filter(function ($it) {
            return is_array($it) ? !empty(array_filter($it)) : !is_null($it);
        });
        $filteredFilters = collect($this->filters())->intersectByKeys($filteredParams);
        foreach ($filteredFilters as $field => $data) {
            $fieldDt = $this->getFieldData($query, $field);
            $value = $filteredParams->get($field);

            //NOT RELATIONSHIP
            if (!$fieldDt->has('relationName') and $fieldDt->get('exists')) {
                $query = $this->makeExtraFiltersQuery($query, $data, $field, $value, $field);
            }

            // RELATIONSHIP FIELD
            if ($fieldDt->has('relationName') and ($fieldDt->get('exists') or $fieldDt->get('isAppended'))) {
                $query->whereHas($fieldDt->get('relationName'), function ($q) use ($fieldDt  , $data, $value) {
                    $foreignTableColumn = $fieldDt->get('table').'.'.Arr::get($data, 'relation_key', 'id');

                    return $this->makeExtraFiltersQuery($q, $data, $fieldDt->get('column'), $value, $foreignTableColumn);
                });
            }
        }
    }

    /**
     * @param Builder $query
     * @param string  $column
     * @param         $value
     *
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    private function askDate(Builder $query, string $column, $value)
    {
        return $query->whereDate($column, optional($value)->toDateString());
    }

    /**
     * @param Builder $query
     * @param string  $field
     * @param         $value
     *
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    private function askHasValueInside(Builder $query, string $field, $value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) ?
            $query->whereNotNull($field) : $query->whereNull($field);
    }

    /**
     * @param Builder $query
     * @param string  $field
     * @param         $value
     *
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    private function askDateRange(Builder $query, string $field, $value)
    {
        return $query->whereDate($field, '>', optional(Arr::get($value, 'start'))->toDateString())
            ->whereDate($field, '<', optional(Arr::get($value, 'end'))->toDateString());
    }

    /**
     * @param Builder $query
     * @param         $data
     * @param         $field
     * @param         $value
     * @param         $fallBackWhereIn
     *
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    private function makeExtraFiltersQuery(Builder $query, $data, string $field, $value, string $fallBackWhereIn = null)
    {
        $type = Arr::get($data, 'type');
        if ('dateTime' == $type) {
            return $this->askDate($query, $field, $value);
        }
        if ('dateRange' == $type) {
            return $this->askDateRange($query, $field, $value);
        }
        if ('hasValueInside' == $type) {
            return $this->askHasValueInside($query, $value, $field);
        }
        if ($fallBackWhereIn) {
            return $query->whereIn($fallBackWhereIn, (array) $value);
        }

        return $query;
    }
}
