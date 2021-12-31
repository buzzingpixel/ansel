<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

interface MigrationsTableContract
{
    /**
     * @return RunMigration[]
     */
    public function getRunMigrations(): array;

    public function addMigration(MigrationContract $migration): void;

    public function removeMigration(MigrationContract $migration): void;
}
