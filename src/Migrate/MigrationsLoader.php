<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use DirectoryIterator;
use Psr\Container\ContainerInterface;
use SplFileInfo;
use Throwable;

use function array_filter;
use function array_map;
use function assert;
use function class_implements;
use function get_declared_classes;
use function in_array;
use function is_array;
use function ksort;

/**
 * @codeCoverageIgnore
 */
class MigrationsLoader
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return MigrationContract[]
     */
    public function load(?string $for = null): array
    {
        /**
         * Make sure all migration classes have been loaded so
         * get_declared_classes() will work properly
         */
        $dir = new DirectoryIterator(__DIR__ . '/Migrations');

        foreach ($dir as $fileInfo) {
            /** @phpstan-ignore-next-line */
            assert($fileInfo instanceof SplFileInfo);

            if ($fileInfo->getExtension() !== 'php') {
                continue;
            }

            try {
                include_once $fileInfo->getPathname();
            } catch (Throwable $e) {
            }
        }

        $migrationClassesUnsorted = array_filter(
            get_declared_classes(),
            static function ($className) {
                $implements = class_implements($className);

                if (! is_array($implements)) {
                    return false;
                }

                return in_array(
                    MigrationContract::class,
                    $implements,
                    true,
                );
            }
        );

        $migrationClasses = [];

        foreach ($migrationClassesUnsorted as $migrationClass) {
            $migrationClasses[$migrationClass] = $migrationClass;
        }

        ksort($migrationClasses);

        $migrations = array_map(
            function (string $className): ?MigrationContract {
                try {
                    $class = $this->container->get($className);

                    assert($class instanceof MigrationContract);

                    return $class;
                } catch (Throwable $e) {
                    return null;
                }
            },
            $migrationClasses,
        );

        $migrations = array_filter(
            $migrations,
            static fn (?MigrationContract $m) => $m !== null,
        );

        if ($for === null) {
            return $migrations;
        }

        return array_filter(
            $migrations,
            static fn (MigrationContract $m) => $m->for() === $for,
        );
    }
}
