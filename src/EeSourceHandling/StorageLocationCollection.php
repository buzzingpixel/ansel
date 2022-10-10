<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

interface StorageLocationCollection
{
    /**
     * @return StorageLocationItem[]
     */
    public function asArray(): array;

    /**
     * @param callable(StorageLocation $location): ReturnType $callback
     *
     * @return ReturnType[]
     *
     * @template ReturnType
     */
    public function map(callable $callback): array;
}
