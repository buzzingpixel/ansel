<?php

declare(strict_types=1);

use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\AnselConfig\ContainerBuilder;

$autoloadFile = __DIR__ . '/vendor/autoload.php';

if (file_exists($autoloadFile)) {
    require_once $autoloadFile;
}

$container = (new ContainerBuilder())->build();

/** @noinspection PhpUnhandledExceptionInspection */
$meta = $container->get(Meta::class);
assert($meta instanceof Meta);

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
