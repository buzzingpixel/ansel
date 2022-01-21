<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\Updates;

use BuzzingPixel\Ansel\Cp\Settings\Ee\McpModel;
use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use BuzzingPixel\Ansel\UpdatesFeed\UpdatesFeedRepository;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetUpdatesAction
{
    private EeCssJs $eeCssJs;

    private Sidebar $sidebar;

    private TwigEnvironment $twig;

    private TranslatorContract $translator;

    private UpdatesFeedRepository $updatesFeedRepository;

    public function __construct(
        EeCssJs $eeCssJs,
        Sidebar $sidebar,
        TwigEnvironment $twig,
        TranslatorContract $translator,
        UpdatesFeedRepository $updatesFeedRepository
    ) {
        $this->eeCssJs               = $eeCssJs;
        $this->sidebar               = $sidebar;
        $this->twig                  = $twig;
        $this->translator            = $translator;
        $this->updatesFeedRepository = $updatesFeedRepository;
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
            $this->translator->getLine('updates'),
            $this->twig->render(
                '@AnselSrc/Cp/Settings/Ee/Updates/Updates.twig',
                [
                    'sidebar' => $this->sidebar->get('updates'),
                    'pageTitle' => $this->translator->getLine(
                        'updates',
                    ),
                    'updates' => $this->updatesFeedRepository->getUpdates(),
                ],
            ),
        );
    }
}
