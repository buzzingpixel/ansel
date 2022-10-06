<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_forge;

class E0010AddFocalColumns implements MigrationContract
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
            ! $this->queryBuilderFactory->create()->field_exists(
                'focal_x',
                'ansel_images'
            )
        ) {
            $this->dbForge->add_column(
                'ansel_images',
                [
                    'focal_x' => [
                        'default' => 0,
                        'type' => 'INT',
                        'unsigned' => true,
                    ],
                ],
            );
        }

        if (
            ! $this->queryBuilderFactory->create()->field_exists(
                'focal_y',
                'ansel_images'
            )
        ) {
            $this->dbForge->add_column(
                'ansel_images',
                [
                    'focal_y' => [
                        'default' => 0,
                        'type' => 'INT',
                        'unsigned' => true,
                    ],
                ],
            );
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
