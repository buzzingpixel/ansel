<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft;

use BuzzingPixel\Ansel\Field\Field\GetCraftField\GetInputHtml;
use BuzzingPixel\Ansel\Field\Field\PostDataImageUrlHandler;
use BuzzingPixel\Ansel\Field\Field\Validate\CraftValidateField;
use BuzzingPixel\Ansel\Field\Settings\Craft\GetSettingsHtml;
use BuzzingPixel\Ansel\Field\Settings\Craft\ValidateSettings;
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
use function dd;

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

    private GetSettingsHtml $getSettingsHtml;

    private ValidateSettings $validateSettings;

    private GetInputHtml $getInputHtml;

    private CraftValidateField $validateField;

    private PostDataImageUrlHandler $postDataImageUrlHandler;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function init(): void
    {
        $container = (new ContainerManager())->container();

        $this->getSettingsHtml = $container->get(GetSettingsHtml::class);

        $this->validateSettings = $container->get(ValidateSettings::class);

        $this->getInputHtml = $container->get(GetInputHtml::class);

        $this->validateField = $container->get(CraftValidateField::class);

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
        $container = (new ContainerManager())->container();
        $meta      = $container->get(Meta::class);
        assert($meta instanceof Meta);

        return $meta->name();
    }

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws InvalidConfigException
     */
    public function getSettingsHtml(): string
    {
        return $this->getSettingsHtml->get(
            $this->fieldSettings,
            $this->getIsNew(),
        );
    }

    /**
     * Runs before field settings save
     */
    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        return $this->validateSettings->validate($this);
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
        return $this->getInputHtml->get($value, $this);
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
        $this->validateField->validate($element, $this);
    }

    public function afterElementSave(
        ElementInterface $element,
        bool $isNew
    ): void {
        $data = $element->getFieldValue($this->handle);

        dd($data);
    }

    // public function beforeElementDelete()
}
