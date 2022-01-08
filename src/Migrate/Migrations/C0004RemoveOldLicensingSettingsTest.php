<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Command;
use craft\db\Connection as DbConnection;
use PHPUnit\Framework\TestCase;

class C0004RemoveOldLicensingSettingsTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private string $tableExists = '';

    private C0004RemoveOldLicensingSettings $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->tableExists = '';

        $this->migration = new C0004RemoveOldLicensingSettings(
            $this->mockDb(),
        );
    }

    private function mockDb(): DbConnection
    {
        $mock = $this->createMock(DbConnection::class);

        $mock->method('tableExists')->willReturnCallback(
            function (string $table): bool {
                $this->calls[] = [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => $table,
                ];

                return $this->tableExists === $table;
            }
        );

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

        $mock->method('delete')->willReturnCallback(
            function (
                string $table,
                string $condition
            ) use ($mock): Command {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => $table,
                    'condition' => $condition,
                ];

                return $mock;
            }
        );

        return $mock;
    }

    public function testFor(): void
    {
        self::assertSame(
            MigrationContract::CRAFT,
            $this->migration->for(),
        );
    }

    public function testUpWhenNoTable(): void
    {
        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => '{{%anselSettings}}',
                ],
                [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => '{{%ansel_settings}}',
                ],
            ],
            $this->calls,
        );
    }

    public function testUpWhenTable1(): void
    {
        $this->tableExists = '{{%anselSettings}}';

        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => '{{%anselSettings}}',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%anselSettings}}',
                    'condition' => "settingsKey = 'licenseKey'",
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%anselSettings}}',
                    'condition' => "settingsKey = 'phoneHome'",
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%anselSettings}}',
                    'condition' => "settingsKey = 'encoding'",
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%anselSettings}}',
                    'condition' => "settingsKey = 'encodingData'",
                ],
            ],
            $this->calls,
        );
    }

    public function testUpWhenTable2(): void
    {
        $this->tableExists = '{{%ansel_settings}}';

        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => '{{%anselSettings}}',
                ],
                [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => '{{%ansel_settings}}',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => "settingsKey = 'licenseKey'",
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => "settingsKey = 'phoneHome'",
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => "settingsKey = 'encoding'",
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => "settingsKey = 'encodingData'",
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
