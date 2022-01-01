<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\ExpressionEngine\legacy\Updates\V140;

/**
 * @codeCoverageIgnore
 */
class Legacy140ImagesUpdater
{
    /**
     * Process the update
     */
    public function process(): void
    {
        // Load the forge class
        ee()->load->dbforge();

        // Modify the upload_location_id column to accept strings
        // (Treasury prefers to refer to locations by handle)
        ee()->dbforge->modify_column('ansel_images', [
            'upload_location_id' => [
                'name' => 'upload_location_id',
                'default' => '',
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);
    }
}
