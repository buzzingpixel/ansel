<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use craft\web\twig\TemplateLoader;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;
use Twig\Source;

use function strpos;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint

class CraftTwigLoader implements LoaderInterface
{
    private FilesystemLoader $anselLoader;

    private TemplateLoader $craftTemplateLoader;

    public function __construct(
        FilesystemLoader $anselLoader,
        TemplateLoader $craftTemplateLoader
    ) {
        $this->anselLoader         = $anselLoader;
        $this->craftTemplateLoader = $craftTemplateLoader;
    }

    private function isAnselPath(string $name): bool
    {
        return strpos($name, '@Ansel') === 0;
    }

    public function getSourceContext($name): Source
    {
        if ($this->isAnselPath($name)) {
            return $this->anselLoader->getSourceContext($name);
        }

        return $this->craftTemplateLoader->getSourceContext($name);
    }

    public function getCacheKey($name): string
    {
        if ($this->isAnselPath($name)) {
            return $this->anselLoader->getCacheKey($name);
        }

        return $this->craftTemplateLoader->getCacheKey($name);
    }

    public function isFresh($name, $time): bool
    {
        if ($this->isAnselPath($name)) {
            return $this->anselLoader->isFresh($name, $time);
        }

        return $this->craftTemplateLoader->isFresh($name, $time);
    }

    public function exists($name): bool
    {
        if ($this->isAnselPath($name)) {
            return $this->anselLoader->exists($name);
        }

        return $this->craftTemplateLoader->exists($name);
    }
}
