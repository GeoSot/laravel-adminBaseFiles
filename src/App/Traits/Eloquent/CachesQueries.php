<?php

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use GeoSot\BaseAdmin\App\Helpers\Models\CacheQueryBuilder as QueryBuilder;
use Illuminate\Support\Facades\Cache;

trait CachesQueries
{

    /**
     * @return Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new QueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }


    protected static function cacheResult(\Closure $callback, string $cacheKey, $ttl = 60)
    {
        return Cache::remember(static::class.$cacheKey, $ttl, $callback);
    }


    protected static function cacheResultForToday(\Closure $callback, string $cacheKey)
    {
        $ednOfDateTimestamp = now()->endOfDay()->timestamp;
        return static::cacheResult($callback, $ednOfDateTimestamp.$cacheKey, $ednOfDateTimestamp);
    }
}
