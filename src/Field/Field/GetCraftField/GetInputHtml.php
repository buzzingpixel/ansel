<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\GetCraftField;

use BuzzingPixel\Ansel\Field\Field\GetCraftFieldAction;
use BuzzingPixel\Ansel\Field\Field\PostDataImageUrlHandler;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Settings\Craft\FieldSettingsFromRaw;
use BuzzingPixel\AnselCms\Craft\AnselCraftField;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;

use function is_array;

class GetInputHtml
{
    private GetCraftFieldAction $getFieldAction;

    private FieldSettingsFromRaw $fieldSettingsFromRaw;

    private PostDataImageUrlHandler $postDataImageUrlHandler;

    public function __construct(
        GetCraftFieldAction $getFieldAction,
        FieldSettingsFromRaw $fieldSettingsFromRaw,
        PostDataImageUrlHandler $postDataImageUrlHandler
    ) {
        $this->getFieldAction          = $getFieldAction;
        $this->fieldSettingsFromRaw    = $fieldSettingsFromRaw;
        $this->postDataImageUrlHandler = $postDataImageUrlHandler;
    }

    /**
     * @param mixed $value
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigException
     */
    public function get(
        $value,
        AnselCraftField $field
    ): string {
        // $value should either be an array of post-back data, or an empty array
        // And we should restore the image URL if a cache for it exists
        $value = $this->postDataImageUrlHandler->restoreFromCache(
            is_array($value) ? $value : []
        );

        return $this->getFieldAction->render(
            $this->fieldSettingsFromRaw->get(
                /** @phpstan-ignore-next-line */
                $field->fieldSettings,
                /** @phpstan-ignore-next-line */
                $field->required,
            ),
            (string) $field->handle,
            PostedData::fromArray($value),
        );
    }
}
