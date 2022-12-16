<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\RequestCache;

use Exception;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

use function array_map;
use function array_values;

class RequestCachePool implements CacheItemPoolInterface
{
    /** @var RequestCacheItem[] */
    private static array $cache = [];

    /**
     * @inheritDoc
     */
    public function getItem($key): RequestCacheItem
    {
        if (! isset(self::$cache[$key])) {
            self::$cache[$key] = new RequestCacheItem(
                $key,
            );
        }

        return self::$cache[$key];
    }

    /**
     * @return RequestCacheItem[]
     *
     * @inheritDoc
     */
    public function getItems(array $keys = []): array
    {
        return array_values(array_map(
            fn (string $key) => $this->getItem($key),
            $keys,
        ));
    }

    /**
     * @inheritDoc
     */
    public function hasItem($key): bool
    {
        return $this->getItem($key)->isHit();
    }

    public function clear(): bool
    {
        self::$cache = [];

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteItem($key): bool
    {
        if (isset(self::$cache[$key])) {
            unset(self::$cache[$key]);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteItems(array $keys): bool
    {
        array_map(
            fn (string $key) => $this->deleteItem($key),
            $keys,
        );

        return true;
    }

    /**
     * @param RequestCacheItem $item
     *
     * @phpstan-ignore-next-line
     */
    public function save(CacheItemInterface $item): bool
    {
        $key = $item->getKey();

        if ($key === '') {
            return false;
        }

        self::$cache[$item->getKey()] = $item;

        return true;
    }

    /**
     * @throws Exception
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        throw new Exception('Not implemented');
    }

    /**
     * @throws Exception
     */
    public function commit(): bool
    {
        throw new Exception('Not implemented');
    }

    /**
     * @param mixed $data
     */
    public function createItem(string $key, $data = null): RequestCacheItem
    {
        return new RequestCacheItem($key, $data);
    }
}
