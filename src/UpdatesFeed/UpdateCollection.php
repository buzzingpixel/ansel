<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed;

use function array_filter;
use function array_map;
use function array_values;
use function array_walk;
use function count;

class UpdateCollection
{
    /** @var UpdateItem[] */
    private array $items = [];

    /**
     * @param UpdateItem[] $items
     */
    public function __construct(array $items = [])
    {
        array_walk(
            $items,
            function (UpdateItem $item): void {
                $this->items[] = $item;
            }
        );
    }

    /**
     * @return UpdateItem[]
     */
    public function items(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items());
    }

    public function first(): UpdateItem
    {
        return array_values($this->items)[0];
    }

    public function firstOrNull(): ?UpdateItem
    {
        return array_values($this->items)[0] ?? null;
    }

    public function at(int $offset): UpdateItem
    {
        return array_values($this->items)[$offset];
    }

    public function atOrNull(int $offset): ?UpdateItem
    {
        return array_values($this->items)[$offset] ?? null;
    }

    /**
     * @return mixed[]
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->items());
    }

    public function filter(callable $callback): UpdateCollection
    {
        return new UpdateCollection(array_filter(
            $this->items,
            $callback,
        ));
    }
}
