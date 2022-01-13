<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Settings;

use function array_filter;
use function array_map;
use function array_values;
use function array_walk;

class SettingsCollection
{
    /** @var SettingItem[] */
    private array $items;

    /**
     * @param SettingItem[] $items
     */
    public function __construct(array $items = [])
    {
        array_walk(
            $items,
            function (SettingItem $item): void {
                $this->items[] = $item;
            }
        );
    }

    /**
     * @return SettingItem[]
     */
    public function items(): array
    {
        return $this->items;
    }

    public function first(): SettingItem
    {
        return array_values($this->items)[0];
    }

    /**
     * @return mixed[]
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->items());
    }

    public function filter(callable $callback): SettingsCollection
    {
        return new SettingsCollection(array_filter(
            $this->items,
            $callback,
        ));
    }

    public function settingsPageOnly(): SettingsCollection
    {
        return $this->filter(static fn (
            SettingItem $item
        ) => $item->includeOnSettingsPage());
    }

    public function getByKey(string $key): SettingItem
    {
        return $this->filter(static fn (
            SettingItem $item
        ) => $item->key() === $key)->first();
    }
}
