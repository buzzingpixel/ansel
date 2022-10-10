<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

use function array_map;
use function array_values;

class StorageLocationItemCollection implements StorageLocationCollection
{
    /** @var StorageLocation[] */
    private array $items;

    /**
     * @param StorageLocation[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = array_values(array_map(
            static fn (StorageLocation $item) => $item,
            $items,
        ));
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        /** @phpstan-ignore-next-line */
        return array_values($this->items);
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback): array
    {
        return array_values(array_map(
            $callback,
            $this->asArray(),
        ));
    }
}
