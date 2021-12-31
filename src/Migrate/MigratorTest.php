<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;

use function assert;
use function get_class;

class MigratorTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    /** @var MigrationContract[] */
    private array $migrations = [];

    /** @var RunMigration[] */
    private array $runMigrations = [];

    private Migrator $migrator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->migrations = [];

        $this->migrator = new Migrator(
            $this->mockMigrationsLoader(),
            $this->mockMigrationsTable(),
        );
    }

    private function mockMigrationsLoader(): MigrationsLoader
    {
        $mock = $this->createMock(MigrationsLoader::class);

        $mock->method('load')->willReturnCallback(
            function (string $for): array {
                $this->calls[] = [
                    'object' => 'MigrationsLoader',
                    'method' => 'load',
                    'for' => $for,
                ];

                return $this->migrations;
            }
        );

        return $mock;
    }

    private function mockMigrationsTable(): MigrationsTableContract
    {
        $mock = $this->createMock(
            MigrationsTableContract::class,
        );

        $mock->method('getRunMigrations')->willReturnCallback(
            function (): array {
                return $this->runMigrations;
            }
        );

        $mock->method('addMigration')->willReturnCallback(
            function (MigrationContract $migrationContract): void {
                $this->calls[] = [
                    'object' => 'MigrationsTableContract',
                    'method' => 'addMigration',
                    'migrationContract' => $migrationContract,
                ];
            }
        );

        $mock->method('removeMigration')->willReturnCallback(
            function (MigrationContract $migrationContract): void {
                $this->calls[] = [
                    'object' => 'MigrationsTableContract',
                    'method' => 'removeMigration',
                    'migrationContract' => $migrationContract,
                ];
            }
        );

        return $mock;
    }

    public function testUpWhenThrowsException(): void
    {
        $migration = $this->createMock(
            MigrationContract::class,
        );

        $migration->method('up')->willReturn(false);

        $this->migrations[] = $migration;

        $exception = null;

        try {
            $this->migrator->migrateUp('fooBarFor');
        } catch (Throwable $e) {
            $exception = $e;
        }

        assert($exception instanceof RuntimeException);

        self::assertSame(
            'Failed to run migrations',
            $exception->getMessage(),
        );

        self::assertSame(
            [
                [
                    'object' => 'MigrationsLoader',
                    'method' => 'load',
                    'for' => 'fooBarFor',
                ],
            ],
            $this->calls,
        );
    }

    public function testUpWhenHasBeenRun(): void
    {
        $this->migrations[] = new NoOpMigration();

        $this->runMigrations[] = new RunMigration(
            NoOpMigration::class,
        );

        $this->migrator->migrateUp('testing');

        self::assertSame(
            [
                [
                    'object' => 'MigrationsLoader',
                    'method' => 'load',
                    'for' => 'testing',
                ],
            ],
            $this->calls,
        );
    }

    public function testUp(): void
    {
        $migration = new NoOpMigration();

        $this->migrations[] = $migration;

        $this->migrator->migrateUp('testing');

        self::assertSame(
            [
                [
                    'object' => 'MigrationsLoader',
                    'method' => 'load',
                    'for' => 'testing',
                ],
                [
                    'object' => 'MigrationsTableContract',
                    'method' => 'addMigration',
                    'migrationContract' => $migration,
                ],
            ],
            $this->calls,
        );
    }

    public function testDownWhenThrowsException(): void
    {
        $migration = $this->createMock(
            MigrationContract::class,
        );

        $migration->method('down')->willReturn(false);

        $this->migrations[] = $migration;

        $this->runMigrations[] = new RunMigration(
            get_class($migration),
        );

        $exception =     null;

        try {
            $this->migrator->migrateDown('fooBarFor');
        } catch (Throwable $e) {
            $exception = $e;
        }

        assert($exception instanceof RuntimeException);

        self::assertSame(
            'Failed to run migrations',
            $exception->getMessage(),
        );

        self::assertSame(
            [
                [
                    'object' => 'MigrationsLoader',
                    'method' => 'load',
                    'for' => 'fooBarFor',
                ],
            ],
            $this->calls,
        );
    }

    public function testUpWhenWasNotUpped(): void
    {
        $this->migrations[] = new NoOpMigration();

        $this->migrator->migrateDown('testing');

        self::assertSame(
            [
                [
                    'object' => 'MigrationsLoader',
                    'method' => 'load',
                    'for' => 'testing',
                ],
            ],
            $this->calls,
        );
    }

    public function testDown(): void
    {
        $migration = new NoOpMigration();

        $this->migrations[] = $migration;

        $this->runMigrations[] = new RunMigration(
            NoOpMigration::class,
        );

        $this->migrator->migrateDown('testing');

        self::assertSame(
            [
                [
                    'object' => 'MigrationsLoader',
                    'method' => 'load',
                    'for' => 'testing',
                ],
                [
                    'object' => 'MigrationsTableContract',
                    'method' => 'removeMigration',
                    'migrationContract' => $migration,
                ],
            ],
            $this->calls,
        );
    }
}
