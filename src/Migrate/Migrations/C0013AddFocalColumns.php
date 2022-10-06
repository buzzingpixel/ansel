<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Connection as DbConnection;
use yii\base\NotSupportedException;
use yii\db\ColumnSchemaBuilder;
use yii\db\Exception;
use yii\db\Schema;

class C0013AddFocalColumns implements MigrationContract
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
     * @throws NotSupportedException
     * @throws Exception
     */
    public function up(): bool
    {
        if (
            ! $this->db->columnExists(
                '{{%ansel_images}}',
                'focal_x'
            )
        ) {
            $this->db->createCommand()->addColumn(
                '{{%ansel_images}}',
                'focal_x',
                (string) (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                ))->notNull(),
            )->execute();
        }

        if (
            ! $this->db->columnExists(
                '{{%ansel_images}}',
                'focal_y'
            )
        ) {
            $this->db->createCommand()->addColumn(
                '{{%ansel_images}}',
                'focal_y',
                (string) (new ColumnSchemaBuilder(
                    Schema::TYPE_INTEGER,
                ))->notNull(),
            )->execute();
        }

        return true;
    }

    public function down(): bool
    {
        /**
         * We're not going to try to revert this.
         */
        return true;
    }
}
