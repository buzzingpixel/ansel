<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Query;
use yii\db\Exception;

use function explode;
use function is_numeric;
use function json_decode;
use function json_encode;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

/**
 * Old migration that still needs to be around for posterity, but we're not
 * going to port it to the new system
 *
 * @codeCoverageIgnore
 */
class m190129_214404_UpdateSettingsIdsToUids extends Migration
{
    /**
     * @throws Exception
     */
    public function safeUp(): bool
    {
        $class = explode(
            '\\',
            'buzzingpixel\ansel\fields\AnselField'
        );

        $classString = '';

        foreach ($class as $part) {
            /** @phpstan-ignore-next-line */
            if (! $part) {
                continue;
            }

            /** @phpstan-ignore-next-line */
            if ($classString) {
                $classString .= '\\\\';
            }

            $classString .= $part;
        }

        $fields = (new Query())->from('{{%fields}}')
            ->where("`type` = '" . $classString . "'")
            ->all();

        foreach ($fields as $field) {
            /** @phpstan-ignore-next-line */
            if (! json_decode($field['settings'], true)) {
                continue;
            }

            /** @phpstan-ignore-next-line */
            $this->reSaveFieldSettings(json_decode(
                $field['settings'],
                true,
            ), $field['id']);
        }

        return true;
    }

    /**
     * @param mixed[] $settings
     * @param mixed   $primaryKey
     *
     * @throws Exception
     */
    private function reSaveFieldSettings(array $settings, $primaryKey): void
    {
        if (
            isset($settings['uploadLocation']) &&
            is_numeric($settings['uploadLocation'])
        ) {
            /** @phpstan-ignore-next-line */
            $volume = Craft::$app->getVolumes()->getVolumeById(
                (int) $settings['uploadLocation'],
            );

            if ($volume && isset($volume->uid)) {
                $settings['uploadLocation'] = $volume->uid;
            }
        }

        if (
            isset($settings['saveLocation']) &&
            is_numeric($settings['saveLocation'])
        ) {
            /** @phpstan-ignore-next-line */
            $volume = Craft::$app->getVolumes()->getVolumeById(
                (int) $settings['saveLocation'],
            );

            if ($volume && isset($volume->uid)) {
                $settings['saveLocation'] = $volume->uid;
            }
        }

        $this->getDb()->createCommand()->update(
            '{{%fields}}',
            [
                'settings' => json_encode($settings),
            ],
            "`id` = '" . $primaryKey . "'"
        )
        ->execute();
    }

    public function safeDown(): bool
    {
        return true;
    }
}
