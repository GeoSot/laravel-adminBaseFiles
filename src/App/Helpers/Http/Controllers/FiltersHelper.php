<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FiltersHelper
{
    public const EXTRA_FILTERS_KEY = 'extra_filters';
    /**
     * @var Collection|Filter[]
     */
    private $filters;

    /**
     * @var
     */
    private $requestParams = [];
    /**
     * @var Model
     */
    private $relatedModel;
    /**
     * @var FieldsHelper
     */
    private $fieldsHelper;


    /**
     * FiltersHelper constructor.
     * @param  Model  $relatedModel
     * @param  FieldsHelper  $fieldsHelper
     * @param  array|Filter []  $filters
     */
    public function __construct(Model $relatedModel, FieldsHelper $fieldsHelper, array $filters)
    {
        $this->filters = collect($filters)->keyBy(function (Filter $filter) {
            return $filter->getKey();
        });
        $this->relatedModel = $relatedModel;
        $this->fieldsHelper = $fieldsHelper;
        $this->requestParams = collect([]);
    }

    /**
     * @param  Request  $request
     *
     * @return Collection
     */
    public function parseParams(Request $request)
    {

        $requestFilters = $request->input(static::EXTRA_FILTERS_KEY, []);

        if (!$requestFilters || $this->requestParams->isNotEmpty()) {
            return $this->requestParams;
        }

        foreach ($this->filters as $filter) {
            $value = Arr::get($requestFilters, $filter->getKey());

            if ($filter->isType(Filter::DATE_TIME) && !is_null($value)) {
                $value = $this->tryParseStringToCarbon($value);
            }

            if ($filter->isType(Filter::DATE_RANGE) && !is_null($value)) {
                $value = array_map(function ($el) {
                    return $this->tryParseStringToCarbon($el);
                }, $value);
            }

            $this->requestParams->put($filter->getKey(), $value);
        }

        return $this->requestParams;
    }

    /**
     * @return Collection
     */
    public function getAvailableData()
    {

        $query = $this->relatedModel->newQuery();

        foreach ($this->filters as $filter) {
            if ($filter->isType(Filter::SELECT, Filter::MULTI_SELECT)) {
                $filter = $this->fillSelectTypeFilter($this->fieldsHelper->getField($query, $filter->getKey()), $filter);
            }
        }

        return $this->filters;
    }


    /**
     * @param $query
     */
    public function applyExtraFiltersOnModel(&$query)
    {
        $filteredParams = $this->requestParams->filter(function ($it) {
            return is_array($it) ? !empty(array_filter($it)) : !is_null($it);
        });
        $filteredFilters = $this->filters->intersectByKeys($filteredParams);

        /** @var Filter $filter */
        foreach ($filteredFilters as $filter) {
            $field = $this->fieldsHelper->getField($query, $filter->getKey());
            $value = $filteredParams->get($filter->getKey());

            //NOT RELATIONSHIP
            if ($field->exists && !$field->isRelated()) {
                $query = $this->makeExtraFiltersQuery($query, $filter, $filter->getKey(), $value, $filter->getKey());
            }

            // RELATIONSHIP FIELD
            if ($field->relationName && ($field->exists || $field->isAppended)) {
                $query->whereHas($field->relationName, function ($q) use ($field, $filter, $value) {
                    $foreignTableColumn = $field->table.'.'.$filter->getRelatedKey();
                    return $this->makeExtraFiltersQuery($q, $filter, $field->column, $value, $foreignTableColumn);
                });
            }
        }

    }


    /**
     * @param  Builder  $query
     * @param  Filter  $filter
     * @param  string  $column
     * @param         $value
     * @param  string|null  $fallBackWhereIn
     *
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    private function makeExtraFiltersQuery(Builder $query, Filter $filter, string $column, $value, string $fallBackWhereIn = null)
    {

        if ($filter->isType(Filter::DATE_TIME)) {
            return $this->askDate($query, $column, $value);
        }
        if ($filter->isType(Filter::DATE_RANGE)) {
            return $this->askDateRange($query, $column, $value);
        }
        if ($filter->isType(Filter::HAS_VALUE)) {
            return $this->askHasValueInside($query, $value, $column);
        }
        if ($fallBackWhereIn) {
            return $query->whereIn($fallBackWhereIn, (array) $value);

        }
        return $query;
    }


    /**
     * @param  Field  $field
     * @param  Filter  $filter
     * @return array|mixed
     */
    private function fillSelectTypeFilter(Field $field, Filter $filter): Filter
    {

        if ($filter->hasValues()) {
            return $filter;
        }
        $values = [];
        $key = $filter->getKey();
        if ($field->exists && !$field->isRelated()) {
            $values = $field->newInstance->newQuery()->groupBy($key)->pluck($key, $key)->toArray();
        }
        if ($field->isRelated()) {
            $values = $field->newInstance::all()->pluck($field->column, $filter->getRelatedKey())->unique()->toArray();
        }

        return $filter->values($values);

    }

    /**
     * @param  Builder  $query
     * @param  string  $column
     * @param         $value
     *
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    private function askDate(Builder $query, string $column, $value)
    {
        return $query->whereDate($column, $this->carbonToDateString($value));
    }


    /**
     * @param  Builder  $query
     * @param  string  $field
     * @param         $value
     *
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    private function askHasValueInside(Builder $query, string $field, $value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN)
            ? $query->whereNotNull($field)
            : $query->whereNull($field);

    }

    /**
     * @param  Builder  $query
     * @param  string  $field
     * @param         $value
     *
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    private function askDateRange(Builder $query, string $field, $value)
    {
        return $query->whereDate($field, '>', $this->carbonToDateString(Arr::get($value, 'start')))
            ->whereDate($field, '<', $this->carbonToDateString(Arr::get($value, 'end')));

    }


    /**
     * @param $el
     * @return Carbon|null
     */
    private function tryParseStringToCarbon($el): ?Carbon
    {
        return strtotime($el) ? Carbon::parse($el) : null;
    }

    /**
     * @param $value
     * @return null|string
     */
    private function carbonToDateString($value)
    {
        return optional($value)->toDateString();
    }


}
