<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Connection as DbConnection;
use yii\db\Exception;

class C0011RemoveOldSettings implements MigrationContract
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

    /**
     * @throws Exception
     */
    public function up(): bool
    {
        $this->db->createCommand()->delete(
            '{{%ansel_settings}}',
            "settingsKey = 'defaultShowTitle'"
        )->execute();

        $this->db->createCommand()->delete(
            '{{%ansel_settings}}',
            "settingsKey = 'defaultRequireTitle'"
        )->execute();

        $this->db->createCommand()->delete(
            '{{%ansel_settings}}',
            "settingsKey = 'defaultTitleLabel'"
        )->execute();

        $this->db->createCommand()->delete(
            '{{%ansel_settings}}',
            "settingsKey = 'defaultShowCaption'"
        )->execute();

        $this->db->createCommand()->delete(
            '{{%ansel_settings}}',
            "settingsKey = 'defaultRequireCaption'"
        )->execute();

        $this->db->createCommand()->delete(
            '{{%ansel_settings}}',
            "settingsKey = 'defaultCaptionLabel'"
        )->execute();

        $this->db->createCommand()->delete(
            '{{%ansel_settings}}',
            "settingsKey = 'defaultShowCover'"
        )->execute();

        $this->db->createCommand()->delete(
            '{{%ansel_settings}}',
            "settingsKey = 'defaultRequireCover'"
        )->execute();

        $this->db->createCommand()->delete(
            '{{%ansel_settings}}',
            "settingsKey = 'defaultCoverLabel'"
        )->execute();

        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
