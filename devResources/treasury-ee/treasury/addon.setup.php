<?php

// Require the Amazon AWS autoloader
if (! class_exists('Aws\AwsClient')) {
	require PATH_THIRD . 'treasury/Vendor/aws/aws-autoloader.php';
}

// Require phpseclib SFTP library
set_include_path(get_include_path() . PATH_SEPARATOR . PATH_THIRD . 'treasury/Vendor/phpseclib');
require PATH_THIRD . 'treasury/Vendor/phpseclib/Net/SFTP.php';
require PATH_THIRD . 'treasury/Vendor/phpseclib/Crypt/RSA.php';

// Get the addon json file
$addonJson = json_decode(
	file_get_contents(PATH_THIRD . 'treasury/addon.json')
);

// Define constants
defined('TREASURY_NAME') || define('TREASURY_NAME', $addonJson->label);
defined('TREASURY_VER') || define('TREASURY_VER', $addonJson->version);
defined('TREASURY_FILE_NAME') || define('TREASURY_FILE_NAME', 'Treasury File');
defined('TREASURY_CACHE_PATH') || define('TREASURY_CACHE_PATH', SYSPATH . 'user/cache/treasury/');

// Return info about the addon for ExpressionEngine
return array(
	'author' => $addonJson->author,
	'author_url' => $addonJson->authorUrl,
	'description' => $addonJson->description,
	'docs_url' => $addonJson->docsUrl,
	'name' => $addonJson->label,
	'namespace' => $addonJson->namespace,
	'settings_exist' => $addonJson->settingsExist,
	'version' => $addonJson->version,
	'fieldtypes' => array(
		'treasury_file' => array(
			'name' => 'Treasury File',
			'compatibility' => 'treasury_file'
		)
	),
	'services' => array(
		'UploadAPI' => function() {
			return new \BuzzingPixel\Treasury\API\Upload();
		},
		'LocationsAPI' => function() {
			return new \BuzzingPixel\Treasury\API\Locations();
		},
		'FilesAPI' => function() {
			return new \BuzzingPixel\Treasury\API\Files();
		},
		'FilePicker' => function() {
			return new \BuzzingPixel\Treasury\API\FilePicker(ee('CP/URL'));
		}
	)
);
