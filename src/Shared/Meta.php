<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

class Meta
{
    private string $version;

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function version(): string
    {
        return $this->version;
    }

    public function name(): string
    {
        return 'Ansel';
    }

    public function shortName(): string
    {
        return 'ansel';
    }

    public function description(): string
    {
        return 'Crop images with pre-defined constraints.';
    }

    public function author(): string
    {
        return 'TJ Draper';
    }

    public function authorUrl(): string
    {
        return 'https://www.buzzingpixel.com';
    }

    public function eeDocsUrl(): string
    {
        return 'https://www.buzzingpixel.com/software/ansel-ee/documentation';
    }

    public function craftDocsUrl(): string
    {
        return 'https://www.buzzingpixel.com/software/ansel-craft/documentation';
    }
}
