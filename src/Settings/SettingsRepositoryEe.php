<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Settings;

use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use BuzzingPixel\Ansel\Translations\TranslatorContract;

use function array_map;

class SettingsRepositoryEe implements SettingsRepositoryContract
{
    private TranslatorContract $translator;

    private EeQueryBuilderFactory $queryBuilderFactory;

    public function __construct(
        TranslatorContract $translator,
        EeQueryBuilderFactory $queryBuilderFactory
    ) {
        $this->translator          = $translator;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function getSettings(): SettingsCollection
    {
        $settingsDb = $this->queryBuilderFactory->create()
            ->get('ansel_settings')
            ->result_array();

        $keyedDbSettings = [];

        foreach ($settingsDb as $setting) {
            $keyedDbSettings[$setting['settings_key']] = $setting;
        }

        $settingsItemsArray = array_map(
            function (string $key) use ($keyedDbSettings) {
                return $this->createSetting(
                    $key,
                    $keyedDbSettings[$key] ?? null,
                );
            },
            SettingsRepositoryContract::ALL_SETTINGS,
        );

        return new SettingsCollection($settingsItemsArray);
    }

    public function saveSetting(SettingItem $setting): void
    {
        $existsCheck = $this->queryBuilderFactory->create()
            ->where('settings_key', $setting->key())
            ->get('ansel_settings')
            ->num_rows();

        switch ($setting->type()) {
            case SettingItem::TYPE_INT:
                $val = (int) $setting->value();
                break;
            case SettingItem::TYPE_BOOL:
                $val = $setting->value() === true ? 'y' : 'n';
                break;
            default:
                $val = (string) $setting->value();
        }

        $values = [
            'settings_type' => $setting->type(),
            'settings_key' => $setting->key(),
            'settings_value' => $val,
        ];

        if ($existsCheck > 0) {
            $this->queryBuilderFactory->create()->update(
                'ansel_settings',
                $values,
                ['settings_key' => $setting->key()],
            );

            return;
        }

        $this->queryBuilderFactory->create()->insert(
            'ansel_settings',
            $values,
        );
    }

    public function saveSettings(SettingsCollection $settings): void
    {
        $settings->map([$this, 'saveSetting']);
    }

    /**
     * @param string[]|null $dbSetting
     */
    private function createSetting(string $key, ?array $dbSetting): SettingItem
    {
        $label = $this->translator->getLine($key);

        $descKey = $key . '_explain';

        $desc = $this->translator->getLine($descKey);

        if ($desc === $descKey) {
            $desc = '';
        }

        $type = $dbSetting['settings_type'] ?? SettingItem::TYPE_STRING;

        switch ($type) {
            case SettingItem::TYPE_INT:
                $val = (int) ($dbSetting['settings_value'] ?? 0);
                break;
            case SettingItem::TYPE_BOOL:
                $val = ($dbSetting['settings_value'] ?? 'n') === 'y';
                break;
            default:
                /** @phpstan-ignore-next-line */
                $val = (string) ($dbSetting['settings_value'] ?? '');
        }

        return new SettingItem(
            $type,
            $key,
            $label,
            $val,
            $desc,
            isset(
                SettingsRepositoryContract::SETTINGS_PAGE[$key],
            )
        );
    }
}
