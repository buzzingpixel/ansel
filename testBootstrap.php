<?php

declare(strict_types=1);

// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols
ini_set('display_errors', 'On');
ini_set('html_errors', '0');
error_reporting(-1);
if (! class_exists(Yii::class)) {
    include_once __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
}

if (! class_exists(Craft::class)) {
    include_once __DIR__ . '/vendor/craftcms/cms/src/Craft.php';
}
