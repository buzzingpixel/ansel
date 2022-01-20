<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\License;

use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use PHPUnit\Framework\TestCase;

use function base64_encode;

class LicenseStatusTest extends TestCase
{
    private string $encoding = '';

    private string $encodingData = '';

    private string $licenseKey = '';

    private LicenseStatus $licenseStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->encoding = '';

        $this->encodingData = '';

        $this->licenseStatus = new LicenseStatus(
            $this->mockInternalFunctions(),
            $this->mockSettingsRepository(),
        );
    }

    private function mockInternalFunctions(): InternalFunctions
    {
        $mock = $this->createMock(InternalFunctions::class);

        $mock->method('time')->willReturn(555);

        return $mock;
    }

    private function mockSettingsRepository(): SettingsRepositoryContract
    {
        $mock = $this->createMock(
            SettingsRepositoryContract::class,
        );

        $mock->method('getSettings')->willReturnCallback(
            function (): SettingsCollection {
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
                        'encoding',
                        'encoding',
                        base64_encode(
                            $this->encoding,
                        ),
                        'encoding',
                        true,
                    ),
                    new SettingItem(
                        'string',
                        'encoding_data',
                        'encoding_data',
                        base64_encode(
                            $this->encodingData,
                        ),
                        'encoding_data',
                        true,
                    ),
                ]);
            }
        );

        return $mock;
    }

    public function testWhenLicenseInvalidAndExpired(): void
    {
        $this->licenseKey = 'foo-bar-license-key';

        $this->encoding = '554';

        $this->encodingData = 'invalid';

        $result = $this->licenseStatus->get();

        self::assertSame(
            LicenseResult::STATUS_INVALID_EXPIRED,
            $result->status(),
        );

        self::assertFalse($result->isValid());

        self::assertFalse($result->isTrial());

        self::assertFalse($result->isExpired());

        self::assertFalse($result->isTrialWithInvalidLicenseKey());

        self::assertTrue($result->isExpiredWithInvalidLicenseKey());
    }

    public function testWhenLicenseInvalidAndNotExpired(): void
    {
        $this->licenseKey = 'foo-bar-license-key';

        $this->encoding = '556';

        $this->encodingData = 'invalid';

        $result = $this->licenseStatus->get();

        self::assertSame(
            LicenseResult::STATUS_INVALID_TRIAL,
            $result->status(),
        );

        self::assertFalse($result->isValid());

        self::assertFalse($result->isTrial());

        self::assertFalse($result->isExpired());

        self::assertTrue($result->isTrialWithInvalidLicenseKey());

        self::assertFalse($result->isExpiredWithInvalidLicenseKey());
    }

    public function testWhenLicenseIsValid(): void
    {
        $this->licenseKey = 'foo-bar-license-key';

        $this->encoding = '556';

        $this->encodingData = 'valid';

        $result = $this->licenseStatus->get();

        self::assertSame(
            LicenseResult::STATUS_VALID,
            $result->status(),
        );

        self::assertTrue($result->isValid());

        self::assertFalse($result->isTrial());

        self::assertFalse($result->isExpired());

        self::assertFalse($result->isTrialWithInvalidLicenseKey());

        self::assertFalse($result->isExpiredWithInvalidLicenseKey());
    }

    public function testWhenNoLicenseKeyAndIsNotExpired(): void
    {
        $this->licenseKey = '';

        $this->encoding = '556';

        $result = $this->licenseStatus->get();

        self::assertSame(
            LicenseResult::STATUS_TRIAL,
            $result->status(),
        );

        self::assertFalse($result->isValid());

        self::assertTrue($result->isTrial());

        self::assertFalse($result->isExpired());

        self::assertFalse($result->isTrialWithInvalidLicenseKey());

        self::assertFalse($result->isExpiredWithInvalidLicenseKey());
    }

    public function testWhenNoLicenseKeyAndIsExpired(): void
    {
        $this->licenseKey = '';

        $this->encoding = '554';

        $result = $this->licenseStatus->get();

        self::assertSame(
            LicenseResult::STATUS_EXPIRED,
            $result->status(),
        );

        self::assertFalse($result->isValid());

        self::assertFalse($result->isTrial());

        self::assertTrue($result->isExpired());

        self::assertFalse($result->isTrialWithInvalidLicenseKey());

        self::assertFalse($result->isExpiredWithInvalidLicenseKey());
    }
}
