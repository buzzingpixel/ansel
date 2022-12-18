<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\GetEeField;

use BuzzingPixel\Ansel\Field\Field\FieldMetaEe;
use BuzzingPixel\Ansel\Field\Field\GetFieldRenderContext;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Shared\EE\EeCssJs;
use BuzzingPixel\Ansel\Shared\Environment;
use BuzzingPixel\Ansel\SourceHandling\Ee\EeSourceAdapterFactory;
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

    private EeSourceAdapterFactory $sourceAdapterFactory;

    private EeDataFactory $eeDataFactory;

    public function __construct(
        EeCssJs $eeCssJs,
        TwigEnvironment $twig,
        Environment $environment,
        GetFieldRenderContext $getFieldRenderContext,
        EeSourceAdapterFactory $sourceAdapterFactory,
        EeDataFactory $eeDataFactory
    ) {
        $this->eeCssJs               = $eeCssJs;
        $this->twig                  = $twig;
        $this->environment           = $environment;
        $this->getFieldRenderContext = $getFieldRenderContext;
        $this->sourceAdapterFactory  = $sourceAdapterFactory;
        $this->eeDataFactory         = $eeDataFactory;
    }

    /**
     * @param mixed $value
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function render(
        FieldSettingsCollection $fieldSettings,
        FieldMetaEe $fieldMeta,
        $value
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
                $fieldMeta->fieldName(),
                [
                    'environment' => $this->environment->toString(),
                    'fileChooserModalLink' => $modalLink,
                ],
                $this->eeDataFactory->create(
                    $fieldMeta,
                    $value,
                ),
            ),
        );
    }
}
