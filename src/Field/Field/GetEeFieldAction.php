<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Field\FileChooserModalLink\FileChooserModalLinkFactory;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Shared\EE\EeCssJs;
use BuzzingPixel\Ansel\Shared\Environment;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
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

    private TranslatorContract $translator;

    private GetFieldParameters $getFieldParameters;

    private FileChooserModalLinkFactory $fileChooserModalLinkFactory;

    public function __construct(
        EeCssJs $eeCssJs,
        TwigEnvironment $twig,
        Environment $environment,
        TranslatorContract $translator,
        GetFieldParameters $getFieldParameters,
        FileChooserModalLinkFactory $fileChooserModalLinkFactory
    ) {
        $this->eeCssJs                     = $eeCssJs;
        $this->twig                        = $twig;
        $this->environment                 = $environment;
        $this->translator                  = $translator;
        $this->getFieldParameters          = $getFieldParameters;
        $this->fileChooserModalLinkFactory = $fileChooserModalLinkFactory;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(FieldSettingsCollection $fieldSettings): string
    {
        $this->eeCssJs->add();

        $modalLink = base64_encode($this->fileChooserModalLinkFactory->getLink(
            $fieldSettings,
        ));

        return $this->twig->render(
            '@AnselSrc/Field/Field/Field.twig',
            [
                'model' => new FieldRenderModel(
                    $fieldSettings->asScalarArray(),
                    $fieldSettings->customFields()->asScalarArray(),
                    $this->getFieldParameters->get()->asArray(),
                    [
                        'imageUploadError' => $this->translator->getLine(
                            'image_upload_error',
                        ),
                        'selectImageFromDevice' => $this->translator->getLine(
                            'select_image_from_device'
                        ),
                        'unusableImage' => $this->translator->getLine(
                            'unusable_image'
                        ),
                    ],
                    [
                        'environment' => $this->environment->toString(),
                        'fileChooserModalLink' => $modalLink,
                    ],
                ),
            ],
        );
    }
}
