<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\Index;

use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use EE_Functions;
use ExpressionEngine\Service\URL\URLFactory;
use Psr\Http\Message\ServerRequestInterface;

use function array_walk;

class PostIndexAction
{
    private URLFactory $urlFactory;

    private EE_Functions $eeFunctions;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        URLFactory $urlFactory,
        EE_Functions $eeFunctions,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->urlFactory         = $urlFactory;
        $this->eeFunctions        = $eeFunctions;
        $this->settingsRepository = $settingsRepository;
    }

    public function run(ServerRequestInterface $request): void
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
                    $value = $value === 'y';
                }

                $setting->setValue($value);
            }
        );

        $this->settingsRepository->saveSettings($settingsCollection);

        $this->eeFunctions->redirect(
            $this->urlFactory->getCurrentUrl(),
        );
    }
}
