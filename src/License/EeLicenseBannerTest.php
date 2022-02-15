<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\License;

use BuzzingPixel\Ansel\Shared\Facades\EeAlerts;
use BuzzingPixel\Ansel\Shared\Meta\Meta;
use BuzzingPixel\Ansel\Translations\TranslatorForTesting;
use ExpressionEngine\Service\Alert\Alert;
use PHPUnit\Framework\TestCase;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint
// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class EeLicenseBannerTest extends TestCase
{
    private Alert $alert;

    private EeLicenseBanner $eeLicenseBanner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->alert = new class () extends Alert {
            public bool $asIssueCalled  = false;
            public bool $canCloseCalled = false;
            public string $titleCall    = '';
            public string $bodyCall     = '';

            /** @phpstan-ignore-next-line */
            public function __construct()
            {
            }

            public function asIssue(): self
            {
                $this->asIssueCalled = true;

                return $this;
            }

            public function canClose(): self
            {
                $this->canCloseCalled = true;

                return $this;
            }

            public function withTitle($title): self
            {
                $this->titleCall = $title;

                return $this;
            }

            /** @phpstan-ignore-next-line */
            public function addToBody(
                $item,
                $class = null,
                /** @phpstan-ignore-next-line */
                $xss_filter = true
            ): self {
                /** @phpstan-ignore-next-line */
                $this->bodyCall .= $item;

                return $this;
            }
        };

        $this->eeLicenseBanner = new EeLicenseBanner(
            $this->mockMeta(),
            $this->mockEeAlerts(),
            new TranslatorForTesting(),
        );
    }

    private function mockEeAlerts(): EeAlerts
    {
        $mock = $this->createMock(EeAlerts::class);

        $mock->method('makeBanner')->willReturn(
            $this->alert,
        );

        return $mock;
    }

    private function mockMeta(): Meta
    {
        $mock = $this->createMock(Meta::class);

        $mock->method('softwarePageLink')->willReturn(
            '/software/page',
        );

        $mock->method('licenseCpLink')->willReturn(
            '/license/cp',
        );

        $mock->method('buzzingPixelAccountUrl')->willReturn(
            '/bzpxl/acct',
        );

        return $mock;
    }

    public function testWhenIsValid(): void
    {
        $this->eeLicenseBanner->setFromLicenseResult(
            new LicenseResult(LicenseResult::STATUS_VALID)
        );

        /** @phpstan-ignore-next-line */
        self::assertFalse($this->alert->asIssueCalled);

        /** @phpstan-ignore-next-line */
        self::assertFalse($this->alert->canCloseCalled);

        /** @phpstan-ignore-next-line */
        self::assertSame('', $this->alert->titleCall);

        /** @phpstan-ignore-next-line */
        self::assertSame('', $this->alert->bodyCall);
    }

    public function testWhenIsTrial(): void
    {
        $this->eeLicenseBanner->setFromLicenseResult(
            new LicenseResult(LicenseResult::STATUS_TRIAL)
        );

        /** @phpstan-ignore-next-line */
        self::assertFalse($this->alert->asIssueCalled);

        /** @phpstan-ignore-next-line */
        self::assertFalse($this->alert->canCloseCalled);

        /** @phpstan-ignore-next-line */
        self::assertSame('', $this->alert->titleCall);

        /** @phpstan-ignore-next-line */
        self::assertSame('', $this->alert->bodyCall);
    }

    public function testWhenIsExpired(): void
    {
        $this->eeLicenseBanner->setFromLicenseResult(
            new LicenseResult(LicenseResult::STATUS_EXPIRED)
        );

        /** @phpstan-ignore-next-line */
        self::assertTrue($this->alert->asIssueCalled);

        /** @phpstan-ignore-next-line */
        self::assertTrue($this->alert->canCloseCalled);

        self::assertSame(
            'ansel_trial_expired-translator',
            /** @phpstan-ignore-next-line */
            $this->alert->titleCall,
        );

        self::assertSame(
            'ansel_trial_expired_body-translator <a href="/software/page">link</a><a href="/license/cp">link</a>',
            /** @phpstan-ignore-next-line */
            $this->alert->bodyCall,
        );
    }

    public function testWhenIsTrialWithInvalidLicense(): void
    {
        $this->eeLicenseBanner->setFromLicenseResult(
            new LicenseResult(
                LicenseResult::STATUS_INVALID_TRIAL,
            )
        );

        /** @phpstan-ignore-next-line */
        self::assertTrue($this->alert->asIssueCalled);

        /** @phpstan-ignore-next-line */
        self::assertTrue($this->alert->canCloseCalled);

        self::assertSame(
            'ansel_license_invalid-translator',
            /** @phpstan-ignore-next-line */
            $this->alert->titleCall,
        );

        self::assertSame(
            'trial_active_invalid_license_key_body-translator <a href="/bzpxl/acct">link</a><a href="/software/page">link</a> <a href="/license/cp">link</a>',
            /** @phpstan-ignore-next-line */
            $this->alert->bodyCall,
        );
    }

    public function testWhenIsNotTrialWithInvalidLicense(): void
    {
        $this->eeLicenseBanner->setFromLicenseResult(
            new LicenseResult(
                LicenseResult::STATUS_INVALID_EXPIRED,
            )
        );

        /** @phpstan-ignore-next-line */
        self::assertTrue($this->alert->asIssueCalled);

        /** @phpstan-ignore-next-line */
        self::assertTrue($this->alert->canCloseCalled);

        self::assertSame(
            'ansel_license_invalid-translator',
            /** @phpstan-ignore-next-line */
            $this->alert->titleCall,
        );

        self::assertSame(
            'ansel_license_invalid_body-translator <a href="/bzpxl/acct">link</a><a href="/software/page">link</a> <a href="/license/cp">link</a>',
            /** @phpstan-ignore-next-line */
            $this->alert->bodyCall,
        );
    }
}
