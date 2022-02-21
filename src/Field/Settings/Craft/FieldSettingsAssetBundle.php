<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\Craft;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\AnselConfig\ContainerManager;
use BuzzingPixel\AssetsDist\AssetsDist;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use function assert;
use function is_array;
use function json_decode;

class FieldSettingsAssetBundle extends AssetBundle
{
    private InternalFunctions $internalFunctions;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @codeCoverageIgnore
     */
    public function init(): void
    {
        parent::init();

        $container = (new ContainerManager())->container();

        $internalFunctions = $container->get(InternalFunctions::class);

        assert($internalFunctions instanceof InternalFunctions);

        $this->internalFunctions = $internalFunctions;

        $this->registerBundle();
    }

    public function registerBundle(): void
    {
        $this->sourcePath = AssetsDist::getPath();

        $this->depends = [CpAsset::class];

        $reactPath = $this->sourcePath . '/react';

        $manifest = json_decode(
            $this->internalFunctions->fileGetContents(
                $reactPath . '/manifest.json',
            ),
            true,
        );

        assert(is_array($manifest));

        $this->js = [
            'react/' . $manifest['src/react.production.min.js'],
            'react/' . $manifest['src/react-dom.production.min.js'],
            'react/' . $manifest['app/fieldSettings/craft/field_settings_fields.tsx'],
        ];
    }
}
