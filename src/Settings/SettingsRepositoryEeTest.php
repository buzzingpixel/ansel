<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Settings;

use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use CI_DB_result;
use ExpressionEngine\Service\Database\Query;
use PHPUnit\Framework\TestCase;

class SettingsRepositoryEeTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private int $numRowsReturn = 0;

    private SettingsRepositoryEe $settingsRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->numRowsReturn = 0;

        $this->settingsRepository = new SettingsRepositoryEe(
            $this->mockTranslator(),
            $this->mockQueryBuilderFactory(),
        );
    }

    private function mockTranslator(): TranslatorContract
    {
        $mock = $this->createMock(TranslatorContract::class);

        $mock->method('getLine')->willReturnCallback(
            static function (string $which): string {
                if ($which === 'phone_home_explain') {
                    return $which;
                }

                return $which . '-translator';
            }
        );

        return $mock;
    }

    private function mockQueryBuilderFactory(): EeQueryBuilderFactory
    {
        $mock = $this->createMock(
            EeQueryBuilderFactory::class,
        );

        $mock->method('create')->willReturn(
            $this->mockQuery(),
        );

        return $mock;
    }

    private function mockQuery(): Query
    {
        $mock = $this->createMock(Query::class);

        $mock->method('where')->willReturnCallback(
            function (string $key, $val) use ($mock): Query {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => $key,
                    'val' => $val,
                ];

                return $mock;
            }
        );

        $mock->method('update')->willReturnCallback(
            function (
                string $table,
                array $set,
                array $where
            ): CI_DB_result {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'update',
                    'table' => $table,
                    'set' => $set,
                    'where' => $where,
                ];

                return $this->mockDbResult();
            }
        );

        $mock->method('insert')->willReturnCallback(
            function (string $table, array $set): CI_DB_result {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => $table,
                    'set' => $set,
                ];

                return $this->mockDbResult();
            }
        );

        $mock->method('get')->willReturnCallback(
            function (string $tableName): CI_DB_result {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => $tableName,
                ];

                return $this->mockDbResult();
            }
        );

        return $mock;
    }

    private function mockDbResult(): CI_DB_result
    {
        $mock = $this->createMock(CI_DB_result::class);

        $mock->method('num_rows')->willReturnCallback(
            function (): int {
                return $this->numRowsReturn;
            }
        );

        $mock->method('result_array')->willReturn([
            [
                'id' => 1,
                'settings_type' => 'string',
                'settings_key' => 'license_key',
                'settings_value' => 'foo-bar-license',
            ],
            [
                'id' => 456,
                'settings_type' => 'int',
                'settings_key' => 'default_image_quality',
                'settings_value' => '87',
            ],
            [
                'id' => 34,
                'settings_type' => 'bool',
                'settings_key' => 'default_require_cover',
                'settings_value' => 'n',
            ],
            [
                'id' => 56,
                'settings_type' => 'bool',
                'settings_key' => 'hide_source_save_instructions',
                'settings_value' => 'y',
            ],
        ]);

        return $mock;
    }

    public function testGetSettings(): void
    {
        $settings = $this->settingsRepository->getSettings();

        self::assertSame(
            [
                [
                    'type' => 'string',
                    'key' => 'license_key',
                    'label' => 'license_key-translator',
                    'value' => 'foo-bar-license',
                    'description' => 'license_key_explain-translator',
                    'includeOnSettingsPage' => false,
                ],
                [
                    'type' => 'string',
                    'key' => 'phone_home',
                    'label' => 'phone_home-translator',
                    'value' => '',
                    'description' => '',
                    'includeOnSettingsPage' => false,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_max_qty',
                    'label' => 'default_max_qty-translator',
                    'value' => '',
                    'description' => 'default_max_qty_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'int',
                    'key' => 'default_image_quality',
                    'label' => 'default_image_quality-translator',
                    'value' => 87,
                    'description' => 'default_image_quality_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_jpg',
                    'label' => 'default_jpg-translator',
                    'value' => '',
                    'description' => 'default_jpg_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_retina',
                    'label' => 'default_retina-translator',
                    'value' => '',
                    'description' => 'default_retina_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_show_title',
                    'label' => 'default_show_title-translator',
                    'value' => '',
                    'description' => 'default_show_title_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_require_title',
                    'label' => 'default_require_title-translator',
                    'value' => '',
                    'description' => 'default_require_title_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_title_label',
                    'label' => 'default_title_label-translator',
                    'value' => '',
                    'description' => 'default_title_label_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_show_caption',
                    'label' => 'default_show_caption-translator',
                    'value' => '',
                    'description' => 'default_show_caption_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_require_caption',
                    'label' => 'default_require_caption-translator',
                    'value' => '',
                    'description' => 'default_require_caption_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_caption_label',
                    'label' => 'default_caption_label-translator',
                    'value' => '',
                    'description' => 'default_caption_label_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_show_cover',
                    'label' => 'default_show_cover-translator',
                    'value' => '',
                    'description' => 'default_show_cover_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'bool',
                    'key' => 'default_require_cover',
                    'label' => 'default_require_cover-translator',
                    'value' => false,
                    'description' => 'default_require_cover_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'default_cover_label',
                    'label' => 'default_cover_label-translator',
                    'value' => '',
                    'description' => 'default_cover_label_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'bool',
                    'key' => 'hide_source_save_instructions',
                    'label' => 'hide_source_save_instructions-translator',
                    'value' => true,
                    'description' => 'hide_source_save_instructions_explain-translator',
                    'includeOnSettingsPage' => true,
                ],
                [
                    'type' => 'string',
                    'key' => 'check_for_updates',
                    'label' => 'check_for_updates-translator',
                    'value' => '',
                    'description' => 'check_for_updates_explain-translator',
                    'includeOnSettingsPage' => false,
                ],
                [
                    'type' => 'string',
                    'key' => 'update_feed',
                    'label' => 'update_feed-translator',
                    'value' => '',
                    'description' => 'update_feed_explain-translator',
                    'includeOnSettingsPage' => false,
                ],
                [
                    'type' => 'string',
                    'key' => 'encoding',
                    'label' => 'encoding-translator',
                    'value' => '',
                    'description' => 'encoding_explain-translator',
                    'includeOnSettingsPage' => false,
                ],
                [
                    'type' => 'string',
                    'key' => 'encoding_data',
                    'label' => 'encoding_data-translator',
                    'value' => '',
                    'description' => 'encoding_data_explain-translator',
                    'includeOnSettingsPage' => false,
                ],
            ],
            $settings->map(static fn (SettingItem $i) => [
                'type' => $i->type(),
                'key' => $i->key(),
                'label' => $i->label(),
                'value' => $i->value(),
                'description' => $i->description(),
                'includeOnSettingsPage' => $i->includeOnSettingsPage(),
            ]),
        );

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => 'ansel_settings',
                ],
            ],
            $this->calls,
        );
    }

    public function testSaveSettingsWhenNumRowsIs0(): void
    {
        $settings = new SettingsCollection(
            [
                new SettingItem(
                    'string',
                    'key1',
                    'label1',
                    'value1',
                    'desc1',
                    false,
                ),
                new SettingItem(
                    'int',
                    'key2',
                    'label2',
                    423,
                    'desc2',
                    true,
                ),
                new SettingItem(
                    'bool',
                    'key3',
                    'label3',
                    true,
                    'desc3',
                    false,
                ),
                new SettingItem(
                    'bool',
                    'key4',
                    'label4',
                    false,
                    'desc4',
                    false,
                ),
            ]
        );

        $this->settingsRepository->saveSettings($settings);

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'val' => 'key1',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'string',
                        'settings_key' => 'key1',
                        'settings_value' => 'value1',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'val' => 'key2',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'int',
                        'settings_key' => 'key2',
                        'settings_value' => 423,
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'val' => 'key3',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'key3',
                        'settings_value' => 'y',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'val' => 'key4',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'key4',
                        'settings_value' => 'n',
                    ],
                ],
            ],
            $this->calls,
        );
    }

    public function testSaveSettingsWhenNumRowsIs1(): void
    {
        $this->numRowsReturn = 1;

        $settings = new SettingsCollection(
            [
                new SettingItem(
                    'string',
                    'key1',
                    'label1',
                    'value1',
                    'desc1',
                    false,
                ),
                new SettingItem(
                    'int',
                    'key2',
                    'label2',
                    423,
                    'desc2',
                    true,
                ),
                new SettingItem(
                    'bool',
                    'key3',
                    'label3',
                    true,
                    'desc3',
                    false,
                ),
                new SettingItem(
                    'bool',
                    'key4',
                    'label4',
                    false,
                    'desc4',
                    false,
                ),
            ]
        );

        $this->settingsRepository->saveSettings($settings);

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'val' => 'key1',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'update',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'string',
                        'settings_key' => 'key1',
                        'settings_value' => 'value1',
                    ],
                    'where' => ['settings_key' => 'key1'],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'val' => 'key2',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'update',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'int',
                        'settings_key' => 'key2',
                        'settings_value' => 423,
                    ],
                    'where' => ['settings_key' => 'key2'],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'val' => 'key3',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'update',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'key3',
                        'settings_value' => 'y',
                    ],
                    'where' => ['settings_key' => 'key3'],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'val' => 'key4',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'tableName' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'update',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'key4',
                        'settings_value' => 'n',
                    ],
                    'where' => ['settings_key' => 'key4'],
                ],
            ],
            $this->calls,
        );
    }
}
