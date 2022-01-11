<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft;

use BuzzingPixel\AnselCms\Craft\Controllers\CpSettings\GetCpSettingsController;
use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\UrlHelper;
use craft\services\Fields;
use craft\web\UrlManager;
use yii\base\Event;
use yii\web\Response;

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

        $this->mapControllers();

        $this->registerFieldTypes();

        $this->registerCpUrlRules();
    }

    public function getSettingsResponse(): Response
    {
        /** @phpstan-ignore-next-line */
        return Craft::$app->controller->redirect(
            UrlHelper::cpUrl('ansel'),
        );
    }

    private function mapControllers(): void
    {
        $this->controllerMap = [
            'get-cp-settings' => GetCpSettingsController::class,
        ];
    }

    private function registerFieldTypes(): void
    {
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

    private function registerCpUrlRules(): void
    {
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            static function (RegisterUrlRulesEvent $event): void {
                $event->rules['ansel'] = 'ansel/get-cp-settings/index';
            }
        );
    }
}
