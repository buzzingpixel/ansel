<?php

namespace BuzzingPixel\Treasury\Service\Install;

class ModuleInstaller
{
	/**
	 * Add module record
	 */
	public static function add()
	{
		$addonInfo = ee('Addon')->get('treasury');

		ee()->db->insert('modules', array(
			'module_name' => 'Treasury',
			'module_version' => $addonInfo->get('version'),
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		));
	}

	/**
	 * Remove module record
	 */
	public static function remove()
	{
		ee()->db->where('module_name', 'Treasury');
		ee()->db->delete('modules');
	}

	/**
	 * Update module record
	 */
	public static function update()
	{
		$addonInfo = ee('Addon')->get('treasury');

		ee()->db->where('module_name', 'Treasury');
		ee()->db->update('modules', array(
			'module_version' => $addonInfo->get('version')
		));
	}
}
