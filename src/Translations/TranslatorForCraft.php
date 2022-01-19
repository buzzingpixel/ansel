<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Translations;

use function strtr;

class TranslatorForCraft implements TranslatorContract
{
    private CraftTranslatorFacade $translator;

    public function __construct(CraftTranslatorFacade $translator)
    {
        $this->translator = $translator;
    }

    public function getLine(string $which): string
    {
        return $this->translator->translate($which);
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
