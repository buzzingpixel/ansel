<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_forge;
use ExpressionEngine\Service\Database\Query;
use PHPUnit\Framework\TestCase;

class E0008AddImagesTableTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $tableExistsReturn = false;

    private E0008AddImagesTable $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->tableExistsReturn = false;

        $this->migration = new E0008AddImagesTable(
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
                        'ansel_id' => [
                            'default' => '',
                            'type' => 'VARCHAR',
                            'constraint' => 255,
                        ],
                        'site_id' => [
                            'default' => 1,
                            'type' => 'TINYINT',
                            'unsigned' => true,
                        ],
                        'source_id' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'content_id' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'field_id' => [
                            'default' => 0,
                            'type' => 'MEDIUMINT',
                            'unsigned' => true,
                        ],
                        'content_type' => [
                            'default' => 'channel',
                            'null' => false,
                            'type' => 'VARCHAR',
                            'constraint' => 255,
                        ],
                        'row_id' => [
                            'type' => 'INT',
                            'unsigned' => true,
                            'default' => 0,
                        ],
                        'col_id' => [
                            'type' => 'INT',
                            'unsigned' => true,
                            'default' => 0,
                        ],
                        'file_id' => [
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'original_location_type' => [
                            'default' => 'ee',
                            'type' => 'VARCHAR',
                            'constraint' => 10,
                        ],
                        'original_file_id' => [
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'upload_location_type' => [
                            'default' => 'ee',
                            'type' => 'VARCHAR',
                            'constraint' => 10,
                        ],
                        'upload_location_id' => [
                            'default' => '',
                            'type' => 'VARCHAR',
                            'constraint' => 255,
                        ],
                        'filename' => ['type' => 'TEXT'],
                        'extension' => [
                            'default' => '',
                            'type' => 'VARCHAR',
                            'constraint' => 10,
                        ],
                        'original_extension' => [
                            'default' => '',
                            'type' => 'VARCHAR',
                            'constraint' => 10,
                        ],
                        'filesize' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'original_filesize' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'width' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'height' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'x' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'y' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'focal_x' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'focal_y' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'title' => [
                            'default' => '',
                            'type' => 'VARCHAR',
                            'constraint' => 255,
                        ],
                        'caption' => [
                            'default' => '',
                            'constraint' => 255,
                            'type' => 'VARCHAR',
                        ],
                        'member_id' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'position' => [
                            'default' => 1,
                            'type' => 'TINYINT',
                            'unsigned' => true,
                        ],
                        'cover' => [
                            'constraint' => 1,
                            'default' => 0,
                            'type' => 'TINYINT',
                            'unsigned' => true,
                        ],
                        'upload_date' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'modify_date' => [
                            'default' => 0,
                            'type' => 'INT',
                            'unsigned' => true,
                        ],
                        'disabled' => [
                            'constraint' => 1,
                            'default' => 0,
                            'type' => 'TINYINT',
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
                    'table' => 'ansel_images',
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
                    'tableName' => 'ansel_images',
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
                    'tableName' => 'ansel_images',
                ],
                [
                    'object' => 'CI_DB_forge',
                    'method' => 'drop_table',
                    'tableName' => 'ansel_images',
                ],
            ],
            $this->calls,
        );
    }
}
