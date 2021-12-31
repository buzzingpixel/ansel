<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\Meta;
use ExpressionEngine\Model\Addon\Module;
use ExpressionEngine\Service\Model\Facade as RecordService;
use ExpressionEngine\Service\Model\Query\Builder;
use PHPUnit\Framework\TestCase;

class EeMigration0002AddModuleRecordTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $firstReturnsRecord;

    private EeMigration0002AddModuleRecord $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->firstReturnsRecord = false;

        $meta = new Meta('4.5.6');

        $this->migration = new EeMigration0002AddModuleRecord(
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

        $mock->method('make')->willReturnCallback(
            function (string $name): Module {
                $this->calls[] = [
                    'object' => 'RecordService',
                    'method' => 'make',
                    'name' => $name,
                ];

                return $this->mockRecord();
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
                    'object' => 'Module',
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

                $mock = $this->createMock(Module::class);

                $mock->method('delete')->willReturnCallback(
                    function () use ($mock): Module {
                        $this->calls[] = [
                            'object' => 'Module',
                            'method' => 'delete',
                        ];

                        return $mock;
                    }
                );

                return $mock;
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

    public function testFor(): void
    {
        self::assertSame(
            MigrationContract::EE,
            $this->migration->for(),
        );
    }

    public function testUpWhenRecordExists(): void
    {
        $this->firstReturnsRecord = true;

        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'RecordService',
                    'method' => 'get',
                    'name' => 'Module',
                ],
                [
                    'object' => 'Module',
                    'method' => 'filter',
                    'property' => 'module_name',
                    'value' => 'Ansel',
                ],
            ],
            $this->calls,
        );
    }

    public function testUpWhenRecordDoesNotExist(): void
    {
        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'RecordService',
                    'method' => 'get',
                    'name' => 'Module',
                ],
                [
                    'object' => 'Module',
                    'method' => 'filter',
                    'property' => 'module_name',
                    'value' => 'Ansel',
                ],
                [
                    'object' => 'RecordService',
                    'method' => 'make',
                    'name' => 'Module',
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
                    'value' => '4.5.6',
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

    public function testDownWhenRecordDoesNotExists(): void
    {
        self::assertTrue($this->migration->down());

        self::assertSame(
            [
                [
                    'object' => 'RecordService',
                    'method' => 'get',
                    'name' => 'Module',
                ],
                [
                    'object' => 'Module',
                    'method' => 'filter',
                    'property' => 'module_name',
                    'value' => 'Ansel',
                ],
            ],
            $this->calls,
        );
    }

    public function testDownWhenRecordDoesExists(): void
    {
        $this->firstReturnsRecord = true;

        self::assertTrue($this->migration->down());

        self::assertSame(
            [
                [
                    'object' => 'RecordService',
                    'method' => 'get',
                    'name' => 'Module',
                ],
                [
                    'object' => 'Module',
                    'method' => 'filter',
                    'property' => 'module_name',
                    'value' => 'Ansel',
                ],
                [
                    'object' => 'Module',
                    'method' => 'delete',
                ],
            ],
            $this->calls,
        );
    }
}
