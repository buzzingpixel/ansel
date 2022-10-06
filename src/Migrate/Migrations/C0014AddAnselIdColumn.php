<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Connection as DbConnection;
use yii\base\NotSupportedException;
use yii\db\ColumnSchemaBuilder;
use yii\db\Exception;
use yii\db\Schema;

class C0014AddAnselIdColumn implements MigrationContract
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
            $this->db->columnExists(
                '{{%ansel_images}}',
                'ansel_id'
            )
        ) {
            return true;
        }

        $this->db->createCommand()->addColumn(
            '{{%ansel_images}}',
            'ansel_id',
            (string) (new ColumnSchemaBuilder(
                Schema::TYPE_STRING,
                255
            )),
        )->execute();

        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
