<?php


namespace GeoSot\BaseAdmin\App\Traits\Controller;

use Illuminate\Support\Arr;

trait HasFields
{

    private function getSearchableFields(): array
    {
        return Arr::get($this->listFields(), 'searchable', []);
    }

    protected function listFields(): array
    {
        return [
            'listable' => ['title', 'is_enabled', 'id'],
            'searchable' => ['title', 'is_enabled',],
            'sortable' => ['title', 'is_enabled', 'id'],
            'linkable' => ['title'],
            'orderBy' => ['column' => 'created_at', 'sort' => 'desc'],
        ];
    }

    private function getSortableFields(): array
    {
        return Arr::get($this->listFields(), 'sortable', []);
    }

    private function getListableFields(): array
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
