<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_forge;
use ExpressionEngine\Service\Database\Query;
use PHPUnit\Framework\TestCase;

class E0007AddUploadKeysTableTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $tableExistsReturn = false;

    private E0007AddUploadKeysTable $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->tableExistsReturn = false;

        $this->migration = new E0007AddUploadKeysTable(
            $this->mockForge(),
            $this->mockQueryBuilderFactory(),
        );
    }

    private function mockForge(): CI_DB_forge
    {
        $mock = $this->createMock(CI_DB_forge::class);

        $mock->method('add_field')->willReturnCallback(
            function (array $field): void {
                $this->calls[] = [
                    'object' => 'CI_DB_forge',
                    'method' => 'add_field',
                    'field' => $field,
                ];
            }
        );

        $mock->method('add_key')->willReturnCallback(
            function (string $key, bool $primary): void {
                $this->calls[] = [
                    'object' => 'CI_DB_forge',
                    'method' => 'add_key',
                    'key' => $key,
                    'primary' => $primary,
                ];
            }
        );

        $mock->method('create_table')->willReturnCallback(
            function (string $table, bool $ifNotExists): void {
                $this->calls[] = [
                    'object' => 'CI_DB_forge',
                    'method' => 'create_table',
                    'table' => $table,
                    'ifNotExists' => $ifNotExists,
                ];
            }
        );

        $mock->method('drop_table')->willReturnCallback(
            function (string $tableName): void {
                $this->calls[] = [
                    'object' => 'CI_DB_forge',
                    'method' => 'drop_table',
                    'tableName' => $tableName,
                ];
            }
        );

        return $mock;
    }

    private function mockQueryBuilderFactory(): EeQueryBuilderFactory
    {
        $mock = $this->createMock(
            EeQueryBuilderFactory::class,
        );

        $mock->method('create')->willReturn(
            $this->mockQuery(),
        );

        return $mock;
    }

    private function mockQuery(): Query
    {
        $mock = $this->createMock(Query::class);

        $mock->method('table_exists')->willReturnCallback(
            function (string $tableName): bool {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'table_exists',
                    'tableName' => $tableName,
                ];

                return $this->tableExistsReturn;
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

    public function testUp(): void
    {
        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'CI_DB_forge',
                    'method' => 'add_field',
                    'field' => [
                        'id' => [
                            'type' => 'INT',
                            'unsigned' => true,
                            'auto_increment' => true,
                        ],
                        'key' => ['type' => 'TEXT'],
                        'created' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'expires' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                    ],
                ],
                [
                    'object' => 'CI_DB_forge',
                    'method' => 'add_key',
                    'key' => 'id',
                    'primary' => true,
                ],
                [
                    'object' => 'CI_DB_forge',
                    'method' => 'create_table',
                    'table' => 'ansel_upload_keys',
                    'ifNotExists' => true,
                ],
            ],
            $this->calls,
        );
    }

    public function testDownWhenTableDoesNotExist(): void
    {
        self::assertTrue($this->migration->down());

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'table_exists',
                    'tableName' => 'ansel_upload_keys',
                ],
            ],
            $this->calls,
        );
    }

    public function testDown(): void
    {
        $this->tableExistsReturn = true;

        self::assertTrue($this->migration->down());

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'table_exists',
                    'tableName' => 'ansel_upload_keys',
                ],
                [
                    'object' => 'CI_DB_forge',
                    'method' => 'drop_table',
                    'tableName' => 'ansel_upload_keys',
                ],
            ],
            $this->calls,
        );
    }
}
