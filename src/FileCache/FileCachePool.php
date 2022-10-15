<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\FileCache;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Ramsey\Uuid\UuidFactoryInterface;

use function array_map;
use function array_values;

abstract class FileCachePool implements CacheItemPoolInterface
{
    private GetCacheItem $getCacheItem;

    private ClearCacheDirectory $clearCacheDirectory;

    private DeleteItem $deleteItem;

    private SaveItem $saveItem;

    private UuidFactoryInterface $uuidFactory;

    public function __construct(
        GetCacheItem $getCacheItem,
        ClearCacheDirectory $clearCacheDirectory,
        DeleteItem $deleteItem,
        SaveItem $saveItem,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->getCacheItem        = $getCacheItem;
        $this->clearCacheDirectory = $clearCacheDirectory;
        $this->deleteItem          = $deleteItem;
        $this->saveItem            = $saveItem;
        $this->uuidFactory         = $uuidFactory;
    }

    abstract public static function cacheDirectory(): CacheDirectory;

    /**
     * @inheritDoc
     */
    public function getItem($key): FileCacheItem
    {
        return $this->getItems([$key])[0];
    }

    /**
     * @return FileCacheItem[]
     *
     * @inheritDoc
     */
    public function getItems(array $keys = []): array
    {
        return array_values(array_map(
            fn (string $key) => $this->getCacheItem->get(
                $key,
                $this::cacheDirectory(),
            ),
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
        $this->clearCacheDirectory->clear($this::cacheDirectory());

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteItem($key): bool
    {
        return $this->deleteItem->delete($this->getItem($key));
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
     * @param FileCacheItem $item
     *
     * @throws Exception
     *
     * @phpstan-ignore-next-line
     */
    public function save(CacheItemInterface $item): bool
    {
        return $this->saveItem->save(
            $item,
            $this::cacheDirectory()
        );
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

    public function createKey(string $fileName): string
    {
        return $this->uuidFactory->uuid4()->toString() . '/' . $fileName;
    }

    public function createItem(
        string $fileName,
        ?DateTimeInterface $expires = null
    ): FileCacheItem {
        if ($expires === null) {
            $expires = (new DateTimeImmutable())->add(
                new DateInterval('P1D')
            );
        }

        /**
         * @noinspection PhpUnhandledExceptionInspection
         *
         * We know the exception cannot be thrown because we're creating the
         * correct key format
         */
        return new FileCacheItem(
            $this->createKey($fileName),
            null,
            null,
            $expires,
        );
    }
}
