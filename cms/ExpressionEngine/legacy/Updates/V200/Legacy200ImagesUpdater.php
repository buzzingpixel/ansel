<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\ExpressionEngine\legacy\Updates\V200;

use CI_DB_forge;

/**
 * @codeCoverageIgnore
 */
class Legacy200ImagesUpdater
{
    private CI_DB_forge $dbForge;

    public function __construct(CI_DB_forge $dbForge)
    {
        $this->dbForge = $dbForge;
    }

    public function process(): void
    {
        // Modify the row_id column to have a default of 0
        $this->dbForge->modify_column('ansel_images', [
            'row_id' => [
                'name' => 'row_id',
                'null' => false,
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
        ]);

        // Modify the col_id column to have a default of 0
        $this->dbForge->modify_column('ansel_images', [
            'col_id' => [
                'name' => 'col_id',
                'null' => false,
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
        ]);
    }
}
