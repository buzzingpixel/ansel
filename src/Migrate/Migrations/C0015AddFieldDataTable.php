<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Connection as DbConnection;
use yii\db\ColumnSchemaBuilder;
use yii\db\Exception;
use yii\db\Schema;

class C0015AddFieldDataTable implements MigrationContract
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
        if ($this->db->tableExists('{{%ansel_field_data}}')) {
            return true;
        }

        $this->db->createCommand()->createTable(
            '{{%ansel_field_data}}',
            [
                'id' => new ColumnSchemaBuilder(Schema::TYPE_PK),
                'ansel_image_ansel_id' => (new ColumnSchemaBuilder(
                    Schema::TYPE_STRING,
                    255
                )),
                'handle' => (new ColumnSchemaBuilder(
                    Schema::TYPE_STRING,
                    255
                )),
                'value' => (new ColumnSchemaBuilder(
                    Schema::TYPE_TEXT,
                )),
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
            '{{%ansel_field_data}}',
        )->execute();

        return true;
    }
}
