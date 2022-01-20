<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Settings;

use BuzzingPixel\Ansel\Shared\CraftQueryBuilderFactory;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use craft\db\Connection as DbConnection;
use yii\db\Exception;

use function array_map;

class SettingsRepositoryCraft implements SettingsRepositoryContract
{
    private DbConnection $db;

    private TranslatorContract $translator;

    private CraftQueryBuilderFactory $queryBuilderFactory;

    public function __construct(
        DbConnection $db,
        TranslatorContract $translator,
        CraftQueryBuilderFactory $queryBuilderFactory
    ) {
        $this->db                  = $db;
        $this->translator          = $translator;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function getSettings(): SettingsCollection
    {
        $settingsDb = $this->queryBuilderFactory->create()
            ->from('{{%ansel_settings}}')
            ->all();

        $keyedDbSettings = [];

        foreach ($settingsDb as $setting) {
            $keyedDbSettings[$setting['settingsKey']] = $setting;
        }

        $settingsItemsArray = array_map(
            function (string $key) use ($keyedDbSettings) {
                /** @phpstan-ignore-next-line */
                $cKey = SettingsRepositoryContract::CRAFT_KEYS[$key] ?? $key;

                return $this->createSetting(
                    $key,
                    $keyedDbSettings[$cKey] ?? null,
                );
            },
            SettingsRepositoryContract::ALL_SETTINGS,
        );

        return new SettingsCollection($settingsItemsArray);
    }

    /**
     * @throws Exception
     */
    public function saveSetting(SettingItem $setting): void
    {
        $key = $setting->key();

        $cKey = SettingsRepositoryContract::CRAFT_KEYS[$key] ?? $key;

        $existsCheck = (int) $this->queryBuilderFactory->create()
            ->from('{{%ansel_settings}}')
            ->where(
                '`settingsKey` = :settingsKey',
                ['settingsKey' => $cKey],
            )
            ->count();

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

        $params = [
            'settingsType' => $setting->type(),
            'settingsKey' => $cKey,
            'settingsValue' => $val,
        ];

        if ($existsCheck > 0) {
            $this->db->createCommand()->update(
                '{{%ansel_settings}}',
                $params,
                ['settingsKey' => $cKey],
            )->execute();

            return;
        }

        $this->db->createCommand()->insert(
            '{{%ansel_settings}}',
            $params,
        )->execute();
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

        $type = $dbSetting['settingsType'] ?? SettingItem::TYPE_STRING;

        switch ($type) {
            case SettingItem::TYPE_INT:
                $val = (int) ($dbSetting['settingsValue'] ?? 0);
                break;
            case SettingItem::TYPE_BOOL:
                $val = ($dbSetting['settingsValue'] ?? 'n') === 'y';
                break;
            default:
                /** @phpstan-ignore-next-line */
                $val = (string) ($dbSetting['settingsValue'] ?? '');
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
