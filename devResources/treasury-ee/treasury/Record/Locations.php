<?php

namespace BuzzingPixel\Treasury\Record;

use BuzzingPixel\Treasury\Service\Data\Base;

class Locations extends Base
{
	// Source table name
	public static $_tableName = 'treasury_locations';

	// Model properties
	protected $site_id = 'int';
	protected $name = array(
		'type' => 'string',
		'db' => array(
			'null' => false,
			'type' => 'VARCHAR',
			'constraint' => 255
		)
	);
	protected $handle = array(
		'type' => 'string',
		'db' => array(
			'null' => false,
			'type' => 'VARCHAR',
			'constraint' => 255
		)
	);
	protected $type = array(
		'type' => 'string',
		'db' => array(
			'null' => false,
			'type' => 'VARCHAR',
			'constraint' => 255
		)
	);
	protected $settings = 'string';

	/**
	 * On settings set
	 *
	 * @param string $val
	 */
	protected function settings__onSet($val)
	{
		return json_encode($val);
	}

	/**
	 * On settings get
	 *
	 * @param string $val
	 */
	protected function settings__onGet($val)
	{
		// Return the json decoded settings
		return json_decode($val);
	}

	/**
	 * On settings save
	 *
	 * @return string
	 */
	protected function settings__onSave()
	{
		return $this->settings;
	}

	/**
	 * Get flat array (including settings)
	 */
	public function getFlatArray()
	{
		// Get everything as an array
		$array = $this->asArray();

		// Unset the settings string
		unset($array['settings']);

		// Json decode settings and make sure it is an array
		$settingsArray = (array) json_decode(
			json_decode($this->settings),
			true
		);

		// Return merged arrays
		return array_merge($array, $settingsArray);
	}
}
