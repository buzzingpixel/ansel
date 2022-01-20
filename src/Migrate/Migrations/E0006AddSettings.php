<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;

use function array_map;
use function base64_encode;

class E0006AddSettings implements MigrationContract
{
    private InternalFunctions $internalFunctions;

    private EeQueryBuilderFactory $queryBuilderFactory;

    public function __construct(
        InternalFunctions $internalFunctions,
        EeQueryBuilderFactory $queryBuilderFactory
    ) {
        $this->internalFunctions   = $internalFunctions;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function for(): string
    {
        return self::EE;
    }

    public function up(): bool
    {
        array_map(
            [$this, 'addRow'],
            $this->getSettings(),
        );

        return true;
    }

    /**
     * @param mixed[] $row
     */
    private function addRow(array $row): void
    {
        $existsCheck = $this->queryBuilderFactory->create()
            ->where('settings_key', $row['settings_key'])
            ->get('ansel_settings')
            ->num_rows();

        if ($existsCheck > 0) {
            return;
        }

        $this->queryBuilderFactory->create()->insert(
            'ansel_settings',
            $row,
        );
    }

    public function down(): bool
    {
        $this->queryBuilderFactory->create()->truncate('ansel_settings');

        return true;
    }

    /**
     * @return array<array-key, array<string, string|int|null>>
     */
    private function getSettings(): array
    {
        return [
            [
                'settings_type' => 'string',
                'settings_key' => 'license_key',
                'settings_value' => null,
            ],
            [
                'settings_type' => 'int',
                'settings_key' => 'phone_home',
                'settings_value' => 0,
            ],
            [
                'settings_type' => 'string',
                'settings_key' => 'default_host',
                'settings_value' => null,
            ],
            [
                'settings_type' => 'int',
                'settings_key' => 'default_max_qty',
                'settings_value' => null,
            ],
            [
                'settings_type' => 'int',
                'settings_key' => 'default_image_quality',
                'settings_value' => 90,
            ],
            [
                'settings_type' => 'bool',
                'settings_key' => 'default_jpg',
                'settings_value' => 'n',
            ],
            [
                'settings_type' => 'bool',
                'settings_key' => 'default_retina',
                'settings_value' => 'n',
            ],
            [
                'settings_type' => 'bool',
                'settings_key' => 'default_show_title',
                'settings_value' => 'n',
            ],
            [
                'settings_type' => 'bool',
                'settings_key' => 'default_require_title',
                'settings_value' => 'n',
            ],
            [
                'settings_type' => 'string',
                'settings_key' => 'default_title_label',
                'settings_value' => null,
            ],
            [
                'settings_type' => 'bool',
                'settings_key' => 'default_show_caption',
                'settings_value' => 'n',
            ],
            [
                'settings_type' => 'bool',
                'settings_key' => 'default_require_caption',
                'settings_value' => 'n',
            ],
            [
                'settings_type' => 'string',
                'settings_key' => 'default_caption_label',
                'settings_value' => null,
            ],
            [
                'settings_type' => 'bool',
                'settings_key' => 'default_show_cover',
                'settings_value' => 'n',
            ],
            [
                'settings_type' => 'bool',
                'settings_key' => 'default_require_cover',
                'settings_value' => 'n',
            ],
            [
                'settings_type' => 'string',
                'settings_key' => 'default_cover_label',
                'settings_value' => null,
            ],
            [
                'settings_type' => 'bool',
                'settings_key' => 'hide_source_save_instructions',
                'settings_value' => 'n',
            ],
            [
                'settings_type' => 'int',
                'settings_key' => 'check_for_updates',
                'settings_value' => 0,
            ],
            [
                'settings_type' => 'int',
                'settings_key' => 'updates_available',
                'settings_value' => 0,
            ],
            [
                'settings_type' => 'string',
                'settings_key' => 'update_feed',
                'settings_value' => '',
            ],
            [
                'settings_type' => 'string',
                'settings_key' => 'encoding',
                'settings_value' => base64_encode(
                    (string) $this->internalFunctions->strToTime(
                        '+30 days',
                        $this->internalFunctions->time(),
                    )
                ),
            ],
            [
                'settings_type' => 'string',
                'settings_key' => 'encoding_data',
                'settings_value' => '',
            ],
        ];
    }
}
