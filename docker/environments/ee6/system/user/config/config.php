<?php

declare(strict_types=1);

$projDir = dirname(__DIR__, 3);

require_once $projDir . '/vendor/autoload.php';

require_once $projDir . '/dumper.php';

$config ??= [];

$config['enable_dock']               = 'y';
$config['enable_frontedit']          = 'y';
$config['automatic_frontedit_links'] = 'y';
$config['enable_mfa']                = 'y';
$config['autosave_interval_seconds'] = '10';

$config['site_license_key'] = '';

$config['app_version']       = '6.3.4';
$config['encryption_key']    = '2372db8ab84dff9c4f63f5d06908947a678e4dff';
$config['session_crypt_key'] = 'be41b9fc80c5a8013b6eee4f526a7868188f5987';
$config['database']          = [
    'expressionengine' => [
        'hostname' => 'ansel-db',
        'database' => 'anselee',
        'username' => 'anselee',
        'password' => 'secret',
        'dbprefix' => 'exp_',
        'char_set' => 'utf8mb4',
        'dbcollat' => 'utf8mb4_unicode_ci',
        'port'     => '',
    ],
];
$config['show_ee_news']      = 'y';
$config['share_analytics']   = 'y';

$secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
    (
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
    );

$protocol = $secure ? 'https://' : 'http://';

$config['base_url']  = $protocol . $_SERVER['HTTP_HOST'] . '/';
$config['base_path'] = dirname(__DIR__, 3) . '/';

// EOF
