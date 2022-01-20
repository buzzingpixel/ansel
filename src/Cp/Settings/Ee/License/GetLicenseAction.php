<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\License;

use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use cebe\markdown\GithubMarkdown;
use Csrf;
use EE_Lang;
use ExpressionEngine\Service\URL\URLFactory;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetLicenseAction
{
    private Csrf $csrf;

    private EE_Lang $lang;

    private EeCssJs $eeCssJs;

    private Sidebar $sidebar;

    private TwigEnvironment $twig;

    private URLFactory $URLFactory;

    private GithubMarkdown $markdown;

    private InternalFunctions $internalFunctions;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        Csrf $csrf,
        EE_Lang $lang,
        EeCssJs $eeCssJs,
        Sidebar $sidebar,
        TwigEnvironment $twig,
        URLFactory $URLFactory,
        GithubMarkdown $markdown,
        InternalFunctions $internalFunctions,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->csrf               = $csrf;
        $this->lang               = $lang;
        $this->eeCssJs            = $eeCssJs;
        $this->sidebar            = $sidebar;
        $this->twig               = $twig;
        $this->URLFactory         = $URLFactory;
        $this->markdown           = $markdown;
        $this->settingsRepository = $settingsRepository;
        $this->internalFunctions  = $internalFunctions;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(): GetLicenseModel
    {
        $this->eeCssJs->add();

        return new GetLicenseModel(
            $this->lang->line('license'),
            $this->twig->render(
                '@AnselSrc/Cp/Settings/Ee/License/License.twig',
                [
                    'sidebar' => $this->sidebar->get('license'),
                    'pageTitle' => $this->lang->line('license'),
                    'formAction' => $this->URLFactory
                        ->make('addons/settings/ansel/license')
                        ->compile(),
                    'csrfToken' => $this->csrf->get_user_token(),
                    'submitButtonContent' => $this->lang->line(
                        'update',
                    ),
                    'submitButtonWorkingContent' => $this->lang->line(
                        'updating',
                    ) . '...',
                    'licenseLabel' => $this->lang->line(
                        'license_agreement',
                    ),
                    'licenseText' => $this->markdown->parse(
                        $this->internalFunctions->fileGetContents(
                            __DIR__ . '/License.md',
                        )
                    ),
                    'licenseKeyLabel' => $this->lang->line(
                        'your_license_key',
                    ),
                    'licenseKey' => $this->settingsRepository->getSettings()
                        ->getByKey('license_key')
                        ->getString(),
                ],
            ),
        );
    }
}
