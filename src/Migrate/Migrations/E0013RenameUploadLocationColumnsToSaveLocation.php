<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_forge;

class E0013RenameUploadLocationColumnsToSaveLocation implements MigrationContract
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
                'upload_location_type',
                'ansel_images'
            )
        ) {
            $this->dbForge->modify_column(
                'ansel_images',
                [
                    'upload_location_type' => [
                        'name' => 'save_location_type',
                        'default' => 'ee',
                        'type' => 'VARCHAR',
                        'constraint' => 10,
                    ],
                ]
            );
        }

        if (
            $this->queryBuilderFactory->create()->field_exists(
                'upload_location_id',
                'ansel_images'
            )
        ) {
            $this->dbForge->modify_column(
                'ansel_images',
                [
                    'upload_location_id' => [
                        'name' => 'save_location_id',
                        'default' => '',
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                    ],
                ]
            );
        }

        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
