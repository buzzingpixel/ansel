<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_forge;

class E0011AddAnselIdColumn implements MigrationContract
{
    private CI_DB_forge $dbForge;

    private EeQueryBuilderFactory $queryBuilderFactory;

    public function __construct(
        CI_DB_forge $dbForge,
        EeQueryBuilderFactory $queryBuilderFactory
    ) {
        $this->dbForge             = $dbForge;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function for(): string
    {
        return self::EE;
    }

    public function up(): bool
    {
        if (
            $this->queryBuilderFactory->create()->field_exists(
                'ansel_id',
                'ansel_images'
            )
        ) {
            return true;
        }

        $this->dbForge->add_column(
            'ansel_images',
            [
                'ansel_id' => [
                    'default' => '',
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
            ],
        );

        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
