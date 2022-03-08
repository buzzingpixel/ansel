<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_result;
use ExpressionEngine\Service\Database\Query;
use PHPUnit\Framework\TestCase;

class E0009RemoveOldSettingsTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private E0009RemoveOldSettings $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->migration = new E0009RemoveOldSettings(
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

    private function mockQuery(): Query
    {
        $mock = $this->createMock(Query::class);

        $mock->method('where')->willReturnCallback(
            function (string $key, string $value) use ($mock): Query {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => $key,
                    'value' => $value,
                ];

                return $mock;
            }
        );

        $mock->method('delete')->willReturnCallback(
            function (string $table): CI_DB_result {
                $this->calls[] = [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => $table,
                ];

                return $this->createMock(
                    CI_DB_result::class,
                );
            }
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
                    'value' => 'default_show_title',
                ],
                [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_require_title',
                ],
                [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_title_label',
                ],
                [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_show_caption',
                ],
                [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_require_caption',
                ],
                [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_caption_label',
                ],
                [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_show_cover',
                ],
                [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_require_cover',
                ],
                [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => 'ansel_settings',
                ],
                [
                    'object' => 'Query',
                    'method' => 'where',
                    'key' => 'settings_key',
                    'value' => 'default_cover_label',
                ],
                [
                    'object' => 'Query',
                    'method' => 'delete',
                    'table' => 'ansel_settings',
                ],
            ],
            $this->calls,
        );
    }

    public function testDown(): void
    {
        self::assertTrue($this->migration->down());

        self::assertSame([], $this->calls);
    }
}
