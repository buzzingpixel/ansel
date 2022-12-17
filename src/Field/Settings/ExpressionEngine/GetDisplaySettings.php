<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\Field\Settings\PopulateFieldSettingsFromDefaults;
use BuzzingPixel\Ansel\RequestCache\RequestCachePool;
use Psr\Cache\InvalidArgumentException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function is_array;

class GetDisplaySettings
{
    private RequestCachePool $requestCache;

    private RenderFieldSettings $renderFieldSettings;

    private FieldSettingsFromRaw $fieldSettingsFromRaw;

    private PopulateFieldSettingsFromDefaults $populateFieldSettingsFromDefaults;

    public function __construct(
        RequestCachePool $requestCache,
        RenderFieldSettings $renderFieldSettings,
        FieldSettingsFromRaw $fieldSettingsFromRaw,
        PopulateFieldSettingsFromDefaults $populateFieldSettingsFromDefaults
    ) {
        $this->requestCache                      = $requestCache;
        $this->renderFieldSettings               = $renderFieldSettings;
        $this->fieldSettingsFromRaw              = $fieldSettingsFromRaw;
        $this->populateFieldSettingsFromDefaults = $populateFieldSettingsFromDefaults;
    }

    /**
     * @param mixed $data
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidArgumentException
     *
     * @phpstan-ignore-next-line
     */
    public function get($data, string $fieldNameRoot): string
    {
        $postedSettings = $this->requestCache->getItem(
            'anselPostedSettings',
        );

        $data = $postedSettings->isHit() ? $postedSettings->get() : $data;

        $dataArray = is_array($data) ? $data : [];

        $fieldSettings = $this->fieldSettingsFromRaw->get(
            $dataArray,
        );

        $isNew = ($dataArray['ansel'] ?? null) === null;

        if ($isNew) {
            $this->populateFieldSettingsFromDefaults->populate(
                $fieldSettings,
            );
        }

        return $this->renderFieldSettings->render(
            $fieldNameRoot,
            $fieldSettings,
        )->content();
    }
}
