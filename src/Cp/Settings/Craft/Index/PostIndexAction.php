<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Craft\Index;

use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\CraftResponseFactory;
use BuzzingPixel\Ansel\Shared\Facades\CraftUrlHelper;
use Psr\Http\Message\ServerRequestInterface;
use yii\web\Response;

use function array_walk;

class PostIndexAction
{
    private CraftUrlHelper $urlHelper;

    private CraftResponseFactory $responseFactory;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        CraftUrlHelper $urlHelper,
        CraftResponseFactory $responseFactory,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->urlHelper          = $urlHelper;
        $this->responseFactory    = $responseFactory;
        $this->settingsRepository = $settingsRepository;
    }

    public function run(ServerRequestInterface $request): Response
    {
        $settingsCollection = $this->settingsRepository
            ->getSettings()
            ->settingsPageOnly();

        $postValues = (array) $request->getParsedBody();

        array_walk(
            $postValues,
            static function (
                string $value,
                string $key
            ) use ($settingsCollection): void {
                $setting = $settingsCollection->filter(
                    static fn (SettingItem $i) => $i->key() === $key,
                )->firstOrNull();

                if ($setting === null) {
                    return;
                }

                if ($setting->type() === $setting::TYPE_INT) {
                    $value = (int) $value;
                } elseif ($setting->type() === $setting::TYPE_BOOL) {
                    $value = $value === '1';
                }

                $setting->setValue($value);
            }
        );

        $this->settingsRepository->saveSettings($settingsCollection);

        return $this->responseFactory->getResponse()->redirect(
            $this->urlHelper->cpUrl('ansel'),
        );
    }
}
