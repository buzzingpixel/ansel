<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

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
}
