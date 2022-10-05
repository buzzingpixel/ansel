<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\Facades\CraftMigrationHelper;
use craft\db\Connection as DbConnection;
use yii\base\NotSupportedException;

class C0012RenameOldColumnNames implements MigrationContract
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

    /**
     * @throws NotSupportedException
     */
    public function up(): bool
    {
        if (
            $this->db->columnExists(
                '{{%ansel_images}}',
                'elementId',
            )
        ) {
            $this->migrationHelper->renameColumn(
                '{{%ansel_images}}',
                'elementId',
                'element_id',
            );
        }

        if (
            $this->db->columnExists(
                '{{%ansel_images}}',
                'fieldId',
            )
        ) {
            $this->migrationHelper->renameColumn(
                '{{%ansel_images}}',
                'fieldId',
                'field_id',
            );
        }

        if (
            $this->db->columnExists(
                '{{%ansel_images}}',
                'userId',
            )
        ) {
            $this->migrationHelper->renameColumn(
                '{{%ansel_images}}',
                'userId',
                'user_id',
            );
        }

        if (
            $this->db->columnExists(
                '{{%ansel_images}}',
                'assetId',
            )
        ) {
            $this->migrationHelper->renameColumn(
                '{{%ansel_images}}',
                'assetId',
                'asset_id',
            );
        }

        if (
            $this->db->columnExists(
                '{{%ansel_images}}',
                'highQualAssetId',
            )
        ) {
            $this->migrationHelper->renameColumn(
                '{{%ansel_images}}',
                'highQualAssetId',
                'high_qual_asset_id',
            );
        }

        if (
            $this->db->columnExists(
                '{{%ansel_images}}',
                'thumbAssetId',
            )
        ) {
            $this->migrationHelper->renameColumn(
                '{{%ansel_images}}',
                'thumbAssetId',
                'thumb_asset_id',
            );
        }

        if (
            $this->db->columnExists(
                '{{%ansel_images}}',
                'originalAssetId',
            )
        ) {
            $this->migrationHelper->renameColumn(
                '{{%ansel_images}}',
                'originalAssetId',
                'original_asset_id',
            );
        }

        return true;
    }

    public function down(): bool
    {
        /**
         * We're not going to try to revert this. This new Ansel codebase
         * doesn't know anything about the old column names.
         */
        return true;
    }
}
