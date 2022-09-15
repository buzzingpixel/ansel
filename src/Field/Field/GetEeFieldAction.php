<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Field\FileChooserModalLink\FileChooserModalLinkFactory;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Shared\EE\EeCssJs;
use BuzzingPixel\Ansel\Shared\Environment;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function base64_encode;

class GetEeFieldAction
{
    private EeCssJs $eeCssJs;

    private TwigEnvironment $twig;

    private Environment $environment;

    private GetFieldRenderContext $getFieldRenderContext;

    private FileChooserModalLinkFactory $fileChooserModalLinkFactory;

    public function __construct(
        EeCssJs $eeCssJs,
        TwigEnvironment $twig,
        Environment $environment,
        GetFieldRenderContext $getFieldRenderContext,
        FileChooserModalLinkFactory $fileChooserModalLinkFactory
    ) {
        $this->eeCssJs                     = $eeCssJs;
        $this->twig                        = $twig;
        $this->environment                 = $environment;
        $this->getFieldRenderContext       = $getFieldRenderContext;
        $this->fileChooserModalLinkFactory = $fileChooserModalLinkFactory;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(
        FieldSettingsCollection $fieldSettings,
        string $fieldNameRoot
    ): string {
        $this->eeCssJs->add();

        $modalLink = base64_encode(
            $this->fileChooserModalLinkFactory->getLink(
                $fieldSettings,
            ),
        );

        return $this->twig->render(
            '@AnselSrc/Field/Field/Field.twig',
            $this->getFieldRenderContext->get(
                $fieldSettings,
                $fieldNameRoot,
                [
                    'environment' => $this->environment->toString(),
                    'fileChooserModalLink' => $modalLink,
                ]
            ),
        );
    }
}
