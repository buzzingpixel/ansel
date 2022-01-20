<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\License;

use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use PHPUnit\Framework\TestCase;

use function assert;
use function is_array;

class ResetLicenseValidityTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private ResetLicenseValidity $resetLicenseValidity;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->resetLicenseValidity = new ResetLicenseValidity(
            $this->mockSettingsRepository(),
        );
    }

    private function mockSettingsRepository(): SettingsRepositoryContract
    {
        $mock = $this->createMock(
            SettingsRepositoryContract::class,
        );

        $mock->method('getSettings')->willReturn(
            new SettingsCollection([
                new SettingItem(
                    'string',
                    'encoding_data',
                    'encoding_data',
                    'foo-encoding-val',
                    'encoding_data',
                    false,
                ),
                new SettingItem(
                    'int',
                    'phone_home',
                    'phone_home',
                    123,
                    'phone_home',
                    false,
                ),
            ]),
        );

        $mock->method('saveSetting')->willReturnCallback(
            function (SettingItem $item): void {
                $this->calls[] = [
                    'object' => 'SettingsRepositoryContract',
                    'method' => 'saveSetting',
                    'item' => $item,
                ];
            }
        );

        return $mock;
    }

    public function testReset(): void
    {
        $this->resetLicenseValidity->reset();

        self::assertCount(2, $this->calls);

        $call0 = $this->calls[0];

        assert(is_array($call0));

        $call0Item = $call0['item'];

        assert($call0Item instanceof SettingItem);

        self::assertSame(
            [
                'object' => 'SettingsRepositoryContract',
                'method' => 'saveSetting',
                'key' => 'encoding_data',
                'value' => '',
            ],
            [
                'object' => $call0['object'],
                'method' => $call0['method'],
                'key' => $call0Item->key(),
                'value' => $call0Item->value(),
            ],
        );

        $call1 = $this->calls[1];

        assert(is_array($call1));

        $call1Item = $call1['item'];

        assert($call1Item instanceof SettingItem);

        self::assertSame(
            [
                'object' => 'SettingsRepositoryContract',
                'method' => 'saveSetting',
                'key' => 'phone_home',
                'value' => 0,
            ],
            [
                'object' => $call1['object'],
                'method' => $call1['method'],
                'key' => $call1Item->key(),
                'value' => $call1Item->value(),
            ],
        );
    }
}
