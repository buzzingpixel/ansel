<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_result;
use ExpressionEngine\Service\Database\Query;
use PHPUnit\Framework\TestCase;

class E0006AddSettingsTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private E0006AddSettings $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->migration = new E0006AddSettings(
            $this->mockQueryBuilderFactory(),
        );
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

    private string $currentWhereKey = '';

    private function mockQuery(): Query
    {
        $mock = $this->createMock(Query::class);

        $mock->method('where')->willReturnCallback(
            function (string $key, $value) use ($mock): Query {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => $key,
                    'value' => $value,
                ];

                $this->currentWhereKey = $value;

                return $mock;
            }
        );

        $mock->method('get')->willReturnCallback(
            function (string $table): CI_DB_result {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => $table,
                ];

                return $this->mockDbResultFor($this->currentWhereKey);
            }
        );

        $mock->method('insert')->willReturnCallback(
            function (string $table, array $set): void {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => $table,
                    'set' => $set,
                ];
            }
        );

        $mock->method('truncate')->willReturnCallback(
            function (string $table): void {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'truncate',
                    'table' => $table,
                ];
            }
        );

        return $mock;
    }

    private function mockDbResultFor(string $key): CI_DB_result
    {
        $mock = $this->createMock(CI_DB_result::class);

        $mock->method('num_rows')->willReturn(
            $key === 'phone_home' ? 1 : 0,
        );

        return $mock;
    }

    public function testFor(): void
    {
        self::assertSame(
            MigrationContract::EE,
            $this->migration->for(),
        );
    }

    public function testUp(): void
    {
        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'license_key',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'string',
                        'settings_key' => 'license_key',
                        'settings_value' => null,
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'phone_home',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_host',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'string',
                        'settings_key' => 'default_host',
                        'settings_value' => null,
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_max_qty',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'int',
                        'settings_key' => 'default_max_qty',
                        'settings_value' => null,
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_image_quality',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'int',
                        'settings_key' => 'default_image_quality',
                        'settings_value' => 90,
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_jpg',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'default_jpg',
                        'settings_value' => 'n',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_retina',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'default_retina',
                        'settings_value' => 'n',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_show_title',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'default_show_title',
                        'settings_value' => 'n',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_require_title',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'default_require_title',
                        'settings_value' => 'n',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_title_label',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'string',
                        'settings_key' => 'default_title_label',
                        'settings_value' => null,
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_show_caption',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'default_show_caption',
                        'settings_value' => 'n',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_require_caption',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'default_require_caption',
                        'settings_value' => 'n',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_caption_label',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'string',
                        'settings_key' => 'default_caption_label',
                        'settings_value' => null,
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_show_cover',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'default_show_cover',
                        'settings_value' => 'n',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_require_cover',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'default_require_cover',
                        'settings_value' => 'n',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_cover_label',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'string',
                        'settings_key' => 'default_cover_label',
                        'settings_value' => null,
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'hide_source_save_instructions',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'bool',
                        'settings_key' => 'hide_source_save_instructions',
                        'settings_value' => 'n',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'check_for_updates',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'int',
                        'settings_key' => 'check_for_updates',
                        'settings_value' => 0,
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'updates_available',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'int',
                        'settings_key' => 'updates_available',
                        'settings_value' => 0,
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'update_feed',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'string',
                        'settings_key' => 'update_feed',
                        'settings_value' => '',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'encoding',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'string',
                        'settings_key' => 'encoding',
                        'settings_value' => '',
                    ],
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'encoding_data',
                ],
                [
                    'object' => 'Query',
                    'method' => 'get',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'insert',
                    'table' => 'ansel_settings',
                    'set' => [
                        'settings_type' => 'string',
                        'settings_key' => 'encoding_data',
                        'settings_value' => '',
                    ],
                ],
            ],
            $this->calls,
        );
    }

    public function testDown(): void
    {
        self::assertTrue($this->migration->down());

        self::assertSame(
            [
                [
                    'object' => 'Query',
                    'method' => 'truncate',
                    'table' => 'ansel_settings',
                ],
            ],
            $this->calls,
        );
    }
}
