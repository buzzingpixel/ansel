<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\Facades\CraftMigrationHelper;
use craft\db\Connection as DbConnection;
use PHPUnit\Framework\TestCase;

class C0007RenameImagesTableTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private bool $tableExistsReturn = false;

    private C0007RenameImagesTable $migration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->tableExistsReturn = false;

        $this->migration = new C0007RenameImagesTable(
            $this->mockDb(),
            $this->mockMigrationHelper(),
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

                return $this->tableExistsReturn;
            }
        );

        return $mock;
    }

    private function mockMigrationHelper(): CraftMigrationHelper
    {
        $mock = $this->createMock(
            CraftMigrationHelper::class,
        );

        $mock->method('renameTable')->willReturnCallback(
            function (string $oldName, string $newName): void {
                $this->calls[] = [
                    'object' => 'CraftMigrationHelper',
                    'method' => 'renameTable',
                    'oldName' => $oldName,
                    'newName' => $newName,
                ];
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

    public function testUpWhenTableDoesNotExist(): void
    {
        $this->tableExistsReturn = false;

        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => '{{%anselImages}}',
                ],
            ],
            $this->calls,
        );
    }

    public function testUpWhenTableExists(): void
    {
        $this->tableExistsReturn = true;

        self::assertTrue($this->migration->up());

        self::assertSame(
            [
                [
                    'object' => 'DbConnection',
                    'method' => 'tableExists',
                    'table' => '{{%anselImages}}',
                ],
                [
                    'object' => 'CraftMigrationHelper',
                    'method' => 'renameTable',
                    'oldName' => '{{%anselImages}}',
                    'newName' => '{{%ansel_images}}',
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
