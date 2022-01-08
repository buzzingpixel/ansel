<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\AnselCms\Craft\AnselCraftField;
use craft\db\Connection as DbConnection;
use yii\db\Exception;

class C0009UpdateAnselFieldTypeNameFromCraft2 implements MigrationContract
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
        $this->db->createCommand()->update(
            '{{%fields}}',
            ['type' => AnselCraftField::class],
            "`type` = 'Ansel_Ansel'",
        )->execute();

        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
