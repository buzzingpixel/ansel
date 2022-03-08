<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use craft\db\Command;
use craft\db\Connection as DbConnection;
use PHPUnit\Framework\TestCase;
use yii\db\Exception;

class C0011RemoveOldSettingsTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private C0011RemoveOldSettings $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->migration = new C0011RemoveOldSettings($this->mockDb());
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
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => 'settingsKey = \'defaultShowTitle\'',
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => 'settingsKey = \'defaultRequireTitle\'',
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => 'settingsKey = \'defaultTitleLabel\'',
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => 'settingsKey = \'defaultShowCaption\'',
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => 'settingsKey = \'defaultRequireCaption\'',
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => 'settingsKey = \'defaultCaptionLabel\'',
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => 'settingsKey = \'defaultShowCover\'',
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => 'settingsKey = \'defaultRequireCover\'',
                ],
                [
                    'object' => 'Command',
                    'method' => 'execute',
                ],
                [
                    'object' => 'Command',
                    'method' => 'delete',
                    'table' => '{{%ansel_settings}}',
                    'condition' => 'settingsKey = \'defaultCoverLabel\'',
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
