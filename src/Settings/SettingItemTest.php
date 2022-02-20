<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Settings;

use PHPUnit\Framework\TestCase;

class SettingItemTest extends TestCase
{
    public function testSetValue(): void
    {
        $item = new SettingItem(
            'foo-type',
            'foo-key',
            'foo-label',
            'foo-value',
            'foo-desc',
            true,
        );

        self::assertSame('foo-type', $item->type());

        self::assertSame('foo-key', $item->key());

        self::assertSame('foo-label', $item->label());

        self::assertSame('foo-value', $item->value());

        self::assertTrue($item->includeOnSettingsPage());

        $item->setValue('bar');

        self::assertSame('foo-type', $item->type());

        self::assertSame('foo-key', $item->key());

        self::assertSame('foo-label', $item->label());

        self::assertSame('bar', $item->value());

        self::assertTrue($item->includeOnSettingsPage());

        $item->setToBase64('foo-bar');

        self::assertSame('foo-bar', $item->getFromBase64());
    }

    public function testGetBool(): void
    {
        $item1 = new SettingItem(
            'foo-type',
            'foo-key',
            'foo-label',
            true,
            'foo-desc',
            true,
        );

        $item2 = new SettingItem(
            'foo-type',
            'foo-key',
            'foo-label',
            false,
            'foo-desc',
            true,
        );

        self::assertTrue($item1->getBool());

        self::assertFalse($item2->getBool());
    }
}
