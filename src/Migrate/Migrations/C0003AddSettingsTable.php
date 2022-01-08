<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Connection as DbConnection;
use yii\db\ColumnSchemaBuilder;
use yii\db\Exception;
use yii\db\Schema;

class C0003AddSettingsTable implements MigrationContract
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
        if ($this->db->tableExists('{{%ansel_settings}}')) {
            return true;
        }

        $this->db->createCommand()->createTable(
            '{{%ansel_settings}}',
            [
                'id' => new ColumnSchemaBuilder(Schema::TYPE_PK),
                'settingsType' => (new ColumnSchemaBuilder('tinytext'))
                    ->notNull(),
                'settingsKey' => (new ColumnSchemaBuilder('tinytext'))
                    ->notNull(),
                'settingsValue' => new ColumnSchemaBuilder(
                    Schema::TYPE_TEXT,
                ),
                'dateCreated' => (new ColumnSchemaBuilder(
                    Schema::TYPE_DATETIME,
                ))->notNull(),
                'dateUpdated' => (new ColumnSchemaBuilder(
                    Schema::TYPE_DATETIME,
                ))->notNull(),
                'uid' => (new ColumnSchemaBuilder(
                    Schema::TYPE_CHAR,
                    36,
                ))->notNull()->defaultValue('0'),
            ]
        )->execute();

        $this->db->createCommand()->batchInsert(
            '{{%ansel_settings}}',
            ['settingsType', 'settingsKey', 'settingsValue'],
            [
                ['string', 'licenseKey', null],
                ['int', 'phoneHome', 0],
                ['string', 'defaultHost', null],
                ['int', 'defaultMaxQty', null],
                ['int', 'defaultImageQuality', 90],
                ['bool', 'defaultJpg', 'n'],
                ['bool', 'defaultRetina', 'n'],
                ['bool', 'defaultShowTitle', 'n'],
                ['bool', 'defaultRequireTitle', 'n'],
                ['string', 'defaultTitleLabel', null],
                ['bool', 'defaultShowCaption', 'n'],
                ['bool', 'defaultRequireCaption', 'n'],
                ['string', 'defaultCaptionLabel', null],
                ['bool', 'defaultShowCover', 'n'],
                ['bool', 'defaultRequireCover', 'n'],
                ['string', 'defaultCoverLabel', null],
                ['bool', 'hideSourceSaveInstructions', 'n'],
                ['string', 'encoding', ''],
                ['string', 'encodingData', ''],
            ],
        )->execute();

        return true;
    }

    /**
     * @throws Exception
     */
    public function down(): bool
    {
        $this->db->createCommand()->dropTableIfExists(
            '{{%ansel_settings}}',
        )->execute();

        return true;
    }
}
