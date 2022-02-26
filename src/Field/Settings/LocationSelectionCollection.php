<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

use function array_filter;
use function array_map;
use function array_merge;
use function array_values;
use function base64_encode;
use function json_encode;

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
            static function (LocationSelectionItem $item) {
                return $item;
            },
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

    public function formatForEEReactDropdown(
        string $name,
        string $selected = ''
    ): string {
        $array = [
            'name' => $name,
            'items' => $this->asArrayNoBlank(),
            'selected' => $selected,
            'disabled' => false,
            'tooMany' => 8,
            'filterUrl' => null,
            'limit' => 100,
            'groupToggle' => [],
            'emptyText' => 'Choose Location...',
            'noResults' => 'No <b>locations</b> found.',
        ];

        $json = (string) json_encode($array);

        return base64_encode($json);
    }

    /**
     * @return array<int, array<string>|string>
     */
    public function asArrayNoBlank(): array
    {
        return array_values(array_map(
            static function (LocationSelectionItem $item) {
                return $item->asArray();
            },
            $this->items,
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
}
