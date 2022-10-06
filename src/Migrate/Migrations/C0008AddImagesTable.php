<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Connection as DbConnection;
use yii\db\ColumnSchemaBuilder;
use yii\db\Exception;
use yii\db\Schema;

class C0008AddImagesTable implements MigrationContract
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
        if ($this->db->tableExists('{{%ansel_images}}')) {
            return true;
        }

        $this->db->createCommand()->createTable(
            '{{%ansel_images}}',
            [
                'id' => new ColumnSchemaBuilder(Schema::TYPE_PK),
                'ansel_id' => (new ColumnSchemaBuilder(
                    Schema::TYPE_STRING,
                    255
                )),
                'element_id' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                    11,
                ))->notNull(),
                'field_id' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                    11,
                ))->notNull(),
                'user_id' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                    11,
                ))->notNull(),
                'asset_id' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                    11,
                ))->notNull(),
                'high_qual_asset_id' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                    11,
                )),
                'thumb_asset_id' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                    11,
                )),
                'original_asset_id' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                    11,
                )),
                'width' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                ))->notNull(),
                'height' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                ))->notNull(),
                'x' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                ))->notNull(),
                'y' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                ))->notNull(),
                'focal_x' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                ))->notNull(),
                'focal_y' => (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                ))->notNull(),
                'title' => (new ColumnSchemaBuilder(
                    Schema::TYPE_STRING,
                    255
                )),
                'caption' => (new ColumnSchemaBuilder(
                    Schema::TYPE_STRING,
                    255
                )),
                'cover' => (new ColumnSchemaBuilder(
                    Schema::TYPE_TINYINT,
                    1
                ))->notNull(),
                'position' => (new ColumnSchemaBuilder(
                    Schema::TYPE_TINYINT,
                    4
                ))->notNull(),
                'disabled' => (new ColumnSchemaBuilder(
                    Schema::TYPE_TINYINT
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
            ]
        )->execute();

        $this->db->createCommand()->addForeignKey(
            $this->db->getForeignKeyName(),
            '{{%ansel_images}}',
            ['element_id'],
            '{{%elements}}',
            ['id'],
            'RESTRICT',
            'RESTRICT',
        )->execute();

        $this->db->createCommand()->addForeignKey(
            $this->db->getForeignKeyName(),
            '{{%ansel_images}}',
            ['field_id'],
            '{{%fields}}',
            ['id'],
            'RESTRICT',
            'RESTRICT',
        )->execute();

        $this->db->createCommand()->addForeignKey(
            $this->db->getForeignKeyName(),
            '{{%ansel_images}}',
            ['user_id'],
            '{{%users}}',
            ['id'],
            'RESTRICT',
            'RESTRICT',
        )->execute();

        $this->db->createCommand()->addForeignKey(
            $this->db->getForeignKeyName(),
            '{{%ansel_images}}',
            ['asset_id'],
            '{{%assets}}',
            ['id'],
            'SET NULL',
            'RESTRICT',
        )->execute();

        $this->db->createCommand()->addForeignKey(
            $this->db->getForeignKeyName(),
            '{{%ansel_images}}',
            ['high_qual_asset_id'],
            '{{%assets}}',
            ['id'],
            'SET NULL',
            'RESTRICT',
        )->execute();

        $this->db->createCommand()->addForeignKey(
            $this->db->getForeignKeyName(),
            '{{%ansel_images}}',
            ['thumb_asset_id'],
            '{{%assets}}',
            ['id'],
            'SET NULL',
            'RESTRICT',
        )->execute();

        $this->db->createCommand()->addForeignKey(
            $this->db->getForeignKeyName(),
            '{{%ansel_images}}',
            ['original_asset_id'],
            '{{%assets}}',
            ['id'],
            'SET NULL',
            'RESTRICT',
        )->execute();

        return true;
    }

    /**
     * @throws Exception
     */
    public function down(): bool
    {
        $this->db->createCommand()->dropTableIfExists(
            '{{%ansel_images}}',
        )->execute();

        return true;
    }
}
