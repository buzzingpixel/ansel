<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Connection as DbConnection;

class C0004RemoveOldLicensingSettings implements MigrationContract
{
    private DbConnection $db;

    public function __construct(DbConnection $db)
    {
        $this->db = $db;
    }

    public function for(): string
    {
        return MigrationContract::CRAFT;
    }

    public function up(): bool
    {
        $tableName = '';

        if ($this->db->tableExists('{{%anselSettings}}')) {
            $tableName = '{{%anselSettings}}';
        } elseif ($this->db->tableExists('{{%ansel_settings}}')) {
            $tableName = '{{%ansel_settings}}';
        }

        if ($tableName === '') {
            return true;
        }

        $this->db->createCommand()->delete(
            $tableName,
            "settingsKey = 'licenseKey'",
        );

        $this->db->createCommand()->delete(
            $tableName,
            "settingsKey = 'phoneHome'",
        );

        $this->db->createCommand()->delete(
            $tableName,
            "settingsKey = 'encoding'",
        );

        $this->db->createCommand()->delete(
            $tableName,
            "settingsKey = 'encodingData'",
        );

        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
