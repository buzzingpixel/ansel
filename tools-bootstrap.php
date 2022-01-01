<?php

declare(strict_types=1);

const ANSEL_ENV  = 'testing';
const BASEPATH   = __DIR__ . '/vendor/expressionengine/expressionengine/system/ee/legacy/';
const ANSEL_NAME = 'Ansel';
const ANSEL_VER  = '9.8.7';

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

require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Service/Template/Variables/ModifiableTrait.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/legacy/fieldtypes/EE_Fieldtype.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/legacy/database/DB_forge.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/legacy/database/DB_driver.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/legacy/database/DB_active_rec.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Service/Database/Query.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/legacy/database/drivers/mysqli/mysqli_driver.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/legacy/database/DB_result.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Service/Model/Facade.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Service/Validation/ValidationAware.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Service/Event/Subscriber.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Service/Event/Publisher.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Library/Mixin/Mixable.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Library/Mixin/MixableImpl.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Library/Data/Entity.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Library/Data/SerializableEntity.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Service/Model/Model.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Model/Addon/Module.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Service/Model/Query/Builder.php';
require_once 'vendor/expressionengine/expressionengine/system/ee/ExpressionEngine/Model/Addon/Action.php';
