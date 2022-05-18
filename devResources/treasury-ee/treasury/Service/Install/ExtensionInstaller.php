<?php

namespace BuzzingPixel\Treasury\Service\Install;

class ExtensionInstaller
{
	/**
	 * Add extension hook
	 */
	public static function add()
	{
		$addonInfo = ee('Addon')->get('treasury');

		ee()->db->insert('extensions', array(
			'class' => 'Treasury_ext',
			'method' => 'cp_custom_menu',
			'hook' => 'cp_custom_menu',
			'settings' => '',
			'version' => $addonInfo->get('version'),
			'enabled' => 'y'
		));
	}

	/**
	 * Remove extension hook
	 */
	public static function remove()
	{
		ee()->db->where('class', 'Treasury_ext');
		ee()->db->delete('extensions');
	}

	/**
	 * Update extension hook
	 */
	public static function update()
	{
		$addonInfo = ee('Addon')->get('treasury');

		ee()->db->where('class', 'Treasury_ext');
		ee()->db->update('extensions', array(
			'version' => $addonInfo->get('version')
		));
	}
}
