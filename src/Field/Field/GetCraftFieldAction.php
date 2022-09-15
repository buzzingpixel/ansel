<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Shared\Craft\AssetBundles\CraftRegisterAssetBundle;
use BuzzingPixel\Ansel\Shared\Environment;
use craft\base\VolumeInterface;
use craft\models\VolumeFolder;
use craft\services\Assets;
use craft\services\Volumes;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;

use function assert;

class GetCraftFieldAction
{
    private Assets $assetsService;

    private TwigEnvironment $twig;

    private Volumes $volumesService;

    private Environment $environment;

    private GetFieldRenderContext $getFieldRenderContext;

    private CraftRegisterAssetBundle $registerAssetBundle;

    public function __construct(
        Assets $assetsService,
        TwigEnvironment $twig,
        Volumes $volumesService,
        Environment $environment,
        GetFieldRenderContext $getFieldRenderContext,
        CraftRegisterAssetBundle $registerAssetBundle
    ) {
        $this->assetsService         = $assetsService;
        $this->twig                  = $twig;
        $this->volumesService        = $volumesService;
        $this->environment           = $environment;
        $this->registerAssetBundle   = $registerAssetBundle;
        $this->getFieldRenderContext = $getFieldRenderContext;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws InvalidConfigException
     */
    public function render(
        FieldSettingsCollection $fieldSettings,
        string $fieldNameRoot
    ): string {
        $this->registerAssetBundle->register();

        $volume = $this->volumesService->getVolumeByUid(
            $fieldSettings->uploadLocation()->directoryId(),
        );

        assert($volume instanceof VolumeInterface);

        $uploadLocationFolder = $this->assetsService->findFolder(
            /** @phpstan-ignore-next-line */
            ['volumeId' => $volume->id],
        );

        assert($uploadLocationFolder instanceof VolumeFolder);

        return $this->twig->render(
            '@AnselSrc/Field/Field/Field.twig',
            $this->getFieldRenderContext->get(
                $fieldSettings,
                $fieldNameRoot,
                [
                    'environment' => $this->environment->toString(),
                    'uploadLocationFolderId' => (string) $uploadLocationFolder->uid,
                ]
            ),
        );
    }
}
