<?php


declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Craft;
use craft\services\Elements as ElementsService;

class CraftElementsBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            ElementsService::class => static function (): ElementsService
            {
                return Craft::$app->getElements();
            }
        ];
    }
}
