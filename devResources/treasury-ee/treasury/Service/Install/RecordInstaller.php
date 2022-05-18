<?php

namespace BuzzingPixel\Treasury\Service\Install;

use BuzzingPixel\Treasury\Utility\Table;

class RecordInstaller
{
	/**
	 * Install a Record
	 *
	 * @param string $name The name of the record class
	 */
	public static function install($name)
	{
		$addonInfo = ee('Addon')->get('treasury');

		// Get the record class
		$recordClass = '\\' . $addonInfo->get('namespace') . '\\Record\\' . $name;
		$recordClass = new $recordClass;

		// Check to make sure this record is installable
		if (! $recordClass::$_tableName) {
			throw new \Exception(
				'This record does not have a table name and cannot be installed'
			);
		}

		// If the table is already installed, halt method
		if (ee()->db->table_exists($recordClass::$_tableName)) {
			return;
		}

		// Insert the table
		Table::insert(
			$recordClass->getDbColumnInfo(),
			$recordClass->getPrimaryKey(),
			$recordClass::$_tableName
		);
	}

	/**
	 * Uninstall a record
	 *
	 * @param string $name The name of the record class
	 */
	public static function uninstall($name)
	{
		$addonInfo = ee('Addon')->get('treasury');

		// Get the record class
		$recordClass = '\\' . $addonInfo->get('namespace') . '\\Record\\' . $name;
		$recordClass = new $recordClass;

		// Check to make sure this record is installable
		if (! $recordClass::$_tableName) {
			throw new \Exception(
				'This record does not have a table name and cannot be removed'
			);
		}

		// If the table is not installed, halt method
		if (! ee()->db->table_exists($recordClass::$_tableName)) {
			return;
		}

		// Remove the table
		Table::remove($recordClass::$_tableName);
	}

	/**
	 * Check and update columns as needed
	 *
	 * @param string $name The name of the record class
	 */
	public static function checkAndUpdateColumns($name)
	{
		$addonInfo = ee('Addon')->get('treasury');

		// Get the record class
		$recordClass = '\\' . $addonInfo->get('namespace') . '\\Record\\' . $name;
		$recordClass = new $recordClass;

		// Loop through record columns and make sure everything is right
		foreach ($recordClass->getDbColumnInfo() as $key => $val) {
			// Move on if the field already exists
			if (ee()->db->field_exists($key, $recordClass::$_tableName)) {
				continue;
			}

			// Add column
			Table::addColumn($recordClass::$_tableName, $key, $val);
		}
	}
}
