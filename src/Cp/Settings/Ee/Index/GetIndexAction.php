<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\Index;

use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use Csrf;
use EE_Lang;
use ExpressionEngine\Service\URL\URLFactory;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetIndexAction
{
    private Csrf $csrf;

    private EE_Lang $lang;

    private EeCssJs $eeCssJs;

    private Sidebar $sidebar;

    private TwigEnvironment $twig;

    private URLFactory $URLFactory;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        Csrf $csrf,
        EE_Lang $lang,
        EeCssJs $eeCssJs,
        Sidebar $sidebar,
        TwigEnvironment $twig,
        URLFactory $URLFactory,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->csrf               = $csrf;
        $this->lang               = $lang;
        $this->eeCssJs            = $eeCssJs;
        $this->sidebar            = $sidebar;
        $this->twig               = $twig;
        $this->URLFactory         = $URLFactory;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(): GetIndexModel
    {
        $this->eeCssJs->add();

        return new GetIndexModel(
            $this->lang->line('settings'),
            $this->twig->render(
                '@AnselSrc/Cp/Settings/Ee/Index/Index.twig',
                [
                    'sidebar' => $this->sidebar->get('settings'),
                    'pageTitle' => $this->lang->line('settings'),
                    'formAction' => $this->URLFactory
                        ->make('addons/settings/ansel')
                        ->compile(),
                    'csrfToken' => $this->csrf->get_user_token(),
                    'submitButtonContent' => $this->lang->line(
                        'save_settings',
                    ),
                    'submitButtonWorkingContent' => $this->lang->line(
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
