<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Connection as DbConnection;
use yii\db\ColumnSchemaBuilder;
use yii\db\Exception;
use yii\db\Schema;

class C0001AddMigrationsTable implements MigrationContract
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
        if ($this->db->tableExists('{{%ansel_migrations}}')) {
            return true;
        }

        $this->db->createCommand()->createTable(
            '{{%ansel_migrations}}',
            [
                'id' => new ColumnSchemaBuilder(Schema::TYPE_PK),
                'migration' => new ColumnSchemaBuilder(Schema::TYPE_TEXT),
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
            '{{%ansel_migrations}}',
        )->execute();

        return true;
    }
}
