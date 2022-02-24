<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft;

use BuzzingPixel\Ansel\Field\Settings\Craft\GetFieldSettings;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollectionValidatorCraft;
use BuzzingPixel\Ansel\Shared\Meta\Meta;
use BuzzingPixel\AnselConfig\ContainerManager;
use craft\base\Field;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;
use yii\db\Schema;

use function assert;
use function count;

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

    private FieldSettingsCollectionValidatorCraft $fieldSettingsCollectionValidator;

    public function init(): void
    {
        $container = (new ContainerManager())->container();

        $getFieldSettings = $container->get(GetFieldSettings::class);
        assert($getFieldSettings instanceof GetFieldSettings);
        $this->getFieldSettings = $getFieldSettings;

        $fieldSettingsCollectionValidator = $container->get(
            FieldSettingsCollectionValidatorCraft::class,
        );
        assert(
            $fieldSettingsCollectionValidator instanceof FieldSettingsCollectionValidatorCraft
        );
        $this->fieldSettingsCollectionValidator = $fieldSettingsCollectionValidator;
    }

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    private function getFieldSettingsCollection(): FieldSettingsCollection
    {
        unset($this->fieldSettings['placeholder']);

        return FieldSettingsCollection::fromFieldArray(
            $this->fieldSettings,
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
        return $this->getFieldSettings->render(
            $this->getFieldSettingsCollection(),
        )->content();
    }

    /**
     * Runs before field settings save
     */
    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        $errors = $this->fieldSettingsCollectionValidator->validate(
            $this->getFieldSettingsCollection(),
        );

        if (count($errors) < 1) {
            return true;
        }

        $this->addErrors($errors);

        return false;
    }
}
