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

class C0003AddSettingsTableTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $tableExistsReturn = false;

    private C0003AddSettingsTable $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->tableExistsReturn = false;

        $this->migration = new C0003AddSettingsTable($this->mockDb());
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

        $mock->method('batchInsert')->willReturnCallback(
            function (
                string $table,
                array $columns,
                array $rows
            ) use ($mock): Command {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'dropTableIfExists',
                    'table' => $table,
                    'columns' => $columns,
                    'rows' => $rows,
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
                    'table' => '{{%ansel_settings}}',
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

        self::assertCount(5, $this->calls);

        self::assertSame(
            [
                'object' => 'DbConnection',
                'method' => 'tableExists',
                'table' => '{{%ansel_settings}}',
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
            '{{%ansel_settings}}',
            /** @phpstan-ignore-next-line */
            $this->calls[1]['table'],
        );

        /** @phpstan-ignore-next-line */
        $columns = $this->calls[1]['columns'];

        assert(is_array($columns));

        self::assertCount(7, $columns);

        $schemaId = $columns['id'];
        assert($schemaId instanceof ColumnSchemaBuilder);
        self::assertSame('pk', (string) $schemaId);

        $schemaSettingsType = $columns['settingsType'];
        assert($schemaSettingsType instanceof ColumnSchemaBuilder);
        self::assertSame(
            'tinytext NOT NULL',
            (string) $schemaSettingsType,
        );

        $schemaSettingsKey = $columns['settingsKey'];
        assert($schemaSettingsKey instanceof ColumnSchemaBuilder);
        self::assertSame(
            'tinytext NOT NULL',
            (string) $schemaSettingsKey,
        );

        $schemaSettingsValue = $columns['settingsValue'];
        assert($schemaSettingsValue instanceof ColumnSchemaBuilder);
        self::assertSame(
            'text',
            (string) $schemaSettingsValue,
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
                'method' => 'dropTableIfExists',
                'table' => '{{%ansel_settings}}',
                'columns' => ['settingsType', 'settingsKey', 'settingsValue'],
                'rows' => [
                    ['string', 'licenseKey', null],
                    ['int', 'phoneHome', 0],
                    ['string', 'defaultHost', null],
                    ['int', 'defaultMaxQty', null],
                    ['int', 'defaultImageQuality', 90],
                    ['bool', 'defaultJpg', 'n'],
                    ['bool', 'defaultRetina', 'n'],
                    ['bool', 'defaultShowTitle', 'n'],
                    ['bool', 'defaultRequireTitle', 'n'],
                    ['string', 'defaultTitleLabel', null],
                    ['bool', 'defaultShowCaption', 'n'],
                    ['bool', 'defaultRequireCaption', 'n'],
                    ['string', 'defaultCaptionLabel', null],
                    ['bool', 'defaultShowCover', 'n'],
                    ['bool', 'defaultRequireCover', 'n'],
                    ['string', 'defaultCoverLabel', null],
                    ['bool', 'hideSourceSaveInstructions', 'n'],
                    ['string', 'encoding', ''],
                    ['string', 'encodingData', ''],
                ],
            ],
            $this->calls[3],
        );

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'execute',
            ],
            $this->calls[4],
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
                    'table' => '{{%ansel_settings}}',
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
