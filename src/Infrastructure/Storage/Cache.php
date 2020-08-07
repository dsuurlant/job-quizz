<?php
declare(strict_types=1);

namespace App\Infrastructure\Storage;

/**
 * It's an in-memory cache so it will slow down considerably if a lot of data is cached.
 * It's better to use a solution like Redis or another external service for query caching.
 */
final class Cache
{
    private static array $cache;

    public static function initialize(): void
    {
        self::$cache = [];
    }

    /**
     * Check if a cached entry exists.
     */
    public static function entryExists(string $query, array $params): bool
    {
        $queryKey = (CachedQuery::create($query, $params))->serialize();
        return array_key_exists($queryKey, self::$cache);
    }

    /**
     * Store data in cache. Overwrites existing data.
     */
    public static function create(string $query, array $params, $results): void
    {
        $queryKey = (CachedQuery::create($query, $params))->serialize();
        self::$cache[$queryKey] = $results;
    }

    /**
     * Retrieve results from cache.
     */
    public static function find(string $query, array $params)
    {
        $queryKey = (CachedQuery::create($query, $params))->serialize();
        if (!array_key_exists($queryKey, self::$cache)) {
            throw SorryCouldNotFindCacheEntry::becauseItDoesNotExist();
        }

        return self::$cache[$queryKey];
    }

    /**
     * Empties cache entry. Doesn't fail if the entry does not exist, because that is the desirable outcome anyway.
     */
    public static function empty(string $query, array $params): void
    {
        $queryKey = (CachedQuery::create($query, $params))->serialize();
        unset(self::$cache[$queryKey]);
    }

    public static function clear(): void
    {
        self::initialize();
    }
}