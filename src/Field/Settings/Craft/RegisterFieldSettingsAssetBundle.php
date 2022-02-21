<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\Craft;

use craft\web\View;
use yii\base\InvalidConfigException;

class RegisterFieldSettingsAssetBundle
{
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @throws InvalidConfigException
     */
    public function register(): void
    {
        $this->view->registerAssetBundle(
            FieldSettingsAssetBundle::class,
        );
    }
}
