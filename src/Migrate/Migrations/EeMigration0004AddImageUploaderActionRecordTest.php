<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use ExpressionEngine\Model\Addon\Action;
use ExpressionEngine\Service\Model\Facade as RecordService;
use ExpressionEngine\Service\Model\Query\Builder;
use PHPUnit\Framework\TestCase;

class EeMigration0004AddImageUploaderActionRecordTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $firstReturnsRecord;

    private EeMigration0004AddImageUploaderActionRecord $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->firstReturnsRecord = false;

        $this->migration = new EeMigration0004AddImageUploaderActionRecord(
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
            function (string $name): Action {
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
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => $property,
                    'value' => $value,
                ];

                return $mock;
            }
        );

        $mock->method('first')->willReturnCallback(
            function (): ?Action {
                if (! $this->firstReturnsRecord) {
                    return null;
                }

                $mock = $this->createMock(Action::class);

                $mock->method('delete')->willReturnCallback(
                    function () use ($mock): Action {
                        $this->calls[] = [
                            'object' => 'Action',
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

    private function mockRecord(): Action
    {
        $mock = $this->createMock(Action::class);

        $mock->method('setProperty')->willReturnCallback(
            function (
                string $name,
                $value
            ) use (
                $mock
            ): Action {
                $this->calls[] = [
                    'object' => 'Action',
                    'method' => 'setProperty',
                    'name' => $name,
                    'value' => $value,
                ];

                return $mock;
            }
        );

        $mock->method('save')->willReturnCallback(
            function () use ($mock): Action {
                $this->calls[] = [
                    'object' => 'Action',
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
                    'name' => 'Action',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'class',
                    'value' => 'Ansel',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'method',
                    'value' => 'imageUploader',
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
                    'name' => 'Action',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'class',
                    'value' => 'Ansel',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'method',
                    'value' => 'imageUploader',
                ],
                [
                    'object' => 'RecordService',
                    'method' => 'make',
                    'name' => 'Action',
                ],
                [
                    'object' => 'Action',
                    'method' => 'setProperty',
                    'name' => 'class',
                    'value' => 'Ansel',
                ],
                [
                    'object' => 'Action',
                    'method' => 'setProperty',
                    'name' => 'method',
                    'value' => 'imageUploader',
                ],
                [
                    'object' => 'Action',
                    'method' => 'setProperty',
                    'name' => 'csrf_exempt',
                    'value' => true,
                ],
                [
                    'object' => 'Action',
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
                    'name' => 'Action',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'class',
                    'value' => 'Ansel',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'method',
                    'value' => 'imageUploader',
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
                    'name' => 'Action',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'class',
                    'value' => 'Ansel',
                ],
                [
                    'object' => 'Builder',
                    'method' => 'filter',
                    'property' => 'method',
                    'value' => 'imageUploader',
                ],
                [
                    'object' => 'Action',
                    'method' => 'delete',
                ],
            ],
            $this->calls,
        );
    }
}
