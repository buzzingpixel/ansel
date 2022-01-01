<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\ExpressionEngine\legacy\Updates\V130;

/**
 * @codeCoverageIgnore
 */
class Legacy130ImagesUpdater
{
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

        // Add the upload_location_type column
        if (! ee()->db->field_exists('upload_location_type', 'ansel_images')) {
            ee()->dbforge->add_column('ansel_images', [
                'upload_location_type' => [
                    'default' => 'ee',
                    'type' => 'VARCHAR',
                    'constraint' => 10,
                ],
            ]);
        }

        // Add the upload_location_type column
        if (ee()->db->field_exists('original_location_type', 'ansel_images')) {
            return;
        }

        ee()->dbforge->add_column('ansel_images', [
            'original_location_type' => [
                'default' => 'ee',
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
        ]);
    }
}
