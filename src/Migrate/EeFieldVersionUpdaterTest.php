<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use BuzzingPixel\Ansel\Shared\Meta;
use ExpressionEngine\Model\Addon\Fieldtype;
use ExpressionEngine\Service\Model\Facade as RecordService;
use ExpressionEngine\Service\Model\Query\Builder;
use PHPUnit\Framework\TestCase;

class EeFieldVersionUpdaterTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $firstReturnsRecord;

    private EeFieldVersionUpdater $updater;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->firstReturnsRecord = false;

        $meta = new Meta('9.8.7');

        $this->updater = new EeFieldVersionUpdater(
            $meta,
            $this->mockRecordService(),
        );
    }

    private function mockRecordService(): RecordService
    {
        $mock = $this->createMock(RecordService::class);

        $mock->method('get')->willReturnCallback(
            function (string $name): Builder {
                $this->calls[] = [
                    'object' => 'RecordService',
                    'method' => 'get',
                    'name' => $name,
                ];

                return $this->mockBuilder();
            }
        );

        return $mock;
    }

    private function mockBuilder(): Builder
    {
        $mock = $this->createMock(Builder::class);

        $mock->method('filter')->willReturnCallback(
            function (
                string $property,
                string $value
            ) use (
                $mock
            ): Builder {
                $this->calls[] = [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => $property,
                    'value' => $value,
                ];

                return $mock;
            }
        );

        $mock->method('first')->willReturnCallback(
            function (): ?Fieldtype {
                if (! $this->firstReturnsRecord) {
                    return null;
                }

                return $this->mockRecord();
            }
        );

        return $mock;
    }

    private function mockRecord(): Fieldtype
    {
        $mock = $this->createMock(Fieldtype::class);

        $mock->method('setProperty')->willReturnCallback(
            function (
                string $name,
                string $value
            ) use (
                $mock
            ): Fieldtype {
                $this->calls[] = [
                    'object' => 'Fieldtype',
                    'method' => 'setProperty',
                    'name' => $name,
                    'value' => $value,
                ];

                return $mock;
            }
        );

        $mock->method('save')->willReturnCallback(
            function () use ($mock): Fieldtype {
                $this->calls[] = [
                    'object' => 'Fieldtype',
                    'method' => 'save',
                ];

                return $mock;
            }
        );

        return $mock;
    }

    public function testUpdateWhenNoRecord(): void
    {
        $this->updater->update();

        self::assertSame(
            [
                [
                    'object' => 'RecordService',
                    'method' => 'get',
                    'name' => 'Fieldtype',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'name',
                    'value' => 'ansel',
                ],
            ],
            $this->calls,
        );
    }

    public function testUpdateWhenRecord(): void
    {
        $this->firstReturnsRecord = true;

        $this->updater->update();

        self::assertSame(
            [
                [
                    'object' => 'RecordService',
                    'method' => 'get',
                    'name' => 'Fieldtype',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'name',
                    'value' => 'ansel',
                ],
                [
                    'object' => 'Fieldtype',
                    'method' => 'setProperty',
                    'name' => 'version',
                    'value' => '9.8.7',
                ],
                [
                    'object' => 'Fieldtype',
                    'method' => 'save',
                ],
            ],
            $this->calls,
        );
    }
}
