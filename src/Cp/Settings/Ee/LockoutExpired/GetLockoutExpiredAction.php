<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\LockoutExpired;

use BuzzingPixel\Ansel\Cp\Settings\Ee\McpModel;
use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetLockoutExpiredAction
{
    private Meta $meta;

    private EeCssJs $eeCssJs;

    private Sidebar $sidebar;

    private TwigEnvironment $twig;

    private TranslatorContract $translator;

    public function __construct(
        Meta $meta,
        EeCssJs $eeCssJs,
        Sidebar $sidebar,
        TwigEnvironment $twig,
        TranslatorContract $translator
    ) {
        $this->meta       = $meta;
        $this->eeCssJs    = $eeCssJs;
        $this->sidebar    = $sidebar;
        $this->twig       = $twig;
        $this->translator = $translator;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(): McpModel
    {
        $this->eeCssJs->add();

        return new McpModel(
            $this->translator->getLine(
                'ansel_trial_expired',
            ),
            $this->twig->render(
                '@AnselSrc/Cp/Settings/Ee/LockoutExpired/LockoutExpired.twig',
                [
                    'sidebar' => $this->sidebar->get(),
                    'pageTitle' => $this->translator->getLine(
                        'ansel_trial_expired',
                    ),
                    'content' => $this->translator->getLineWithReplacements(
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
                ],
            ),
        );
    }
}
