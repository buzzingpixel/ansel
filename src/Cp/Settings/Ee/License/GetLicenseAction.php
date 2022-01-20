<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\License;

use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use cebe\markdown\GithubMarkdown;
use Csrf;
use ExpressionEngine\Service\URL\URLFactory;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetLicenseAction
{
    private Csrf $csrf;

    private EeCssJs $eeCssJs;

    private Sidebar $sidebar;

    private TwigEnvironment $twig;

    private URLFactory $URLFactory;

    private GithubMarkdown $markdown;

    private TranslatorContract $translator;

    private InternalFunctions $internalFunctions;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        Csrf $csrf,
        EeCssJs $eeCssJs,
        Sidebar $sidebar,
        TwigEnvironment $twig,
        URLFactory $URLFactory,
        GithubMarkdown $markdown,
        TranslatorContract $translator,
        InternalFunctions $internalFunctions,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->csrf               = $csrf;
        $this->eeCssJs            = $eeCssJs;
        $this->sidebar            = $sidebar;
        $this->twig               = $twig;
        $this->URLFactory         = $URLFactory;
        $this->markdown           = $markdown;
        $this->translator         = $translator;
        $this->internalFunctions  = $internalFunctions;
        $this->settingsRepository = $settingsRepository;
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
            $this->translator->getLine('license'),
            $this->twig->render(
                '@AnselSrc/Cp/Settings/Ee/License/License.twig',
                [
                    'sidebar' => $this->sidebar->get('license'),
                    'pageTitle' => $this->translator->getLine('license'),
                    'formAction' => $this->URLFactory
                        ->make('addons/settings/ansel/license')
                        ->compile(),
                    'csrfToken' => $this->csrf->get_user_token(),
                    'submitButtonContent' => $this->translator->getLine(
                        'update',
                    ),
                    'submitButtonWorkingContent' => $this->translator->getLine(
                        'updating',
                    ) . '...',
                    'licenseLabel' => $this->translator->getLine(
                        'license_agreement',
                    ),
                    'licenseText' => $this->markdown->parse(
                        $this->internalFunctions->fileGetContents(
                            __DIR__ . '/License.md',
                        )
                    ),
                    'licenseKeyLabel' => $this->translator->getLine(
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
