<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use BuzzingPixel\Ansel\Migrate\Migrations\E0001AddMigrationsTable;
use BuzzingPixel\Ansel\Shared\CraftQueryBuilderFactory;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_forge;
use craft\db\Command;
use craft\db\Connection as DbConnection;
use craft\db\Query;
use PHPUnit\Framework\TestCase;
use yii\db\Exception;

use function array_map;

class MigrationsTableCraftTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $tableExistsReturn = false;

    private MigrationsTableCraft $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->tableExistsReturn = false;

        $this->service = new MigrationsTableCraft(
            $this->mockDb(),
            $this->mockQueryBuilderFactory(),
        );
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

        $mock->method('insert')->willReturnCallback(
            function (
                string $table,
                array $columns,
                bool $includeAuditColumns
            ) use ($mock): Command {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'insert',
                    'table' => $table,
                    'columns' => $columns,
                    'includeAuditColumns' => $includeAuditColumns,
                ];

                return $mock;
            }
        );

        $mock->method('delete')->willReturnCallback(
            function (
                string $table,
                string $condition,
                array $params
            ) use ($mock): Command {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => $table,
                    'condition' => $condition,
                    'params' => $params,
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

    private function mockQueryBuilderFactory(): CraftQueryBuilderFactory
    {
        $mock = $this->createMock(
            CraftQueryBuilderFactory::class,
        );

        $mock->method('create')->willReturn(
            $this->mockQuery(),
        );

        return $mock;
    }

    private function mockQuery(): Query
    {
        $mock = $this->createMock(Query::class);

        $mock->method('select')->willReturnCallback(
            function (string $columns) use ($mock): Query {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'select',
                    'columns' => $columns,
                ];

                return $mock;
            }
        );

        $mock->method('from')->willReturnCallback(
            function (string $tables) use ($mock): Query {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'from',
                    'tables' => $tables,
                ];

                return $mock;
            }
        );

        $mock->method('all')->willReturn([
            ['migration' => 'fooBarMigration1'],
            ['migration' => 'fooBarMigration2'],
        ]);

        return $mock;
    }

    public function testGetRunMigrationsWhenTableDoesNotExist(): void
    {
        $this->tableExistsReturn = false;

        self::assertSame(
            [],
            $this->service->getRunMigrations(),
        );

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

    public function testGetRunMigrationsWhenTableExists(): void
    {
        $this->tableExistsReturn = true;

        self::assertSame(
            [
                'fooBarMigration1',
                'fooBarMigration2',
            ],
            array_map(
                static fn (RunMigration $r) => $r->migration(),
                $this->service->getRunMigrations(),
            ),
        );

        self::assertSame(
            [
                [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => '{{%ansel_migrations}}',
                ],
                [
                    'object' => 'Query',
                    'method' => 'select',
                    'columns' => '*',
                ],
                [
                    'object' => 'Query',
                    'method' => 'from',
                    'tables' => '{{%ansel_migrations}}',
                ],
            ],
            $this->calls,
        );
    }

    /**
     * @throws Exception
     */
    public function testAddMigration(): void
    {
        $migration = new E0001AddMigrationsTable(
            $this->createMock(CI_DB_forge::class),
            $this->createMock(
                EeQueryBuilderFactory::class,
            ),
        );

        $this->service->addMigration($migration);

        self::assertSame(
            [
                [
                    'object' => 'Command',
                    'method' => 'insert',
                    'table' => '{{%ansel_migrations}}',
                    'columns' => ['migration' => E0001AddMigrationsTable::class],
                    'includeAuditColumns' => false,
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
            ],
            $this->calls,
        );
    }

    public function testRemoveMigrationWhenTableDoesNotExist(): void
    {
        $this->tableExistsReturn = false;

        $migration = new E0001AddMigrationsTable(
            $this->createMock(CI_DB_forge::class),
            $this->createMock(
                EeQueryBuilderFactory::class,
            ),
        );

        $this->service->removeMigration($migration);

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

    public function testRemoveMigrationWhenTableExists(): void
    {
        $this->tableExistsReturn = true;

        $migration = new E0001AddMigrationsTable(
            $this->createMock(CI_DB_forge::class),
            $this->createMock(
                EeQueryBuilderFactory::class,
            ),
        );

        $this->service->removeMigration($migration);

        self::assertSame(
            [
                [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => '{{%ansel_migrations}}',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_migrations}}',
                    'condition' => '`migration` = :migration',
                    'params' => [':migration' => E0001AddMigrationsTable::class],
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
