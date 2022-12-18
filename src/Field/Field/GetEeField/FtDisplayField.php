<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\GetEeField;

use BuzzingPixel\Ansel\Field\Field\EeContentType;
use BuzzingPixel\Ansel\Field\Field\FieldMetaEe;
use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\FieldSettingsFromRaw;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class FtDisplayField
{
    private FieldSettingsFromRaw $fieldSettingsFromRaw;

    private GetEeFieldAction $getFieldAction;

    public function __construct(
        GetEeFieldAction $getFieldAction,
        FieldSettingsFromRaw $fieldSettingsFromRaw
    ) {
        $this->getFieldAction       = $getFieldAction;
        $this->fieldSettingsFromRaw = $fieldSettingsFromRaw;
    }

    /**
     * @param mixed                 $value
     * @param mixed[]               $rawEeFieldSettings
     * @param float|int|string|bool $fieldId
     * @param float|int|string|bool $fieldName
     * @param float|int|string|bool $contentType
     * @param float|int|string|bool $contentId
     *
     * @throws ContainerExceptionInterface
     * @throws LoaderError
     * @throws NotFoundExceptionInterface
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function display(
        $value,
        array $rawEeFieldSettings,
        $fieldId,
        $fieldName,
        $contentType,
        $contentId
    ): string {
        // TODO: License check

        $fieldSettings = $this->fieldSettingsFromRaw->get(
            $rawEeFieldSettings,
            true
        );

        return $this->getFieldAction->render(
            $fieldSettings,
            new FieldMetaEe(
                (int) $fieldId,
                (string) $fieldName,
                new EeContentType((string) $contentType),
                // We don't have enough info to get this here (thanks EE) and we
                // don't need it (thankfully)
                0,
                (int) $contentId,
            ),
            $value,
        );
    }
}
