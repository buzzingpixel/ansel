<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\EeSourceHandling\SourceAdapterFactory;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Shared\EE\EeCssJs;
use BuzzingPixel\Ansel\Shared\Environment;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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

    private SourceAdapterFactory $sourceAdapterFactory;

    public function __construct(
        EeCssJs $eeCssJs,
        TwigEnvironment $twig,
        Environment $environment,
        GetFieldRenderContext $getFieldRenderContext,
        SourceAdapterFactory $sourceAdapterFactory
    ) {
        $this->eeCssJs               = $eeCssJs;
        $this->twig                  = $twig;
        $this->environment           = $environment;
        $this->getFieldRenderContext = $getFieldRenderContext;
        $this->sourceAdapterFactory  = $sourceAdapterFactory;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function render(
        FieldSettingsCollection $fieldSettings,
        string $fieldNameRoot,
        PostedData $data
    ): string {
        $this->eeCssJs->add();

        $sourceAdapter = $this->sourceAdapterFactory->createInstanceByShortName(
            $fieldSettings->uploadLocation()->directoryType()
        );

        $modalLink = base64_encode(
            $sourceAdapter->getModalLink($fieldSettings),
        );

        return $this->twig->render(
            '@AnselSrc/Field/Field/Field.twig',
            $this->getFieldRenderContext->get(
                $fieldSettings,
                $fieldNameRoot,
                [
                    'environment' => $this->environment->toString(),
                    'fileChooserModalLink' => $modalLink,
                ],
                $data,
            ),
        );
    }
}
