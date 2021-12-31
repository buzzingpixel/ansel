<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft;

use craft\base\Plugin;

use function define;

class AnselCraftPlugin extends Plugin
{
    public function init(): void
    {
        parent::init();

        define(ANSEL_ENV, 'craft');
    }
}
