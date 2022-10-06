<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Command;
use craft\db\Connection as DbConnection;
use PHPUnit\Framework\TestCase;
use yii\db\ColumnSchemaBuilder;
use yii\db\Exception;

use function assert;
use function is_array;

class C0008AddImagesTableTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $tableExistsReturn = false;

    private C0008AddImagesTable $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->migration = new C0008AddImagesTable($this->mockDb());
    }

    private function mockDb(): DbConnection
    {
        $mock = $this->createMock(DbConnection::class);

        $mock->method('tableExists')->willReturnCallback(
            function (string $table): bool {
                $this->calls[] = [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => $table,
                ];

                return $this->tableExistsReturn;
            }
        );

        $mock->method('getForeignKeyName')->willReturn(
            'fooBarForeign',
        );

        $mock->method('createCommand')->willReturnCallback(
            function (): Command {
                return $this->mockCommand();
            }
        );

        return $mock;
    }

    private function mockCommand(): Command
    {
        $mock = $this->createMock(Command::class);

        $mock->method('createTable')->willReturnCallback(
            function (
                string $table,
                array $columns
            ) use ($mock): Command {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'createTable',
                    'table' => $table,
                    'columns' => $columns,
                ];

                return $mock;
            }
        );

        $mock->method('addForeignKey')->willReturnCallback(
            function (
                string $name,
                string $table,
                array $columns,
                string $refTable,
                array $refColumns,
                string $delete,
                string $update
            ) use ($mock): Command {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'createTable',
                    'name' => $name,
                    'table' => $table,
                    'columns' => $columns,
                    'refTable' => $refTable,
                    'refColumns' => $refColumns,
                    'delete' => $delete,
                    'update' => $update,
                ];

                return $mock;
            }
        );

        $mock->method('dropTableIfExists')->willReturnCallback(
            function (string $table) use ($mock): Command {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'dropTableIfExists',
                    'table' => $table,
                ];

                return $mock;
            }
        );

        $mock->method('execute')->willReturnCallback(
            function (): int {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'execute',
                ];

                return 123;
            }
        );

        return $mock;
    }

    public function testFor(): void
    {
        self::assertSame(
            MigrationContract::CRAFT,
            $this->migration->for(),
        );
    }

    /**
     * @throws Exception
     */
    public function testUpWhenTableExists(): void
    {
        $this->tableExistsReturn = true;

        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => '{{%ansel_images}}',
                ],
            ],
            $this->calls,
        );
    }

    /**
     * @throws Exception
     */
    public function testUpWhenTableDoesNotExist(): void
    {
        $this->tableExistsReturn = false;

        self::assertTrue($this->migration->up());

        self::assertCount(17, $this->calls);

        self::assertSame(
            [
                'object' => 'DbConnection',
                'method' => 'tableExists',
                'table' => '{{%ansel_images}}',
            ],
            $this->calls[0],
        );

        self::assertSame(
            'Command',
            /** @phpstan-ignore-next-line */
            $this->calls[1]['object'],
        );

        self::assertSame(
            'createTable',
            /** @phpstan-ignore-next-line */
            $this->calls[1]['method'],
        );

        self::assertSame(
            '{{%ansel_images}}',
            /** @phpstan-ignore-next-line */
            $this->calls[1]['table'],
        );

        /** @phpstan-ignore-next-line */
        $columns = $this->calls[1]['columns'];

        assert(is_array($columns));

        self::assertCount(23, $columns);

        $schemaId = $columns['id'];
        assert($schemaId instanceof ColumnSchemaBuilder);
        self::assertSame('pk', (string) $schemaId);

        $schemaAnselId = $columns['ansel_id'];
        assert($schemaAnselId instanceof ColumnSchemaBuilder);
        self::assertSame(
            'string(255)',
            (string) $schemaAnselId,
        );

        $schemaElementId = $columns['element_id'];
        assert($schemaElementId instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer(11) NOT NULL',
            (string) $schemaElementId,
        );

        $schemaFieldId = $columns['field_id'];
        assert($schemaFieldId instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer(11) NOT NULL',
            (string) $schemaFieldId,
        );

        $schemaUserId = $columns['user_id'];
        assert($schemaUserId instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer(11) NOT NULL',
            (string) $schemaUserId,
        );

        $schemaAssetId = $columns['asset_id'];
        assert($schemaAssetId instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer(11) NOT NULL',
            (string) $schemaAssetId,
        );

        $schemaHighQualAssetId = $columns['high_qual_asset_id'];
        assert($schemaHighQualAssetId instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer(11)',
            (string) $schemaHighQualAssetId,
        );

        $schemaThumbAssetId = $columns['thumb_asset_id'];
        assert($schemaThumbAssetId instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer(11)',
            (string) $schemaThumbAssetId,
        );

        $schemaOrigAssetId = $columns['original_asset_id'];
        assert($schemaOrigAssetId instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer(11)',
            (string) $schemaOrigAssetId,
        );

        $schemaWidth = $columns['width'];
        assert($schemaWidth instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer NOT NULL',
            (string) $schemaWidth,
        );

        $schemaHeight = $columns['height'];
        assert($schemaHeight instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer NOT NULL',
            (string) $schemaHeight,
        );

        $schemaX = $columns['x'];
        assert($schemaX instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer NOT NULL',
            (string) $schemaX,
        );

        $schemaY = $columns['y'];
        assert($schemaY instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer NOT NULL',
            (string) $schemaY,
        );

        $schemaY = $columns['focal_x'];
        assert($schemaY instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer NOT NULL',
            (string) $schemaY,
        );

        $schemaY = $columns['focal_y'];
        assert($schemaY instanceof ColumnSchemaBuilder);
        self::assertSame(
            'integer NOT NULL',
            (string) $schemaY,
        );

        $schemaTitle = $columns['title'];
        assert($schemaTitle instanceof ColumnSchemaBuilder);
        self::assertSame(
            'string(255)',
            (string) $schemaTitle,
        );

        $schemaCaption = $columns['caption'];
        assert($schemaCaption instanceof ColumnSchemaBuilder);
        self::assertSame(
            'string(255)',
            (string) $schemaCaption,
        );

        $schemaCover = $columns['cover'];
        assert($schemaCover instanceof ColumnSchemaBuilder);
        self::assertSame(
            'tinyint(1) NOT NULL',
            (string) $schemaCover,
        );

        $schemaPosition = $columns['position'];
        assert($schemaPosition instanceof ColumnSchemaBuilder);
        self::assertSame(
            'tinyint(4) NOT NULL',
            (string) $schemaPosition,
        );

        $disabled = $columns['disabled'];
        assert($disabled instanceof ColumnSchemaBuilder);
        self::assertSame(
            'tinyint',
            (string) $disabled,
        );

        $schemaDateCreated = $columns['dateCreated'];
        assert($schemaDateCreated instanceof ColumnSchemaBuilder);
        self::assertSame(
            'datetime NOT NULL',
            (string) $schemaDateCreated,
        );

        $schemaDateUpdated = $columns['dateUpdated'];
        assert($schemaDateUpdated instanceof ColumnSchemaBuilder);
        self::assertSame(
            'datetime NOT NULL',
            (string) $schemaDateUpdated,
        );

        $schemaUid = $columns['uid'];
        assert($schemaUid instanceof ColumnSchemaBuilder);
        self::assertSame(
            "char(36) NOT NULL DEFAULT '0'",
            (string) $schemaUid,
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'execute',
            ],
            $this->calls[2],
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'createTable',
                'name' => 'fooBarForeign',
                'table' => '{{%ansel_images}}',
                'columns' => ['element_id'],
                'refTable' => '{{%elements}}',
                'refColumns' => ['id'],
                'delete' => 'RESTRICT',
                'update' => 'RESTRICT',
            ],
            $this->calls[3],
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'execute',
            ],
            $this->calls[4]
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'createTable',
                'name' => 'fooBarForeign',
                'table' => '{{%ansel_images}}',
                'columns' => ['field_id'],
                'refTable' => '{{%fields}}',
                'refColumns' => ['id'],
                'delete' => 'RESTRICT',
                'update' => 'RESTRICT',
            ],
            $this->calls[5],
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'execute',
            ],
            $this->calls[6]
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'createTable',
                'name' => 'fooBarForeign',
                'table' => '{{%ansel_images}}',
                'columns' => ['user_id'],
                'refTable' => '{{%users}}',
                'refColumns' => ['id'],
                'delete' => 'RESTRICT',
                'update' => 'RESTRICT',
            ],
            $this->calls[7],
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'execute',
            ],
            $this->calls[8]
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'createTable',
                'name' => 'fooBarForeign',
                'table' => '{{%ansel_images}}',
                'columns' => ['asset_id'],
                'refTable' => '{{%assets}}',
                'refColumns' => ['id'],
                'delete' => 'SET NULL',
                'update' => 'RESTRICT',
            ],
            $this->calls[9],
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'execute',
            ],
            $this->calls[10]
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'createTable',
                'name' => 'fooBarForeign',
                'table' => '{{%ansel_images}}',
                'columns' => ['high_qual_asset_id'],
                'refTable' => '{{%assets}}',
                'refColumns' => ['id'],
                'delete' => 'SET NULL',
                'update' => 'RESTRICT',
            ],
            $this->calls[11],
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'execute',
            ],
            $this->calls[12]
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'createTable',
                'name' => 'fooBarForeign',
                'table' => '{{%ansel_images}}',
                'columns' => ['thumb_asset_id'],
                'refTable' => '{{%assets}}',
                'refColumns' => ['id'],
                'delete' => 'SET NULL',
                'update' => 'RESTRICT',
            ],
            $this->calls[13],
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'execute',
            ],
            $this->calls[14]
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'createTable',
                'name' => 'fooBarForeign',
                'table' => '{{%ansel_images}}',
                'columns' => ['original_asset_id'],
                'refTable' => '{{%assets}}',
                'refColumns' => ['id'],
                'delete' => 'SET NULL',
                'update' => 'RESTRICT',
            ],
            $this->calls[15],
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'execute',
            ],
            $this->calls[16]
        );
    }

    /**
     * @throws Exception
     */
    public function testDown(): void
    {
        self::assertTrue($this->migration->down());

        self::assertSame(
            [
                [
                    'object' => 'Command',
                    'method' => 'dropTableIfExists',
                    'table' => '{{%ansel_images}}',
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
            ],
            $this->calls,
        );
    }
}
