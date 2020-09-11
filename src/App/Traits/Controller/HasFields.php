<?php


namespace GeoSot\BaseAdmin\App\Traits\Controller;

use Illuminate\Support\Arr;

trait HasFields
{

    private function getSearchableFields()
    {
        return Arr::get($this->listFields(), 'searchable', []);
    }

    protected function listFields()
    {
        return [
            'listable' => ['title', 'enabled', 'id'],
            'searchable' => ['title', 'enabled',],
            'sortable' => ['title', 'enabled', 'id'],
            'linkable' => ['title'],
            'orderBy' => ['column' => 'created_at', 'sort' => 'desc'],
        ];
    }

    private function getSortableFields()
    {
        return Arr::get($this->listFields(), 'sortable', []);
    }

    private function getListableFields()
    {
        return Arr::get($this->listFields(), 'listable', []);
    }

    /**
     * @param  string|null  $arg
     * @param  string|null  $default
     *
     * @return mixed
     */
    private function getOrderByOptions(string $arg = null, string $default = null)
    {
        $options = Arr::get($this->listFields(), 'orderBy', []);

        return is_null($arg) ? $options : Arr::get($options, $arg, $default);
    }

}
