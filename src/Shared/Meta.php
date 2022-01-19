<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use ExpressionEngine\Service\URL\URLFactory as CPURLFactory;

use function assert;

class Meta
{
    private string $env;

    private string $version;

    public function __construct(
        string $env,
        string $version
    ) {
        $this->env     = $env;
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

    public function docsUrl(): string
    {
        if ($this->env === 'ee') {
            return 'https://www.buzzingpixel.com/software/ansel-ee/documentation';
        }

        if ($this->env === 'craft') {
            return 'https://www.buzzingpixel.com/software/ansel-craft/documentation';
        }

        return '';
    }

    public function softwarePageLink(): string
    {
        if ($this->env === 'ee') {
            return 'https://www.buzzingpixel.com/software/ansel-ee';
        }

        if ($this->env === 'craft') {
            return 'https://www.buzzingpixel.com/software/ansel-craft';
        }

        return '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function licenseCpLink(): string
    {
        if ($this->env !== 'ee') {
            return '';
        }

        $cpUrl = ee('CP/URL');

        assert($cpUrl instanceof CPURLFactory);

        return $cpUrl->make('addons/settings/ansel/license')->compile();
    }

    public function buzzingPixelAccountUrl(): string
    {
        return 'https://www.buzzingpixel.com/account';
    }
}
