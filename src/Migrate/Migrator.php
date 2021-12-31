<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use RuntimeException;

use function array_filter;
use function array_reverse;
use function count;
use function get_class;

class Migrator
{
    private MigrationsLoader $migrationsLoader;

    private MigrationsTableContract $migrationsTable;

    public function __construct(
        MigrationsLoader $migrationsLoader,
        MigrationsTableContract $migrationsTable
    ) {
        $this->migrationsLoader = $migrationsLoader;
        $this->migrationsTable  = $migrationsTable;
    }

    public function migrateUp(string $for): void
    {
        $migrations = $this->migrationsLoader->load($for);

        $runMigrations = $this->migrationsTable->getRunMigrations();

        foreach ($migrations as $migration) {
            $runMigration = array_filter(
                $runMigrations,
                static fn (
                    RunMigration $run
                ) => get_class($migration) === $run->migration()
            );

            if (count($runMigration) > 0) {
                continue;
            }

            if (! $migration->up()) {
                throw new RuntimeException(
                    'Failed to run migrations',
                );
            }

            $this->migrationsTable->addMigration($migration);
        }
    }

    public function migrateDown(string $for): void
    {
        $migrations = $this->migrationsLoader->load($for);

        $migrations = array_reverse($migrations);

        $runMigrations = $this->migrationsTable->getRunMigrations();

        foreach ($migrations as $migration) {
            $runMigration = array_filter(
                $runMigrations,
                static fn (
                    RunMigration $runMigration
                ) => get_class($migration) === $runMigration->migration()
            );

            if (count($runMigration) < 1) {
                continue;
            }

            if (! $migration->down()) {
                throw new RuntimeException(
                    'Failed to run migrations',
                );
            }

            $this->migrationsTable->removeMigration($migration);
        }
    }
}
