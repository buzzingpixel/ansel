<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\ExpressionEngine\legacy\Updates\V130;

/**
 * @codeCoverageIgnore
 */
class Legacy130SettingsUpdater
{
    public function process(): void
    {
        // Delete image cache settings (no longer needed)

        ee()->db->delete(
            'ansel_settings',
            ['settings_key' => 'image_cache_location'],
        );

        ee()->db->delete(
            'ansel_settings',
            ['settings_key' => 'image_cache_url'],
        );
    }
}
