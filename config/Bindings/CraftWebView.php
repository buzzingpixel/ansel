<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Craft;
use craft\web\View;

class CraftWebView
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            View::class => static function (): View {
                /** @phpstan-ignore-next-line */
                return Craft::$app->getView();
            },
        ];
    }
}
