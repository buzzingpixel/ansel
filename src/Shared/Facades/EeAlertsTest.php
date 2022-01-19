<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Facades;

use ExpressionEngine\Service\Alert\Alert;
use ExpressionEngine\Service\Alert\AlertCollection;
use PHPUnit\Framework\TestCase;

class EeAlertsTest extends TestCase
{
    private Alert $alert;

    private EeAlerts $eeAlerts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->alert = $this->createMock(Alert::class);

        $this->eeAlerts = new EeAlerts(
            $this->mockAlertCollection(),
        );
    }

    private function mockAlertCollection(): AlertCollection
    {
        $mock = $this->createMock(AlertCollection::class);

        $mock->method('makeBanner')->willReturn(
            $this->alert,
        );

        return $mock;
    }

    public function testMakeBanner(): void
    {
        self::assertSame(
            $this->alert,
            $this->eeAlerts->makeBanner(),
        );
    }
}
