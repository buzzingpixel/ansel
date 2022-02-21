<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Craft\AssetBundles;

use craft\web\View;
use yii\base\InvalidConfigException;

class CraftRegisterAssetBundle
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
        $this->view->registerAssetBundle(CraftAnselAssetBundle::class);
    }
}
