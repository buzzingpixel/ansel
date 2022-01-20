<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\License;

use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

use function assert;
use function base64_encode;
use function is_array;

class LicensePingTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    /** @var mixed[] */
    private array $serverParams = [];

    private string $licenseKey = '';

    private int $phoneHome = 0;

    private string $encodingData = '';

    private LicensePing $licensePing;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->serverParams = [];

        $this->licenseKey = '';

        $this->phoneHome = 0;

        $this->encodingData = '';

        $this->licensePing = new LicensePing(
            'foo-app-key',
            $this->mockGuzzleClient(),
            $this->mockRequest(),
            $this->mockInternalFunctions(),
            $this->mockSettingsRepository(),
        );
    }

    private function mockGuzzleClient(): GuzzleClient
    {
        $mock = $this->createMock(GuzzleClient::class);

        $mock->method('post')->willReturnCallback(
            function (string $uri, array $options): ResponseInterface {
                $this->calls[] = [
                    'object' => 'GuzzleClient',
                    'method' => 'post',
                    'uri' => $uri,
                    'options' => $options,
                ];

                return $this->mockGuzzleResponse();
            }
        );

        return $mock;
    }

    private function mockGuzzleResponse(): ResponseInterface
    {
        $mock = $this->createMock(ResponseInterface::class);

        $mock->method('getBody')->willReturn(
            $this->mockGuzzleStream(),
        );

        return $mock;
    }

    private function mockGuzzleStream(): StreamInterface
    {
        $mock = $this->createMock(StreamInterface::class);

        $mock->method('getContents')->willReturn(
            '{"message":"foo-message"}'
        );

        return $mock;
    }

    private function mockRequest(): ServerRequestInterface
    {
        $mock = $this->createMock(
            ServerRequestInterface::class,
        );

        $mock->method('getServerParams')->willReturnCallback(
            function (): array {
                return $this->serverParams;
            }
        );

        return $mock;
    }

    private function mockInternalFunctions(): InternalFunctions
    {
        $mock = $this->createMock(InternalFunctions::class);

        $mock->method('time')->willReturn(555);

        $mock->method('strToTime')->willReturnCallback(
            function (string $dateTime, int $baseTimestamp): int {
                $this->calls[] = [
                    'object' => 'InternalFunctions',
                    'method' => 'strToTime',
                    'dateTime' => $dateTime,
                    'baseTimestamp' => $baseTimestamp,
                ];

                return 456;
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
            function () {
                return new SettingsCollection([
                    new SettingItem(
                        'string',
                        'license_key',
                        'license_key',
                        $this->licenseKey,
                        'license_key',
                        false,
                    ),
                    new SettingItem(
                        'string',
                        'phone_home',
                        'phone_home',
                        $this->phoneHome,
                        'phone_home',
                        false,
                    ),
                    new SettingItem(
                        'string',
                        'encoding_data',
                        'encoding_data',
                        base64_encode($this->encodingData),
                        'encoding_data',
                        false,
                    ),
                ]);
            }
        );

        $mock->method('saveSetting')->willReturnCallback(
            function (SettingItem $setting): void {
                $this->calls[] = [
                    'object' => 'SettingsRepositoryContract',
                    'method' => 'saveSetting',
                    'setting' => $setting,
                ];
            }
        );

        return $mock;
    }

    /**
     * @throws GuzzleException
     */
    public function testWhenNoLicenseKey(): void
    {
        $this->licensePing->runFromWebRequest();

        self::assertSame([], $this->calls);
    }

    /**
     * @throws GuzzleException
     */
    public function testWhenNotTimeToPhoneHome(): void
    {
        $this->licenseKey = 'foo-license-key';

        $this->phoneHome = 556;

        $this->licensePing->runFromWebRequest();

        self::assertSame([], $this->calls);
    }

    /**
     * @throws GuzzleException
     */
    public function testWhenEncodingDataInvalid(): void
    {
        $this->serverParams = [
            'SERVER_NAME' => 'foo-server-name',
            'HTTP_HOST' => 'foo-http-host',
        ];

        $this->licenseKey = 'foo-license-key';

        $this->phoneHome = 556;

        $this->encodingData = 'invalid';

        $this->licensePing->runFromWebRequest();

        self::assertCount(4, $this->calls);

        $call0 = $this->calls[0];

        self::assertSame(
            [
                'object' => 'GuzzleClient',
                'method' => 'post',
                'uri' => 'https://www.buzzingpixel.com/api/v1/check-license',
                'options' => [
                    'form_params' => [
                        'app' => 'foo-app-key',
                        'domain' => 'foo-server-name',
                        'license' => 'foo-license-key',
                    ],
                ],
            ],
            $call0,
        );

        $call1 = $this->calls[1];

        assert(is_array($call1));

        $call1Setting = $call1['setting'];

        assert($call1Setting instanceof SettingItem);

        self::assertSame(
            [
                'object' => 'SettingsRepositoryContract',
                'method' => 'saveSetting',
                'key' => 'encoding_data',
                'value' => 'foo-message',
            ],
            [
                'object' => $call1['object'],
                'method' => $call1['method'],
                'key' => $call1Setting->key(),
                'value' => $call1Setting->getFromBase64(),
            ],
        );

        $call2 = $this->calls[2];

        self::assertSame(
            [
                'object' => 'InternalFunctions',
                'method' => 'strToTime',
                'dateTime' => '+1 day',
                'baseTimestamp' => 555,
            ],
            $call2,
        );

        $call3 = $this->calls[3];

        assert(is_array($call3));

        $call3Setting = $call3['setting'];

        assert($call3Setting instanceof SettingItem);

        self::assertSame(
            [
                'object' => 'SettingsRepositoryContract',
                'method' => 'saveSetting',
                'key' => 'phone_home',
                'value' => 456,
            ],
            [
                'object' => $call3['object'],
                'method' => $call3['method'],
                'key' => $call3Setting->key(),
                'value' => $call3Setting->value(),
            ],
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testWhenTimeToPhoneHome(): void
    {
        $this->serverParams = ['HTTP_HOST' => 'foo-http-host'];

        $this->licenseKey = 'foo-license-key';

        $this->phoneHome = 554;

        $this->encodingData = 'valid';

        $this->licensePing->runFromWebRequest();

        self::assertCount(4, $this->calls);

        $call0 = $this->calls[0];

        self::assertSame(
            [
                'object' => 'GuzzleClient',
                'method' => 'post',
                'uri' => 'https://www.buzzingpixel.com/api/v1/check-license',
                'options' => [
                    'form_params' => [
                        'app' => 'foo-app-key',
                        'domain' => 'foo-http-host',
                        'license' => 'foo-license-key',
                    ],
                ],
            ],
            $call0,
        );

        $call1 = $this->calls[1];

        assert(is_array($call1));

        $call1Setting = $call1['setting'];

        assert($call1Setting instanceof SettingItem);

        self::assertSame(
            [
                'object' => 'SettingsRepositoryContract',
                'method' => 'saveSetting',
                'key' => 'encoding_data',
                'value' => 'foo-message',
            ],
            [
                'object' => $call1['object'],
                'method' => $call1['method'],
                'key' => $call1Setting->key(),
                'value' => $call1Setting->getFromBase64(),
            ],
        );

        $call2 = $this->calls[2];

        self::assertSame(
            [
                'object' => 'InternalFunctions',
                'method' => 'strToTime',
                'dateTime' => '+1 day',
                'baseTimestamp' => 555,
            ],
            $call2,
        );

        $call3 = $this->calls[3];

        assert(is_array($call3));

        $call3Setting = $call3['setting'];

        assert($call3Setting instanceof SettingItem);

        self::assertSame(
            [
                'object' => 'SettingsRepositoryContract',
                'method' => 'saveSetting',
                'key' => 'phone_home',
                'value' => 456,
            ],
            [
                'object' => $call3['object'],
                'method' => $call3['method'],
                'key' => $call3Setting->key(),
                'value' => $call3Setting->value(),
            ],
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testWhenNoServer(): void
    {
        $this->licenseKey = 'foo-license-key';

        $this->phoneHome = 554;

        $this->encodingData = 'valid';

        $this->licensePing->runFromWebRequest();

        self::assertCount(4, $this->calls);

        $call0 = $this->calls[0];

        self::assertSame(
            [
                'object' => 'GuzzleClient',
                'method' => 'post',
                'uri' => 'https://www.buzzingpixel.com/api/v1/check-license',
                'options' => [
                    'form_params' => [
                        'app' => 'foo-app-key',
                        'domain' => '',
                        'license' => 'foo-license-key',
                    ],
                ],
            ],
            $call0,
        );

        $call1 = $this->calls[1];

        assert(is_array($call1));

        $call1Setting = $call1['setting'];

        assert($call1Setting instanceof SettingItem);

        self::assertSame(
            [
                'object' => 'SettingsRepositoryContract',
                'method' => 'saveSetting',
                'key' => 'encoding_data',
                'value' => 'foo-message',
            ],
            [
                'object' => $call1['object'],
                'method' => $call1['method'],
                'key' => $call1Setting->key(),
                'value' => $call1Setting->getFromBase64(),
            ],
        );

        $call2 = $this->calls[2];

        self::assertSame(
            [
                'object' => 'InternalFunctions',
                'method' => 'strToTime',
                'dateTime' => '+1 day',
                'baseTimestamp' => 555,
            ],
            $call2,
        );

        $call3 = $this->calls[3];

        assert(is_array($call3));

        $call3Setting = $call3['setting'];

        assert($call3Setting instanceof SettingItem);

        self::assertSame(
            [
                'object' => 'SettingsRepositoryContract',
                'method' => 'saveSetting',
                'key' => 'phone_home',
                'value' => 456,
            ],
            [
                'object' => $call3['object'],
                'method' => $call3['method'],
                'key' => $call3Setting->key(),
                'value' => $call3Setting->value(),
            ],
        );
    }
}
