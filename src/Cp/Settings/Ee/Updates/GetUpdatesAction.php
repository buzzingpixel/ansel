<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\Updates;

use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\UpdatesFeed\UpdatesFeedRepository;
use EE_Lang;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetUpdatesAction
{
    private EE_Lang $lang;

    private EeCssJs $eeCssJs;

    private Sidebar $sidebar;

    private TwigEnvironment $twig;

    private UpdatesFeedRepository $updatesFeedRepository;

    public function __construct(
        EE_Lang $lang,
        EeCssJs $eeCssJs,
        Sidebar $sidebar,
        TwigEnvironment $twig,
        UpdatesFeedRepository $updatesFeedRepository
    ) {
        $this->lang                  = $lang;
        $this->eeCssJs               = $eeCssJs;
        $this->sidebar               = $sidebar;
        $this->twig                  = $twig;
        $this->updatesFeedRepository = $updatesFeedRepository;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(): GetUpdatesModel
    {
        $this->eeCssJs->add();

        return new GetUpdatesModel(
            $this->lang->line('updates'),
            $this->twig->render(
                '@AnselSrc/Cp/Settings/Ee/Updates/Updates.twig',
                [
                    'sidebar' => $this->sidebar->get('updates'),
                    'pageTitle' => $this->lang->line('updates'),
                    'updates' => $this->updatesFeedRepository->getUpdates(),
                ],
            ),
        );
    }
}
