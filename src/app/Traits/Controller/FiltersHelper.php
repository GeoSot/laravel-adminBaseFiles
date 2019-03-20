<?php


namespace GeoSot\BaseAdmin\App\Traits\Controller;

use Carbon\Carbon;
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


    private function getExtraFiltersData()
    {
        $filters = $this->filters();
        //        $query   = !$this->_useMainRepo ? $this->_class::select() : null;
        $query = (new $this->_class())->newQuery();///newModelQuery

        foreach ($filters as $key => $filterVals) {
            if (in_array($filterVals['type'], ['select', 'multiSelect'])) {
                $filters [$key]['values'] = $this->getValuesForSelectTypeFilters($query, $key, $filters);
            }
        }

        return collect($filters);
    }

    /**
     * @param $query
     * @param $key
     * @param $filters
     *
     * @return mixed
     */
    private function getValuesForSelectTypeFilters($query, $key, $filters)
    {
        $fieldDt = $this->getFieldData($query, $key);

        if (Arr::has($filters[$key], 'values')) {
            return Arr::get($filters[$key], 'values');
        }
        if ($fieldDt->get('exists') and !$fieldDt->has('relationName')) {
            return $this->_hydratedModel->newQuery()->groupBy($key)->pluck($key, $key)->toArray();//newModelQuery
        }
        if ($fieldDt->has('relationName')) {
            return $fieldDt->get('fullClass')::all()->pluck($fieldDt->get('column'), Arr::get($filters[$key], 'relation_key', 'id'))->unique();
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

        $params         = collect([]);
        $filters        = $this->filters();
        $requestFilters = $request->input('extra_filters', []);
        //dd($requestFilters);
        foreach ($filters as $key => $filterVals) {
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
     * @param Request    $request
     * @param Collection $params
     * @param            $query
     */
    private function applyExtraFiltersOnModel(Request $request, Collection &$params, &$query)
    {
        $filteredParams  = $params->get('extra_filters')->filter(function ($it) {
            return is_array($it) ? !empty(array_filter($it)) : !is_null($it);
        });
        $filteredFilters = collect($this->filters())->intersectByKeys($filteredParams);
        foreach ($filteredFilters as $field => $data) {
            $fieldDt = $this->getFieldData($query, $field);
            $value   = $filteredParams->get($field);

            //NOT RELATIONSHIP
            if (!$fieldDt->has('relationName') and $fieldDt->get('exists')) {

                switch (Arr::get($data, 'type')) {
                    case "dateTime":
                        $query->whereDate($field, optional($value)->toDateString());
                        break;
                    case "dateRange":
                        $query->whereDate($field, '>', Arr::get($value, 'start'))->whereDate($field, '<', Arr::get($value, 'end'));
                        break;
                    case "hasValueInside":
                        if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                            $query->whereNotNull($field);
                        } else {
                            $query->whereNull($field);
                        }
                        break;
                    default:
                        $query->whereIn($field, (array)$value);
                }
            }

            // RELATIONSHIP FIELD
            if ($fieldDt->has('relationName') and ($fieldDt->get('exists') or $fieldDt->get('isAppended'))) {

                $query->whereHas($fieldDt->get('relationName'), function ($q) use ($fieldDt, $params, $field, $data, $value) {

                    switch (Arr::get($data, 'type')) {
                        case "dateTime":
                            $q->whereDate($fieldDt->get('column'), optional($value)->toDateString());
                            break;
                        case "dateRange":
                            $q->whereDate($fieldDt->get('column'), '>', optional(Arr::get($value, 'start'))->toDateString())
                                ->whereDate($fieldDt->get('column'), '<', optional(Arr::get($value, 'end'))->toDateString());
                            break;
                        case "hasValueInside":
                            if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                                $q->whereNotNull($fieldDt->get('column'));
                            } else {
                                $q->whereNull($fieldDt->get('column'));
                            }
                            break;
                        default:
                            $q->whereIn($fieldDt->get('table') . '.' . Arr::get($data, 'relation_key', 'id'), (array)$value);
                    }

                });
            }
        }
    }


}
