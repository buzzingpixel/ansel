<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft;

use BuzzingPixel\Ansel\Field\Field\GetCraftFieldAction;
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

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function displayName(): string
    {
        return self::getMeta()->name();
    }

    private GetFieldSettings $getFieldSettings;

    private FieldSettingsCollectionValidatorContract $fieldSettingsValidator;

    private PopulateFieldSettingsFromDefaults $populateFieldSettingsFromDefaults;

    private GetCraftFieldAction $getFieldAction;

    private ValidateFieldAction $validateFieldAction;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function init(): void
    {
        $container = (new ContainerManager())->container();

        /** @phpstan-ignore-next-line */
        $this->getFieldSettings = $container->get(GetFieldSettings::class);

        /** @phpstan-ignore-next-line */
        $this->fieldSettingsValidator = $container->get(
            FieldSettingsCollectionValidatorContract::class,
        );

        /** @phpstan-ignore-next-line */
        $this->populateFieldSettingsFromDefaults = $container->get(
            PopulateFieldSettingsFromDefaults::class,
        );

        /** @phpstan-ignore-next-line */
        $this->getFieldAction = $container->get(GetCraftFieldAction::class);

        /** @phpstan-ignore-next-line */
        $this->validateFieldAction = $container->get(
            ValidateFieldAction::class
        );
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
        $value = is_array($value) ? $value : [];

        return $this->getFieldAction->render(
            $this->getFieldSettingsCollection(true),
            (string) $this->handle,
            PostedData::fromArray($value),
        );
    }

    /**
     * @return mixed
     */
    // public function normalizeValue($value, ?ElementInterface $element = null)
    // {
    //     // If there is no value, we can return null
    //     /** @phpstan-ignore-next-line */
    //     if (empty($value)) {
    //         return null;
    //     }
    //
    //     return $value;
    //
    //     // If the value is an array, this is post data and we can return as is
    //     if (is_array($value)) {
    //         return $value;
    //     }
    //
    //     // TODO: Normalize value
    //     // dd('normalizeValue', $value);
    //
    //     return $value;
    // }

    // public function isValueEmpty($value, ElementInterface $element): bool
    // {
    //     if (empty($_POST)) {
    //         return true;
    //     }
    //
    //     dd('isValueEmpty', $value);
    // }

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

        if (! is_array($data)) {
            dd('not array', $data);
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
}
