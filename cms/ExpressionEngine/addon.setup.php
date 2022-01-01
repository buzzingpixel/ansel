<?php

declare(strict_types=1);

use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\AnselConfig\ContainerManager;

const ANSEL_ENV = 'ee';

$autoloadFile = __DIR__ . '/vendor/autoload.php';

if (file_exists($autoloadFile)) {
    require_once $autoloadFile;
}

$container = (new ContainerManager())->container();

/** @noinspection PhpUnhandledExceptionInspection */
$meta = $container->get(Meta::class);
assert($meta instanceof Meta);

define('ANSEL_NAME', $meta->name());
define('ANSEL_VER', $meta->version());

return [
    'author' => $meta->author(),
    'author_url' => $meta->authorUrl(),
    'description' => $meta->description(),
    'docs_url' => $meta->eeDocsUrl(),
    'name' => $meta->name(),
    'namespace' => 'BuzzingPixel\AnselCms\ExpressionEngine;',
    'settings_exist' => true,
    'version' => $meta->version(),
    'fieldtypes' => [
        'ansel' => ['name' => $meta->name()],
    ],
    'services' => [],
];
