<?php

namespace GeoSot\BaseAdmin\App\Helpers\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Query\Builder as QueryBuilder;

class CacheQueryBuilder extends QueryBuilder
{
    /**
     * @inheritDoc
     */
    protected function runSelect()
    {
        return Cache::remember($this->getCacheKey(), 15, function () {
            return parent::runSelect();
        });
    }

    /**
     * Returns a Unique String that can identify this Query.
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return json_encode([
            $this->toSql() => $this->getBindings()
        ]);
    }
}
