<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\AnselCms\Craft\AnselCraftField;
use craft\db\Command;
use craft\db\Connection as DbConnection;
use PHPUnit\Framework\TestCase;
use yii\db\Exception;

class C0009UpdateAnselFieldTypeNameFromCraft2Test extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private C0009UpdateAnselFieldTypeNameFromCraft2 $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->migration = new C0009UpdateAnselFieldTypeNameFromCraft2(
            $this->mockDb(),
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
                string $condition
            ) use ($mock): Command {
                $this->calls[] = [
                    'object' => 'Command',
                    'method' => 'createTable',
                    'table' => $table,
                    'columns' => $columns,
                    'condition' => $condition,
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

    public function testFor(): void
    {
        self::assertSame(
            MigrationContract::CRAFT,
            $this->migration->for(),
        );
    }

    /**
     * @throws Exception
     */
    public function testUp(): void
    {
        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'Command',
                    'method' => 'createTable',
                    'table' => '{{%fields}}',
                    'columns' => ['type' => AnselCraftField::class],
                    'condition' => "`type` = 'Ansel_Ansel'",
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
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
