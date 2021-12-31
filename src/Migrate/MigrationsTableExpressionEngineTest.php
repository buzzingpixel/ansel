<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_result;
use ExpressionEngine\Service\Database\Query;
use PHPUnit\Framework\TestCase;

use function array_map;

class MigrationsTableExpressionEngineTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $tableExistsReturn = false;

    private MigrationsTableExpressionEngine $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->tableExistsReturn = false;

        $this->service = new MigrationsTableExpressionEngine(
            $this->mockQueryBuilderFactory(),
        );
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

        $mock->method('get')->willReturnCallback(
            function (string $tableName): CI_DB_result {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => $tableName,
                ];

                return $this->mockDbResult();
            }
        );

        $mock->method('insert')->willReturnCallback(
            function (string $table, array $set): void {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => $table,
                    'set' => $set,
                ];
            }
        );

        $mock->method('delete')->willReturnCallback(
            function (string $table, array $where): void {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => $table,
                    'where' => $where,
                ];
            }
        );

        return $mock;
    }

    private function mockDbResult(): CI_DB_result
    {
        $mock = $this->createMock(CI_DB_result::class);

        $mock->method('result_array')->willReturn([
            ['migration' => 'fooBarMigration1'],
            ['migration' => 'fooBarMigration2'],
        ]);

        return $mock;
    }

    public function testGetRunMigrationsWhenNoTable(): void
    {
        self::assertSame(
            [],
            $this->service->getRunMigrations(),
        );

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'table_exists',
                    'tableName' => 'ansel_migrations',
                ],
            ],
            $this->calls,
        );
    }

    public function testGetRunMigrationsWhenHasTable(): void
    {
        $this->tableExistsReturn = true;

        self::assertSame(
            [
                ['migration' => 'fooBarMigration1'],
                ['migration' => 'fooBarMigration2'],
            ],
            array_map(
                static fn (RunMigration $m) => [
                    'migration' => $m->migration(),
                ],
                $this->service->getRunMigrations(),
            ),
        );

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'table_exists',
                    'tableName' => 'ansel_migrations',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => 'ansel_migrations',
                ],
            ],
            $this->calls,
        );
    }

    public function testAddMigration(): void
    {
        $migration = new NoOpMigration();

        $this->service->addMigration($migration);

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_migrations',
                    'set' => ['migration' => NoOpMigration::class],
                ],
            ],
            $this->calls,
        );
    }

    public function testRemoveMigrationWhenNoTable(): void
    {
        $migration = new NoOpMigration();

        $this->service->removeMigration($migration);

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'table_exists',
                    'tableName' => 'ansel_migrations',
                ],
            ],
            $this->calls,
        );
    }

    public function testRemoveMigrationWhenHasTable(): void
    {
        $this->tableExistsReturn = true;

        $migration = new NoOpMigration();

        $this->service->removeMigration($migration);

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'table_exists',
                    'tableName' => 'ansel_migrations',
                ],
                [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => 'ansel_migrations',
                    'where' => ['migration' => NoOpMigration::class],
                ],
            ],
            $this->calls,
        );
    }
}
