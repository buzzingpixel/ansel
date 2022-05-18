<?php

namespace BuzzingPixel\Treasury\Utility;

class Table
{
	/**
	 * Insert table
	 *
	 * @param array $fields
	 * @param string $primaryKey
	 * @param string $tableName
	 */
	public static function insert($fields, $primaryKey, $tableName)
	{
		ee()->load->dbforge();

		// Set auto inc id field
		$fields[$primaryKey] = array(
			'type' => 'INT',
			'unsigned' => true,
			'auto_increment' => true
		);

		// Add fields to forge
		ee()->dbforge->add_field($fields);

		// Set the 'id' field as the primary key
		ee()->dbforge->add_key($primaryKey, true);

		// Create the table
		ee()->dbforge->create_table($tableName, true);
	}

	/**
	 * Remove table
	 *
	 * @param string $tableName
	 */
	public static function remove($tableName)
	{
		ee()->load->dbforge();

		ee()->dbforge->drop_table($tableName);
	}

	/**
	 * Add column
	 *
	 * @param string $tableName
	 * @param string $fieldName
	 * @param array $fieldPreferences
	 */
	public static function addColumn($tableName, $fieldName, $fieldPreferences)
	{
		ee()->load->dbforge();

		ee()->dbforge->add_column($tableName, array(
			$fieldName => $fieldPreferences
		));
	}
}
