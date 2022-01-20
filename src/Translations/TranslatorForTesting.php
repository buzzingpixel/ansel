<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Translations;

use function strtr;

class TranslatorForTesting implements TranslatorContract
{
    public function getLine(string $which): string
    {
        if ($which === 'ansel_trial_expired_body') {
            return $which . '-translator {{purchaseLinkStart}}link{{linkEnd}}' .
                '{{licenseLinkStart}}link{{linkEnd}}';
        }

        if ($which === 'trial_active_invalid_license_key_body') {
            return $which . '-translator {{accountLinkStart}}link{{linkEnd}}' .
                '{{purchaseLinkStart}}link{{linkEnd}} {{licenseLinkStart}}link{{linkEnd}}';
        }

        if ($which === 'ansel_license_invalid_body') {
            return $which . '-translator {{accountLinkStart}}link{{linkEnd}}' .
                '{{purchaseLinkStart}}link{{linkEnd}} {{licenseLinkStart}}link{{linkEnd}}';
        }

        return $which . '-translator';
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
