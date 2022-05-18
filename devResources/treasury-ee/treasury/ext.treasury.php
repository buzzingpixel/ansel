<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Treasury_ext
{
	// Set the version for ExpressionEngine
	public $version = TREASURY_VER;

	/**
	 * cp_custom_menu hook
	 *
	 * @param object $menu EllisLab\ExpressionEngine\Service\CustomMenu\Menu
	 */
	public function cp_custom_menu($menu)
	{
		// Set the treasury base url
		$baseUrl = 'addons/settings/treasury';

		// Make sure lang is loaded
		ee()->lang->loadfile('treasury');

		// Start the menu
		$sub = $menu->addSubmenu(lang('treasury_files'));

		// Add all locations
		$sub->addItem(
			lang('all_locations'),
			ee('CP/URL')->make($baseUrl)
		);

		// Get locations
		$locations = ee('treasury:LocationsAPI')->getAllLocations();

		// Add fuzzy filter if number of locations is greater than 4
		if (count($locations) > 4) {
			$sub->withFilter(lang('find_locations'));
		}

		// Loop through the locations
		foreach ($locations as $location) {
			// Add the location to the sub menu
			$sub->addItem(
				$location->name,
				ee('CP/URL')->make("{$baseUrl}/showLocation/{$location->id}")
			);
		}
	}
}
