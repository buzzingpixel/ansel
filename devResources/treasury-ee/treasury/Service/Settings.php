<?php

namespace BuzzingPixel\Treasury\Service;

use BuzzingPixel\Treasury\Pattern\Singleton;

class Settings extends Singleton
{
	/**
	 * Boolean mapping
	 */
	private static $boolMapping = array(
		'' => false,
		'n' => false,
		'no' => false,
		'false' => false,
		false => false,
		'y' => true,
		'yes' => true,
		'true' => true,
		true => true
	);

	private static $reverseBoolMapping = array(
		'' => 'n',
		'n' => 'n',
		'no' => 'n',
		'false' => 'n',
		false => 'n',
		'y' => 'y',
		'yes' => 'y',
		'true' => 'y',
		true => 'y'
	);

	/**
	 * @var Singleton The reference to *Singleton* instance of this class
	 */
	protected static $instance;

	/**
	 * Availalbe settings
	 */
	private $settings = array();

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 *
	 * The constructor runs only once
	 */
	protected function __construct()
	{
		// Make sure this method is not being run during an uninstall
		if (! ee()->db->table_exists('treasury_settings')) {
			return;
		}

		// Get the settings from the database
		$query = ee()->db->select('*')
			->from('treasury_settings')
			->order_by('id', 'asc')
			->get()
			->result();

		// Loop through the settings items
		foreach ($query as $item) {
			// Add setting to array
			$this->settings[$item->settings_key] = $item;
		}
	}

	/**
	 * Get magic method
	 *
	 * @param string $name
	 */
	public function __get($name)
	{
		// Check if setting exists
		if (! isset($this->settings[$name])) {
			return null;
		}

		// Get the setting
		$setting = $this->settings[$name];

		// Get the bool mapping
		$boolMapping = self::$boolMapping;

		// Check the setting type
		if ($setting->settings_type === 'int') {
			return (int) $setting->settings_value;
		} elseif ($setting->settings_type === 'bool') {
			return isset($boolMapping[$setting->settings_value]) ?
				$boolMapping[$setting->settings_value] : false;
		} else {
			return $setting->settings_value;
		}
	}

	/**
	 * Set magic method
	 *
	 * @param string $name
	 * @param mixed $val
	 */
	public function __set($name, $val)
	{
		// Check if setting exists
		if (! isset($this->settings[$name])) {
			return null;
		}

		// Clean the value
		$val = ee('Security/XSS')->clean($val);

		// Get the setting
		$setting = $this->settings[$name];

		// Get the bool mapping
		$boolMapping = self::$reverseBoolMapping;

		// Check the setting type
		if ($setting->settings_type === 'int') {
			$this->settings[$name]->settings_value = (int) $val;
		} elseif ($setting->settings_type === 'bool') {
			$this->settings[$name]->settings_value = isset($boolMapping[$val]) ?
				$boolMapping[$val]->settings_value : 'n';
		} else {
			$this->settings[$name]->settings_value = $val;
		}
	}

	/**
	 * Save settings
	 */
	public function save()
	{
		// Update the db
		ee()->db->update_batch('treasury_settings', $this->settings, 'id');
	}
}
