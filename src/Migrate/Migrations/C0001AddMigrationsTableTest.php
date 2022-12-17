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

class C0001AddMigrationsTableTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $tableExistsReturn = false;

    private C0001AddMigrationsTable $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->tableExistsReturn = false;

        $this->migration = new C0001AddMigrationsTable($this->mockDb());
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
                    'table' => '{{%ansel_migrations}}',
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

        self::assertCount(3, $this->calls);

        self::assertSame(
            [
                'object' => 'DbConnection',
                'method' => 'tableExists',
                'table' => '{{%ansel_migrations}}',
            ],
            $this->calls[0],
        );

        self::assertSame(
            'Command',
            $this->calls[1]['object'],
        );

        self::assertSame(
            'createTable',
            $this->calls[1]['method'],
        );

        self::assertSame(
            '{{%ansel_migrations}}',
            $this->calls[1]['table'],
        );

        $columns = $this->calls[1]['columns'];

        assert(is_array($columns));

        self::assertCount(2, $columns);

        $schemaId = $columns['id'];

        assert($schemaId instanceof ColumnSchemaBuilder);

        self::assertSame('pk', (string) $schemaId);

        $schemaMigration = $columns['migration'];

        assert($schemaMigration instanceof ColumnSchemaBuilder);

        self::assertSame('text', (string) $schemaMigration);

        self::assertSame(
            [
                'object' => 'Command',
                'method' => 'execute',
            ],
            $this->calls[2],
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
                    'table' => '{{%ansel_migrations}}',
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
