<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

use function array_filter;
use function array_map;
use function array_merge;
use function array_values;

class LocationSelectionCollection
{
    /** @var LocationSelectionItem[] */
    private array $items = [];

    /**
     * @param LocationSelectionItem[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = array_values(array_map(
            static fn (LocationSelectionItem $item) => $item,
            $items,
        ));
    }

    /**
     * @return array<int, array<string>|string>
     */
    public function asArray(): array
    {
        return array_values(array_merge(
            [
                [
                    'label' => 'Choose Location...',
                    'value' => '',
                ],
            ],
            array_map(
                static function (LocationSelectionItem $item) {
                    return $item->asArray();
                },
                $this->items,
            ),
        ));
    }

    public function firstOrNull(): ?LocationSelectionItem
    {
        return array_values($this->items)[0] ?? null;
    }

    public function filter(callable $callback): self
    {
        return new self(array_filter(
            $this->items,
            $callback,
        ));
    }

    public function getLocationByValueOrNull(
        string $value
    ): ?LocationSelectionItem {
        return $this->filter(static fn (
            LocationSelectionItem $i
        ) => $i->value() === $value)->firstOrNull();
    }

    public function add(LocationSelectionCollection $toAdd): self
    {
        return new self(array_merge(
            $this->items,
            $toAdd->items,
        ));
    }
}
