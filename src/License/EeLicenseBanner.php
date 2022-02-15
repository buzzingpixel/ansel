<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\License;

use BuzzingPixel\Ansel\Shared\Facades\EeAlerts;
use BuzzingPixel\Ansel\Shared\Meta\Meta;
use BuzzingPixel\Ansel\Translations\TranslatorContract;

class EeLicenseBanner
{
    private Meta $meta;

    private EeAlerts $eeAlerts;

    private TranslatorContract $translator;

    public function __construct(
        Meta $meta,
        EeAlerts $eeAlerts,
        TranslatorContract $translator
    ) {
        $this->meta       = $meta;
        $this->eeAlerts   = $eeAlerts;
        $this->translator = $translator;
    }

    public function setFromLicenseResult(LicenseResult $result): void
    {
        if ($result->isValid() || $result->isTrial()) {
            return;
        }

        if ($result->isExpired()) {
            $this->setAlert(
                $this->translator->getLine('ansel_trial_expired'),
                $this->translator->getLineWithReplacements(
                    'ansel_trial_expired_body',
                    [
                        '{{purchaseLinkStart}}' => '<a href="' .
                            $this->meta->softwarePageLink() .
                            '">',
                        '{{licenseLinkStart}}' => '<a href="' .
                            $this->meta->licenseCpLink() .
                            '">',
                        '{{linkEnd}}' => '</a>',
                    ],
                ),
            );

            return;
        }

        if ($result->isTrialWithInvalidLicenseKey()) {
            $this->setAlert(
                $this->translator->getLine(
                    'ansel_license_invalid',
                ),
                $this->translator->getLineWithReplacements(
                    'trial_active_invalid_license_key_body',
                    [
                        '{{accountLinkStart}}' => '<a href="' .
                            $this->meta->buzzingPixelAccountUrl() .
                            '">',
                        '{{purchaseLinkStart}}' => '<a href="' .
                            $this->meta->softwarePageLink() .
                            '">',
                        '{{licenseLinkStart}}' => '<a href="' .
                            $this->meta->licenseCpLink() .
                            '">',
                        '{{linkEnd}}' => '</a>',
                    ],
                ),
            );

            return;
        }

        // Now we know that $result->isExpiredWithInvalidLicenseKey() === true

        $this->setAlert(
            $this->translator->getLine(
                'ansel_license_invalid',
            ),
            $this->translator->getLineWithReplacements(
                'ansel_license_invalid_body',
                [
                    '{{accountLinkStart}}' => '<a href="' .
                        $this->meta->buzzingPixelAccountUrl() .
                        '">',
                    '{{purchaseLinkStart}}' => '<a href="' .
                        $this->meta->softwarePageLink() .
                        '">',
                    '{{licenseLinkStart}}' => '<a href="' .
                        $this->meta->licenseCpLink() .
                        '">',
                    '{{linkEnd}}' => '</a>',
                ],
            ),
        );
    }

    private function setAlert(string $title, string $body): void
    {
        $this->eeAlerts->makeBanner()
            ->asIssue()
            ->canClose()
            ->withTitle($title)
            ->addToBody($body)
            ->now();
    }
}
