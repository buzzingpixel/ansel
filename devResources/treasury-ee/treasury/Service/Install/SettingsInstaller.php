<?php

namespace BuzzingPixel\Treasury\Service\Install;

use BuzzingPixel\Treasury\Utility\Table;

class SettingsInstaller
{
	/**
	 * Add
	 */
	public static function add()
	{
		// Settings fields
		$fields = array(
			'settings_type' => array(
				'type' => 'TINYTEXT'
			),
			'settings_key' => array(
				'type' => 'TINYTEXT'
			),
			'settings_value' => array(
				'type' => 'TEXT'
			)
		);

		// Add the settings table
		Table::insert(
			$fields,
			'id',
			'treasury_settings'
		);

		// Insert settings rows data
		ee()->db->insert_batch('treasury_settings', array(
			array(
				'settings_type' => 'string',
				'settings_key' => 'license_key',
				'settings_value' => null
			),
			array(
				'settings_type' => 'int',
				'settings_key' => 'phone_home',
				'settings_value' => 0
			),
			array(
				'settings_type' => 'int',
				'settings_key' => 'check_for_updates',
				'settings_value' => 0
			),
			array(
				'settings_type' => 'int',
				'settings_key' => 'updates_available',
				'settings_value' => 0
			),
			array(
				'settings_type' => 'string',
				'settings_key' => 'update_feed',
				'settings_value' => ''
			)
		));
	}

	/**
	 * Remove
	 */
	public static function remove()
	{
		Table::remove('treasury_settings');
	}
}
