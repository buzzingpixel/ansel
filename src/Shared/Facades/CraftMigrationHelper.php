<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Facades;

use craft\helpers\MigrationHelper;

/**
 * @codeCoverageIgnore
 */
class CraftMigrationHelper
{
    public function renameTable(string $oldName, string $newName): void
    {
        MigrationHelper::renameTable($oldName, $newName);
    }

    public function renameColumn(
        string $tableName,
        string $oldName,
        string $newName
    ): void {
        MigrationHelper::renameColumn(
            $tableName,
            $oldName,
            $newName,
        );
    }
}
