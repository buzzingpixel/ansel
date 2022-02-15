<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\LockoutInvalid;

use BuzzingPixel\Ansel\Cp\Settings\Ee\McpModel;
use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetLockoutInvalidAction
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
                'ansel_license_invalid',
            ),
            $this->twig->render(
                '@AnselSrc/Cp/Settings/Ee/LockoutInvalid/LockoutInvalid.twig',
                [
                    'sidebar' => $this->sidebar->get(),
                    'pageTitle' => $this->translator->getLine(
                        'ansel_license_invalid',
                    ),
                    'content' => $this->translator->getLineWithReplacements(
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
                ],
            ),
        );
    }
}
