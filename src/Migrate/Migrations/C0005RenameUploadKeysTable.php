<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\Facades\CraftMigrationHelper;
use craft\db\Connection as DbConnection;

class C0005RenameUploadKeysTable implements MigrationContract
{
    private DbConnection $db;

    private CraftMigrationHelper $migrationHelper;

    public function __construct(
        DbConnection $db,
        CraftMigrationHelper $migrationHelper
    ) {
        $this->db              = $db;
        $this->migrationHelper = $migrationHelper;
    }

    public function for(): string
    {
        return MigrationContract::CRAFT;
    }

    public function up(): bool
    {
        if (! $this->db->tableExists('{{%anselUploadKeys}}')) {
            return true;
        }

        $this->migrationHelper->renameTable(
            '{{%anselUploadKeys}}',
            '{{%ansel_upload_keys}}',
        );

        return true;
    }

    public function down(): bool
    {
        /**
         * We're not going to try to revert this. This new Ansel codebase
         * doesn't know anything about the old table name.
         */
        return true;
    }
}
