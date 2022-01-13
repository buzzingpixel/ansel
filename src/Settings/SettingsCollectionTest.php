<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Settings;

use PHPUnit\Framework\TestCase;

class SettingsCollectionTest extends TestCase
{
    public function test(): void
    {
        $item1 = new SettingItem(
            'foo-type1',
            'foo-key1',
            'foo-label1',
            'foo-value1',
            'foo-desc1',
            false,
        );

        $item2 = new SettingItem(
            'foo-type2',
            'foo-key2',
            'foo-label2',
            'foo-value2',
            'foo-desc2',
            true,
        );

        $collection = new SettingsCollection([$item1, $item2]);

        $settingsOnlyCollection = $collection->settingsPageOnly();

        self::assertSame(
            [$item1, $item2],
            $collection->items(),
        );

        self::assertSame($item1, $collection->first());

        self::assertSame(
            [
                ['label' => 'foo-label1'],
                ['label' => 'foo-label2'],
            ],
            $collection->map(static fn (SettingItem $i) => [
                'label' => $i->label(),
            ]),
        );

        self::assertSame(
            'foo-label1',
            $collection->getByKey('foo-key1')->label(),
        );

        self::assertSame(
            'foo-label2',
            $collection->getByKey('foo-key2')->label(),
        );

        self::assertSame(
            'foo-label2',
            $settingsOnlyCollection->first()->label(),
        );
    }
}
