<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['site_license_key'] = '';
// ExpressionEngine Config Items
// Find more configs and overrides at
// https://docs.expressionengine.com/latest/general/system-configuration-overrides.html

$config['app_version'] = '7.2.4';
$config['encryption_key'] = '9237738cf71d9465c1881a4598eaab74d762f6ab';
$config['session_crypt_key'] = '7f442750ea343e5781fc868e97982f97b001297b';
$config['database'] = array(
	'expressionengine' => array(
		'hostname' => 'ansel-db',
		'database' => 'coilpack',
		'username' => 'coilpack',
		'password' => 'secret',
		'dbprefix' => 'exp_',
		'char_set' => 'utf8mb4',
		'dbcollat' => 'utf8mb4_unicode_ci',
		'port'     => ''
	),
);
$config['show_ee_news'] = 'y';
$config['share_analytics'] = 'y';

// EOF