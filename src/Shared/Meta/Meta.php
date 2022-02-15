<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Meta;

use BuzzingPixel\Ansel\Shared\Environment;
use BuzzingPixel\Ansel\Shared\Version;
use ExpressionEngine\Service\URL\URLFactory as CPURLFactory;

use function assert;
use function ee;

class Meta
{
    private Version $version;

    private Environment $environment;

    public function __construct(
        Version $version,
        Environment $environment
    ) {
        $this->version     = $version;
        $this->environment = $environment;
    }

    public function version(): string
    {
        return $this->version->toString();
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
        if ($this->environment->isEqualTo('ee')) {
            return 'https://www.buzzingpixel.com/software/ansel-ee/documentation';
        }

        if ($this->environment->isEqualTo('craft')) {
            return 'https://www.buzzingpixel.com/software/ansel-craft/documentation';
        }

        return '';
    }

    public function softwarePageLink(): string
    {
        if ($this->environment->isEqualTo('ee')) {
            return 'https://www.buzzingpixel.com/software/ansel-ee';
        }

        if ($this->environment->isEqualTo('craft')) {
            return 'https://www.buzzingpixel.com/software/ansel-craft';
        }

        return '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function licenseCpLink(): string
    {
        if ($this->environment->isNotEqualTo('ee')) {
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
