<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;

class E0009RemoveOldSettings implements MigrationContract
{
    private EeQueryBuilderFactory $queryBuilderFactory;

    public function __construct(EeQueryBuilderFactory $queryBuilderFactory)
    {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function for(): string
    {
        return self::EE;
    }

    public function up(): bool
    {
        $this->queryBuilderFactory->create()
            ->where('settings_key', 'default_show_title')
            ->delete('ansel_settings');

        $this->queryBuilderFactory->create()
            ->where('settings_key', 'default_require_title')
            ->delete('ansel_settings');

        $this->queryBuilderFactory->create()
            ->where('settings_key', 'default_title_label')
            ->delete('ansel_settings');

        $this->queryBuilderFactory->create()
            ->where('settings_key', 'default_show_caption')
            ->delete('ansel_settings');

        $this->queryBuilderFactory->create()
            ->where('settings_key', 'default_require_caption')
            ->delete('ansel_settings');

        $this->queryBuilderFactory->create()
            ->where('settings_key', 'default_caption_label')
            ->delete('ansel_settings');

        $this->queryBuilderFactory->create()
            ->where('settings_key', 'default_show_cover')
            ->delete('ansel_settings');

        $this->queryBuilderFactory->create()
            ->where('settings_key', 'default_require_cover')
            ->delete('ansel_settings');

        $this->queryBuilderFactory->create()
            ->where('settings_key', 'default_cover_label')
            ->delete('ansel_settings');

        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
