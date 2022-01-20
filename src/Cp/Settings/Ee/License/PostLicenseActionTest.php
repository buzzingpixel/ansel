<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\License;

use BuzzingPixel\Ansel\License\LicensePing;
use BuzzingPixel\Ansel\License\ResetLicenseValidity;
use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use EE_Functions;
use ExpressionEngine\Service\URL\URLFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class PostLicenseActionTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private PostLicenseAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new PostLicenseAction(
            $this->mockUrlFactory(),
            $this->mockLicensePing(),
            $this->mockEeFunctions(),
            $this->mockResetLicenseValidity(),
            $this->mockSettingsRepository(),
        );
    }

    private function mockUrlFactory(): URLFactory
    {
        $mock = $this->createMock(URLFactory::class);

        $mock->method('getCurrentUrl')->willReturn(
            '/foo/bar/current/url',
        );

        return $mock;
    }

    private function mockLicensePing(): LicensePing
    {
        $mock = $this->createMock(LicensePing::class);

        $mock->method('runFromWebRequest')->willReturnCallback(
            function (): void {
                $this->calls[] = [
                    'object' => 'LicensePing',
                    'method' => 'runFromWebRequest',
                ];
            }
        );

        return $mock;
    }

    private function mockEeFunctions(): EE_Functions
    {
        $mock = $this->createMock(EE_Functions::class);

        $mock->method('redirect')->willReturnCallback(
            function (string $location): void {
                $this->calls[] = [
                    'object' => 'EE_Functions',
                    'method' => 'redirect',
                    'location' => $location,
                ];
            }
        );

        return $mock;
    }

    private function mockResetLicenseValidity(): ResetLicenseValidity
    {
        $mock = $this->createMock(
            ResetLicenseValidity::class,
        );

        $mock->method('reset')->willReturnCallback(
            function (): void {
                $this->calls[] = [
                    'object' => 'ResetLicenseValidity',
                    'method' => 'reset',
                ];
            }
        );

        return $mock;
    }

    private function mockSettingsRepository(): SettingsRepositoryContract
    {
        $mock = $this->createMock(
            SettingsRepositoryContract::class,
        );

        $mock->method('getSettings')->willReturnCallback(
            static function (): SettingsCollection {
                return new SettingsCollection([
                    new SettingItem(
                        'string',
                        'license_key',
                        'license_key',
                        '',
                        'license_key',
                        false,
                    ),
                ]);
            }
        );

        $mock->method('saveSetting')->willReturnCallback(
            function (SettingItem $setting): void {
                $this->calls[] = [
                    'object' => 'SettingsRepository',
                    'method' => 'saveSettings',
                    'settingKey' => $setting->key(),
                    'settingValue' => $setting->value(),
                ];
            }
        );

        return $mock;
    }

    public function testRun(): void
    {
        $request = $this->createMock(
            ServerRequestInterface::class,
        );

        $request->method('getParsedBody')->willReturn(
            ['license_key' => 'foo-bar-key'],
        );

        $this->action->run($request);

        self::assertSame(
            [
                [
                    'object' => 'SettingsRepository',
                    'method' => 'saveSettings',
                    'settingKey' => 'license_key',
                    'settingValue' => 'foo-bar-key',
                ],
                [
                    'object' => 'ResetLicenseValidity',
                    'method' => 'reset',
                ],
                [
                    'object' => 'LicensePing',
                    'method' => 'runFromWebRequest',
                ],
                [
                    'object' => 'EE_Functions',
                    'method' => 'redirect',
                    'location' => '/foo/bar/current/url',
                ],
            ],
            $this->calls,
        );
    }
}
