<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft;

use BuzzingPixel\Ansel\Field\Settings\Craft\GetFieldSettings;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Shared\Meta\Meta;
use BuzzingPixel\AnselConfig\ContainerManager;
use craft\base\Field;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\db\Schema;

use function assert;

/**
 * @codeCoverageIgnore
 */
class AnselCraftField extends Field
{
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

    public function init(): void
    {
        $container = (new ContainerManager())->container();

        $getFieldSettings = $container->get(GetFieldSettings::class);
        assert($getFieldSettings instanceof GetFieldSettings);
        $this->getFieldSettings = $getFieldSettings;
    }

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function getSettingsHtml(): string
    {
        // dump($this->getSettings());

        return $this->getFieldSettings->render(
            new FieldSettingsCollection(),
        )->content();
    }
}
