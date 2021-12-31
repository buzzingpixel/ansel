<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

/**
 * @codeCoverageIgnore
 */
class NoOpMigration implements MigrationContract
{
    public function for(): string
    {
        return 'testing';
    }

    public function up(): bool
    {
        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
