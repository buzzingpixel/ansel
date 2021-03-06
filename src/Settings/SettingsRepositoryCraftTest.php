<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Settings;

use BuzzingPixel\Ansel\Shared\CraftQueryBuilderFactory;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use craft\db\Command;
use craft\db\Connection as DbConnection;
use craft\db\Query;
use PHPUnit\Framework\TestCase;

class SettingsRepositoryCraftTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private int $countReturn = 0;

    private SettingsRepositoryCraft $settingsRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->countReturn = 0;

        $this->settingsRepository = new SettingsRepositoryCraft(
            $this->mockDb(),
            $this->mockTranslator(),
            $this->mockQueryBuilderFactory(),
        );
    }

    private function mockDb(): DbConnection
    {
        $mock = $this->createMock(DbConnection::class);

        $mock->method('createCommand')->willReturnCallback(
            function (): Command {
                return $this->mockCommand();
            }
        );

        return $mock;
    }

    private function mockCommand(): Command
    {
        $mock = $this->createMock(Command::class);

        $mock->method('update')->willReturnCallback(
            function (
                string $table,
                array $columns,
                array $condition
            ) use ($mock): Command {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'update',
                    'table' => $table,
                    'columns' => $columns,
                    'condition' => $condition,
                ];

                return $mock;
            }
        );

        $mock->method('insert')->willReturnCallback(
            function (
                string $table,
                array $columns
            ) use ($mock): Command {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'insert',
                    'table' => $table,
                    'columns' => $columns,
                ];

                return $mock;
            }
        );

        $mock->method('execute')->willReturnCallback(
            function (): int {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'execute',
                ];

                return 123;
            }
        );

        return $mock;
    }

    private function mockTranslator(): TranslatorContract
    {
        $mock = $this->createMock(TranslatorContract::class);

        $mock->method('getLine')->willReturnCallback(
            static function (string $key): string {
                if ($key === 'phone_home_explain') {
                    return $key;
                }

                return $key . '-translator';
            }
        );

        return $mock;
    }

    private function mockQueryBuilderFactory(): CraftQueryBuilderFactory
    {
        $mock = $this->createMock(
            CraftQueryBuilderFactory::class,
        );

        $mock->method('create')->willReturn(
            $this->mockQuery(),
        );

        return $mock;
    }

    private function mockQuery(): Query
    {
        $mock = $this->createMock(Query::class);

        $mock->method('from')->willReturnCallback(
            function (string $table) use ($mock): Query {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'from',
                    'table' => $table,
                ];

                return $mock;
            }
        );

        $mock->method('where')->willReturnCallback(
            function (
                string $condition,
                array $params
            ) use ($mock): Query {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'where',
                    'condition' => $condition,
                    'params' => $params,
                ];

                return $mock;
            }
        );

        $mock->method('count')->willReturnCallback(
            function (): int {
                return $this->countReturn;
            }
        );

        $mock->method('all')->willReturn([
            [
                'id' => 1,
                'settingsType' => 'string',
                'settingsKey' => 'licenseKey',
                'settingsValue' => 'foo-bar-license',
            ],
            [
                'id' => 456,
                'settingsType' => 'int',
                'settingsKey' => 'defaultImageQuality',
                'settingsValue' => '87',
            ],
            [
                'id' => 34,
                'settingsType' => 'bool',
                'settingsKey' => 'defaultRequireCover',
                'settingsValue' => 'n',
            ],
            [
                'id' => 56,
                'settingsType' => 'bool',
                'settingsKey' => 'hideSourceSaveInstructions',
                'settingsValue' => 'y',
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
                    'method' => 'from',
                    'table' => '{{%ansel_settings}}',
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
                    'method' => 'from',
                    'table' => '{{%ansel_settings}}',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'condition' => '`settingsKey` = :settingsKey',
                    'params' => ['settingsKey' => 'key1'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'insert',
                    'table' => '{{%ansel_settings}}',
                    'columns' => [
                        'settingsType' => 'string',
                        'settingsKey' => 'key1',
                        'settingsValue' => 'value1',
                    ],
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Query',
                    'method' => 'from',
                    'table' => '{{%ansel_settings}}',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'condition' => '`settingsKey` = :settingsKey',
                    'params' => ['settingsKey' => 'key2'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'insert',
                    'table' => '{{%ansel_settings}}',
                    'columns' => [
                        'settingsType' => 'int',
                        'settingsKey' => 'key2',
                        'settingsValue' => 423,
                    ],
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Query',
                    'method' => 'from',
                    'table' => '{{%ansel_settings}}',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'condition' => '`settingsKey` = :settingsKey',
                    'params' => ['settingsKey' => 'key3'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'insert',
                    'table' => '{{%ansel_settings}}',
                    'columns' => [
                        'settingsType' => 'bool',
                        'settingsKey' => 'key3',
                        'settingsValue' => 'y',
                    ],
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Query',
                    'method' => 'from',
                    'table' => '{{%ansel_settings}}',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'condition' => '`settingsKey` = :settingsKey',
                    'params' => ['settingsKey' => 'key4'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'insert',
                    'table' => '{{%ansel_settings}}',
                    'columns' => [
                        'settingsType' => 'bool',
                        'settingsKey' => 'key4',
                        'settingsValue' => 'n',
                    ],
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
            ],
            $this->calls,
        );
    }

    public function testSaveSettingsWhenNumRowsIs1(): void
    {
        $this->countReturn = 1;

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
                    'method' => 'from',
                    'table' => '{{%ansel_settings}}',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'condition' => '`settingsKey` = :settingsKey',
                    'params' => ['settingsKey' => 'key1'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'update',
                    'table' => '{{%ansel_settings}}',
                    'columns' => [
                        'settingsType' => 'string',
                        'settingsKey' => 'key1',
                        'settingsValue' => 'value1',
                    ],
                    'condition' => ['settingsKey' => 'key1'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Query',
                    'method' => 'from',
                    'table' => '{{%ansel_settings}}',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'condition' => '`settingsKey` = :settingsKey',
                    'params' => ['settingsKey' => 'key2'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'update',
                    'table' => '{{%ansel_settings}}',
                    'columns' => [
                        'settingsType' => 'int',
                        'settingsKey' => 'key2',
                        'settingsValue' => 423,
                    ],
                    'condition' => ['settingsKey' => 'key2'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Query',
                    'method' => 'from',
                    'table' => '{{%ansel_settings}}',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'condition' => '`settingsKey` = :settingsKey',
                    'params' => ['settingsKey' => 'key3'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'update',
                    'table' => '{{%ansel_settings}}',
                    'columns' => [
                        'settingsType' => 'bool',
                        'settingsKey' => 'key3',
                        'settingsValue' => 'y',
                    ],
                    'condition' => ['settingsKey' => 'key3'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Query',
                    'method' => 'from',
                    'table' => '{{%ansel_settings}}',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'condition' => '`settingsKey` = :settingsKey',
                    'params' => ['settingsKey' => 'key4'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'update',
                    'table' => '{{%ansel_settings}}',
                    'columns' => [
                        'settingsType' => 'bool',
                        'settingsKey' => 'key4',
                        'settingsValue' => 'n',
                    ],
                    'condition' => ['settingsKey' => 'key4'],
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
            ],
            $this->calls,
        );
    }
}
