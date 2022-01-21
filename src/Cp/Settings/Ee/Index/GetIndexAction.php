<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\Index;

use BuzzingPixel\Ansel\Cp\Settings\Ee\McpModel;
use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use Csrf;
use ExpressionEngine\Service\URL\URLFactory;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetIndexAction
{
    private Csrf $csrf;

    private EeCssJs $eeCssJs;

    private Sidebar $sidebar;

    private TwigEnvironment $twig;

    private URLFactory $URLFactory;

    private TranslatorContract $translator;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        Csrf $csrf,
        EeCssJs $eeCssJs,
        Sidebar $sidebar,
        TwigEnvironment $twig,
        URLFactory $URLFactory,
        TranslatorContract $translator,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->csrf               = $csrf;
        $this->eeCssJs            = $eeCssJs;
        $this->sidebar            = $sidebar;
        $this->twig               = $twig;
        $this->URLFactory         = $URLFactory;
        $this->translator         = $translator;
        $this->settingsRepository = $settingsRepository;
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
            $this->translator->getLine('settings'),
            $this->twig->render(
                '@AnselSrc/Cp/Settings/Ee/Index/Index.twig',
                [
                    'sidebar' => $this->sidebar->get('settings'),
                    'pageTitle' => $this->translator->getLine('settings'),
                    'formAction' => $this->URLFactory
                        ->make('addons/settings/ansel')
                        ->compile(),
                    'csrfToken' => $this->csrf->get_user_token(),
                    'submitButtonContent' => $this->translator->getLine(
                        'save_settings',
                    ),
                    'submitButtonWorkingContent' => $this->translator->getLine(
                        'saving',
                    ) . '...',
                    'settingsCollection' => $this->settingsRepository
                        ->getSettings()
                        ->settingsPageOnly(),
                ]
            ),
        );
    }
}
