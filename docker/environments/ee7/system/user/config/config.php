<?php

declare(strict_types=1);

$projDir = dirname(__DIR__, 3);

require_once $projDir . '/vendor/autoload.php';

require_once dirname($projDir) . '/dumper.php';

$config ??= [];

$config['enable_dock']               = 'y';
$config['enable_frontedit']          = 'y';
$config['automatic_frontedit_links'] = 'y';
$config['enable_mfa']                = 'y';
$config['autosave_interval_seconds'] = '10';

$config['site_license_key'] = '';

$config['app_version']       = '7.1.5';
$config['encryption_key']    = '4f9116ecc15c486c52548e0cb7c5c6ad8ae23f2b';
$config['session_crypt_key'] = '99817a5a9d1dfdb9157bcff97cc5d106a689eaac';
$config['database']          = [
    'expressionengine' => [
        'hostname' => 'ansel-db',
        'database' => 'anselee7',
        'username' => 'anselee7',
        'password' => 'secret',
        'dbprefix' => 'exp_',
        'char_set' => 'utf8mb4',
        'dbcollat' => 'utf8mb4_unicode_ci',
        'port'     => '',
    ],
];
$config['show_ee_news']      = 'y';

$secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
    (
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
    );

$protocol = $secure ? 'https://' : 'http://';

$config['base_url']  = $protocol . $_SERVER['HTTP_HOST'] . '/';
$config['base_path'] = dirname(__DIR__, 3) . '/';

// if (! empty($_POST)) {
//     dd($_POST);
// }

// EOF