<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

use function array_filter;
use function array_keys;
use function array_map;
use function array_values;
use function count;

class SourceAdapterListCollection
{
    /** @var SourceAdapterListItem[] */
    private array $items;

    /**
     * @param SourceAdapterListItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = array_values(array_map(
            static fn (SourceAdapterListItem $item) => $item,
            $items
        ));
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @param callable(SourceAdapterListItem $adapterItem, int $index): ReturnType $callback
     *
     * @return ReturnType[]
     *
     * @template ReturnType
     */
    public function map(callable $callback): array
    {
        $asArray = $this->asArray();

        return array_values(array_map(
            $callback,
            $asArray,
            array_keys($asArray),
        ));
    }

    /**
     * @return SourceAdapterListItem[]
     */
    public function asArray(): array
    {
        return $this->items;
    }

    public function getByShortName(string $shortName): ?SourceAdapterListItem
    {
        return array_values(array_filter(
            $this->items,
            static fn (
                SourceAdapterListItem $i
            ) => $i->shortName() === $shortName,
        ))[0] ?? null;
    }
}
