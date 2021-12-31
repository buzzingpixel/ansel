<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;

use function array_map;
use function get_class;

class MigrationsTableExpressionEngine implements MigrationsTableContract
{
    private EeQueryBuilderFactory $queryBuilderFactory;

    public function __construct(EeQueryBuilderFactory $queryBuilderFactory)
    {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @inheritDoc
     */
    public function getRunMigrations(): array
    {
        if (
            ! $this->queryBuilderFactory->create()->table_exists(
                'ansel_migrations'
            )
        ) {
            return [];
        }

        $runs = $this->queryBuilderFactory->create()
            ->get('ansel_migrations')
            ->result_array();

        return array_map(
            static function (array $result): RunMigration {
                return new RunMigration($result['migration']);
            },
            $runs,
        );
    }

    public function addMigration(MigrationContract $migration): void
    {
        $this->queryBuilderFactory->create()->insert(
            'ansel_migrations',
            ['migration' => get_class($migration)],
        );
    }

    public function removeMigration(MigrationContract $migration): void
    {
        if (
            ! $this->queryBuilderFactory->create()->table_exists(
                'ansel_migrations'
            )
        ) {
            return;
        }

        $this->queryBuilderFactory->create()->delete(
            'ansel_migrations',
            ['migration' => get_class($migration)],
        );
    }
}
