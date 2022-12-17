<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft;

use BuzzingPixel\Ansel\Field\Field\GetCraftFieldAction;
use BuzzingPixel\Ansel\Field\Field\PostDataImageUrlHandler;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Field\Validate\ValidatedFieldError;
use BuzzingPixel\Ansel\Field\Field\Validate\ValidateFieldAction;
use BuzzingPixel\Ansel\Field\Settings\Craft\GetFieldSettings;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollectionValidatorContract;
use BuzzingPixel\Ansel\Field\Settings\PopulateFieldSettingsFromDefaults;
use BuzzingPixel\Ansel\Shared\Meta\Meta;
use BuzzingPixel\AnselConfig\ContainerManager;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\errors\InvalidFieldException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;
use yii\db\Schema;

use function assert;
use function count;
use function dd;
use function is_array;
use function is_string;
use function json_decode;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint

/**
 * @codeCoverageIgnore
 */
class AnselCraftField extends Field
{
    /**
     * Irritatingly, public properties on the class represent field settings
     *
     * @var mixed[]
     */
    public array $fieldSettings = [];

    private static ?Meta $meta = null;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private static function getMeta(): Meta
    {
        if (self::$meta !== null) {
            return self::$meta;
        }

        $container = (new ContainerManager())->container();

        $meta = $container->get(Meta::class);

        assert($meta instanceof Meta);

        self::$meta = $meta;

        return $meta;
    }

    private GetFieldSettings $getFieldSettings;

    private FieldSettingsCollectionValidatorContract $fieldSettingsValidator;

    private PopulateFieldSettingsFromDefaults $populateFieldSettingsFromDefaults;

    private GetCraftFieldAction $getFieldAction;

    private ValidateFieldAction $validateFieldAction;

    private PostDataImageUrlHandler $postDataImageUrlHandler;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function init(): void
    {
        $container = (new ContainerManager())->container();

        $this->getFieldSettings = $container->get(GetFieldSettings::class);

        $this->fieldSettingsValidator = $container->get(
            FieldSettingsCollectionValidatorContract::class,
        );

        $this->populateFieldSettingsFromDefaults = $container->get(
            PopulateFieldSettingsFromDefaults::class,
        );

        $this->getFieldAction = $container->get(GetCraftFieldAction::class);

        $this->validateFieldAction = $container->get(
            ValidateFieldAction::class
        );

        $this->postDataImageUrlHandler = $container->get(
            PostDataImageUrlHandler::class,
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function displayName(): string
    {
        return self::getMeta()->name();
    }

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    private function getFieldSettingsCollection(
        bool $useRequired = false
    ): FieldSettingsCollection {
        unset($this->fieldSettings['placeholder']);

        $fieldSettings = $this->fieldSettings;

        if ($useRequired && $this->required === '1') {
            $minQty = (int) $fieldSettings['minQty'] ?? '';

            if ($minQty < 1) {
                $fieldSettings['minQty'] = '1';
            }
        }

        return FieldSettingsCollection::fromFieldArray(
            $fieldSettings,
        );
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws InvalidConfigException
     */
    public function getSettingsHtml(): string
    {
        $fieldSettings = $this->getFieldSettingsCollection();

        if ($this->getIsNew()) {
            $this->populateFieldSettingsFromDefaults->populate(
                $fieldSettings,
            );
        }

        return $this->getFieldSettings->render(
            $fieldSettings,
        )->content();
    }

    /**
     * Runs before field settings save
     */
    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        $errors = $this->fieldSettingsValidator->validate(
            $this->getFieldSettingsCollection(),
        );

        if (count($errors) < 1) {
            return true;
        }

        $this->addErrors($errors);

        return false;
    }

    /**
     * @throws SyntaxError
     * @throws InvalidConfigException
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function getInputHtml(
        $value,
        ?ElementInterface $element = null
    ): string {
        // $value should either be an array of post-back data, or an empty array
        // And we should restore the image URL if a cache for it exists
        $value = $this->postDataImageUrlHandler->restoreFromCache(
            is_array($value) ? $value : []
        );

        return $this->getFieldAction->render(
            $this->getFieldSettingsCollection(true),
            (string) $this->handle,
            PostedData::fromArray($value),
        );
    }

    /**
     * @return mixed
     */
    public function normalizeValue($value, ?ElementInterface $element = null)
    {
        return $this->postDataImageUrlHandler->scrub($value);
    }

    /**
     * @inheritdoc
     * @phpstan-ignore-next-line
     */
    public function getElementValidationRules(): array
    {
        $rules = parent::getElementValidationRules();

        /** Calls the validateField on this class */
        $rules[] = 'validateField';

        return $rules;
    }

    /**
     * @throws InvalidFieldException
     */
    public function validateField(ElementInterface $element): void
    {
        $data = $element->getFieldValue((string) $this->handle);

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (! is_array($data)) {
            $element->addError(
                $this->handle,
                ['An unknown error occurred'],
            );

            return;
        }

        $fieldSettings = $this->getFieldSettingsCollection(true);

        $validatedFieldResult = $this->validateFieldAction->validate(
            $fieldSettings,
            PostedData::fromArray($data)
        );

        $validatedFieldResult->map(fn (
            ValidatedFieldError $error
        ) => $element->addError(
            $this->handle,
            $error->errorMsg(),
        ));
    }

    public function afterElementSave(
        ElementInterface $element,
        bool $isNew
    ): void {
        $data = $element->getFieldValue($this->handle);

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        dd($data);
    }

    // public function beforeElementDelete()
}
