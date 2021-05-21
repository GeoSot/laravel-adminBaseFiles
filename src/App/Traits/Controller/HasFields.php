<?php


namespace GeoSot\BaseAdmin\App\Traits\Controller;

use Illuminate\Support\Arr;

trait HasFields
{

    private $defaultListFields = [
        'listable' => ['title', 'is_enabled', 'id'],
        'searchable' => ['title', 'id',],
        'sortable' => ['title', 'is_enabled', 'id'],
        'linkable' => ['title'],
        'orderBy' => ['column' => 'created_at', 'sort' => 'desc'],
    ];


    private function getSearchableFields(): array
    {
        return Arr::get($this->listFields(), 'searchable', []);
    }

    protected function listFields(): array
    {
        return [];
    }

    protected function getListFields(): array
    {
        return array_merge($this->defaultListFields, $this->listFields());
    }


    private function getSortableFields(): array
    {
        return Arr::get($this->getListFields(), 'sortable', []);
    }

    private function getListableFields(): array
    {
        return Arr::get($this->getListFields(), 'listable', []);
    }

    /**
     * @param  string|null  $arg
     * @param  string|null  $default
     *
     * @return mixed
     */
    private function getOrderByOptions(string $arg = null, string $default = null)
    {
        $options = Arr::get($this->getListFields(), 'orderBy', []);

        return is_null($arg) ? $options : Arr::get($options, $arg, $default);
    }

}
