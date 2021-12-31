<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

class RunMigration
{
    private string $migration;

    public function __construct(string $migration)
    {
        $this->migration = $migration;
    }

    public function migration(): string
    {
        return $this->migration;
    }
}
