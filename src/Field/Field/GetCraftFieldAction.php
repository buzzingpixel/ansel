<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Shared\Craft\AssetBundles\CraftRegisterAssetBundle;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;

class GetCraftFieldAction
{
    private TwigEnvironment $twig;
    private CraftRegisterAssetBundle $registerAssetBundle;

    public function __construct(
        TwigEnvironment $twig,
        CraftRegisterAssetBundle $registerAssetBundle
    ) {
        $this->twig                = $twig;
        $this->registerAssetBundle = $registerAssetBundle;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws InvalidConfigException
     */
    public function render(
        FieldSettingsCollection $fieldSettings
    ): string {
        $this->registerAssetBundle->register();

        return $this->twig->render(
            '@AnselSrc/Field/Field/Field.twig',
            [
                'model' => new FieldRenderModel(
                    $fieldSettings->asScalarArray(),
                    $fieldSettings->customFields()->asScalarArray(),
                ),
            ],
        );
    }
}