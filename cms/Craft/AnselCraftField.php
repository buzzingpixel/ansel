<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft;

use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\AnselConfig\ContainerManager;
use craft\base\Field;
use yii\db\Schema;

use function assert;

class AnselCraftField extends Field
{
    private static ?Meta $meta = null;

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

    public static function displayName(): string
    {
        return self::getMeta()->name();
    }

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }
}
