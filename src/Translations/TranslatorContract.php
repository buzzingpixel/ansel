<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Translations;

interface TranslatorContract
{
    public function getLine(string $which): string;

    /**
     * @param array<string, string> $replacements
     */
    public function getLineWithReplacements(
        string $which,
        array $replacements
    ): string;
}
