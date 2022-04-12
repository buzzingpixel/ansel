<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Shared\Craft\AssetBundles\CraftRegisterAssetBundle;
use BuzzingPixel\Ansel\Shared\Environment;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use craft\models\VolumeFolder;
use craft\services\Assets;
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

    private Environment $environment;

    private TranslatorContract $translator;

    private GetFieldParameters $getFieldParameters;

    private CraftRegisterAssetBundle $registerAssetBundle;

    public function __construct(
        Assets $assetsService,
        TwigEnvironment $twig,
        Environment $environment,
        TranslatorContract $translator,
        GetFieldParameters $getFieldParameters,
        CraftRegisterAssetBundle $registerAssetBundle
    ) {
        $this->twig                = $twig;
        $this->environment         = $environment;
        $this->translator          = $translator;
        $this->getFieldParameters  = $getFieldParameters;
        $this->registerAssetBundle = $registerAssetBundle;
        $this->assetsService       = $assetsService;
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

        $uploadLocationFolder = $this->assetsService->findFolder(
            ['uid' => $fieldSettings->uploadLocation()->directoryId()],
        );

        assert($uploadLocationFolder instanceof VolumeFolder);

        return $this->twig->render(
            '@AnselSrc/Field/Field/Field.twig',
            [
                'model' => new FieldRenderModel(
                    $fieldSettings->asScalarArray(),
                    $fieldSettings->customFields()->asScalarArray(),
                    $this->getFieldParameters->get()->asArray(),
                    [
                        'imageUploadError' => $this->translator->getLine(
                            'image_upload_error',
                        ),
                        'selectImageFromDevice' => $this->translator->getLine(
                            'select_image_from_device'
                        ),
                        'unusableImage' => $this->translator->getLine(
                            'unusable_image'
                        ),
                    ],
                    [
                        'environment' => $this->environment->toString(),
                        'uploadLocationFolderId' => (string) $uploadLocationFolder->uid,
                    ],
                ),
            ],
        );
    }
}
