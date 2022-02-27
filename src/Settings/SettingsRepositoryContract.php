<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Settings;

interface SettingsRepositoryContract
{
    public const ALL_SETTINGS = [
        'license_key' => 'license_key',
        'phone_home' => 'phone_home',
        'default_max_qty' => 'default_max_qty',
        'default_image_quality' => 'default_image_quality',
        'default_jpg' => 'default_jpg',
        'default_retina' => 'default_retina',
        'hide_source_save_instructions' => 'hide_source_save_instructions',
        'check_for_updates' => 'check_for_updates',
        'update_feed' => 'update_feed',
        'encoding' => 'encoding',
        'encoding_data' => 'encoding_data',
    ];
    public const CRAFT_KEYS   = [
        'license_key' => 'licenseKey',
        'phone_home' => 'phoneHome',
        'default_max_qty' => 'defaultMaxQty',
        'default_image_quality' => 'defaultImageQuality',
        'default_jpg' => 'defaultJpg',
        'default_retina' => 'defaultRetina',
        'hide_source_save_instructions' => 'hideSourceSaveInstructions',
        'check_for_updates' => 'checkForUpdates',
        'update_feed' => 'updateFeed',
        'encoding' => 'encoding',
        'encoding_data' => 'encodingData',
    ];

    public const SETTINGS_PAGE = [
        'default_max_qty' => 'default_max_qty',
        'default_image_quality' => 'default_image_quality',
        'default_jpg' => 'default_jpg',
        'default_retina' => 'default_retina',
        'hide_source_save_instructions' => 'hide_source_save_instructions',
    ];

    public function getSettings(): SettingsCollection;

    public function saveSetting(SettingItem $setting): void;

    public function saveSettings(SettingsCollection $settings): void;
}
