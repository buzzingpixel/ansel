<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Migrate\MigrationsTableContract;
use BuzzingPixel\Ansel\Migrate\RunMigration;
use BuzzingPixel\AnselConfig\ContainerManager;
use craft\db\Migration;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_filter;
use function count;
use function get_class;

/**
 * @codeCoverageIgnore
 */
abstract class AnselMigration extends Migration
{
    protected MigrationsTableContract $migrationsTable;

    protected MigrationContract $migration;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function init(): void
    {
        parent::init();

        $container = (new ContainerManager())->container();

        /** @phpstan-ignore-next-line */
        $this->migrationsTable = $container->get(
            MigrationsTableContract::class,
        );

        $this->migration = $this->setMigration($container);
    }

    abstract protected function setMigration(
        ContainerInterface $container
    ): MigrationContract;

    public function safeUp(): bool
    {
        $runMigration = array_filter(
            $this->migrationsTable->getRunMigrations(),
            fn (
                RunMigration $r
            ) => $r->migration() === get_class($this->migration),
        );

        if (count($runMigration) > 0) {
            return true;
        }

        $this->migration->up();

        $this->migrationsTable->addMigration($this->migration);

        return true;
    }

    public function safeDown(): bool
    {
        $runMigration = array_filter(
            $this->migrationsTable->getRunMigrations(),
            fn (
                RunMigration $r
            ) => $r->migration() === get_class($this->migration),
        );

        if (count($runMigration) < 1) {
            return true;
        }

        $this->migration->down();

        $this->migrationsTable->removeMigration($this->migration);

        return true;
    }
}
