<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Shared\EE\EeCssJs;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetEeFieldAction
{
    private EeCssJs $eeCssJs;

    private TwigEnvironment $twig;

    private GetFieldParameters $getFieldParameters;

    public function __construct(
        EeCssJs $eeCssJs,
        TwigEnvironment $twig,
        GetFieldParameters $getFieldParameters
    ) {
        $this->eeCssJs            = $eeCssJs;
        $this->twig               = $twig;
        $this->getFieldParameters = $getFieldParameters;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(
        FieldSettingsCollection $fieldSettings
    ): string {
        $this->eeCssJs->add();

        return $this->twig->render(
            '@AnselSrc/Field/Field/Field.twig',
            [
                'model' => new FieldRenderModel(
                    $fieldSettings->asScalarArray(),
                    $fieldSettings->customFields()->asScalarArray(),
                    $this->getFieldParameters->get()->asArray(),
                ),
            ],
        );
    }
}
