<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use BuzzingPixel\Ansel\Shared\Meta;
use ExpressionEngine\Model\Addon\Module;
use ExpressionEngine\Service\Model\Facade as RecordService;
use ExpressionEngine\Service\Model\Query\Builder;
use PHPUnit\Framework\TestCase;

class EeModuleVersionUpdaterTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $firstReturnsRecord;

    private EeModuleVersionUpdater $updater;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->firstReturnsRecord = false;

        $meta = new Meta(
            'ee',
            '9.8.7',
        );

        $this->updater = new EeModuleVersionUpdater(
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
            function (): ?Module {
                if (! $this->firstReturnsRecord) {
                    return null;
                }

                return $this->mockRecord();
            }
        );

        return $mock;
    }

    private function mockRecord(): Module
    {
        $mock = $this->createMock(Module::class);

        $mock->method('setProperty')->willReturnCallback(
            function (
                string $name,
                string $value
            ) use (
                $mock
            ): Module {
                $this->calls[] = [
                    'object' => 'Module',
                    'method' => 'setProperty',
                    'name' => $name,
                    'value' => $value,
                ];

                return $mock;
            }
        );

        $mock->method('save')->willReturnCallback(
            function () use ($mock): Module {
                $this->calls[] = [
                    'object' => 'Module',
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
                    'name' => 'Module',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'module_name',
                    'value' => 'Ansel',
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
                    'name' => 'Module',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'module_name',
                    'value' => 'Ansel',
                ],
                [
                    'object' => 'Module',
                    'method' => 'setProperty',
                    'name' => 'module_name',
                    'value' => 'Ansel',
                ],
                [
                    'object' => 'Module',
                    'method' => 'setProperty',
                    'name' => 'module_version',
                    'value' => '9.8.7',
                ],
                [
                    'object' => 'Module',
                    'method' => 'setProperty',
                    'name' => 'has_cp_backend',
                    'value' => 'y',
                ],
                [
                    'object' => 'Module',
                    'method' => 'setProperty',
                    'name' => 'has_publish_fields',
                    'value' => 'n',
                ],
                [
                    'object' => 'Module',
                    'method' => 'save',
                ],
            ],
            $this->calls,
        );
    }
}
