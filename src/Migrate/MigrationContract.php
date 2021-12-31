<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

interface MigrationContract
{
    public const EE = 'ee';

    public function for(): string;

    public function up(): bool;

    public function down(): bool;
}
