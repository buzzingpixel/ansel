<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft;

use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use yii\base\Event;

use function define;
use function defined;

/**
 * @codeCoverageIgnore
 */
class AnselCraftPlugin extends Plugin
{
    public function init(): void
    {
        parent::init();

        if (! defined('ANSEL_ENV')) {
            define('ANSEL_ENV', 'craft');
        }

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            static function (
                RegisterComponentTypesEvent $event
            ): void {
                $event->types[] = AnselCraftField::class;
            },
        );
    }
}
