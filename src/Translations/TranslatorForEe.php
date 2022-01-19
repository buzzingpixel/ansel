<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Translations;

use EE_Lang;

use function strtr;

class TranslatorForEe implements TranslatorContract
{
    private EE_Lang $lang;

    public function __construct(EE_Lang $lang)
    {
        $this->lang = $lang;
    }

    public function getLine(string $which): string
    {
        return $this->lang->line($which);
    }

    /**
     * @inheritDoc
     */
    public function getLineWithReplacements(
        string $which,
        array $replacements
    ): string {
        return strtr($this->getLine($which), $replacements);
    }
}
